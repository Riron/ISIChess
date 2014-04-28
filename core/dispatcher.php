<?php
class Dispatcher{

	private $request;

	function __construct(){
		$this->request = new Request();
		Router::parse($this->request->url(), $this->request);

		if(!$controller = $this->loadController()){
			$this->error('Le controleur n\'existe pas');
		}
		else{
			if(in_array($this->request->action, array_diff(get_class_methods($controller), get_class_methods('Controller')))){
				call_user_func_array(array($controller, $this->request->action), $this->request->params);
			}
			else{
				$this->error('Le contrôleur n\'a pas de méthode '.$this->request->action.'.');
			}
		}
	}

	function error($message){
		$controller = new Controller($this->request);
		$controller->e404($message);
	}

	function loadController(){
		$controller = $this->request->controller.'Controller';
		$file = 'controllers/'.$controller.'.php';
		if(!file_exists($file)){
			return false;
		}
		require($file);
		$controller = new $controller($this->request);
		$controller->session = new Session();

		return $controller;
	}

	function request(){
		return $this->request;
	}

}