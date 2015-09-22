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
namespace WalkingParticles\commands;

use WalkingParticles\base\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\Player;

class WprandCommand extends BaseCommand{
        
        public function onCommand(CommandSender $issuer, Command $cmd, $label, array $args){
                if($cmd->getName() == "wprand"){
                        if(!$issuer instanceof Player){
                                $issuer->sendMessage("Command only works in-game!");
                                return true;
                        }
                        if(!$issuer->hasPermission("walkingparticles.command.wprand")){
                                $issuer->sendMessage($this->getPlugin()->colourMessage("&cYou don't have permission for this!"));
                                return true;
                        }
                        $this->getPlugin()->switchRandomMode();
                        return true;
                }
                
        }
        ?>
        
}
