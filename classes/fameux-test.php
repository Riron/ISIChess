<?php

function maFonction(&$unArray){
	$unArray[]=3;
}

$monTableau=array();
$monTableau[]=2;
$monTableau[]=4;
maFonction($monTableau);
print_r($monTableau);

?>