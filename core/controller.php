<?php 

class Controller {

	private $layout = 'default';
	protected $request;

	/**
	* Constructeur
	* @param $request Objet request de l'application recupéré dans le dispatcher
	*/
	function __construct($request) {
		$this->request = $request;
		if(isset($this->models)){
			foreach ($this->models as $v) {
				$this->loadModel($v);
			}
		}
		if(isset($_POST)){
			$this->request->data = $_POST;
		}
		Model::$compteur = 0;
	}

	/**
	* Permet de rendre une vue
	* @param $view la vue qui sera appellée
	* @param $params les paramètres qui seront envoyés à la vue
	*/
	function render($view, $params = array()){
		// On extrait les parametres à passer à la vue
		if(!is_array($params)){
			$this->e404('Le paramètre envoyé à la vue n\'est pas de type array');
		}
		extract($params);

		// Pour les vues d'erreur on specifie directement le chemin (pas de controller errorController)
		if(strpos($view, '/') === 0){
			$file = 'views'.$view.'.php';
		}
		// Sinon on appelle la vue placée dans son dossier controller
		else{
			$file = 'views/'.$this->request->controller.'/'.$view.'.php';
		}

		// Temporisation pour ne pas rendre la vue tout de suite mais dans son layout
		ob_start();
		require($file);
		$content_for_layout = ob_get_clean();

		if($this->layout == false){
			echo $content_for_layout;
		}
		else{
			require('views/layout/'.$this->layout.'.php');
		}
	}

	/**
	* Permet de charger un modele depuis un controller
	* @param $name nom du model à charger
	*/
	function loadModel($model = null){
		if($model != null){
			require_once(ROOT.'models/'.strtolower($model).'.php');
			if(!isset($this->$model)) {
				$this->$model = new $model();
			}
		}
		else{
			foreach ($this->models as $v) {
				require_once(ROOT.'models/'.strtolower($v).'.php');
				if(!isset($this->$v)) {
					$this->$v = new $v();
				}
			}
		}
	}

	/**
	* Rend une erreur 404
	* @param $message Message affiche sur la page  d'erreur
	*/
	function e404($message){
		header("HTTP/1.0 404 Not Found");
		//Pas de controller error, on rend directement une vue en spécifiant le chemin
		$this->render('/errors/e404', array('message' => $message));
		die();
	}

	/**
	* Permet de rediriger l'utilisateur
	* @param $route La route vers laquelle l'utilisateur sera redirigé
	*/
	function redirect($route){
		header('Location:'.$route);
	}

	/**
	* Permet de générer une URL du type controller/action/params
	* @param $controller Le controller pointé
	* @param $action L'action pointée
	* @param $params Les paramètres qu'on passe
	*/
	function generateUrl($controller, $action, $params=array()){
		$route = WEBROOT.'index.php?'.strtolower($controller).'/'.$action.'/';
		if(is_array($params)){
			foreach ($params as $k => $v) {
				$route.= $v.'/';
			}
		}
		else{
			$route .= $params.'/';
		}
		return $route;
	}
}

