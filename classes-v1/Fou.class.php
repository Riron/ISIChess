<?php 

	class Fou extends PieceLonguePortee{
	
		/*Renvoie un tableau de positions pouvant �tre occup�es par un cheval*/

		function tableauDeplacementsAutorises($unPlateau){
			$tableauDesDeplacementsAutorises=array();
			$this->genereCoupsPossiblesDiagonales($unPlateau,$tableauDesDeplacementsAutorises);
			return $tableauDesDeplacementsAutorises;
		}
		
	}
	
?>