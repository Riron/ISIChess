<?php 

	class Position{
	
		private $i;
		private $j;
		
		public function setI($unI){
			$this->i=$unI;
		}
		
		public function getI(){
			return $this->i;
		}
		
		function setJ($unJ){
			$this->j=$unJ;
		}
		
		function getJ(){
			return $this->j;
		}

		function __construct($unI,$unJ){
			$this->setI($unI);
			$this->setJ($unJ);
		}
		
	}
	
?>