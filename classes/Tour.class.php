<?php 

	/*
	*	Une tour est une pi�ce longue port�e se d�pla�ant suivant les lignes/colonnes.
	*/


	class Tour extends PieceLonguePortee{
		
		function tableauCoupsTheoriquementPossibles($unJeuDEchec){
			$this->tabCoupsTheoriquementPossibles=array();
			$this->genereCoupsPossiblesLignesColonnes($unJeuDEchec,$this->tabCoupsTheoriquementPossibles);
		}
		
		/*
		*	Red�finition de d�placer afin d'emp�cher un roque si la tour s'est d�j� d�plac�e.
		*/
		
		function d�placer($unePosition,$unPlateau){
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