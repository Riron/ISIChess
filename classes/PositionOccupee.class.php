<?php

	class PositionOccupee extends Position{
	
		private $piece;
		
		function setPiece($unePiece){
			$this->piece=$unePiece;
		}
		
		function getPiece(){
			return $this->piece;
		}

		function __construct($unI,$unJ,$unePiece){
			parent::__construct($unI,$unJ);
			$this->setPiece($unePiece);
		}
		
	}
	
?>