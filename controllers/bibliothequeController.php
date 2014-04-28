<?php
class bibliothequeController extends Controller {

	protected $models = array('bibliotheque');

	/**
	* Liste toutes les parties publiques
	*/
	function index(){
		$this->loadModel();

		// On recupere les infos relatives aux parties
		$parties = $this->bibliotheque->find(array(
			'champs' => array('id_partie','id_utilisateur_blanc', 'id_utilisateur_noir', 'dat_fin'),
			'conditions' => array('bl_public' => 1, 'cod_etat' => 3)
		));

		// On recupere les pseudos du joueur B
		$joueurB = $this->bibliotheque->innerJoin(array(
			'champs' => array('utilisateur.login'),
			'tableEtrangere' => 'utilisateur',
			'jointure' => 'partie.id_utilisateur_blanc = utilisateur.id_utilisateur',
			'conditions' => array('partie.bl_public' => 1, 'cod_etat' => 3)
		));
		// On recupere les pseudos du joueu N
		$joueurN = $this->bibliotheque->innerJoin(array(
			'champs' => array('utilisateur.login'),
			'tableEtrangere' => 'utilisateur',
			'jointure' => 'partie.id_utilisateur_noir = utilisateur.id_utilisateur',
			'conditions' => array('partie.bl_public' => 1, 'cod_etat' => 3)
		));
		
		$this->render(__FUNCTION__, array('parties' => $parties, 'jB' => $joueurB, 'jN' => $joueurN));
	}
}