<?php 

	abstract class PieceLonguePortee extends Piece{
	
		function completeTableauCoupsTheoriquementPossibles($unJeuDEchec,&$tableauDesDeplacementsAutorises,$positionEventuelle){
			if($unJeuDEchec->getPlateau()->surPlateau($positionEventuelle)){
				if(get_class($unJeuDEchec->getPlateau()->sePlacerALaPosition($positionEventuelle))==='PositionOccupee' && $this->atteritSurPieceEnnemieSiOccupeLaPosition($unJeuDEchec,$positionEventuelle)){
					$tableauDesDeplacementsAutorises[]=$positionEventuelle;
					return false;
				}
				else if(get_class($unJeuDEchec->getPlateau()->sePlacerALaPosition($positionEventuelle))==='PositionOccupee' && $this->atteritSurPieceAllieeSiOccupeLaPosition($positionEventuelle)){
					return false;
				}
				else{
					$tableauDesDeplacementsAutorises[]=$positionEventuelle;
					return true;
				}
			}
			return false;
		}
		
		function genereCoupsPossiblesDiagonales($unJeuDEchec,&$tableauDesDeplacementsAutorises){
			$diagonaleBasDroiteOk=true;
			$diagonaleBasGaucheOk=true;
			$diagonaleHautDroiteOk=true;
			$diagonaleHautGaucheOk=true;
			for ($i=1;$i<=7;$i++){
				if($diagonaleBasDroiteOk){
					$positionEventuelle=new Position($this->getPosition()->getI()+$i,$this->getPosition()->getJ()+$i);
					$diagonaleBasDroiteOk=$this->completeTableauCoupsTheoriquementPossibles($unJeuDEchec,$tableauDesDeplacementsAutorises,$positionEventuelle);
				}
				if($diagonaleBasGaucheOk){
					$positionEventuelle=new Position($i+$this->getPosition()->getI(),$this->getPosition()->getJ()-$i);
					$diagonaleBasGaucheOk=$this->completeTableauCoupsTheoriquementPossibles($unJeuDEchec,$tableauDesDeplacementsAutorises,$positionEventuelle);
				}
				if($diagonaleHautDroiteOk){
					$positionEventuelle=new Position($this->getPosition()->getI()-$i,$i+$this->getPosition()->getJ());
					$diagonaleHautDroiteOk=$this->completeTableauCoupsTheoriquementPossibles($unJeuDEchec,$tableauDesDeplacementsAutorises,$positionEventuelle);
				}
				if($diagonaleHautGaucheOk){
					$positionEventuelle=new Position($this->getPosition()->getI()-$i,$this->getPosition()->getJ()-$i);
					$diagonaleHautGaucheOk=$this->completeTableauCoupsTheoriquementPossibles($unJeuDEchec,$tableauDesDeplacementsAutorises,$positionEventuelle);					
				}	
			}		
		}
		
		function genereCoupsPossiblesLignesColonnes($unJeuDEchec,&$tableauDesDeplacementsAutorises){
			$colonneBasOk=true;
			$colonneHautOk=true;
			$ligneGaucheOk=true;
			$ligneDroiteOk=true;
			for($k=1;$k<=7;$k++){
				if($colonneBasOk){
					$positionEventuelle=new Position($this->getPosition()->getI()+$k,$this->getPosition()->getJ());
					$colonneBasOk=$this->completeTableauCoupsTheoriquementPossibles($unJeuDEchec,$tableauDesDeplacementsAutorises,$positionEventuelle);
				}
				if($colonneHautOk){
					$positionEventuelle=new Position($this->getPosition()->getI()-$k,$this->getPosition()->getJ());
					$colonneHautOk=$this->completeTableauCoupsTheoriquementPossibles($unJeuDEchec,$tableauDesDeplacementsAutorises,$positionEventuelle);
				}
				if($ligneGaucheOk){
					$positionEventuelle=new Position($this->getPosition()->getI(),$this->getPosition()->getJ()-$k);
					$ligneGaucheOk=$this->completeTableauCoupsTheoriquementPossibles($unJeuDEchec,$tableauDesDeplacementsAutorises,$positionEventuelle);
				}
				if($ligneDroiteOk){
					$positionEventuelle=new Position($this->getPosition()->getI(),$this->getPosition()->getJ()+$k);
					$ligneDroiteOk=$this->completeTableauCoupsTheoriquementPossibles($unJeuDEchec,$tableauDesDeplacementsAutorises,$positionEventuelle);					
				}	
			}		
		}
		
	}
	
?>