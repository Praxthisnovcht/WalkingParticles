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
 
namespace WalkingParticles\commands;

use WalkingParticles\base\BaseCommand;
use WalkingParticles\Particles;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\Player;

class AdminCommand extends BaseCommand{
  
  public function onCommand(CommandSender $issuer, Command $cmd, $label, array $args){
   switch($cmd->getName()):
     case "walkp":
       if($issuer->hasPermission("walkingparticles.command") || $issuer->hasPermission("walkingparticles.command.admin")){
         if(isset($args[0])){
           switch($args[0]):
             case "help":
             case "h":
               if(isset($args[1])){
               	switch($args[1]):
               	 case 1:
               	   $this->getPlugin()->getServer()->dispatchCommand($issuer, "walkp help");
               	   return true;
               	 break;
               	 case 2:
               	   $issuer->sendMessage($this->getPlugin()->colourMessage("&aShowing help page &6(2/2)"));
               	   $issuer->sendMessage($this->getPlugin()->colourMessage("&l&b- &r&f/walkp pack <use|create|delete|addp|rmp|get|list> <args..>"));
               	   return true;
               	 break;
               	endswitch;
               }else{
               $issuer->sendMessage($this->getPlugin()->colourMessage("&aShowing help page &6(1/2)"));
               $issuer->sendMessage($this->getPlugin()->colourMessage("&l&b- &r&f/walkp defaultparticle <particle>"));
               $issuer->sendMessage($this->getPlugin()->colourMessage("&l&b- &r&f/walkp defaultamplifier <amplifier>"));
               $issuer->sendMessage($this->getPlugin()->colourMessage("&l&b- &r&f/walkp add <particle> <player>"));
               $issuer->sendMessage($this->getPlugin()->colourMessage("&l&b- &r&f/walkp remove <particle> <player>"));
               $issuer->sendMessage($this->getPlugin()->colourMessage("&l&b- &r&f/walkp amplifier <amplifier> <player>"));
               $issuer->sendMessage($this->getPlugin()->colourMessage("&l&b- &r&f/walkp display line|group <player>"));
               $issuer->sendMessage($this->getPlugin()->colourMessage("&l&b- &r&f/walkp randomshow <player>"));
               $issuer->sendMessage($this->getPlugin()->colourMessage("&l&b- &r&f/walkp clear <player>"));
               $issuer->sendMessage($this->getPlugin()->colourMessage("&l&b- &r&f/walkp get <player>"));
               $issuer->sendMessage($this->getPlugin()->colourMessage("&l&b- &r&f/walkp list"));
               return true;
              }
             break;
             case "setdefaultamplifier":
             case "defaultamplifier":
               if(isset($args[1])){
                 if(is_numeric($args[1])){
                   $this->getConfig()->set("default-amplifier", $args[1]);
                   $this->getConfig()->save();
                   $issuer->sendMessage("§fThe default §eamplifier §fof §bWalkingParticles §fhas been changed!");
                   return true;
                 }else{
                   $issuer->sendMessage("§cInvalid amplifier!");
                   return true;
                 }
               }else{
                 $issuer->sendMessage("§fUsage: /walkp defaultamplifier <amplifier>");
                 return true;
               }
             break;
             case "setdefaultparticle":
             case "defaultparticle":
               if(isset($args[1])){
                 $particle = $args[1];
                 $this->getConfig()->set("default-particle", $particle);
                 $this->getConfig()->save();
                 $issuer->sendMessage("§fThe default §bWalkingParticles §f has been changed!");
                 return true;
               }else{
                 $issuer->sendMessage("§fUsage: /walkp setdefault <particle>");
                 return true;
               }
             break;
            case "add":
            case "addparticle":
              if(isset($args[1])){
                $particle = $args[1];
                if(isset($args[2])){
                  $target = $this->getPlugin()->getServer()->getPlayer($args[2]);
                  if($target !== null){
                    if($particle == "all"){
                      foreach($this->getPlugin()->getParticles()->getAll() as $ps){
                        $this->getPlugin()->clearPlayerParticle($target);
                        $this->getPlugin()->addPlayerParticle($target, $ps);
                        $issuer->sendMessage("§aAdded §l§bALL §r§aparticles to ".$target->getName());
                        return true;
                      }
                    }else{
                      $this->getPlugin()->addPlayerParticle($target, $particle);
                      $issuer->sendMessage("§aYou added ".$particle." to §b".$target->getName()."§a's WalkingParticles!");
                      return true;
                    }
                  }else{
                    $issuer->sendMessage("§cInvalid target!");
                  }
                }else{
                  if($issuer instanceof Player){
                    if($particle == "all"){
                      foreach($this->getPlugin()->getParticles()->getAll() as $ps){
                        $this->getPlugin()->clearPlayerParticle($issuer);
                        $this->getPlugin()->addPlayerParticle($issuer, $ps);
                        $issuer->sendMessage("§aAdded §l§bALL §r§aparticles to you!");
                        return true;
                      }
                    }else{
                      $this->getPlugin()->addPlayerParticle($issuer, $particle);
                      $issuer->sendMessage("§aYou added §b".$particle." §aparticle to your WalkingParticles!");
                      return true;
                    }
                  }else{
                    $issuer->sendMessage("Usage: /walkp add <particle> <player>");
                    return true;
                  }
                }
              }else{
                $issuer->sendMessage("Usage: /walkp add <particle> <player>");
                return true;
              }
            break;
            case "removeparticle":
            case "rmparticle":
            case "remove":
              if(isset($args[1])){
                $particle = $args[1];
                if(isset($args[2])){
                  $target = $this->getPlugin()->getServer()->getPlayer($args[2]);
                  if($target !== null){
                    $this->getPlugin()->removePlayerParticle($target, $particle);
                    $issuer->sendMessage("§aYou removed §b".$target->getName()."§a's Walking Particle!");
                    return true;
                  }else{
                    $issuer->sendMessage("§cInvalid target!");
                    return true;
                  }
                }else{   
                  if($issuer instanceof Player){
                    $this->getPlugin()->removePlayerParticle($issuer, $particle);
                    $issuer->sendMessage("§aYour particle '§b".$particle."§a' has been removed!");
                    return true;
                  }else{
                    $issuer->sendMessage("Usage: /walkp remove <particle> <player>");
                    return true;
                  }  
                }
              }else{
                $issuer->sendMessage("Usage: /walkp remove <particle> <player>");
                return true;
              }
            break;
            case "setamplifier":
            case "amplifier":
              if(isset($args[2]) && isset($args[1])){
                if(is_numeric($args[1])){
                  $target = $this->getPlugin()->getServer()->getPlayer($args[2]);
                  if($target !== null){
                    $amplifier = $args[1];
                    $this->getPlugin()->setPlayerAmplifier($target, $amplifier);
                    $issuer->sendMessage("§aYou changed §b".$target->getName()."§a's amplifier!");
                    return true;
                  }else{
                    $issuer->sendMessage("§cInvalid target!");
                    return true;
                  }
                }else{
                  $issuer->sendMessage("§cInvalid amplifier!");
                  return true;
                }
              }else if(isset($args[1]) && !isset($args[2])){
                if(is_numeric($args[1]) !== false){
                  if($issuer instanceof Player){
                    $amplifier = $args[1];
                    $this->getPlugin()->setPlayerAmplifier($issuer, $amplifier);
                    $issuer->sendMessage("§aYou changed yout amplifier of §bWalkingParticles§a!");
                    return true;
                  }else{
                    $issuer->sendMessage("Usage: /wparticles amplifier <amplifier> <player>");
                    return true;
                  }
                }else{
                  $issuer->sendMessage("§cInvalid amplifier!");
                  return true;
                }
              }else{
                $issuer->sendMessage("§fUsage: /walkp amplifier <amplifier> <player>");
                return true;
              }
            break;
            case "display":
              if(isset($args[1]) && isset($args[2])){
                $target = $this->getPlugin()->getServer()->getPlayer($args[2]);
                if($target !== null){
                    switch($args[1]):
                      case "line":
                        $this->getPlugin()->setPlayerDisplay($target, "line");
                        $issuer->sendMessage("§aYou set §e".$target->getName()."§a's display to §bline§a!");
                        return true;
                      case "group":
                        $this->getPlugin()->setPlayerDisplay($target, "group");
                      $issuer->sendMessage("§aYou set §e".$target->getName()."§a's display to §bgroup§a!");
                        return true;
                      default:
                        $issuer->sendMessage("Usage: /walkp display line|group <target>");
                        return true;
                    endswitch;
                  }else{
                    $issuer->sendMessage("§cInvalid target!");
                    return true;
                  }
              }else if(isset($args[1]) && !isset($args[2])){
                if($issuer instanceof Player){
                  switch($args[1]):
                    case "line":
                      $this->getPlugin()->setPlayerDisplay($issuer, "line");
                      $issuer->sendMessage("§aYou set your display to §bline§a!");
                      return true;
                    case "group":
                      $this->getPlugin()->setPlayerDisplay($issuer, "group");
                      $issuer->sendMessage("§aYou set your display to §bgroup§a!");
                      return true;
                    default:
                      $issuer->sendMessage("Usage: /walkp display line|group");
                      return true;
                  endswitch;
                }
              }else{
                $issuer->sendMessage("Usage: /walkp display line|group <target>");
                return true;
              }
            break;
            case "clear":
            case "stop":
              if(isset($args[1])){
                $target = $this->getPlugin()->getServer()->getPlayer($args[1]);
                if($target !== null){
                  if($this->getPlugin()->isCleared($target) !== true){
                    $this->getPlugin()->clearPlayerParticle($target);
                    $issuer->sendMessage("§aYou cleared §b".$target->getName()."§a's WalkingParticles!");
                    $target->sendMessage("§aYour §bWalkingParticles §ahas been cleared!");
                    return true;
                  }else{
                    $issuer->sendMessage("§cThere is no particle in use!");
                    return true;
                  }
                }else{
                  $issuer->sendMessage("§cInvalid target!");
                  return true;
                }
              }else{
              	  if($issuer instanceof Player){
                  if($this->getPlugin()->isCleared($issuer) !== true){
                    $this->getPlugin()->clearPlayerParticle($issuer);
                    $issuer->sendMessage("§aYour §bWalkingParticles §ahas been cleared!");
                    return true;
                  }else{
                    $issuer->sendMessage("§cThere are no particles in use!");
                  return true;
                  }
                }else{
                	  $issuer->sendMessage("Commamd only works in-game!");
                	  return true;
                }
              }
            break;
            case "pack":
             if(isset($args[1])){
             	 switch($args[1]):
             	  case "use":
             	  case "apply":
             	   if(isset($args[2])){
             	   	if($this->getPlugin()->packExists($args[2])){
             	   		if($issuer instanceof Player){
             	   			$this->getPlugin()->activatePack($issuer, $args[2]);
             	    		$issuer->sendMessage("§aYou are now using walkp pack §b".$args[2]);
             	    		return true;
             	   		}else{
             	   			$issuer->sendMessage("Command only works in-game!");
             	   			return true;
             	   		}
             	   	}else{
             	   		$issuer->sendMessage("§cPack doesn't exist!");
             	   		return true;
             	   	}
             	   }else{
             	   	$issuer->sendMessage("Usage: /walkp pack use <pack_name>");
             	   	return true;
             	   }
             	  break;
             	  case "create":
             	  case "cre":
             	   if(isset($args[2])){
             	   	$pack_name = $args[2];
             	   	if($this->getPlugin()->packExists($pack_name) !== true){
             	   		$this->getPlugin()->createPack($pack_name);
             	   		$issuer->sendMessage("§aWalkp pack created successfully with the name §b".$pack_name);
             	   		return true;
             	   	}else{
             	   		$issuer->sendMessage("§cPack already exists!");
             	   		return true;
             	   	}
             	   }else{
             	   	$issuer->sendMessage("Usage: /walkp pack create <pack_name>");
             	   	return true;
             	   }
             	  break;
             	  case "delete":
             	  case "del":
             	        	   if(isset($args[2])){
             	   	$pack_name = $args[2];
             	   	if($this->getPlugin()->packExists($pack_name) !== false){
             	   		$this->getPlugin()->deletePack($pack_name);
             	   		$issuer->sendMessage("§aWalkp pack deleted successfully!");
             	   		return true;
             	   	}else{
             	   		$issuer->sendMessage("§cPack doesn't exists!");
             	   		return true;
             	   	}
             	   }else{
             	   	$issuer->sendMessage("Usage: /walkp pack delete <pack_name>");
             	   	return true;
             	   }
             	  break;
             	  case "addp":
             	    if(isset($args[2]) && isset($args[3])){
             	    if($this->getPlugin()->packExists($args[2]) !== false){
             	    	$this->getPlugin()->addParticleToPack($args[2], $args[3]);
             	    	$issuer->sendMessage("§aYou added §e".$args[3]." §aparticle to the pack §b".$args[2]."§a!");
             	    	return true;
             	    }else{
             	    	$issuer->sendMessage("§cPack doesn't exist!");
             	    	return true;
             	    }
             	   }else{
             	    $issuer->sendMessage("Usage: /walkp addp <pack_name> <particle>");
             	    return true;
             	   }
             	  break;
             	  case "rmp":
             	    if(isset($args[2]) && isset($args[3])){
             	    if($this->getPlugin()->packExists($args[2])){
             	    	$this->getPlugin()->removeParticleFromPack($args[2], $args[3]);
             	    	$issuer->sendMessage("§aYou removed §e".$args[3]." §aparticle from the pack §b".$args[2]."§a!");
             	    	return true;
             	    }else{
             	    	$issuer->sendMessage("§cPack doesn't exist!");
             	    	return true;
             	    }
             	   }else{
             	    $issuer->sendMessage("Usage: /walkp rmp <pack_name> <particle>");
             	    return true;
             	   }
             	  break;
             	  case "get":
             	    if(isset($args[2])){
             	    	$pack_name = $args[2];
             	    	if($this->getPlugin()->packExists($pack_name) !== false){
             	    		$issuer->sendMessage("§aPack §b".$pack_name." §acontains: §6".$this->getPlugin()->getPackParticles($pack_name));
             	    		return true;
             	    	}else{
             	    		$issuer->sendMessage("§cPack doesn't exist!");
             	    		return true;
             	    	}
             	    }else{
             	    	$issuer->sendMessage("Usage: /walkp pack get <pack_name>");
             	    	return true;
             	    }
             	  break;
             	  case "list":
             	   $issuer->sendMessage("§aList of particle packs: §6".$this->getPlugin()->listPacks());
             	   return true;
             	  break;
             	 endswitch;
             }else{
             	 $issuer->sendMessage("Usage: /walkp pack <use|create|delete|addp|rmp|get|list> <args..>");
             	 return true;
             }
            break;
            case "randomshow":
            case "random":
            case "randommode":
              if(isset($args[1])){
               $target = $this->getPlugin()->getServer()->getPlayer($args[1]);
               if($target !== null){
                $this->getPlugin()->switchRandomMode($target, ($this->getPlugin()->isRandomMode($target) !== true ? true : false));
                $issuer->sendMessage("§aYou turned ".($this->getPlugin()->isRandomMode($target) !== true ? "off" : "on")." §b".$target->getName()."§a's random mode!");
                $target->sendMessage("§aYour random mode has been turned ".($this->getPlugin()->isRandomMode($target) !== true ? "off" : "on")."!");
                return true;
               }else{
                $issuer->sendMessage("§cInvalid target!");
                return true;
               }
              }else{
               if($issuer instanceof Player){
                $this->getPlugin()->switchRandomMode($issuer, ($this->getPlugin()->isRandomMode($issuer) !== true ? true : false));
                $issuer->sendMessage("§aYour random mode has been turned ".($this->getPlugin()->isRandomMode($issuer) !== true ? "off" : "on")."!");
                return true;
               }else{
                $issuer->sendMessage("Command only works in-game!");
                return true;
               }
              }
            break;
            case "get":
              if(isset($args[1])){
                $target = $this->getPlugin()->getServer()->getPlayer($args[1]);
                if($target !== null){
                  $issuer->sendMessage("§e".$target->getName()."§a's §bWalkingParticles§a: §f".$this->getPlugin()->getAllPlayerParticles($target));
                  return true;
                }else{
                  $issuer->sendMessage("§cInvalid target!");
                  return true;
                }
              }else{
              	  if($issuer instanceof Player){
              	  	  $issuer->sendMessage("§aYour §bWalkingParticles§a: §f".$this->getPlugin()->getAllPlayerParticles($issuer));
                  return true;
              	  }else{
              	  	  $issuer->sendMessage("Command only works in-game!");
              	  	  return true;
              	  }
              }
            break;
            case "list":
              $particles = new Particles($this->getPlugin());
              $issuer->sendMessage("§aList of available particles: §6".implode(", ", $particles->getAll()));
              return true;
            break;
          endswitch;
        }else{
          return false;
        }
      }else{
        $issuer->sendMessage("§cYou don't have permission for this!");
        return true;
      }
    break;
   endswitch;
 }
  
}
?>