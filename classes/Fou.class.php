<?php 

	class Fou extends PieceLonguePortee{
	
		/*Renvoie un tableau de positions pouvant �tre occup�es par un fou*/

		function tableauCoupsTheoriquementPossibles($unJeuDEchec){
			$this->tabCoupsTheoriquementPossibles=array();
			$this->genereCoupsPossiblesDiagonales($unJeuDEchec,$this->tabCoupsTheoriquementPossibles);
		}
		
	}
	
?>