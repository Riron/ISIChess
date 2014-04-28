<?php
class ajaxController extends Controller {

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

		// exit afin d'eviter que quoi que ce soit d'autre soit renvoyé
		exit(json_encode($nb));
	}

	/**
	* Permet de gérer les notifications => si on a des invitations ou des parties en attente
	*/
	function notifications(){
		$res['nb_invit'] = 0;

		// On check s'il y a des invitations en attente
		if(!$this->session->isLogged())
			exit(json_encode($res));

		$id = $this->session->readEntry('user')->id_utilisateur;

		$this->loadModel('Etat');
		$this->loadModel('Chess');
		$nb_invit = $this->Chess->innerJoin(array(
			'champs' => array('COUNT(*)'),
			'tableEtrangere' => $this->Etat->table,
			'jointure' => 'etat_partie.cod_etat = partie.cod_etat',
			'conditions' => array('id_utilisateur_noir' => $id, 'lib_etat' => 'Validation')
		));

		//On check s'il y a des parties ou c'est à moi de jouer
		$monTour = 0; //Nb de parties à laquelle on doit jouer
		$this->loadModel('Chess');
		$gamesBlanc = $this->Chess->find(array(
			'champs' => array('id_partie'),
			'conditions' => array('cod_etat' => 2, 'id_utilisateur_blanc' => $id)
		));
		$gamesNoir = $this->Chess->find(array(
			'champs' => array('id_partie'),
			'conditions' => array('cod_etat' => 2, 'id_utilisateur_noir' => $id)
		));

		$this->loadModel('Coup');
		foreach ($gamesBlanc as $k => $v) {
			$c = $this->Coup->findFirst(array(
				'champs' => array('cod_san_noir'),
				'conditions' => array('id_partie' => $v->id_partie),
				'order' => array('champ' => 'num_coup', 'sens' => 'DESC')
			));
			//Si aucun coup n'a encore été joué, c'est au blanc
			if(!$c){
				$monTour++;
			}
			else{
				if($c->cod_san_noir != null)
					$monTour++;
			}
		}

		foreach ($gamesNoir as $k => $v) {
			$c = $this->Coup->findFirst(array(
				'champs' => array('cod_san_noir'),
				'conditions' => array('id_partie' => $v->id_partie),
				'order' => array('champ' => 'num_coup', 'sens' => 'DESC')
			));
			if($c != false){
				if($c->cod_san_noir === null)
					$monTour++;					
			}
		}

		
		//On construit un tableau de résultats
		$tmp = 'COUNT(*)';
		$res['nb_invit'] = $nb_invit[0]->$tmp;
		$res['mon_tour'] = $monTour;

		//On renvoit le tout
		exit(json_encode($res));
	}
}