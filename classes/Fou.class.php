<?php 

	class Fou extends PieceLonguePortee{
	
		/*Renvoie un tableau de positions pouvant être occupées par un fou*/

		function tableauCoupsTheoriquementPossibles($unJeuDEchec){
			$this->tabCoupsTheoriquementPossibles=array();
			$this->genereCoupsPossiblesDiagonales($unJeuDEchec,$this->tabCoupsTheoriquementPossibles);
		}
		
	}
	
?>