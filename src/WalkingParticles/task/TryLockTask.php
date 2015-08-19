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

namespace WalkingParticles\task;

use pocketmine\scheduler\PluginTask;
use pocketmine\math\Vector3;
use pocketmine\Player;

use WalkingParticles\WalkingParticles;

class TryLockTask extends PluginTask{
  public $plugin;
  public $player;
  
  public function __construct(WalkingParticles $plugin, Player $player){
    $this->plugin = $plugin;
    $this->player = $player;
    parent::__construct($plugin);
  }
  
  public function onRun($tick){
    if($this->player !== null && in_array($this->player->getName(), $this->plugin->try_locked)){
      unset($this->plugin->try_locked[$this->player->getName()]);
      $this->player->sendTip("You are now able to try particles xD");
      $this->plugin->getServer()->getScheduler()->cancelTask($this->getTaskId());
    }
  }
  
}
?>