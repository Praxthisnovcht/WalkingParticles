<?php

namespace WalkingParticles\task;

use WalkingParticles\WalkingParticles;
use WalkingParticles\base\BaseTask;

class TimerTask extends BaseTask{

	public function onRun($tick){
		$t = $this->getPlugin()->getData("data4")->getAll();
		foreach(array_keys($t) as $p){
			$this->getPlugin()->getData("data4")->set($p, $this->getPlugin()->getData("data4")->get("p") - 1);
			$this->getPlugin()->getData("data4")->save();
			if($this->getPlugin()->getServer()->getPlayer($p) !== null){
				$this->getPlugin()->getServer()->getPlayer($p)->sendTip($this->getPlugin()->colourMessage("&aTry section time left:\n" . $this->getPlugin()->getData("data4")->get("p") . " seconds"));
			}
			if($this->getPlugin()->getData("data4")->get($p) === 0){
				$this->getPlugin()->getData("data4")->remove($p);
				$this->getPlugin()->getData("data4")->save();
			}
		}
	}

}