<?php
class Router{

	/**
	* Parse une URL
	* @param $url L'URL vers laquelle on pointe
	* @param $request L'objet request qu'on va remplir
	* @return notre requete parsee
	*/
	static function parse($url, $request){
		$url = trim($url, '/');
		if($url == ''){
			$request->controller = Config::$defautRoute[0];
			$request->action = Config::$defautRoute[1];
			$request->params = Config::$defautRoute[2];
		}
		else{
			$params = explode('/', $url);

			$request->controller = $params[0];
			$request->action = isset($params[1]) ? $params[1] : 'index';
			$request->params = array_slice($params, 2);
		}

		return $request;
	}

}