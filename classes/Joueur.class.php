<?php 

	class Joueur{
	
		/*
			couleur est un string. Dans le cas du jeu d'échec standard, couleur vaudra 'Blanc' ou 'noir'.
			Mais on peut imaginer un jeu d'échecs à trois comme cela existe d'ailleurs déjà. Voilà pourquoi il ne s'agit pas d'un booléen.
			coupsTheoriquementPossibles est le tableau de toutes les positions que pourrait occuper avec une quelconque pièce le joueur sans les histoires d'échec à parer, de cloutage...
			Sont bien sûr excluses les positions des pièces en elle-même. Seules les nouvelles positions comptent.
			coupsEffectivementPossibles est le tableau des positions vraiment exploitables par un joueur.
			Un joueur a bien sûr des pièces et un roi. A noter que parmi les pièces apparaît déjà le roi.
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
			Retourne true si le joueur est en échec dans la situation actuelle.
			Retourne false sinon.
			estEnEchec() requiert bien sûr un contexte : le jeu d'échec en cours.
			estEnEchec() devrait être adapté dans le cas des échecs à trois puisqu'on ne considère que l'autre joueur, et pas plusieurs autres.
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