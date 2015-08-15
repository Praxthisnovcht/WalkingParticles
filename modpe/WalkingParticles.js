/*
 * This file is the modpe version of WalkingParticles.
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

var particles = [];

function modTick(){
	if(particles.length > 0){
		temp = particle.length - 1
		for(i=0;i=temp;i+1){
		 Level.addParticle(ParticleType.particle[i], Player.getX(), Player.getY(), Player.getZ(), 0, 0, 0, 3);
	 }
	}
}

function procCmd(cmd){
	cmd = cmd.split(" ");
	switch(cmd){
		case "walkp":
		 if(cmd[0] !== null){
		 	switch($cmd[0]){
		 		case "add":
		 		 if(cmd[1] !== null){
		 		 	pc = cmd[1];
		 		 }
		 		break;
		 		case "remove":
		 		 if(cmd[1] !== null){
		 		 	pc = cmd[1];
		 		 }
		 		break;
		 	}
		 }
		break;
	}
}

function newLevel(){
	clientMessage("You are using WalkingParticles mod by hoyinm14mc!");
}