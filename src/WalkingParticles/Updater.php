<?php

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
                $files = files($this->plugin->getServer()->getDataPath()."plugins");
                foreach($files as $file){
                        if(strpos($file, "WalkingParticles")){
                                unlink($file);
                        }
                }
                //Update..
        }
}
