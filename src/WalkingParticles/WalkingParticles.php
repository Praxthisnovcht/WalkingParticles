<?php

/*
 * This file is the main class of WalkingParticles.
 * Copyright (C) 2015  CyberCube-HK
 *
 * WalkingParticles is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * WalkingParticles is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with WalkingParticles.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace WalkingParticles;

use WalkingParticles\events\PlayerAddWPEvent;
use WalkingParticles\events\PlayerClearWPEvent;
use WalkingParticles\events\PlayerRemoveWPEvent;
use WalkingParticles\events\PlayerSetAmplifierEvent;
use WalkingParticles\events\PlayerSetDisplayEvent;
use WalkingParticles\events\PlayerSwitchRandommodeEvent;
use WalkingParticles\events\PlayerApplyPackEvent;
use WalkingParticles\listeners\PlayerListener;
use WalkingParticles\listeners\SignListener;
use WalkingParticles\listeners\EntityListener;
use WalkingParticles\task\ParticleShowTask;
use WalkingParticles\task\RandomModeTask;
use WalkingParticles\Particles;
use WalkingParticles\commands\WppackCommand;
use WalkingParticles\commands\WplistCommand;
use WalkingParticles\commands\WpgetCommand;
use WalkingParticles\commands\AdminCommand;

use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\math\Vector3;
use pocketmine\utils\Config;
use pocketmine\command\CommandExecutor;
use pocketmine\item\Item;
use pocketmine\utils\TextFormat;

class WalkingParticles extends PluginBase{
  
  const VERSION = "2.0.0#25";
  
  private static $instance = null;
  private $eco = null;
  
  public $random_mode = [];

 public function onEnable(){
   $this->getLogger()->info("Loading resources..");
   if(!is_dir($this->getDataFolder())){
     mkdir($this->getDataFolder());
   }
   $this->saveDefaultConfig();
   $this->reloadConfig();
   $this->updateConfig();
   $this->data = new Config($this->getDataFolder()."players.yml", Config::YAML, array());
   $this->data2 = new Config($this->getDataFolder()."particlepacks.yml", Config::YAML, array());
   $this->data3 = new Config($this->getDataFolder()."temp1.yml", array());
   $this->getLogger()->info("Loading economic plugins..");
   $plugins = array("EconomyAPI", "PocketMoney", "MassiveEconomy", "GoldStd");
   foreach($plugins as $plugin){
    $pl = $this->getServer()->getPluginManager()->getPlugin($plugin);
    if($this->pluginLoaded($pl) !== false){
    	$this->eco = $pl;
    	$this->getLogger()->info("Loaded with ".$plugin."!");
    }
   }
   if($this->eco === null){ 
    $this->getLogger()->info("No economy plugin found!");
   }
   $this->getLogger()->info("Loading plugin..");
   self::$instance = $this;
   $this->particles = new Particles($this);  
   $this->getServer()->getScheduler()->scheduleRepeatingTask(new ParticleShowTask($this), 13);  
   $this->getServer()->getScheduler()->scheduleRepeatingTask(new RandomModeTask($this), 10);
   $this->getServer()->getPluginManager()->registerEvents(new  PlayerListener($this), $this);
   $this->getServer()->getPluginManager()->registerEvents(new SignListener($this), $this);
   $this->getCommand("wppack")->setExecutor(new WppackCommand($this));
   $this->getCommand("wplist")->setExecutor(new WplistCommand($this));
   $this->getCommand("wpget")->setExecutor(new WpgetCommand($this));
   $this->getCommand("walkingparticles")->setExecutor(new AdminCommand($this));
   $this->getLogger()->info($this->colourMessage("&aLoaded Successfully!"));
 }
 
 private function pluginLoaded($plugin){
   return $plugin !== null;
 }
 
 private function updateConfig(){
   $this->getLogger()->info("Updating config file..");
   if($this->getConfig()->exists("v") !== true || $this->getConfig()->get("v") !== self::VERSION){
     unlink($this->getDataFolder()."config.yml");
     $this->saveDefaultConfig();
     $this->reloadConfig();
   }
 }
 
 public static function getInstance(){
   return self::instance;
 }
 
 public function getEco(){
 	 return $this->eco;
 }
 
 public function getData(){
   return $this->data;
 }
 
 public function getParticles(){
   return $this->particles;
 }
 
 public function colourMessage($message){
 	return str_replace("&", "§", $message);
 }
 
 public function putTemp(Player $player){
 	 if($this->isCleared($player) !== true){
 	 	 $t = $this->data->getAll();
 	 	 $temp = $this->data3->getAll();
 	 	 foreach($t[$player->getName()]["particle"] as $pc){
 	 	 	 $temp[$player->getName()][] = $pc;
 	 	 }
 	 	 $this->data3->setAll($temp);
 	 	 $this->data3->save();
 	 }
 }
 
 public function byeTemp(Player $player){
 	 $temp = $this->data3->getAll();
 	 if(isset($temp[$player->getName()])){
 	 	 foreach($temp[$player->getName()] as $pc){
 	 	 	 $this->clearPlayerParticle($player);
 	 	 	 $this->addPlayerParticle($player, $pc);
 	 	 }
 	 	 unset($temp[$player->getName()]);
 	 	 $this->data3->setAll($temp);
 	 	 $this->data3->save();
 	 }
 }
 
 public function addPlayerParticle(Player $player, $particle){
   $this->getServer()->getPluginManager()->callEvent($event = new PlayerAddWPEvent($this, $player, $particle));
		if($event->isCancelled()){
			return false;
		}
   $t = $this->data->getAll();
   $t[$player->getName()]["particle"][] = $particle;
   $this->data->setAll($t);
   $this->data->save();
   return true;
 }
 
 public function removePlayerParticle(Player $player, $particle){
   $this->getServer()->getPluginManager()->callEvent($event = new PlayerRemoveWPEvent($this, $player, $particle));
   if($event->isCancelled()){
     return false;
   }
   $t = $this->data->getAll();
   $p = array_search($particle, $t[$player->getName()]["particle"]);
   unset($t[$player->getName()]["particle"][$p]);
   $this->data->setAll($t);
   $this->data->save();
   return true;
 }
 
 public function clearPlayerParticle(Player $player){
   $t = $this->data->getAll();
   $this->getServer()->getPluginManager()->callEvent($event = new PlayerClearWPEvent($this, $player, $t[$player->getName()]["particle"]));
   if($event->isCancelled()){
     return false;
   }
   foreach($t[$player->getName()]["particle"] as $p){
   	$pa = array_search($p, $t[$player->getName()]["particle"]);
   	unset($t[$player->getName()]["particle"][$pa]);
   }
   $this->data->setAll($t);
   $this->data->save();
   return true;
 }
 
 public function getAllPlayerParticles(Player $player){
   $t = $this->data->getAll();
   $particles = $t[$player->getName()]["particle"];
   $p = "";
   foreach($particles as $ps){
     $p .= $ps.", ";
   }
   return substr($p, 0, -2);
 }
 
 public function isCleared(Player $player){
   $t = $this->data->getAll();
   $array = $t[$player->getName()]["particle"];
   return (bool) count($array) < 1;
 }
 
 public function setPlayerAmplifier(Player $player, $amplifier){
   	$this->getServer()->getPluginManager()->callEvent($event = new PlayerSetAmplifierEvent($this, $player, $amplifier));
   if($event->isCancelled()){
     return false;
   }
   $t = $this->data->getAll();
   $t[$player->getName()]["amplifier"] = $amplifier;
   $this->data->setAll($t);
   $this->data->save();
   return true;
 }
 
 public function getPlayerAmplifier(Player $player){
   $t = $this->data->getAll();
   return $t[$player->getName()]["amplifier"];
 }
 
 public function setPlayerDisplay(Player $player, $display){
 	  $this->getServer()->getPluginManager()->callEvent($event = new PlayerSetDisplayEvent($this, $player, $display));
   if($event->isCancelled()){
     return false;
   }
   $t = $this->data->getAll();
   $t[$player->getName()]["display"] = $display;
   $this->data->setAll($t);
   $this->data->save();
   return true;
 }
 
 public function getPlayerDisplay(Player $player){
   $t = $this->data->getAll();
   return $t[$player->getName()]["display"];
 }
 
 /*Packs
   API Part*/
   
 public function activatePack(Player $player, $pack_name){
  	$this->getServer()->getPluginManager()->callEvent($event = new PlayerApplyPackEvent($this, $player, $pack_name, 0, null));
   if($event->isCancelled()){
     return false;
   }
 	$p = $this->data2->getAll();
 	$this->clearPlayerParticle($player);
 	foreach($p[$pack_name] as $pc){
  $this->addPlayerParticle($player, $pc);
 	}
 	return true;
 }
   
 public function createPack($pack_name){
 	$p = $this->data2->getAll();
 	$p[$pack_name][] = "";
 	$this->data2->setAll($p);
 	$this->data2->save();
 }
 
 public function addParticleToPack($pack_name, $particle){
 	$p = $this->data2->getAll();
 	$pa = array_search("", $p[$pack_name]);
  unset($p[$pack_name][$pa]);
 	$p[$pack_name][] = $particle;
 	$this->data2->setAll($p);
 	$this->data2->save();
 }
 
 public function removeParticleFromPack($pack_name, $particle){
 	 $p = $this->data2->getAll();
 	 $pc = array_search($particle, $p[$pack_name]);
   unset($p[$player->getName()]["particle"][$pc]);
   $this->data->setAll($p);
   $this->data->save();
 }
 
 public function getPack($pack_name){
 	$p = $this->data2->getAll();
 	return $p[$pack_name];
 }
 
 public function deletePack($pack_name){
 	$p = $this->data2->getAll();
 	unset($p[$pack_name]);
 	$this->data2->setAll($p);
 	$this->data2->save();
 }
 
 public function packExists($pack_name){
 	$p = $this->data2->getAll();
 	return isset($p[$pack_name]);
 }
 
 public function getPackParticles($pack_name){
 	$p = $this->data2->getAll();
 	$msg = "";
 	foreach($p[$pack_name] as $ps){
 		$msg .= $ps.", ";
 	}
 	return substr($msg, 0, -2);
 }
 
 public function listPacks(){
 	$p = $this->data2->getAll();
 	$array = array_keys($p);
 	$msg = "";
 	foreach($array as $pack_names){
 		$msg .= $pack_names.", ";
 	}
 	return substr($msg, 0, -2);;
 }
 
 /* RANDOM MODE
    API PART*/
 
 public function changeParticle(Player $player){
   $this->clearPlayerParticle($player);
   $this->addPlayerParticle($player, $this->particles->getRandomParticle());
   $this->addPlayerParticle($player, $this->particles->getRandomParticle());
   return true;
 }
 
 public function switchRandomMode(Player $player, $value){
      $this->getServer()->getPluginManager()->callEvent($event = new PlayerSwitchRandommodeEvent($this, $player, $value));
      if($event->isCancelled()){         
        return false;
      }
     	switch($value):
      case true:
 	     	$this->random_mode[$player->getName()] = $player->getName();
 	     	$this->putTemp($player);
 	     break;
 	    case false:
 	     	unset($this->random_mode[$player->getName()]);
 	     	$this->byeTemp($player);
 	     break;
   	endswitch;
   	return true;
 }
 
 public function isRandomMode(Player $player){
 	return in_array($player->getName(), $this->random_mode);
 }
 
}
?>