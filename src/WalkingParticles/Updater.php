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

use WalkingParticles\WalkingParticles;

class Updater{

	private $plugin;

	private $dlurl;

	private $version;

	public function __construct(WalkingParticles $plugin, $dlurl, $path, $version){
		$this->plugin = $plugin;
		$this->dlurl = $dlurl;
		$this->version = $version;
	}

	public function update(){
		$files = files($this->plugin->getServer()->getDataPath() . "plugins");
		foreach($files as $file){
			if(strpos($file, "WalkingParticles_")){
				unlink($file);
			}
		}
	}

}
