<?php 

	class Reine extends PieceLonguePortee{
	
		/*Renvoie un tableau de positions pouvant �tre occup�es par un cheval*/
		
		function tableauCoupsTheoriquementPossibles($unJeuDEchec){
			$this->tabCoupsTheoriquementPossibles=array();
			$this->genereCoupsPossiblesDiagonales($unJeuDEchec,$this->tabCoupsTheoriquementPossibles);
			$this->genereCoupsPossiblesLignesColonnes($unJeuDEchec,$this->tabCoupsTheoriquementPossibles);
		}
		
	}
	
?>