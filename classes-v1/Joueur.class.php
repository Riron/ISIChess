<?php 

	class Joueur{
	
		private $couleur;
		private $listeDeCoupsPossibles;
		private $desPieces;
		
		function setCouleur($uneCouleur){
			$this->couleur=$uneCouleur;
		}
		
		function getCouleur(){
			return $this->couleur;
		}
		
		function setPosition($unePosition){
			$this->position=$unePosition;
		}
		
		function getPosition(){
			return $this->position;
		}
		
		function __construct($uneCouleur,$unePosition){
			$this->setCouleur($uneCouleur);
			$this->setPosition($unePosition);
		}
		
		function deplacer($unePosition,$unPlateau){
			$this->setPosition($unePosition);
		}
		
		abstract function tableauDeplacementsAutorises($unPlateau);
		
		public static function convertitUnStringEtRetourneUnePiece($unString,$unePosition){
			(mb_strtolower($unString)===$unString) ? $laCouleur='Noir':$laCouleur='Blanc';
			return new self::$tableauCorrespondance[mb_strtolower($unString)]($laCouleur,$unePosition);
		}
		
	}
	
?>