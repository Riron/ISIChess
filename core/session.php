<?php
class Session{

	public function __construct(){
		session_start();
	}

	/**
	* Permet de definir un message Flash
	*/
	public function setFlash($message, $type = 'info'){
		$_SESSION['flash'] = array(
			'message' => $message,
			'type' => $type
		);
	}

	/**
	* Permet d'afficher un message Flash
	*/
	public function flash(){
		if(isset($_SESSION['flash'])){
			echo '<div class="alert alert-'.$_SESSION['flash']['type'].'"> <a class="close" data-dismiss="alert">x</a>';
			echo $_SESSION['flash']['message'];
			echo '</div>';

			unset($_SESSION['flash']);
		}
	}

	/**
	* Permet d'ajouter une entree en session
	* @param $key La clé de l'entree
	* @param $value La valeur de l'entree
	*/
	public function addEntry($key, $value){
		$_SESSION[$key] = $value;
	}

	/**
	* Permet de lire une entree en session
	* @param $key Cle de l'entree a lire
	*/
	public function readEntry($key = null){
		if($key){
			if(isset($_SESSION[$key])){
				return $_SESSION[$key];
			}
			else{
				return false;
			}
		}
		else{
			return $_SESSION;
		}
	}

	/**
	* Permet de supprimer une entree de la session
	* @param $key La clé de l'entree a supprimer
	*/
	public function unsetEntry($key){
		if(isset($_SESSION[$key]))
			unset($_SESSION[$key]);
	}

	/**
	* Permet de tester si l'utilisateur est logge
	* @return Booleen, 1 si connecte, 0 sinon
	*/
	public function isLogged(){
		return isset($_SESSION['user']->id_utilisateur);
	}

	/**
	* Permet de tester si l'utilisateur est admin
	* @return Booleen, 1 si admin, 0 sinon
	*/
	public function isAdmin(){
		return isset($_SESSION['user']->bl_admin) ? $_SESSION['user']->bl_admin : false;
	}


}