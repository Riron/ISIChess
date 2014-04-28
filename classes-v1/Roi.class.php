<?php 

	/*require('Piece.class.php');
	require('Cheval.class.php');
	require('Pion.class.php');
	require('Plateau.class.php');
	require('Reine.class.php');
	require('Fou.class.php');
	require('Tour.class.php');
	require('Position.class.php');
	require('PositionOccupee.class.php');*/

	class Roi extends Piece{
	
		/*Renvoie un tableau de positions pouvant être occupées par un cheval*/
		
		function estEnEchec($unPlateau){
			
		}
		
		
		function tableauDeplacementsAutorises($unPlateau){
			$tableauDesDeplacementsAutorises=array();
			for ($i=-1;$i<=1;$i++){
				for ($j=-1;$j<=1;$j++){
					if($i!=0 || $j!=0){
						$positionEventuelle=new Position($i+$this->getPosition()->getI(),$j+$this->getPosition()->getJ());
						if($unPlateau->surPlateau($positionEventuelle) && $unPlateau->pasSurPieceAlliee($this,$positionEventuelle) && $unPlateau->roiNeSeMetPasEnEchec($positionEventuelle)){
							$tableauDesDeplacementsAutorises[]=$positionEventuelle;
						}
					}
				}
			}
			return $tableauDesDeplacementsAutorises;
		}
		
	}
	
?>