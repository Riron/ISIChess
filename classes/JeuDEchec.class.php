<?php

	/*
		Import de tous les fichiers permettant de gérer le jeu d'échec.
	*/
	
	require('Joueur.class.php');
	require('Plateau.class.php');
	require('Piece.class.php');
	require('PieceLonguePortee.class.php');
	require('Cheval.class.php');
	require('Pion.class.php');
	require('Roi.class.php');
	require('Reine.class.php');
	require('Fou.class.php');
	require('Tour.class.php');
	require('Position.class.php');
	require('PositionOccupee.class.php');
	require('Conversion.class.php');
	
	class JeuDEchec{
	
		/*
		*	joueurs est un tableau de joueurs. Ce champ est public afin de ne pas avoir à passer par une variable temporaire si on utilise le getter getJoueurs().
		*	On ne peut pas faire $this->getJoueurs()[0] directement avant PHP 5.5 !
		*	joueurs[0] est le joueur Blanc.
		*	joueur[1] est le joueur Noir.
		*	plateau est de type plateau.
		*	couleurDuJoueurConsultantLaPartie est la couleur du joueur qui regarde la partie et a donc fait la requête. Cette dernière va nous servir pour initialiser les différents coups.
		*	couleurDuJoueurConsultantLaPartie peut être soit 'Blanc' soit 'Noir'.
		*/
		
		public $joueurs;
		private $plateau;
		private $couleurDuJoueurConsultantLaPartie;
		
		function setJoueurs($desJoueurs){
			$this->joueurs=$desJoueurs;
		}
		
		function getJoueurs(){
			return $this->joueurs;
		}
		
		function setPlateau($unCertainPlateau){
			$this->plateau=$unCertainPlateau;
		}
		
		function getPlateau(){
			return $this->plateau;
		}
		
		function ajouterJoueur($unJoueur){
			$this->joueurs[]=$unJoueur;
		}
		
		function getCouleurDuJoueurConsultantLaPartie(){
			return $this->couleurDuJoueurConsultantLaPartie;
		}
		
		function setCouleurDuJoueurConsultantLaPartie($uneCouleur){
			$this->couleurDuJoueurConsultantLaPartie=$uneCouleur;
		}
		
		/*
		*	Méthode permettant l'import de parties directement à partir d'un code PGN/SAN.
		*	Elle ne requiert pour seul argument un code SAN.
		*	Elle vérifie si ce dernier est valide et renvoie un tableau de coups où toute ambiguité a été levée.
		*	Ce tableau est ensuite ajouté en base par le chessController.
		*	Il n'y a bien sûr ajout que si le code SAN est valide.
		*/
		
		function jouerAPartirDeSAN($unSan){
			$data=array();
			$san=$unSan;
			$keywords = preg_split("#[0-9]{1,2}\.#",$san);
			$resultat=array();
			$resultat['coups']=array();
			$tableauDesCoups=array();
			foreach($keywords as $k=>$coup){
				if(!$coup==''){
					$petitTableauDeCoups=explode(' ',$coup);
					$tableauDesCoups[]=($petitTableauDeCoups[0]=='')? $petitTableauDeCoups[1]: $petitTableauDeCoups[0];
					$tableauDesCoups[]=($petitTableauDeCoups[0]=='')? $petitTableauDeCoups[2]: $petitTableauDeCoups[1];
				}
			}

			/*
				On teste la nature de la pièce étant en train de bouger.
				Si la première lettre est une majuscule, alors il s'agit d'autre chose que d'un pion.
				Sinon, il s'agit d'un pion.
			*/
			
			foreach($tableauDesCoups as $k=>$coup){
				//On cherche si le coup est une promotion.
				$promo='Reine';
				if(is_numeric(strpos("=",$coup))){
					preg_match("=[A-Z]",$coup,$promote);
					$promo=$promote[0];
				}
				if($coup=='O-O-O' || $coup=='O-O'){
					$naturePiece='Roi';
					if($k%2==0){
						if($coup=='O-O'){
							$positionFinale=new Position(8,7);
						}
						else{
							$positionFinale=new Position(8,3);
						}
					}
					else{
						if($coup=='O-O'){
							$positionFinale=new Position(1,7);
						}
						else{
							$positionFinale=new Position(1,3);
						}
					}
				}
				else{
					if(is_numeric($coup[0])){
						preg_match("#[0-1]{1}-[0-1]{1}#",$unSan,$bilan);
						if(strpos($unSan,'1/2-1/2')){
							$bilan=array();
							$bilan[0]="2-2";
						}
						$resultat['res_blanc']=$bilan[0][0];
						$resultat['res_noir']=$bilan[0][2];
						return $resultat;
					}
					else if(mb_strtoupper($coup[0])===$coup[0]){
						$naturePiece=Conversion::$stringPieceVersPiece[mb_strtolower($coup[0])];
					}
					else{
						$naturePiece='Pion';
					}
					preg_match("#[a-h]{1}[1-8]{1}#",$coup,$coupIntermediaire);
					//On n'a pas encore traité petit roque et grand roque
					$positionFinale=new Position(Conversion::$tableauLignesEchecsVersLignesModelisation[$coupIntermediaire[0][1]],Conversion::$tableauColonnesEchecsVersColonnesModelisation[$coupIntermediaire[0][0]]);
				}
				
				//Premier parcours pour voir combien de pièces du type donné peuvent aller à la case donnée
				$nombreDePiecesPossibles=0;
				foreach($this->joueurs[$k%2]->pieces as $piece){
					if(get_class($piece)==$naturePiece){
						if(in_array($positionFinale,$piece->tabCoupsEffectivementPossibles)){
							$nombreDePiecesPossibles++;
						}
					}
				}
				//Si aucune pièce ne peut y aller, il y a une erreur dans le code SAN
				if($nombreDePiecesPossibles==0){
					return false;
				}
				//Si une unique pièce peut y aller, alors on se moque de la précision de la ligne ou de la colonne si elle existe.
				else if($nombreDePiecesPossibles==1){
					foreach($this->joueurs[$k%2]->pieces as $piece){
						if(get_class($piece)==$naturePiece){
							if(in_array($positionFinale,$piece->tabCoupsEffectivementPossibles)){
								$data['startX']=$piece->getPosition()->getI();
								$data['startY']=$piece->getPosition()->getJ();
								$data['endX']=$positionFinale->getI();
								$data['endY']=$positionFinale->getJ();
								$data['promotion']=$promo;
								$this->jouerCoup($data);
								$this->setCouleurDuJoueurConsultantLapartie($this->getPlateau()->getCouleurDuJoueurActuel());
								$this->initialiserLesCoupsTheoriquementPossiblesDesJoueurs();
								$this->initialiserLesCoupsEffectivementPossiblesDuJoueur($this->getJoueurSuivant($this->joueurs[$k%2]));
							}
						}
					}
				}
				//Sinon, cela signifie qu'il peut y avoir plusieurs pièces qui peuvent aller à la case donnée. On doit voir quelle est la pièce qui bouge vraiment grâce à l'indicateur ligne/colonne fourni.
				else{
					//Une fois ces lettres supprimées, on est assuré de se retrouver devant le caractère de désambiguité.
					$lettresASupprimer=array("R","B","Q","K","N","x",$coupIntermediaire[0]);
					$indicateurLigneOuColonne=str_replace($lettresASupprimer, "",$coup);
					$ligneDepart=-1;
					$colonneDepart=-1;
					if(is_numeric($indicateurLigneOuColonne)){
						$ligneDepart=$indicateurLigneOuColonne[0];
					}
					else{
						$colonneDepart=$indicateurLigneOuColonne[0];
					}
					foreach($this->joueurs[$k%2]->pieces as $piece){
						if(get_class($piece)==$naturePiece){
							if(in_array($positionFinale,$piece->tabCoupsEffectivementPossibles) && (($ligneDepart!=-1 && $piece->getPosition()->getI()==Conversion::$tableauLignesEchecsVersLignesModelisation[$ligneDepart]) || ($colonneDepart!=-1 && $piece->getPosition()->getJ()==Conversion::$tableauColonnesEchecsVersColonnesModelisation[$colonneDepart]))){
								$data['startX']=$piece->getPosition()->getI();
								$data['startY']=$piece->getPosition()->getJ();
								$data['endX']=$positionFinale->getI();
								$data['endY']=$positionFinale->getJ();
								$data['promotion']=$promo;
								$this->jouerCoup($data);
								$this->setCouleurDuJoueurConsultantLaPartie($this->getPlateau()->getCouleurDuJoueurActuel());
								$this->initialiserLesCoupsTheoriquementPossiblesDesJoueurs();
								$this->initialiserLesCoupsEffectivementPossiblesDuJoueur($this->getJoueurSuivant($this->joueurs[$k%2]));
							}
						}
					}
				}
				if($this->getCouleurDuJoueurConsultantLaPartie()==='Noir'){
					$resultat['coups'][(int)$k/2]['blanc']=$this->getPlateau()->getSan();
				}
				else{
					$resultat['coups'][(int)$k/2]['noir']=$this->getPlateau()->getSan();
				}
				$resultat['coups'][(int)$k/2]['fen']=$this->getPlateau()->toFen();
			}
			preg_match("#[0-1]{1}-[0-1]{1}#",$unSan,$bilan);
			if(strpos($unSan,'1/2-1/2')){
				$bilan=array();
				$bilan[0]="2-2";
			}
			$resultat['res_blanc']=$bilan[0][0];
			$resultat['res_noir']=$bilan[0][2];
			return $resultat;
		}
		
		/*
			La méthode qui suit remplit remplit le tableau coupsTheoriquementPossibles de chaque joueur.
		*/
		
		function initialiserLesCoupsTheoriquementPossiblesDuJoueur($unJoueur){
			$unJoueur->setCoupsTheoriquementPossibles(array());
			foreach($unJoueur->getPieces() as $piece){
				$piece->tableauCoupsTheoriquementPossibles($this);
				foreach($piece->tabCoupsTheoriquementPossibles as $position){
					$unJoueur->ajouterCoupTheorique($position);
				}
			}	
		}
		
		function initialiserLesCoupsEffectivementPossiblesDuJoueur($unJoueur){
			$unJoueur->setCoupsEffectivementPossibles(array());
			foreach($unJoueur->getPieces() as $piece){
				$piece->tableauCoupsEffectivementPossibles($this);
				foreach($piece->tabCoupsEffectivementPossibles as $position){
					$unJoueur->ajouterCoupEffectif($position);
				}
			}
		}
		
		function reinitialiserLesCoupsTheoriquementPossiblesDesJoueurs(){
			foreach($this->getJoueurs() as $joueur){
				foreach($joueur->pieces as $piece){
					$piece->tabCoupsTheoriquementPossibles=array();
				}
			}
		}
		
		function reinitialiserLesCoupsEffectivementPossiblesDesJoueurs(){
			foreach($this->getJoueurs() as $joueur){
				foreach($joueur->pieces as $piece){
					$piece->tabCoupsEffectivementPossibles=array();
				}
			}
		}
		
		function initialiserLesCoupsTheoriquementPossiblesDesJoueurs(){
			foreach($this->getJoueurs() as $joueur){
				$this->initialiserLesCoupsTheoriquementPossiblesDuJoueur($joueur);
			}
		}
		
		function initialiserLesCoupsEffectivementPossiblesDesJoueurs(){
			foreach($this->getJoueurs() as $joueur){
				$this->initialiserLesCoupsEffectivementPossiblesDuJoueur($joueur);
			}
		}
		
		/*
			Méthode permettant de régler le nombre de joueurs par défaut à 2.
		*/
		
		function nombreJoueurs(){
			return 2;
		}
		
		/*
		*	Reçoit data qui est un tableau avec différents champs : startX, startY, endX, endY, promotion notamment.
		*	Joue le coup en question, sous réserve que ce soit le bon joueur qui demande le mouvement, et que le coup soit valide, bien entendu.
		*/
		
		function jouerCoup($data){
			if(get_class($this->getPlateau()->positions[$data['startX']][$data['startY']])==='PositionOccupee' && $this->getPlateau()->positions[$data['startX']][$data['startY']]->getPiece()->getCouleur()===$this->getPlateau()->getCouleurDuJoueurActuel() && $this->getCouleurDuJoueurConsultantLaPartie()==$this->getPlateau()->getCouleurDuJoueurActuel()){
				if(in_array(new Position($data['endX'],$data['endY']),$this->getPlateau()->positions[$data['startX']][$data['startY']]->getPiece()->tabCoupsEffectivementPossibles)){
					if(isset($data['promotion']) && get_class($this->getPlateau()->positions[$data['startX']][$data['startY']]->getPiece())==='Pion' && $this->getPlateau()->positions[$data['startX']][$data['startY']]->getPiece()->surLigneAvantPromotion($this->getPlateau())){
						$this->getPlateau()->positions[$data['startX']][$data['startY']]->getPiece()->deplacer(new Position($data['endX'],$data['endY']),$this->getPlateau());
						$cle=array_search($this->getPlateau()->positions[$data['endX']][$data['endY']]->getPiece(),$this->getPlateau()->positions[$data['endX']][$data['endY']]->getPiece()->getJoueur()->pieces);
						$player=$this->getPlateau()->positions[$data['endX']][$data['endY']]->getPiece()->getJoueur();
						$i=$this->getPlateau()->positions[$data['endX']][$data['endY']]->getI();
						$j=$this->getPlateau()->positions[$data['endX']][$data['endY']]->getJ();
						$this->getPlateau()->positions[$data['endX']][$data['endY']]->setPiece(new $data['promotion']($player,new Position($i,$j)));
						unset($this->getPlateau()->positions[$data['endX']][$data['endY']]->getPiece()->getJoueur()->pieces[$cle]);
						$promo=Conversion::$promo;
						if($this->getPlateau()->getCouleurDuJoueurActuel()==='Blanc' && substr($this->getPlateau()->getSan(),-1,1)=='x'){
							$this->getPlateau()->setSan($this->getPlateau()->getSan().'='.$promo[$data['promotion']]);
						}
						else if($this->getPlateau()->getCouleurDuJoueurActuel()==='Blanc'){
							$this->getPlateau()->setSan($this->getPlateau()->getSan().' ='.$promo[$data['promotion']]);
						}
						else if($this->getPlateau()->getCouleurDuJoueurActuel()==='Noir' && substr($this->getPlateau()->getSan(),-1,1)=='x'){
							$this->getPlateau()->setSan($this->getPlateau()->getSan().'='.mb_strtolower($promo[$data['promotion']]));
						}
						else{
							$this->getPlateau()->setSan($this->getPlateau()->getSan().' ='.mb_strtolower($promo[$data['promotion']]));
						}
					}
					else{
						$this->getPlateau()->positions[$data['startX']][$data['startY']]->getPiece()->deplacer(new Position($data['endX'],$data['endY']),$this->getPlateau());
					}
					//Le joueur dont c'est le tour de jouer est mis à jour.
					$this->getPlateau()->getCouleurDuJoueurActuel()==='Noir' ? $this->getPlateau()->setCouleurDuJoueurActuel('Blanc') : $this->getPlateau()->setCouleurDuJoueurActuel('Noir');
					return true;
				}
				return false;
			}
			return false;
		}
		
		function getJoueurSuivant($unJoueur){
			$cleJoueurEnCours=array_search($unJoueur,$this->getJoueurs());
			return $this->joueurs[($cleJoueurEnCours+1)%($this->nombreJoueurs())];
		}
		
		/*
		*	Retourne 0 si échec et mat. Un mat est une situation où un joueur est en échec et ne peut jouer aucun coup effectif.
		*	Retour 1 si pat. Un pat est une situation où un joueur n'est pas en échec, mais ne peut jouer aucun coup effectif.
		*	Retourne 2 si la partie peut continuer son cours. L'abandon est géré au niveau du chesscontroller.
		*/
		
		function patOuMatOuRien(){
			foreach($this->getJoueurs() as $joueur){
				if($joueur->getCouleur()===$this->getPlateau()->getCouleurDuJoueurActuel()){
					if($joueur->estEnEchec($this) && empty($joueur->coupsEffectivementPossibles)){
						return 0;
					}
					else if(empty($joueur->coupsEffectivementPossibles)){
						return 1;
					}
				}
			}
			return 2;
		}
		
		function autorisationDuCoup($unePiece,$unePosition){
			//On stocke la position initiale de la pièce.
			$positionInitiale=$unePiece->getPosition();
			
			//Si la pièce mange, on stocke la pièce mangée.
			if(get_class($this->getPlateau()->sePlacerALaPosition($unePosition))==='PositionOccupee'){
				$pieceInitialementOccupante=$this->getPlateau()->sePlacerALaPosition($unePosition)->getPiece();
				$cle=array_search($pieceInitialementOccupante,$this->getJoueurSuivant($unePiece->getJoueur())->pieces);
				unset($this->getJoueurSuivant($unePiece->getJoueur())->pieces[$cle]);
			}
			
			//Situation de mange en passant et donc de prise particulière
			else if(get_class($this->getPlateau()->sePlacerALaPosition($unePosition))==='Position' && $unePiece->estPion() && $unePiece->getPosition()->getJ()!=$unePosition->getJ()){
				$positionPionMange=new Position($unePiece->getPosition()->getI(),$unePosition->getJ());
				$pionInitialementOccupant=$this->getPlateau()->sePlacerALaPosition($positionPionMange)->getPiece();
				$cle=array_search($pionInitialementOccupant,$this->getJoueurSuivant($unePiece->getJoueur())->pieces);
				unset($this->getJoueurSuivant($unePiece->getJoueur())->pieces[$cle]);				
			}
			
			//On simule un déplacement.
			
			$unePiece->deplacerPourTester($unePosition,$this->getPlateau());
			
			//On regarde les coups théoriquement possibles de l'adversaire.
			$this->initialiserLesCoupsTheoriquementPossiblesDuJoueur($this->getJoueurSuivant($unePiece->getJoueur()));
			if(!$unePiece->getJoueur()->estEnEchec($this)){
				$resultat=true;
			}
			else{
				$resultat=false;
			}
			
			//On déplace en sens inverse.
			
			$unePiece->deplacerPourTester($positionInitiale,$this->getPlateau());
			
			//On replace la pièce éventuellement mangée.
			if(isset($pieceInitialementOccupante)){
				$this->getPlateau()->positions[$unePosition->getI()][$unePosition->getJ()]=new PositionOccupee($unePosition->getI(),$unePosition->getJ(),$pieceInitialementOccupante);
				$this->getJoueurSuivant($unePiece->getJoueur())->pieces[$cle]=$pieceInitialementOccupante;
			}
			else if(isset($pionInitialementOccupant)){
				$this->getPlateau()->positions[$positionPionMange->getI()][$positionPionMange->getJ()]=new PositionOccupee($positionPionMange->getI(),$positionPionMange->getJ(),$pionInitialementOccupant);
				$this->getJoueurSuivant($unePiece->getJoueur())->pieces[$cle]=$pionInitialementOccupant;				
			}
			
			//On réinitialise les coups théoriquement possibles de l'adversaire.
			$this->initialiserLesCoupsTheoriquementPossiblesDuJoueur($this->getJoueurSuivant($unePiece->getJoueur()));
			return $resultat;
		}
		
		function __construct($codeFenActuelDuPlateau,$uneCouleur){
			$this->setJoueurs(array());
			$this->setCouleurDuJoueurConsultantLaPartie($uneCouleur);
			$this->ajouterJoueur((new Joueur('Blanc')));
			$this->ajouterJoueur((new Joueur('Noir')));
			$this->setPlateau(new Plateau($codeFenActuelDuPlateau,$this->getJoueurs()));
			$this->getPlateau()->setCouleurDuJoueurConsultantLaPartie($uneCouleur);
			$this->initialiserLesCoupsTheoriquementPossiblesDesJoueurs();
			$this->getPlateau()->getCouleurDuJoueurActuel()==='Noir' ? $this->initialiserLesCoupsEffectivementPossiblesDuJoueur($this->joueurs[1]) : $this->initialiserLesCoupsEffectivementPossiblesDuJoueur($this->joueurs[0]);
		}
		
	}
	
?>