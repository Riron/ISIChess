<?php 

	/*require('Piece.class.php');
	require('Plateau.class.php');
	require('Pion.class.php');
	require('Roi.class.php');
	require('Reine.class.php');
	require('Fou.class.php');
	require('Tour.class.php');
	require('Position.class.php');
	require('PositionOccupee.class.php');*/

	class Cheval extends Piece{
	
		/*Renvoie un tableau de positions pouvant être occupées par un cheval*/
		
		function tableauDeplacementsAutorises($unPlateau){
			for ($i=-2;$i<=2;$i++ ){
				for ($j=-2;$j<=2;$j++ ){
					if(abs($i)+abs($j)==3){
						$positionEventuelle=new Position($i+$this->getPosition()->getI(),$j+$this->getPosition()->getJ());
						if($unPlateau->surPlateau($positionEventuelle) && $unPlateau->pasSurPieceAlliee($this,$positionEventuelle)){
							$tableauDesDeplacementsAutorises[]=$positionEventuelle;
						}
					}
				}
			}
			return $tableauDesDeplacementsAutorises;
		}
		
	}
	
?>