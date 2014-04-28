<?php

class blogController extends Controller{

	protected $models = array('blog');

	function index(){
		$result = 'test';
		$this->render(__FUNCTION__, array('test' => $result));
	}

	function test($id=1){
		$this->loadModel();
		$result = $this->blog->findFirst(array(
			'conditions' => array('id'=> $id)
		));
		if(empty($result)){
			$this->e404('Introuvable');
		}
		$this->session->setFlash('test');
		$this->render(__FUNCTION__, array('test' => $result));

	}
}