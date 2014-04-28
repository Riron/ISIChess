<?php 

	class Fou extends PieceLonguePortee{
	
		/*Renvoie un tableau de positions pouvant être occupées par un cheval*/

		function tableauDeplacementsAutorises($unPlateau){
			$tableauDesDeplacementsAutorises=array();
			$this->genereCoupsPossiblesDiagonales($unPlateau,$tableauDesDeplacementsAutorises);
			return $tableauDesDeplacementsAutorises;
		}
		
	}
	
?>