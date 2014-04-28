<?php

	class Cheval extends Piece{
	
		/*Renvoie un tableau de positions pouvant être théoriquement occupées par un cheval*/
		
		function tableauCoupsTheoriquementPossibles($unJeuDEchec){
			$this->tabCoupsTheoriquementPossibles=array();
			for ($i=-2;$i<=2;$i++ ){
				for ($j=-2;$j<=2;$j++ ){
					if(abs($i)+abs($j)==3){
						$positionEventuelle=new Position($i+$this->getPosition()->getI(),$j+$this->getPosition()->getJ());
						if($unJeuDEchec->getPlateau()->surPlateau($positionEventuelle) && $this->nAtteritPasSurPieceAllieeSiOccupeLaPosition($positionEventuelle)){
							$this->tabCoupsTheoriquementPossibles[]=$positionEventuelle;
						}
					}
				}
			}
		}
		
	}
	
?>