<?php
class Request{

	private $url;

	/**
	* Constructeur, stocke les parametres de la requete dans $url
	*/
	function __construct(){
		$this->url = $_SERVER["QUERY_STRING"];
		//$this->url = $_GET['p'];
	}

	/**
	* Accesseur pour $this->url
	*/
	function url(){
		return $this->url;
	}

}