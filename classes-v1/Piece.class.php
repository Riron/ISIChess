<?php 

	/*require('Plateau.class.php');
	require('Cheval.class.php');
	require('Pion.class.php');
	require('Roi.class.php');
	require('Reine.class.php');
	require('Fou.class.php');
	require('Tour.class.php');
	require('Position.class.php');
	require('PositionOccupee.class.php');*/

	abstract class Piece{
	
		private $couleur;
		private $position;
		private static $tableauCorrespondance=array(
			'k' => 'Roi',
			'p' => 'Pion',
			'n' => 'Cheval',
			'q' => 'Reine',
			'b' => 'Fou',
			'r' => 'Tour'
		);
		
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