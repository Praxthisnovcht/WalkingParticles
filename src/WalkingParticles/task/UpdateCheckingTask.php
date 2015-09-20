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
namespace WalkingParticles\task;

use WalkingParticles\UpdateChecker;
use WalkingParticles\base\BaseTask;

class UpdateCheckingTask extends BaseTask{
	public function onRun($tick){
		if($this->getPlugin()->getConfig()->get("enable-updatechecker") !== true){
			return;
		}
		$this->getPlugin()->getLogger()->info($this->getPlugin()->colourMessage("Checking for update..  It may take you some time...  (Channel: ".$this->plugin->getConfig()->get("channel-updatechecker").")"));
		$updatechecker = new UpdateChecker($this->getPlugin(), $this->getPlugin()->getConfig()->get("channel-updatechecker"));
		try{
			$updatechecker->checkUpdate();
		} catch(\Exception $e){
			$this->getPlugin()->getLogger()->debug("Error!  Unable to check update.  Reason: $e");
		}
	}
	
}
?>