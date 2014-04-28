<?php 

	abstract class PieceLonguePortee extends Piece{
	
		function completeTableauDesDeplacementsAutorises($unPlateau,&$tableauDesDeplacementsAutorises,$positionEventuelle){
			if($unPlateau->surPlateau($positionEventuelle)){
				if(get_class($unPlateau->sePlacerALaPosition($positionEventuelle))==='PositionOccupee' && $unPlateau->surPieceEnnemie($this,$unPlateau->sePlacerALaPosition($positionEventuelle))){
					$tableauDesDeplacementsAutorises[]=$positionEventuelle;
					return false;
				}
				else if(get_class($unPlateau->sePlacerALaPosition($positionEventuelle))==='PositionOccupee' && $unPlateau->surPieceAlliee($this,$unPlateau->sePlacerALaPosition($positionEventuelle))){
					return false;
				}
				else{
					$tableauDesDeplacementsAutorises[]=$positionEventuelle;
					return true;
				}
			}			
		}
		
		function genereCoupsPossiblesDiagonales($unPlateau,&$tableauDesDeplacementsAutorises){
			$diagonaleBasDroiteOk=true;
			$diagonaleBasGaucheOk=true;
			$diagonaleHautDroiteOk=true;
			$diagonaleHautGaucheOk=true;
			for ($i=1;$i<=7;$i++){
				if($diagonaleBasDroiteOk){
					$positionEventuelle=new Position($this->getPosition()->getI()+$i,$this->getPosition()->getJ()+$i);
					$diagonaleBasDroiteOk=$this->completeTableauDesDeplacementsAutorises($unPlateau,$tableauDesDeplacementsAutorises,$positionEventuelle);
				}
				if($diagonaleBasGaucheOk){
					$positionEventuelle=new Position($i+$this->getPosition()->getI(),$this->getPosition()->getJ()-$i);
					$diagonaleBasGaucheOk=$this->completeTableauDesDeplacementsAutorises($unPlateau,$tableauDesDeplacementsAutorises,$positionEventuelle);
				}
				if($diagonaleHautDroiteOk){
					$positionEventuelle=new Position($this->getPosition()->getI()-$i,$i+$this->getPosition()->getJ());
					$diagonaleHautDroiteOk=$this->completeTableauDesDeplacementsAutorises($unPlateau,$tableauDesDeplacementsAutorises,$positionEventuelle);
				}
				if($diagonaleHautGaucheOk){
					$positionEventuelle=new Position($this->getPosition()->getI()-$i,$this->getPosition()->getJ()-$i);
					$diagonaleHautGaucheOk=$this->completeTableauDesDeplacementsAutorises($unPlateau,$tableauDesDeplacementsAutorises,$positionEventuelle);					
				}	
			}		
		}
		
		function genereCoupsPossiblesLignesColonnes($unPlateau,&$tableauDesDeplacementsAutorises){
			$colonneBasOk=true;
			$colonneHautOk=true;
			$ligneGaucheOk=true;
			$ligneDroiteOk=true;
			for($k=1;$k<=7;$k++){
				if($colonneBasOk){
					$positionEventuelle=new Position($this->getPosition()->getI()+$k,$this->getPosition()->getJ());
					$colonneBasOk=$this->completeTableauDesDeplacementsAutorises($unPlateau,$tableauDesDeplacementsAutorises,$positionEventuelle);
				}
				if($colonneHautOk){
					$positionEventuelle=new Position($this->getPosition()->getI()-$k,$this->getPosition()->getJ());
					$colonneHautOk=$this->completeTableauDesDeplacementsAutorises($unPlateau,$tableauDesDeplacementsAutorises,$positionEventuelle);
				}
				if($ligneGaucheOk){
					$positionEventuelle=new Position($this->getPosition()->getI(),$this->getPosition()->getJ()-$k);
					$diagonaleHautDroiteOk=$this->completeTableauDesDeplacementsAutorises($unPlateau,$tableauDesDeplacementsAutorises,$positionEventuelle);
				}
				if($ligneDroiteOk){
					$positionEventuelle=new Position($this->getPosition()->getI(),$this->getPosition()->getJ()+$k);
					$ligneDroiteOk=$this->completeTableauDesDeplacementsAutorises($unPlateau,$tableauDesDeplacementsAutorises,$positionEventuelle);					
				}	
			}		
		}
		
	}
	
?>