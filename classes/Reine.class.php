<?php 

	class Reine extends PieceLonguePortee{
	
		/*Renvoie un tableau de positions pouvant être occupées par un cheval*/
		
		function tableauCoupsTheoriquementPossibles($unJeuDEchec){
			$this->tabCoupsTheoriquementPossibles=array();
			$this->genereCoupsPossiblesDiagonales($unJeuDEchec,$this->tabCoupsTheoriquementPossibles);
			$this->genereCoupsPossiblesLignesColonnes($unJeuDEchec,$this->tabCoupsTheoriquementPossibles);
		}
		
	}
	
?>