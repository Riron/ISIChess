<?php 

	/*require('Piece.class.php');
	require('Cheval.class.php');
	require('Plateau.class.php');
	require('Roi.class.php');
	require('Reine.class.php');
	require('Fou.class.php');
	require('Tour.class.php');
	require('Position.class.php');
	require('PositionOccupee.class.php');*/

	class Pion extends Piece{
	
		/*Renvoie un tableau de positions pouvant être occupées par un cheval*/
		
		function tableauDeplacementsAutorises($unPlateau){
			$tableauDesDeplacementsAutorises=array();
			if($this->getPosition()->getI()==2 && $this->getCouleur()=='Noir'){
				$avanceeOk=true;
				for ($i=1;$i<=2;$i++){
					$positionEventuelle=new Position($this->getPosition()->getI()+$i,$this->getPosition()->getJ());
					if($avanceeOk && $unPlateau->estInoccupee($positionEventuelle)){
						$tableauDesDeplacementsAutorises[]=$positionEventuelle;
					}
					else{
						$avanceeOk=false;
					}
				}
			}
			else if($this->getPosition()->getI()==7 && $this->getCouleur()=='Blanc'){
				$avanceeOk=true;
				for ($i=1;$i<=2;$i++){
					$positionEventuelle=new Position($this->getPosition()->getI()-$i,$this->getPosition()->getJ());
					if($avanceeOk && $unPlateau->estInoccupee($positionEventuelle)){
						$tableauDesDeplacementsAutorises[]=$positionEventuelle;
					}
					else{
						$avanceeOk=false;
					}
				}
			}
			else if($this->getCouleur()=='Noir'){
					$positionEventuelle=new Position($this->getPosition()->getI()+1,$this->getPosition()->getJ());
					if($unPlateau->estInoccupee($positionEventuelle)){
						$tableauDesDeplacementsAutorises[]=$positionEventuelle;
					}
				}
			else if($this->getCouleur()=='Blanc'){
					$positionEventuelle=new Position($this->getPosition()->getI()-1,$this->getPosition()->getJ());
					if($unPlateau->estInoccupee($positionEventuelle)){
						$tableauDesDeplacementsAutorises[]=$positionEventuelle;
					}
			}
			return $tableauDesDeplacementsAutorises;
		}
	}
	
?>