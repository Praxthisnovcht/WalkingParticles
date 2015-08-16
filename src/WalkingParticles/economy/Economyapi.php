<?php

/*
 * This file is a part of WalkingParticles.
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

namespace WalkingParticles\economy;

use WalkingParticles\WalkingParticles;
use WalkingParticles\events\PlayerApplyPackEvent;
use pocketmine\Player;

class Economyapi{
	
	public $plugin;
	
	public function __construct(WalkingParticles $plugin){
		$this->plugin = $plugin;
	}
	
	public function applyPack(Player $player, $pack){
		$this->plugin->getServer()->getPluginManager()->callEvent($event = new PlayerApplyPackEvent($this, $player, $pack_name, 1, 2));
  if($event->isCancelled()){
    return false;
  }
		$money = $this->plugin->getEco()->getInstance()->myMoney($player);
		if($money < $this->plugin->getConfig()->get("apply-pack-fee")){
			$player->sendMessage($this->plugin->colourMessage("&cYou don't have enough money to apply the pack!\n&cYou need ".$this->plugin->getConfig()->get("apply-pack-fee")));
			return false;
		}
		if($this->plugin->packExists($pack) !== true){
			$player->sendMessage($this->plugin->colourMessage("&cPack doesn't exist!"));
			return false;
		}
		$this->plugin->getEco()->getInstance()->reduceMoney($player, $this->plugin->getConfig()->get("apply-pack-fee"));
		$this->plugin->activatePack($player, $pack);
		$player->sendMessage($this->plugin->colourMessage("&aYou applied &b".$pack." &apack successfully!"));
		$player->sendMessage("Bank : -$".$this->plugin->getConfig()->get("apply-pack-fee")." | $".$this->plugin->getEco()->getInstance()->myMoney($player)." left");
		return true;
	}
	
}
?>