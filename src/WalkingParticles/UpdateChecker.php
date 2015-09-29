<?php

/*
 * This file is a part of WalkingParticles.
 * Copyright (C) 2015 CyberCube-HK
 *
 * WalkingParticles is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * WalkingParticles is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with WalkingParticles. If not, see <http://www.gnu.org/licenses/>.
 */
namespace WalkingParticles;

use pocketmine\Server;
use WalkingParticles\events\UpdateCheckingEvent;
use pocketmine\utils\Utils;
use pocketmine\utils\Config;

class UpdateChecker{

	private $channel;

	private $plugin;

	/**
	 *
	 * @param string $channel        	
	 * @param int $interval        	
	 */
	public function __construct($plugin, $channel){
		$this->plugin = $plugin;
		$this->channel = $channel;
	}
	
	private function getPlugin(){
		return $this->plugin;
	}

	public function checkUpdate(){
		$this->getPlugin()->getServer()->getPluginManager()->callEvent($event = new UpdateCheckingEvent($this->getPlugin()));
		if($event->isCancelled()){
			return false;
		}
		//Android device not checkable
		if(! file_exists($this->getPlugin()->getServer()->getDataPath() . "start.cmd" || ! file_exists($this->getPlugin()->getServer()->getDataPath() . "start.sh"))){
			echo "Command not being supported on your device!";
			return;
		}
		if($this->channel == "stable"){
			$address = "http://forums.pocketmine.net/api.php?action=getResource&value=1192";
		} else if($this->channel == "beta"){
			$address = "https://api.github.com/repos/cybercube-hk/walkingparticles/releases";
		} else{
			$this->plugin->getLogger()->alert("[UPDATER] INVALID CHANNEL!");
			return false;
		}
		$i = json_decode(Utils::getURL($address), true);
		if($this->channel == "beta"){
			$i = $i[0];
			$this->newversion = substr($i["name"], 18);
			$this->dlurl = $i["assets"][0]["browser_download_url"];
		} else if($this->channel == "stable"){
			$this->newversion = $i["version_string"];
			$this->dlurl = "http://forums.pocketmine.net/plugins/walkingparticles.1192/download?version=" . $i["current_version_id"];
		}
		$plugin = $this->getPlugin();
		if($plugin::VERSION !== $this->newversion){
			$path = $this->plugin->getDataFolder() . "newest-version-download-link.txt";
			echo "\n";
			$this->plugin->getLogger()->info("Your version is too old or too new!  The latest " . $this->channel . " version is: (version: " . $this->newversion . ")");
			$this->plugin->getLogger()->info("Download url for the latest version: §e" . $this->dlurl . "");
			$this->plugin->getLogger()->info("The link is being saved into: §bnewest-version-download-link.txt\n");
			$txt = new Config($path, Config::ENUM);
			$txt->set("Version ".$this->newversion." -> ".$this->dlurl, true);
			$txt->save();
			return true;
		}
		echo "\n";
		$this->plugin->getLogger()->info("No updates found!  Your WalkingPartices version is up-to-date!\n");
		return true;
	}

}
?>
