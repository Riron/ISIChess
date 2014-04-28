<?php 

	class Joueur{
	
		/*
			couleur est un string. Dans le cas du jeu d'�chec standard, couleur vaudra 'Blanc' ou 'noir'.
			Mais on peut imaginer un jeu d'�checs � trois comme cela existe d'ailleurs d�j�. Voil� pourquoi il ne s'agit pas d'un bool�en.
			coupsTheoriquementPossibles est le tableau de toutes les positions que pourrait occuper avec une quelconque pi�ce le joueur sans les histoires d'�chec � parer, de cloutage...
			Sont bien s�r excluses les positions des pi�ces en elle-m�me. Seules les nouvelles positions comptent.
			coupsEffectivementPossibles est le tableau des positions vraiment exploitables par un joueur.
			Un joueur a bien s�r des pi�ces et un roi. A noter que parmi les pi�ces appara�t d�j� le roi.
		*/
	
		private $couleur;
		public $coupsTheoriquementPossibles;
		public $coupsEffectivementPossibles;
		public $pieces;
		private $roi;
		private $petitRoqueOk;
		private $grandRoqueOk;
		
		function setCouleur($uneCouleur){
			$this->couleur=$uneCouleur;
		}
		
		function getCouleur(){
			return $this->couleur;
		}
		
		function setCoupsTheoriquementPossibles($desCoups){
			$this->coupsTheoriquementPossibles=$desCoups;
		}
		
		function getCoupsTheoriquementPossibles(){
			return $this->coupsTheoriquementPossibles;
		}
		
		function setCoupsEffectivementPossibles($desCoups){
			$this->coupsEffectivementPossibles=$desCoups;
		}
		
		function getCoupsEffectivementPossibles(){
			return $this->coupsEffectivementPossibles;
		}
		
		function setPieces($desPieces){
			$this->pieces=$desPieces;
		}
		
		function getPieces(){
			return $this->pieces;
		}
		
		function setRoi($unRoi){
			$this->roi=$unRoi;
		}
		
		function getRoi(){
			return $this->roi;
		}
		
		function ajouterPiece($unePiece){
			$this->pieces[]=$unePiece;
		}
		
		function ajouterCoupTheorique($unCoup){
			$this->coupsTheoriquementPossibles[]=$unCoup;
		}
		
		function ajouterCoupEffectif($unCoup){
			$this->coupsEffectivementPossibles[]=$unCoup;
		}
		
		/*
			Retourne true si le joueur est en �chec dans la situation actuelle.
			Retourne false sinon.
			estEnEchec() requiert bien s�r un contexte : le jeu d'�chec en cours.
			estEnEchec() devrait �tre adapt� dans le cas des �checs � trois puisqu'on ne consid�re que l'autre joueur, et pas plusieurs autres.
		*/
		
		function estEnEchec($unJeuDEchec){
			$autreJoueur=$unJeuDEchec->getJoueurSuivant($this);
			$leRoi=$this->getRoi();
			foreach($autreJoueur->getCoupsTheoriquementPossibles() as $position){
				if($leRoi->getPosition()->estEgale($position)){
					return true;
				}
			}
			return false;
		}
		
		function petitRoqueOk(){
			return $this->petitRoqueOk;
		}
		
		function grandRoqueOk(){
			return $this->grandRoqueOk;
		}
		
		function setPetitRoqueOk($unBooleen){
			$this->petitRoqueOk=$unBooleen;
		}
		
		function setGrandRoqueOk($unBooleen){
			$this->grandRoqueOk=$unBooleen;
		}
		
		function __construct($uneCouleur){
			$this->setCouleur($uneCouleur);
			$this->setGrandRoqueOk(false);
			$this->setPetitRoqueOk(false);
			$this->setPieces(array());
			$this->setCoupsTheoriquementPossibles(array());
			$this->setCoupsEffectivementPossibles(array());
		}
		
	}
	
?>