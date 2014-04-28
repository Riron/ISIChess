<?php

	abstract class Piece{
	
		/*
		*	La classe pièce est abstraite. On n'aura jamais d'instances de cette classe.
		*	Elle n'en est pas moins très utile pour factoriser notre code.
		*	Une pièce a un joueur, une position (de type Position et non PositionOccupee).
		*	Deux tableaux publics de coups.
		*	tabCoupsTheoriquementPossibles est le tableau des coups théoriquement possibles de la pièce. On appelle coup théoriquement possible un coup qui pourrait se faire si on oubliait les clouages.
		*	Un Coup théoriquement possible prend en compte le fait qu'on ne puisse pas se déplacer sur une case occupée par une pièce alliée, qu'on ne peut pas franchir une pièce quelle qu'elle soit, à part si on est un cheval...
		*	Un Coup théoriquement possible est un coup effectivement jouable, même en incluant la règle des clouages.
		*	Si un Roi est en échec, il doit absolument s'en libérer dans son tour.
		*	Si un roi n'est pas en échec, il ne peut pas s'y mettre volontairement, même si cette mise est en échec n'est pas dûe directement à son mouvement.
		*/
	
		private $joueur;
		private $position;
		public $tabCoupsTheoriquementPossibles;
		public $tabCoupsEffectivementPossibles;
		
		function setJoueur($unJoueur){
			$this->joueur=$unJoueur;
		}
		
		function getJoueur(){
			return $this->joueur;
		}

		function getCouleur(){
			return $this->joueur->getCouleur();
		}
		
		function setPosition($unePosition){
			$this->position=$unePosition;
		}
		
		function getPosition(){
			return $this->position;
		}
		
		private function leJoueurPeutIlEtreAtteintSiLaPieceVaALaPosition($unJoueur,$unePositionDansLeChampDeLaPiece){
			foreach($unJoueur->getPieces() as $unePiece){
				if($unePiece->getPosition()->estEgale($unePositionDansLeChampDeLaPiece)){
					return true;
				}
			}
			return false;	
		}
		
		function estNoire(){
			return $this->getCouleur()==='Noir';
		}
		
		function estBlanche(){
			return $this->getCouleur()==='Blanc';
		}
		
		/*
		*	Ces méthodes ont des noms évocateurs et serviront pour la gestion des clouages.
		*/
		
		function atteritSurPieceAllieeSiOccupeLaPosition($unePositionDansLeChampDeLaPiece){
			return $this->leJoueurPeutIlEtreAtteintSiLaPieceVaALaPosition($this->getJoueur(),$unePositionDansLeChampDeLaPiece);
		}
		
		function nAtteritPasSurPieceAllieeSiOccupeLaPosition($unePositionDansLeChampDeLaPiece){
			return !$this->leJoueurPeutIlEtreAtteintSiLaPieceVaALaPosition($this->getJoueur(),$unePositionDansLeChampDeLaPiece);
		}
		
		function atteritSurPieceEnnemieSiOccupeLaPosition($unJeuDEchec,$unePositionDansLeChampDeLaPiece){
			return $this->leJoueurPeutIlEtreAtteintSiLaPieceVaALaPosition($unJeuDEchec->getJoueurSuivant($this->getJoueur()),$unePositionDansLeChampDeLaPiece);
		}
		
		function nAtteritPasSurPieceEnnemieSiOccupeLaPosition($unJeuDEchec,$unePositionDansLeChampDeLaPiece){
			return !$this->leJoueurPeutIlEtreAtteintSiLaPieceVaALaPosition($unJeuDEchec->getJoueurSuivant($this->getJoueur()),$unePositionDansLeChampDeLaPiece);
		}
		
		/*
			Le premier argument peut soit être une pièce soit être un joueur.
		*/
		
		function __construct($unJoueur,$unePosition){
				$this->setJoueur($unJoueur);
				$this->setPosition($unePosition);
				$this->getJoueur()->ajouterPiece($this);
		}
		
		/*
		*	Une pièce peut se déplacer, grâce à la méthode déplacer qui prend en paramètre une position et un plateau.
		*	Déplacer ne vérifie en aucun cas que le mouvement est possible.
		*	C'est le JeuDEchec qui s'assure au préalable que le mouvement est possible avant de déplacer la pièce.
		*/
		
		function deplacer($unePosition,$unPlateau){
			$tableauCorresp_1=Conversion::tableauColonnesModelisationVersColonnesEchecs();
			$tableauCorresp_2=Conversion::tableauLignesModelisationVersLignesEchecs();			
			$positionInitiale=$this->getPosition();
			$cle=array_search($this,$this->getJoueur()->getPieces());
			$this->setPosition($unePosition);
			
			//Si on mange
			if(get_class($unPlateau->positions[$unePosition->getI()][$unePosition->getJ()])==='PositionOccupee'){
				$pieceAManger=$unPlateau->positions[$unePosition->getI()][$unePosition->getJ()]->getPiece();
				if($unePosition->getI()==1 && $unePosition->getJ()==1){
					$pieceAManger->getJoueur()->setGrandRoqueOk(false);
				}
				if($unePosition->getI()==1 && $unePosition->getJ()==8){
					$pieceAManger->getJoueur()->setPetitRoqueOk(false);
				}
				if($unePosition->getI()==8 && $unePosition->getJ()==1){
					$pieceAManger->getJoueur()->setGrandRoqueOk(false);
				}
				if($unePosition->getI()==8 && $unePosition->getJ()==8){
					$pieceAManger->getJoueur()->setPetitRoqueOk(false);
				}
				$clePieceAManger=array_search($pieceAManger,$pieceAManger->getJoueur()->pieces);
				unset($pieceAManger->getJoueur()->pieces[$clePieceAManger]);
				$unPlateau->setSan($tableauCorresp_1[$positionInitiale->getJ()].''.$tableauCorresp_2[$positionInitiale->getI()].' '.$tableauCorresp_1[$unePosition->getJ()].''.$tableauCorresp_2[$unePosition->getI()].' x');
			}
			//Si on ne mange pas
			else{
				$unPlateau->setSan($tableauCorresp_1[$positionInitiale->getJ()].''.$tableauCorresp_2[$positionInitiale->getI()].' '.$tableauCorresp_1[$unePosition->getJ()].''.$tableauCorresp_2[$unePosition->getI()]);
			}
			$unPlateau->positions[$positionInitiale->getI()][$positionInitiale->getJ()]=new Position($positionInitiale->getI(),$positionInitiale->getJ());
			$unPlateau->positions[$unePosition->getI()][$unePosition->getJ()]=new PositionOccupee($unePosition->getI(),$unePosition->getJ(),$this);
			$this->getJoueur()->pieces[$cle]=$this;
			$unPlateau->setEnPassant(null);
		}
		
		/*
		*	Sert uniquement à simuler le déplacement d'une pièce pour vérifier qu'un mouvement est autorisé.
		*/
		
		function deplacerPourTester($unePosition,$unPlateau){
			$positionInitiale=$this->getPosition();
			$cle=array_search($this,$this->getJoueur()->getPieces());
			$this->setPosition($unePosition);
			$unPlateau->positions[$positionInitiale->getI()][$positionInitiale->getJ()]=new Position($positionInitiale->getI(),$positionInitiale->getJ());
			$unPlateau->positions[$unePosition->getI()][$unePosition->getJ()]=new PositionOccupee($unePosition->getI(),$unePosition->getJ(),$this);
			$this->getJoueur()->pieces[$cle]=$this;
		}
		
		/*
		*	Renvoie l'image d'une pièce.
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
					return '<img data-pos="'.$string.'" src="'.WEBROOT.'webroot/img/pieces/black_'.mb_strtolower(get_class($this)).'.png" alt="Black '.mb_strtolower(get_class($this)).'">';
				}
				else{
					return '<img src="'.WEBROOT.'webroot/img/pieces/black_'.mb_strtolower(get_class($this)).'.png" alt="Black '.mb_strtolower(get_class($this)).'">';
				}
			}
			else{
				if(isset($string)){
					return '<img data-pos="'.$string.'" src="'.WEBROOT.'webroot/img/pieces/white_'.mb_strtolower(get_class($this)).'.png" alt="White '.mb_strtolower(get_class($this)).'">';
				}
				else{
					return '<img src="'.WEBROOT.'webroot/img/pieces/white_'.mb_strtolower(get_class($this)).'.png" alt="White '.mb_strtolower(get_class($this)).'">';
				}
			}
		}
		
		function estPion(){
			return false;
		}
		
		function tableauCoupsEffectivementPossibles($unJeuDEchec){
			$this->tabCoupsEffectivementPossibles=array();
			foreach($this->tabCoupsTheoriquementPossibles as $position){
				if($unJeuDEchec->autorisationDuCoup($this,$position)){
					$this->tabCoupsEffectivementPossibles[]=$position;
				}
			}
		}
		
		abstract function tableauCoupsTheoriquementPossibles($unPlateau);
		
		/*
			Méthode statique permettant de créer une pièce à partir de sa représentation sous forme de caractère dans la notation FEN.
			Doit être adaptée si on veut étendre le jeu à des échecs à trois par exemple.
			Si la lettre est minuscule, on a affaire à une pièce noire, et donc au second joueur.
			Si la lettre est majuscule on a affaire à une pièce blanche et donc au premier joueur.
		*/
		
		public static function convertitUnStringEtRetourneUnePiece($unString,$unePosition,$desJoueurs){
			(mb_strtolower($unString)===$unString) ? $leJoueur=$desJoueurs[1]:$leJoueur=$desJoueurs[0];
			return new Conversion::$stringPieceVersPiece[mb_strtolower($unString)]($leJoueur,$unePosition);
		}
		
	}
	
?>