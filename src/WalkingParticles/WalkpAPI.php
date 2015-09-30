<?php

namespace WalkingParticles;

interface WalkpAPI{
        
        public function tryPlayerParticle($player, $player2);
        public function usePlayerParticle($player, $player2);
        public function playerTempExists($player);
        public function putTemp($player);
        public function byeTemp($player);
        public function setPlayerParticle($player, $particle);
        public function addPlayerParticle($player, $particle);
        public function removePlayerParticle($player, $particle);
        public function clearPlayerParticle($player);
        public function getAllPlayerParticles($player);
        public function isCleared($player);
        public function setPlayerAmplifier($player, $amplifier);
        public function getPlayerAmplifier($player);
        public function setPlayerDisplay($player, $display);
        public function getPlayerDisplay($player);
        public function activatePack($player, $pack_name);
        public function createPack($pack_name);
        public function addParticleToPack($pack_name, $particle);
        public function getPack($pack_name);
        public function deletePack($pack_name);
        public function packExists($pack_name);
        public function getPackParticles($pack_name);
        public function listPacks();
        public function changeParticle($player);
        public function switchRandomMode($player, $value = true);
        public function isRandomMode($player);
        public function switchItemMode($player, $value = true);
        public function isItemMode($player);
        
}
?>
