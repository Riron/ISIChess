<?php 

	class Position{
	
		private $i;
		private $j;
		
		function setI($unI){
			$this->i=$unI;
		}
		
		function getI(){
			return $this->i;
		}
		
		function setJ($unJ){
			$this->j=$unJ;
		}
		
		function getJ(){
			return $this->j;
		}
		
		function estEgale($unePosition){
			return ($this->getI()==$unePosition->getI() && $this->getJ()==$unePosition->getJ());
		}

		function __construct($unI,$unJ){
			$this->setI($unI);
			$this->setJ($unJ);
		}
		
	}
	
?>