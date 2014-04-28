<?php
class chessController extends Controller {

	protected $models = array('Chess', 'Coup');

	/**
	* "Salle de jeu", affichage de la board avec possibilité de jouer, et d'un chat
	* @param $id Id de la partie
	*/
	
	function play($id=null){
		if($id==null){
			$this->session->setFlash('Numéro de partie invalide');
			return $this->redirect(WEBROOT);
		}
		
		$this->loadModel();
		
		// On charge la partie d'id passe en parametre
		$partie = $this->Chess->findFirst(array(
			'conditions' => array('id_partie' => $id)
		));

		// Si la partie n'existe pas, erreur
		if($partie==null){
			$this->session->setFlash('Numéro de partie invalide');
			return $this->redirect(WEBROOT);
		}

		//Si l'utilisateur n'appartient pas a la partie il est redirigé, sinon on stocke la couleur du joueur qui désire jouer
		if($partie->id_utilisateur_blanc == $this->session->readEntry('user')->id_utilisateur){
			$couleurJoueur = 'Blanc';
		}
		else if($partie->id_utilisateur_noir == $this->session->readEntry('user')->id_utilisateur){
			$couleurJoueur = 'Noir';
		}
		else{
			$this->session->setFlash('Vous n\'etes pas un joueur de la partie');
			return $this->redirect(WEBROOT);
		}

		//Si la partie est déjà terminée on quitte aussi la page
		if($partie->cod_etat == 3){
			$this->session->setFlash('Erreur: on ne peut pas accéder à une partie terminée', 'error');
			return $this->redirect($this->generateUrl('users', 'profile', $this->session->readEntry('user')->id_utilisateur));
		}

		//Si l'adversaire a abondonné on le signale et on change l'état de la partie
		if($partie->cod_etat == 5){
			$this->Chess->update(array(
				'conditions' => array('id_partie' => $id),
				'champs' => array('cod_etat' => 4)
			));

			//On update nb victoire/abandon
			$this->loadModel('Users');
			$v = 'id_utilisateur_'.strtolower($couleurJoueur);
			$perdant = ($couleurJoueur == 'Noir') ? 'blanc' : 'noir';
			$p = 'id_utilisateur_'.$perdant;
			$this->Users->update(array(
				'conditions' => array('id_utilisateur' => $partie->$v),
				'champs' => array('nb_victoire' => 'nb_victoire+1', 'noQuote' => 1)
			));
			$this->Users->update(array(
				'conditions' => array('id_utilisateur' => $partie->$p),
				'champs' => array('nb_abandon' => 'nb_abandon + 1', 'nb_defaite' => 'nb_defaite + 1', 'noQuote' => 1)
			));

			$this->session->setFlash('Votre adversaire a abandonné, vous êtes le vainqueur', 'success');
			return $this->redirect($this->generateUrl('chess', 'play', $id));
		}
		//Avec code intermédiaire pour afficher aux deux joueurs
		if($partie->cod_etat == 4){
			$this->Chess->update(array(
				'conditions' => array('id_partie' => $id),
				'champs' => array('cod_etat' => 3)
			));
		}

		// On recupere l'etat de la partie
		$this->loadModel('Coup');
		$etat = $this->Coup->getEtat($id);

		//On charge le modele message
		$this->loadModel('Message');

		// On inclut les classes necessaires au jeu
		require_once(ROOT.'classes/JeuDEchec.class.php');

		$this->chess = new JeuDEchec($etat, $couleurJoueur);
		
		//Si on recoit des données via _POST
		if($this->request->data){
			// Si on recoit un message
			if(isset($this->request->data['message'])){
				$this->Message->insert(array(
					'champs' => array('id_utilisateur' => $this->session->readEntry('user')->id_utilisateur, 'id_partie' => $id, 'dath_message' => date("Y-m-d H:i:s"), 'txt_message' => $this->request->data['message'])
				));
			}
			//Sinon c'est que c'est un coup
			else{
				//Si le coup est valide
				if($this->chess->jouerCoup($this->request->data)){
					//Est ce un nouveau coup ou bien doit on compléter un coup existant?
					$codSan = $this->chess->getPlateau()->getSan();
					if($couleurJoueur == 'Noir'){
						$numCoup = $this->Coup->findFirst(array(
							'champs' => array('num_coup'),
							'conditions' => array('id_partie' => $id),
							'order' => array('champ' => 'num_coup', 'sens' => 'DESC')
						));
						$this->Coup->update(array(
							'champs' => array('cod_fen' => $this->chess->getPlateau()->toFen(), 'cod_san_noir' => $codSan),
							'conditions' => array('id_partie' => $id, 'num_coup' => $numCoup->num_coup)
						));
					}
					else{
						$this->Coup->insert(array(
							'champs' => array('id_partie' => $id, 'cod_fen' => $this->chess->getPlateau()->toFen(), 'cod_san_blanc' => $codSan),
						));
					}

					//On reconstruit les coups possibles du bon joueur afin de pouvoir bouger les pieces sur l'échiquier en JQuery
					$this->chess->initialiserLesCoupsTheoriquementPossiblesDesJoueurs();
					if($this->chess->getPlateau()->getCouleurDuJoueurActuel()==='Noir'){
						$this->chess->initialiserLesCoupsEffectivementPossiblesDuJoueur($this->chess->joueurs[1]);
					}
					else{
						$this->chess->initialiserLesCoupsEffectivementPossiblesDuJoueur($this->chess->joueurs[0]);
					}
				}
				//Sinon on renvoit une erreur
				else{
					$this->session->setFlash('Coup invalide', 'error');
				}
			}
		}

		// On test si la partie est finie
		// 1 => PAT
		// 0 => Echec et mat
		if($this->chess->patOuMatOuRien() == 1){
			$this->session->setFlash('Pat !', 'info');
			if($this->chess->getPlateau()->getCouleurDuJoueurActuel()==$couleurJoueur){
				$this->Chess->update(array(
						'champs' => array('dat_fin' => date("Y-m-d H:i:s"), 'bl_joueur_blanc' => 2, 'bl_joueur_noir' => 2,'cod_etat' => 3),
						'conditions' => array('id_partie' => $id)
				));

				//On incrémente le nombre de pat
				$this->loadModel('Users');
				$this->Users->update(array(
					'conditions' => array('or' => array('id_utilisateur' => $partie->id_utilisateur_blanc, 'id_utilisateur' => $partie->id_utilisateur_noir)),
					'champs' => array('nb_pat' => 'nb_pat + 1', 'noQuote' => 1)
				));

				//Permet d'updater les ratings
				require_once(ROOT.'classes/Rating.class.php');
				$this->loadModel('Users');
				$ancienRatingJoueurBlanc=$this->Users->findFirst(array(
					'conditions' => array('id_utilisateur' => $partie->id_utilisateur_blanc),
					'champs' => array('indice_niveau')
				));
				$ancienRatingJoueurNoir=$this->Users->findFirst(array(
					'conditions' => array('id_utilisateur' => $partie->id_utilisateur_noir),
					'champs' => array('indice_niveau')
				));
				$ratings=Rating::donneNouveauRating($ancienRatingJoueurBlanc->indice_niveau, $ancienRatingJoueurNoir->indice_niveau,0.5);
				$this->Users->update(array(
					'conditions' => array('id_utilisateur' => $partie->id_utilisateur_blanc),
					'champs' => array('indice_niveau' => $ratings[0])
				));
				$this->Users->update(array(
					'conditions' => array('id_utilisateur' => $partie->id_utilisateur_noir),
					'champs' => array('indice_niveau' => $ratings[1])
				));
			}
		}
		else if($this->chess->patOuMatOuRien() == 0){
			if($this->chess->getPlateau()->getCouleurDuJoueurActuel()==$couleurJoueur){
				$this->session->setFlash('Echec et mat ! Désolé, une prochaine fois peut être', 'error');
				$vainqueur=($couleurJoueur == 'Noir') ? 'blanc' : 'noir';

				$this->Chess->update(array(
					'champs' => array('dat_fin' => date("Y-m-d H:i:s"), 'bl_joueur_'.$vainqueur => 1, 'bl_joueur_'.strtolower($couleurJoueur) => 0,'cod_etat' => 3),
					'conditions' => array('id_partie' => $id)
				));

				//On incrémente le nombre de défaites/victoires
				$this->loadModel('Users');
				$v = 'id_utilisateur_'.$vainqueur;
				$this->Users->update(array(
					'conditions' => array('id_utilisateur' => $partie->$v),
					'champs' => array('nb_victoire' => 'nb_victoire + 1', 'noQuote' => 1)
				));
				$p = 'id_utilisateur_'.strtolower($couleurJoueur);
				$this->Users->update(array(
					'conditions' => array('id_utilisateur' => $partie->$p),
					'champs' => array('nb_defaite' => 'nb_defaite + 1', 'noQuote' => 1)
				));

				//Permet d'updater les ratings
				require_once(ROOT.'classes/Rating.class.php');
				$this->loadModel('Users');
				$ancienRatingJoueurBlanc=$this->Users->findFirst(array(
					'conditions' => array('id_utilisateur' => $partie->id_utilisateur_blanc),
					'champs' => array('indice_niveau')
				));
				$ancienRatingJoueurNoir=$this->Users->findFirst(array(
					'conditions' => array('id_utilisateur' => $partie->id_utilisateur_noir),
					'champs' => array('indice_niveau')
				));
				$ratings=Rating::donneNouveauRating($ancienRatingJoueurBlanc->indice_niveau, $ancienRatingJoueurNoir->indice_niveau, ($vainqueur == 'blanc')?1:0);
				$this->Users->update(array(
					'conditions' => array('id_utilisateur' => $partie->id_utilisateur_blanc),
					'champs' => array('indice_niveau' => $ratings[0])
				));
				$this->Users->update(array(
					'conditions' => array('id_utilisateur' => $partie->id_utilisateur_noir),
					'champs' => array('indice_niveau' => $ratings[1])
				));
			}
			else{
				$this->session->setFlash('Echec et mat ! Bravooooo !!!', 'success');
			}
		}

		//On recupère les messages associes a la partie
		$messages = $this->Message->find(array(
			'conditions' => array('id_partie' => $id),
			'order' => array('champ' => 'dath_message', 'sens' => 'DESC')
		));

		//On fais tous les appels necessaires à l'affichage du plateau
		$listeJoueurs=$this->chess->getJoueurs();
		$lePlateau=$this->chess->getPlateau();
		$lesPositions=$lePlateau->getPositions();
		$this->chess->getPlateau()->buildBoard();

		//Finalement on rend la vue
		$this->render(__FUNCTION__, array('board' => $this->chess->getPlateau()->getHtml(), 'id' => $id, 'messages' => $messages, 'joueurActuel' => $couleurJoueur, 'joueurQuiDoitJouer' => $this->chess->getPlateau()->getCouleurDuJoueurActuel()));
	}

	/**
	* Permet d'envoyer une invitation a jouer une partie. 
	* On cré la partie. Elle sera supprimée si jamais l'invitation est declinée
	* @param $id Id de la partie
	*/
	function invite($id){
		$this->loadModel('Chess');
		$id_partie = $this->Chess->insert(array(
			'champs' => array('id_utilisateur_blanc' => $this->session->readEntry('user')->id_utilisateur, 'id_utilisateur_noir' => $id, 'dat_debut' => date("Y-m-d H:i:s"), 'cod_etat' => 1, 'bl_public' => 0)
		));
		$this->redirect($this->generateUrl('chess', 'play', $id_partie));
	}
	
	function inviteIA(){
		$this->loadModel('Chess');
		$id_partie = $this->Chess->insert(array(
			'champs' => array('id_utilisateur_blanc' => $this->session->readEntry('user')->id_utilisateur, 'id_utilisateur_noir' => 'IA', 'dat_debut' => date("Y-m-d H:i:s"), 'cod_etat' => 2)
		));
		$this->redirect($this->generateUrl('chess', 'playAgainstIA', $id_partie));		
	}

	/**
	* Permet de confirmer une invitation. Change donc le statut de la partie
	* @param $id Id de la partie a confirmer
	*/
	function confirmInvit($id=null){
		//Si $id is not set, erreur
		if($id==null){
			$this->session->setFlash('Numéro de partie invalide');
			return $this->redirect(WEBROOT);
		}

		$id_usr = $this->session->readEntry('user')->id_utilisateur;
		$players = $this->Chess->findFirst(array(
			'champs' => array('id_utilisateur_noir'),
			'conditions' => array('id_partie' => $id)
		));

		// Si l'invitation ne nous concerne pas, erreur
		if($players->id_utilisateur_noir != $id_usr){
			$this->session->setFlash('Vous n\'êtes pas autorisé à intervenir dans cette partie');
			return $this->redirect(WEBROOT);
		}

		//Sinon tout va bien, on change le statut de la partie a "En cours"
		$this->loadModel('Etat');
		$this->loadModel('Chess');
		$this->Chess->update(array(
			'champs' => array('cod_etat' => 2),
			'conditions' => array('id_partie' => $id)
		));

		$this->redirect($this->generateUrl('chess', 'play', $id));
	}

	/**
	* Permet de supprimer toutes les informations relatives a une partie si une invitation est refusee
	* @param $id Id de la partie a supprimer
	*/
	function delete($id){
		$this->loadModel();

		$this->Chess->delete(array(
			'conditions' => array('id_partie' => $id)
		));

		$this->Coup->delete(array(
			'conditions' => array('id_partie' => $id)
		));

		$this->session->setFlash('L\'invitation a bien été déclinée !', 'success');
		$this->redirect($this->generateUrl('users', 'invite'));
	}

	/**
	* Permet de visualiser une partie publique
	* @param $id Id de la partie à visualiser
	*/
	function view($id = null){
		if($id==null){
			$this->session->setFlash('Numéro de partie invalide');
			return $this->redirect(WEBROOT);
		}

		// On charge tous les coups de la partie
		$this->loadModel('Coup');
		$coups = $this->Coup->find(array(
			'champs' => array('num_coup', 'cod_san_blanc', 'cod_san_noir'),
			'conditions' => array('id_partie' => $id)
		));
		$partie = $this->Chess->findFirst(array(
			'champs' => array('bl_joueur_blanc', 'bl_joueur_noir', 'id_utilisateur_blanc', 'id_utilisateur_noir'),
			'conditions' => array('id_partie' => $id)
		));
		$this->loadModel('Users');
		$userBlanc = $this->Users->findFirst(array(
			'champs' => array('login'),
			'conditions' => array('id_utilisateur' => $partie->id_utilisateur_blanc)
		)); 
		$userNoir = $this->Users->findFirst(array(
			'champs' => array('login'),
			'conditions' => array('id_utilisateur' => $partie->id_utilisateur_noir)
		)); 
		$this->render(__FUNCTION__, array('tableauSan' => json_encode($coups), 'partie' => json_encode($partie), 'userBlanc' => $userBlanc->login, 'userNoir' => $userNoir->login));
		
	}

	/**
	* Permet à l'admin d'ajouter une partie à partir du code SAN
	*/
	function add(){
		//Acessible uniquement aux admins
		if(!$this->session->isAdmin()){
			$this->session->setFlash('Seul un admin peut ajouter une partie');
			return $this->redirect(WEBROOT);
		}

		//Si on a des données envoyées en _POST on les traite
		if($this->request->data){
			// On inclut les classes necessaires au jeu
			require_once(ROOT.'classes/JeuDEchec.class.php');

			$this->chess = new JeuDEchec(null,'Blanc');
			$partie=$this->chess->jouerAPartirDeSan($this->request->data['codeSan']);

			if(!$partie){
				$this->session->setFlash('Code SAN invalide', 'error');
				return $this->redirect($this->generateUrl('chess', 'add'));
			}

			// On crée une nouvelle partie
			$id = $this->Chess->insert(array(
				'champs' => array('id_utilisateur_blanc' => $this->request->data['player1'], 'id_utilisateur_noir' => $this->request->data['player2'], 'cod_etat' => 3, 'bl_public' => 1, 'bl_joueur_blanc' => $partie['res_blanc'] , 'bl_joueur_noir' => $partie['res_noir'], 'dat_debut' => date("Y-m-d H:i:s"), 'dat_fin' => date("Y-m-d H:i:s"))
			));

			//On insère tous les coups
			foreach ($partie['coups'] as $k => $v){
				$this->Coup->insert(array(
					'champs' => array('id_partie' => $id, 'cod_san_blanc' => $v['blanc'],'cod_san_noir' => isset($v['noir'])? $v['noir']:NULL, 'cod_fen' => $v['fen'])
				));
			}

			$this->session->setFlash('La partie a bien été ajoutée et est désormais accessible dans la bibliothèque', 'success');
		}

		// On charge la liste des utilisateurs pour l'envoyer à la vue
		$this->loadModel('Users');
		$users = $this->Users->find(array(
			'champs' => array('id_utilisateur', 'login')
		));

		$this->render(__FUNCTION__, array('utilisateurs' => $users));
	}

	/**
	* Permet à un joueur d'abandonner
	* @param $id id de la partie
	* @param $couleur couleur du joueur qui abondonne
	*/
	function abandonner($id, $couleur){
		$partie = $this->Chess->findFirst(array(
			'conditions' => array('id_partie' => $id)
		));
		if($partie->cod_etat==1){
			$this->loadModel();
			
			$this->Chess->delete(array(
				'conditions' => array('id_partie' => $id)
			));

			$this->Coup->delete(array(
				'conditions' => array('id_partie' => $id)
			));
		}
		else{
			// On determine la couleur du vainqueur
			$vainqueur = ($couleur == 'Noir') ? 'blanc' : 'noir';

			// On Update le statut de la partie
			$this->Chess->update(array(
				'champs' => array('dat_fin' => date("Y-m-d H:i:s"), 'bl_joueur_'.$vainqueur => 1, 'bl_joueur_'.strtolower($couleur) => 0,'cod_etat' => 5),
				'conditions' => array('id_partie' => $id)
			));

			// Puis on redirige l'utilisateur vers son profil
		}
		$this->session->setFlash("Vous avez abandonné la partie");
		$this->redirect($this->generateUrl('users', 'profile', $this->session->readEntry('user')->id_utilisateur));
	}

	/**
	* Permet de renvoyer un boolean concernant la nécéssité ou non d'updater la page d'une partie (si l'autre joueur a joué)
	* @param $id id de la partie concernée
	*/
	function update($id){
		$this->loadModel('Coup');
		$etat = $this->Coup->getEtat($id);
		$etat = explode(' ',str_replace('/', '.', $etat));
		$etat = $etat[0];

		$ended = $this->Chess->findFirst(array(
			'champs' => array('cod_etat'),
			'conditions' => array('id_partie' => $id)
		));

		//Exit afin d'eviter un probleme si du code html est affiché par la suite. (ex: barre de dev)
		exit(json_encode(array('etat' => $etat, 'ended' => $ended->cod_etat)));
	}

	/**
	* Permet de rendre une partie publique
	* @param $id
	* @param $choix définit si on veut rendre la partie publique ou privée
	*/
	function makePublic($id, $choix){
		//Acessible uniquement aux admins
		if(!$this->session->isAdmin()){
			$this->session->setFlash('Seul un admin peut rendre une partie publique');
			return $this->redirect(WEBROOT);
		}

		if($choix) {
			$this->Chess->update(array(
				'champs' => array('bl_public' => 1),
				'conditions' => array('id_partie' => $id)
			));

			$this->session->setFlash('La partie '.$id.' est désormais publique');
		}
		else{
			$this->Chess->update(array(
				'champs' => array('bl_public' => 0),
				'conditions' => array('id_partie' => $id)
			));

			$this->session->setFlash('La partie '.$id.' est désormais privée');
		}
		return $this->redirect($this->generateUrl('users', 'admin'));
	}
}