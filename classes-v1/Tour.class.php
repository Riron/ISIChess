<?php 


	class Tour extends PieceLonguePortee{
	
		/*Renvoie un tableau de positions pouvant �tre occup�es par un cheval*/
		
		function tableauDeplacementsAutorises($unPlateau){
			$tableauDesDeplacementsAutorises=array();
			$this->genereCoupsPossiblesLignesColonnes($unPlateau,$tableauDesDeplacementsAutorises);
			return $tableauDesDeplacementsAutorises;
		}
		
	}
	
?>