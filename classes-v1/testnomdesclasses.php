<?php 

	class Position{
		public $i;
		function __construct(){
			$this->i=5;
		}
	}
	
	$monEnfant='Position';
	$maVie=new $monEnfant();
	print_r($maVie->i);
	
?>