<?php 

	class Reine extends PieceLonguePortee{
	
		/*Renvoie un tableau de positions pouvant être occupées par un cheval*/
		
		function tableauDeplacementsAutorises($unPlateau){
			$tableauDesDeplacementsAutorises=array();
			$this->genereCoupsPossiblesDiagonales($unPlateau,$tableauDesDeplacementsAutorises);
			$this->genereCoupsPossiblesLignesColonnes($unPlateau,$tableauDesDeplacementsAutorises);
			return $tableauDesDeplacementsAutorises;
		}
		
	}
	
?>