<?php 

	/*
	*	Une tour est une pièce longue portée se déplaçant suivant les lignes/colonnes.
	*/


	class Tour extends PieceLonguePortee{
		
		function tableauCoupsTheoriquementPossibles($unJeuDEchec){
			$this->tabCoupsTheoriquementPossibles=array();
			$this->genereCoupsPossiblesLignesColonnes($unJeuDEchec,$this->tabCoupsTheoriquementPossibles);
		}
		
		/*
		*	Redéfinition de déplacer afin d'empêcher un roque si la tour s'est déjà déplacée.
		*/
		
		function déplacer($unePosition,$unPlateau){
			if($this->getPosition()->getJ()===$unJeuDEchec->getPlateau()->plateauDEchecA8Lignes8Colonnes()){
				$this->getJoueur()->setPetitRoqueOk(false);
			}
			if($this->getPosition()->getJ()===1){
				$this->getJoueur()->setGrandRoqueOk(false);
			}
			parent::deplacer($unePosition,$unPlateau);
		}
		
	}
	
?>