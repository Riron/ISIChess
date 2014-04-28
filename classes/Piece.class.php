<?php

	abstract class Piece{
	
		/*
		*	La classe pi�ce est abstraite. On n'aura jamais d'instances de cette classe.
		*	Elle n'en est pas moins tr�s utile pour factoriser notre code.
		*	Une pi�ce a un joueur, une position (de type Position et non PositionOccupee).
		*	Deux tableaux publics de coups.
		*	tabCoupsTheoriquementPossibles est le tableau des coups th�oriquement possibles de la pi�ce. On appelle coup th�oriquement possible un coup qui pourrait se faire si on oubliait les clouages.
		*	Un Coup th�oriquement possible prend en compte le fait qu'on ne puisse pas se d�placer sur une case occup�e par une pi�ce alli�e, qu'on ne peut pas franchir une pi�ce quelle qu'elle soit, � part si on est un cheval...
		*	Un Coup th�oriquement possible est un coup effectivement jouable, m�me en incluant la r�gle des clouages.
		*	Si un Roi est en �chec, il doit absolument s'en lib�rer dans son tour.
		*	Si un roi n'est pas en �chec, il ne peut pas s'y mettre volontairement, m�me si cette mise est en �chec n'est pas d�e directement � son mouvement.
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
		*	Ces m�thodes ont des noms �vocateurs et serviront pour la gestion des clouages.
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
			Le premier argument peut soit �tre une pi�ce soit �tre un joueur.
		*/
		
		function __construct($unJoueur,$unePosition){
				$this->setJoueur($unJoueur);
				$this->setPosition($unePosition);
				$this->getJoueur()->ajouterPiece($this);
		}
		
		/*
		*	Une pi�ce peut se d�placer, gr�ce � la m�thode d�placer qui prend en param�tre une position et un plateau.
		*	D�placer ne v�rifie en aucun cas que le mouvement est possible.
		*	C'est le JeuDEchec qui s'assure au pr�alable que le mouvement est possible avant de d�placer la pi�ce.
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
		*	Sert uniquement � simuler le d�placement d'une pi�ce pour v�rifier qu'un mouvement est autoris�.
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
		*	Renvoie l'image d'une pi�ce.
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
			M�thode statique permettant de cr�er une pi�ce � partir de sa repr�sentation sous forme de caract�re dans la notation FEN.
			Doit �tre adapt�e si on veut �tendre le jeu � des �checs � trois par exemple.
			Si la lettre est minuscule, on a affaire � une pi�ce noire, et donc au second joueur.
			Si la lettre est majuscule on a affaire � une pi�ce blanche et donc au premier joueur.
		*/
		
		public static function convertitUnStringEtRetourneUnePiece($unString,$unePosition,$desJoueurs){
			(mb_strtolower($unString)===$unString) ? $leJoueur=$desJoueurs[1]:$leJoueur=$desJoueurs[0];
			return new Conversion::$stringPieceVersPiece[mb_strtolower($unString)]($leJoueur,$unePosition);
		}
		
	}
	
?>