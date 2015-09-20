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
use WalkingParticles\WalkingParticles;
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
	public function __construct(WalkingParticles $plugin, $channel){
		$this->plugin = $plugin;
		$this->channel = $channel;
	}

	public function checkUpdate(){
		if($this->channel == "stable"){
			$address = "http://forums.pocketmine.net/api.php?action=getResource&value=1192";
		} else if($this->channel == "beta"){
			$address = "https://api.github.com/repos/cybercube-hk/walkingparticles/releases";
		} else{
			$this->plugin->getLogger()->info("[UPDATER] INVALID CHANNEL!");
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
		$plugin = new WalkingParticles();
		if($this->plugin->getConfig()->get("v") != $this->newversion){
			$path = $this->plugin->getDataFolder()."newest-version-download-link.txt";
			if(file_exists($path)){
				unlink($path);
			}
			echo "\n";
			$this->plugin->getLogger()->info("Your version is too old/new!  The latest ".$this->channel." version is: (version: " . $this->newversion . ")");
			$this->plugin->getLogger()->info("Download url for the latest version: §e" . $this->dlurl . "");
			$this->plugin->getLogger()->info("The link is being saved into: newest-version-download-link.txt\n");
			$txt = new Config($path, Config::ENUM);
			$txt->set($this->dlurl, true);
			$txt->save();
			return true;
		}
		$this->plugin->getLogger()->info("No updates found!  Your WalkingPartices version is up-to-date!");
		return true;
	}

}
?>