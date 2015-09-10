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
use WalkingParticles\economy\Economyapi;
use WalkingParticles\economy\Goldstd;
use WalkingParticles\economy\Massiveeconomy;
use WalkingParticles\economy\Pocketmoney;
use WalkingParticles\WalkingParticles;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\Player;

class WppackCommand extends BaseCommand{

	public function onCommand(CommandSender $issuer, Command $cmd, $label, array $args){
		switch($cmd->getName()):
			case "wppack":
				if($issuer->hasPermission("walkingparticles.command") || $issuer->hasPermission("walkingparticles.command.wppack")){
					if($issuer instanceof Player){
						if(isset($args[0])){
							switch($args[0]):
								case "apply":
									if(isset($args[1])){
										$pack_name = $args[1];
										if($this->getPlugin()->getEco() !== null){
											switch($this->getPlugin()->getEco()->getName()):
												case "EconomyAPI":
													$economyapi = new Economyapi($this->getPlugin());
													$economyapi->applyPack($issuer, $pack_name);
													return true;
												break;
												case "PocketMoney":
													$pocketmoney = new Pocketmoney($this->getPlugin());
													$pocketmoney->applyPack($issuer, $pack_name);
													return true;
												break;
												case "MassiveEconomy":
													$me = new Massiveeconomy($this->getPlugin());
													$me->applyPack($issuer, $pack_name);
													return true;
												break;
												case "GoldStd":
													$goldstd = new Goldstd($this->getPlugin());
													$goldstd->applyPack($issuer, $pack_name);
													return true;
												break;
											endswitch
											;
										} else{
											$issuer->sendMessage("Command is currently disabled!");
											return true;
										}
									} else{
										$issuer->sendMessage("Usage: /wppack apply <pack>");
										return true;
									}
								break;
								case "get":
									if(isset($args[1])){
										$pack_name = $args[1];
										if($this->getPlugin()->packExists($pack_name) !== false){
											$issuer->sendMessage($this->getPlugin()->colourMessage("&aPack &b" . $pack_name . " &acontains: &6" . $this->getPlugin()->getPackParticles($pack_name)));
											return true;
										} else{
											$issuer->sendMessage($this->getPlugin()->colourMessage("&cPack doesn't exist!"));
											return true;
										}
									} else{
										$issuer->sendMessage("Usage: /wppack get <pack>");
										return true;
									}
								break;
								case "list":
									$issuer->sendMessage($this->getPlugin()->colourMessage("&aList of particle packs: &6" . $this->getPlugin()->listPacks()));
									return true;
								break;
							endswitch
							;
						} else{
							return false;
						}
					} else{
						$issuer->sendMessage("Command only works in-game!");
						return true;
					}
				} else{
					$issuer->sendMessage($this->getPlugin()->colourMessage("&cYou don't have permission for this!"));
					return true;
				}
			break;
		endswitch
		;
	}

}
?>