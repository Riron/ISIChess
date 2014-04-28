<?php 

	/*require('Piece.class.php');
	require('Plateau.class.php');
	require('Cheval.class.php');
	require('Pion.class.php');
	require('Roi.class.php');
	require('Reine.class.php');
	require('Fou.class.php');
	require('Tour.class.php');
	require('Position.class.php');*/

	class PositionOccupee extends Position{
	
		private $unePiece;
		
		function setUnePiece($uneCertainePiece){
			$this->unePiece=$uneCertainePiece;
		}
		
		function getUnePiece(){
			return $this->unePiece;
		}

		function __construct($unI,$unJ,$uneCertainePiece){
			parent::__construct($unI,$unJ);
			$this->setUnePiece($uneCertainePiece);
		}
		
	}
	
?>