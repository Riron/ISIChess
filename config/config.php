<?php
class Config {

	// Niveau de debug, afin d'afficher ou non certaines erreurs (0 mise en prod, 1 dev)
	static $debug = 1;

	// Informations dur la (les) database(s) a laquelle on se connecte.
	static $databases = array(
		'default' => array(
			'host' =>'localhost',
			'database' => 'projetweb',
			'login' => 'root',
			'password' => ''
		)
	);

	// Controller, action, et eventuellement paramètres qui seront utilises pour la page d'acceuil
	static $defautRoute = array('home', 'index', array());
}
