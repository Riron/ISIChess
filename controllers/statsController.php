<?php
class statsController extends Controller {

	protected $models = array('Chess');

	/**
	* Permet de renvoyer des statistiques globales du site
	*/
	function statistiques(){
		$parties = $this->Chess->find(array(
						'champs' => array('*')
					));

		$nb['parties'] = count($parties);

		$nb['partiesEnCours'] = 0;
		$nb['partiesPubliques'] = 0;
		$nb['victoiresNoir'] = 0;
		$nb['victoiresBlanc'] = 0;
		foreach($parties as $k => $v){
			if($v->cod_etat == 2)
				$nb['partiesEnCours']++;
			if($v->bl_joueur_blanc == 1)
				$nb['victoiresBlanc']++;
			if($v->bl_joueur_noir == 1)
				$nb['victoiresNoir']++;
			if($v->bl_public == 1)
				$nb['partiesPubliques']++;
		}

		$this->loadModel('Users');
		$users = $this->Users->find(array(
						'champs' => array('*')
					));
		$nb['user'] = count($users);

		$this->loadModel('Coup');
		$coups = $this->Coup->find(array(
					'champs' => array('*')
				));
		$nb['coups'] = count($coups);

		//debug($nb);
		exit(json_encode($nb));
	}

}