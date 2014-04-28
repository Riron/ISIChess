<?php

	class Pion extends Piece{
	
		/*Renvoie un tableau de positions pouvant être occupées par un pion*/
		
		function genereCoupsPossiblesMangerEnAvantEnDiagonale($unJeuDEchec,&$tableauDesDeplacementsAutorises){
			if($this->getCouleur()==='Noir'){
				$positionEventuelle=new Position($this->getPosition()->getI()+1,$this->getPosition()->getJ()+1);
				if($unJeuDEchec->getPlateau()->surPlateau($positionEventuelle) && $this->atteritSurPieceEnnemieSiOccupeLaPosition($unJeuDEchec,$positionEventuelle)){
					$tableauDesDeplacementsAutorises[]=$positionEventuelle;
				}
				$positionEventuelle=new Position($this->getPosition()->getI()+1,$this->getPosition()->getJ()-1);
				if($unJeuDEchec->getPlateau()->surPlateau($positionEventuelle) && $this->atteritSurPieceEnnemieSiOccupeLaPosition($unJeuDEchec,$positionEventuelle)){
					$tableauDesDeplacementsAutorises[]=$positionEventuelle;
				}
			}
			else{
				$positionEventuelle=new Position($this->getPosition()->getI()-1,$this->getPosition()->getJ()+1);
				if($unJeuDEchec->getPlateau()->surPlateau($positionEventuelle) && $this->atteritSurPieceEnnemieSiOccupeLaPosition($unJeuDEchec,$positionEventuelle)){
					$tableauDesDeplacementsAutorises[]=$positionEventuelle;
				}
				$positionEventuelle=new Position($this->getPosition()->getI()-1,$this->getPosition()->getJ()-1);
				if($unJeuDEchec->getPlateau()->surPlateau($positionEventuelle) && $this->atteritSurPieceEnnemieSiOccupeLaPosition($unJeuDEchec,$positionEventuelle)){
					$tableauDesDeplacementsAutorises[]=$positionEventuelle;
				}				
			}
		}
		
		function estPion(){
			return true;
		}
		
		function genereCoupsPossiblesMangerEnPassant($unJeuDEchec,&$tableauDesDeplacementsAutorises){
			if($this->getCouleur()==='Noir'){
				$positionEventuelle=new Position($this->getPosition()->getI()+1,$this->getPosition()->getJ()+1);
				if($unJeuDEchec->getPlateau()->surPlateau($positionEventuelle) && !is_null($unJeuDEchec->getPlateau()->getEnPassant()) && $unJeuDEchec->getPlateau()->getEnPassant()->estEgale($positionEventuelle)){
					$tableauDesDeplacementsAutorises[]=$positionEventuelle;
				}
				$positionEventuelle=new Position($this->getPosition()->getI()+1,$this->getPosition()->getJ()-1);
				if($unJeuDEchec->getPlateau()->surPlateau($positionEventuelle) && !is_null($unJeuDEchec->getPlateau()->getEnPassant()) && $unJeuDEchec->getPlateau()->getEnPassant()->estEgale($positionEventuelle)){
					$tableauDesDeplacementsAutorises[]=$positionEventuelle;
				}
			}
			else{
				$positionEventuelle=new Position($this->getPosition()->getI()-1,$this->getPosition()->getJ()+1);
				if($unJeuDEchec->getPlateau()->surPlateau($positionEventuelle) && !is_null($unJeuDEchec->getPlateau()->getEnPassant()) && $unJeuDEchec->getPlateau()->getEnPassant()->estEgale($positionEventuelle)){
					$tableauDesDeplacementsAutorises[]=$positionEventuelle;
				}
				$positionEventuelle=new Position($this->getPosition()->getI()-1,$this->getPosition()->getJ()-1);
				if($unJeuDEchec->getPlateau()->surPlateau($positionEventuelle) && !is_null($unJeuDEchec->getPlateau()->getEnPassant()) && $unJeuDEchec->getPlateau()->getEnPassant()->estEgale($positionEventuelle)){
					$tableauDesDeplacementsAutorises[]=$positionEventuelle;
				}			
			}			
		}
		
		/*
		*	Indique si le pion est sur la ligne avant promotion ou non.
		*/
		
		function surLigneAvantPromotion($unPlateau){
			return (($this->getCouleur()=='Noir' && $this->getPosition()->getI()==$unPlateau->plateauDEchecA8Lignes8Colonnes()-1) || ($this->getCouleur()=='Blanc' && $this->getPosition()->getI()==2));
		}
		
		function deplacer($unePosition,$unPlateau){
			$positionInitiale=$this->getPosition();
			
			//Spécifique aux pions se déplaçant en diagonale sur des cases inoccupées, c'est-à-dire effectuant une prise en passant. Il faut alors supprimer la pièce en question.
			if($unePosition->getJ()!=$positionInitiale->getJ() && get_class($unPlateau->sePlacerALaPosition($unePosition))==='Position'){
				$tableauCorresp_1=Conversion::tableauColonnesModelisationVersColonnesEchecs();
				$tableauCorresp_2=Conversion::tableauLignesModelisationVersLignesEchecs();
				$this->setPosition($unePosition);
				$pionMangeEnPassant=$unPlateau->sePlacerALaPosition(new Position($positionInitiale->getI(),$unePosition->getJ()))->getPiece();
				$clePion=array_search($pionMangeEnPassant,$pionMangeEnPassant->getJoueur()->getPieces());
				
				//Transforme les positions initiales du Roi et de la Tour en Position (donc inoccupée)
				$unPlateau->positions[$positionInitiale->getI()][$positionInitiale->getJ()]=new Position($positionInitiale->getI(),$positionInitiale->getJ());
				$unPlateau->positions[$positionInitiale->getI()][$unePosition->getJ()]=new Position($positionInitiale->getI(),$unePosition->getJ());
				
				//Transforme les positions d'arrivée du Roi et de la Tour en PositionOccupee
				$unPlateau->positions[$unePosition->getI()][$unePosition->getJ()]=new PositionOccupee($unePosition->getI(),$unePosition->getJ(),$this);
				unset($pionMangeEnPassant->getJoueur()->pieces[$clePion]);
				
				//On met un San particulier.
				$unPlateau->setSan($tableauCorresp_1[$positionInitiale->getJ()].''.$tableauCorresp_2[$positionInitiale->getI()].' '.$tableauCorresp_1[$unePosition->getJ()].''.$tableauCorresp_2[$unePosition->getI()].' e.p.');
			}
			else{
				parent::deplacer($unePosition,$unPlateau);
			}
			//Si on a avancé de deux cases, on indique que la prise en passant en possible.
			if(abs($positionInitiale->getI()-$this->getPosition()->getI())==2){
				$unPlateau->setEnPassant(new Position(($positionInitiale->getI()+$this->getPosition()->getI())/2,$this->getPosition()->getJ()));
			}
		}
		
		/*
		*	On redéfinit ici la méthode image afin d'y mettre un attribut data-pion : ceci servira pour la promotion des pions.
		*/
		
		function image($unPlateau){
			if(isset($this->tabCoupsEffectivementPossibles) && !empty($this->tabCoupsEffectivementPossibles) && $unPlateau->getCouleurDuJoueurConsultantLaPartie()==$unPlateau->getCouleurDuJoueurActuel()){
				$string='';
				foreach($this->tabCoupsEffectivementPossibles as $pos){
					$string.=$pos->getI();
					$string.=$pos->getJ();
					$string.=' ';
				}
			}
			if($this->getCouleur()==='Noir'){
				if(isset($string)){
					return '<img data-pion="pion "data-pos="'.$string.'" src="'.WEBROOT.'webroot/img/pieces/black_'.mb_strtolower(get_class($this)).'.png" alt="Black '.mb_strtolower(get_class($this)).'">';
				}
				else{
					return '<img data-pion="pion" src="'.WEBROOT.'webroot/img/pieces/black_'.mb_strtolower(get_class($this)).'.png" alt="Black '.mb_strtolower(get_class($this)).'">';
				}
			}
			else{
				if(isset($string)){
					return '<img data-pion="pion" data-pos="'.$string.'" src="'.WEBROOT.'webroot/img/pieces/white_'.mb_strtolower(get_class($this)).'.png" alt="White '.mb_strtolower(get_class($this)).'">';
				}
				else{
					return '<img data-pion="pion" src="'.WEBROOT.'webroot/img/pieces/white_'.mb_strtolower(get_class($this)).'.png" alt="White '.mb_strtolower(get_class($this)).'">';
				}
			}
		}
		
		function tableauCoupsTheoriquementPossibles($unJeuDEchec){
			$this->tabCoupsTheoriquementPossibles=array();
			if($this->getPosition()->getI()==2 && $this->getCouleur()=='Noir'){
				$avanceeOk=true;
				for ($i=1;$i<=2;$i++){
					$positionEventuelle=new Position($this->getPosition()->getI()+$i,$this->getPosition()->getJ());
					if($avanceeOk && $unJeuDEchec->getPlateau()->surPlateau($positionEventuelle) && $unJeuDEchec->getPlateau()->estInoccupee($positionEventuelle)){
						$this->tabCoupsTheoriquementPossibles[]=$positionEventuelle;
					}
					else{
						$avanceeOk=false;
					}
				}
			}
			else if($this->getPosition()->getI()==7 && $this->getCouleur()=='Blanc'){
				$avanceeOk=true;
				for ($i=1;$i<=2;$i++){
					$positionEventuelle=new Position($this->getPosition()->getI()-$i,$this->getPosition()->getJ());
					if($avanceeOk && $unJeuDEchec->getPlateau()->surPlateau($positionEventuelle) && $unJeuDEchec->getPlateau()->estInoccupee($positionEventuelle)){
						$this->tabCoupsTheoriquementPossibles[]=$positionEventuelle;
					}
					else{
						$avanceeOk=false;
					}
				}
			}
			else if($this->getCouleur()=='Noir'){
					$positionEventuelle=new Position($this->getPosition()->getI()+1,$this->getPosition()->getJ());
					if($unJeuDEchec->getPlateau()->surPlateau($positionEventuelle) && $unJeuDEchec->getPlateau()->estInoccupee($positionEventuelle)){
						$this->tabCoupsTheoriquementPossibles[]=$positionEventuelle;
					}
				}
			else if($this->getCouleur()=='Blanc'){
					$positionEventuelle=new Position($this->getPosition()->getI()-1,$this->getPosition()->getJ());
					if($unJeuDEchec->getPlateau()->surPlateau($positionEventuelle) && $unJeuDEchec->getPlateau()->estInoccupee($positionEventuelle)){
						$this->tabCoupsTheoriquementPossibles[]=$positionEventuelle;
					}
			}
			$this->genereCoupsPossiblesMangerEnAvantEnDiagonale($unJeuDEchec,$this->tabCoupsTheoriquementPossibles);
			$this->genereCoupsPossiblesMangerEnPassant($unJeuDEchec,$this->tabCoupsTheoriquementPossibles);
		}
	}
	
?>