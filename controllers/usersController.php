<?php
class usersController extends Controller {

	protected $models = array('Users');

	/**
	* Permet de se connecter
	*/
	function login(){
		// Si on recoit des données en POST
		if($this->request->data){
			$this->loadModel();
			$data = $this->request->data;
			$user = $this->Users->findFirst(array(
				'conditions' => array('login' => $data['login'], 'password' => $data['password'])
			));
			// Si l'utilisateur est authentifié, on le rentre en session
			if(!empty($user)){
				$this->session->addEntry('user', $user);
				$this->session->setFlash('Vous êtes désormais connecté', 'success');
			}
			else{
				$this->session->setFlash('Mauvais login ou mot de passe', 'error');
			}
		}
		//Si on est correctement loggé
		if($this->session->isLogged()){
			return $this->redirect($this->generateUrl('users', 'profile', $this->session->readEntry('user')->id_utilisateur));
		}
		$this->render(__FUNCTION__);

	}

	/**
	* Permet de se déconnecter
	*/
	function logout(){
		$this->session->unsetEntry('user');
		$this->session->setFlash('Vous êtes désormais déconnecté', 'success');
		$this->redirect(WEBROOT);
	}

	/**
	* Permet de s'inscrire à la plate forme
	*/
	function subscribe(){
		if($this->request->data){
			$error = FALSE;
			$data = $this->request->data;
			// On fait des verifications sur les champs
			if(!validateMail($data['email'])){
				$this->session->setFlash('Adresse e-mail incorrecte', 'error');
				$error = TRUE;
			}
			if(empty($data['login']) | empty($data['password']) | empty($data['confirm'])){
				$this->session->setFlash('Vous devez rentrer un login et un mot de passe', 'error');
				$error = TRUE;
			}
			if($data['password'] != $data['confirm']){
				$this->session->setFlash('Les deux mots de passe doivent etre identique', 'error');
				$error = TRUE;
			}

			if(!$error){
				// Si tous les tests sont bons, on charge le modele et on insere le nvel user en base
				$this->loadModel();
				$this->Users->insert(array(
					'champs' => array('login' => $data['login'], 'password' => $data['password'], 'avatar' => $data['optionsAvatar'], 'email' => $data['email'], 'prenom' => $data['prenom'], 'nom' => $data['nom'], 'indice_niveau' => 1500, 'nb_victoire' => 0, 'nb_defaite' => 0, 'nb_abandon' => 0, 'nb_pat' => 0)
				));

				// On logue l'utilisateur directement
				return $this->login();
			}
		}
		$this->render(__FUNCTION__);
	}

	/**
	* Permet d'afficher le profil d'un utilisateur
	* @param $id
	*/
	function profile($id = 0){
		$this->loadModel();
		$user = $this->Users->findFirst(array(
			'conditions' => array('id_utilisateur' => $id
		)));

		if(empty($user)){
			$this->session->setFlash('Cet utilisateur n\'existe pas', 'error');
			return $this->redirect(WEBROOT);
		}
		$this->render(__FUNCTION__, array('user' => $user));
	}

	/**
	* Permet de lister les utilisateurs inscrits à la plate forme
	*/
	function listUsers(){
		if(!$this->session->isLogged()){
			$this->session->setFlash('Vous devez être identifié pour accéder à la liste des membres.');
			return $this->redirect($this->generateUrl(WEBROOT));
		}
		$this->loadModel();
		$users = $this->Users->find(array(
			'champs' => array('id_utilisateur', 'login', 'bl_admin', 'email', 'indice_niveau')
		));
		$this->render(__FUNCTION__, array('users' => $users));
	}

	/**
	* Permet de lister les parties en cours de l'utilisateur courant
	*/
	function games(){
		if(!$this->session->isLogged()){
			return $this->redirect($this->generateUrl(WEBROOT));
		}
		$id = $this->session->readEntry('user')->id_utilisateur;
		$this->loadModel('Chess');
		$games = $this->Chess->find(array(
			'conditions' => array('or' => array('id_utilisateur_blanc' => $id, 'id_utilisateur_noir' => $id), 'dat_fin' => 'IS NULL', 'cod_etat' => 2)
		));
		$this->loadModel();
		foreach ($games as $k => $v) {
			($v->id_utilisateur_blanc == $id) ? $id_adversaire = $v->id_utilisateur_noir : $id_adversaire = $v->id_utilisateur_blanc;
			$v->adversaire = $this->Users->findAdversaire($id_adversaire);
		}

		$abandons = $this->Chess->find(array(
			'conditions' => array('or' => array('id_utilisateur_blanc' => $id, 'id_utilisateur_noir' => $id), 'cod_etat' => 4)
		));
		foreach ($abandons as $k => $v) {
			($v->id_utilisateur_blanc == $id) ? $id_adversaire = $v->id_utilisateur_noir : $id_adversaire = $v->id_utilisateur_blanc;
			$v->adversaire = $this->Users->findAdversaire($id_adversaire);
		}

		$games = array_merge($games, $abandons);

		$this->loadModel('Coup');
		foreach ($games as $k => $v) {
			$v->monTour = 0;
			$c = $this->Coup->findFirst(array(
				'champs' => array('cod_san_noir'),
				'conditions' => array('id_partie' => $v->id_partie),
				'order' => array('champ' => 'num_coup', 'sens' => 'DESC')
			));
			//Si aucun coup n'a encore été joué, c'est au blanc
			if(!$c && $v->id_utilisateur_blanc == $id){
				$v->monTour = 1;
			}
			else if($c != false){
				if($c->cod_san_noir != null && $v->id_utilisateur_blanc == $id)
					$v->monTour = 1;
				else if($c->cod_san_noir === null && $v->id_utilisateur_noir == $id)
					$v->monTour = 1;
			}
		}

		$gamesEnded = $this->Chess->find(array(
			'conditions' => array('or' => array('id_utilisateur_blanc' => $id, 'id_utilisateur_noir' => $id), 'cod_etat' => 3)
		));

		foreach ($gamesEnded as $k => $v) {
			($v->id_utilisateur_blanc == $id) ? $id_adversaire = $v->id_utilisateur_noir : $id_adversaire = $v->id_utilisateur_blanc;
			$v->adversaire = $this->Users->findAdversaire($id_adversaire);
		}
		$this->render(__FUNCTION__, array('games' => $games, 'gamesEnded' => $gamesEnded));
	}

	/**
	* Permet de lister les invitations en attente
	*/
	function invite(){

		$id = $this->session->readEntry('user')->id_utilisateur;

		$this->loadModel('Etat');
		$this->loadModel('Chess');
		$parties = $this->Chess->innerJoin(array(
			'champs' => array('partie.id_partie', 'id_utilisateur_blanc',  'id_utilisateur_noir', 'dat_debut'),
			'tableEtrangere' => $this->Etat->table,
			'jointure' => 'etat_partie.cod_etat = partie.cod_etat',
			'conditions' => array('id_utilisateur_noir' => $id, 'lib_etat' => 'Validation')
		));

		foreach ($parties as $k => $v) {
			($v->id_utilisateur_blanc == $id) ? $id_adversaire = $v->id_utilisateur_noir : $id_adversaire = $v->id_utilisateur_blanc;
			$v->adversaire = $this->Users->findAdversaire($id_adversaire);
		}

		$this->render(__FUNCTION__, array('invitations' => $parties));
	}

	/**
	* Permet d'éditer un profil
	* @param $id Id du profil à éditer
	*/
	function edit($id){
		// On peut éditer le profil uniquement si c'est son propre compte ou si on est admin
		if(!$this->session->isAdmin() && $id != $this->session->readEntry('user')->id_utilisateur){
			return $this->redirect(WEBROOT);
		}

		if($this->request->data){
			$error = false;
			$data = $this->request->data;
			// On fait des verifications sur les champs
			if(!validateMail($data['email'])){
				$this->session->setFlash('Adresse e-mail incorrecte', 'error');
				$error = true;
			}
			if(empty($data['password']) | empty($data['confirm'])){
				$this->session->setFlash('Vous devez rentrer un mot de passe', 'error');
				$error = true;
			}
			if($data['password'] != $data['confirm']){
				$this->session->setFlash('Les deux mots de passe doivent etre identique', 'error');
				$error = true;
			}

			if(!$error){
				// Si tous les tests sont bons, on charge le modele et on insere le nvel user en base
				$this->loadModel();
				$this->Users->update(array(
					'champs' => array('nom' => $data['nom'], 'prenom' => $data['prenom'], 'email' => $data['email'], 'password' => $data['password']),
					'conditions' => array('id_utilisateur' => $id)
				));

				// On redirige l'utilisateur vers son profil
				return $this->redirect($this->generateUrl('users', 'profile'));
			}
		}
		$this->render(__FUNCTION__, array('id' => $id));
		
	}

	/**
	* Permet de générer le "panel admin"
	*/
	function admin(){
		//Seul les admins peuvent accéder à cette action
		if(!$this->session->isAdmin()){
			$this->session->setFlash("Et non ! Tu n'es pas admin petit malin.");
			return $this->redirect(WEBROOT);
		}
		
		//On charge la liste des utilisateurs
		$this->loadModel();
		$users = $this->Users->find(array(
			'champs' => array('id_utilisateur', 'login', 'bl_admin', 'email', 'nom', 'prenom')
		));

		//On charge la liste des parties
		$this->loadModel('Chess');
		$parties = $this->Chess->find(array(

		));

		$this->render(__FUNCTION__, array('users' => $users, 'parties' => $parties));
	}

	/**
	* Permet de rendre un utilisateur admin
	* @param $id Id de l'user
	* @param $choix upgrade / downgrade
	*/
	function makeAdmin($id, $choix){
		//Acessible uniquement aux admins
		if(!$this->session->isAdmin()){
			$this->session->setFlash('Seul un admin peut changer le statut d\'un utilisateur');
			return $this->redirect(WEBROOT);
		}

		// choix = 1/0 => on rend admin ou simple user
		if($choix) {
			$this->Users->update(array(
				'champs' => array('bl_admin' => 1),
				'conditions' => array('id_utilisateur' => $id)
			));

			$this->session->setFlash('L\'utilisateur '.$id.' est désormais admin');
		}
		else{
			$this->Users->update(array(
				'champs' => array('bl_admin' => 0),
				'conditions' => array('id_utilisateur' => $id)
			));

			$this->session->setFlash('L\'utilisateur '.$id.' est désormais admin');
		}
		return $this->redirect($this->generateUrl('users', 'admin'));
	}

}