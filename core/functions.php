<?php
/**
* Permet d'afficher clairement une variable pour debugger plus facilement
*/
function debug($var){
	if(Config::$debug <1){
		return false;
	}

	$trace = debug_backtrace();
	echo '<p><strong>Debug de '.$trace[0]['file'].' l. '.$trace[0]['line'].' </strong></p>';
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}

/**
* Permet de valider la conformite d'une adresse email
*/
function validateMail($mail)
{
	return(filter_var($mail, FILTER_VALIDATE_EMAIL));
}