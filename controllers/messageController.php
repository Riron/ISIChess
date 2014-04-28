<?php
class messageController extends Controller {

	protected $models = array('Message');

	/**
	* Permet d'envoyer une invitation à un autre utilisateur pour jouer une partie avec lui
	* @param $id Id de la partie créée
	*/
	function invite($id){
		$this->loadModel('Chess');
		$id_partie = $this->Chess->insert(array(
			'champs' => array('id_utilisateur_blanc' => $this->session->readEntry('user')->id_utilisateur, 'id_utilisateur_noir' => $id, 'dat_debut' => date("d/m/y"), 'cod_etat' => 1)
		));

		$this->loadModel('Coup');
		$this->Coup->insert(array(
			'champs' => array('id_partie' => $id_partie, 'cod_san_blanc' => '', 'cod_fen' => '')
		));

		$this->redirect($this->generateUrl('chess', 'play', $id_partie));
	}

}