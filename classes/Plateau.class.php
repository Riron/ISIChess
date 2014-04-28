<?php
	
	class Plateau{
	
		/*
		*	positions est un tableau à double entrée. Il s'agit d'une matrice de positions.
		*	Ainsi, positions[1][2] correspond à la case de la première ligne, deuxième colonne, de l'échiquier. Cette case est représenté par le type Position ou PositionOccupée.
		*	Une Position est vide, une PositionOccupée est occupée par une certaine pièce.
		*	positions est une variable publique afin d'éviter d'avoir à passer par une variable temporaire si on veut faire $this->getPositions()[1][3] par exemple, cela est impossible avant PHP 5 !
		*	A noter que positions est un tableau "humain" qui commence donc par conséquent à 1 et non à 0.
		*	enPassant est une variable stockant le morceau correspondant à la prise en passant dans le code FEN. Ce morceau est de type Position.
		*	couleurDuJoueurActuel est une variable stockant la couleur du joueur dont c'est le tour de jouer. Il peut s'agir d'un joueur différent du joueur consultant actuellement la page bien sûr.
		*	couleurDuJoueurConsultantLaPartie est identique à celui qu'on retrouve dans la classe JeuDEchec. Il s'agit de la couleur du joueur consultant la partie actuellement.
		*	html est le code HTML représentant l'état du plateau.
		*	codeFen est une variable stockant le code FEN du plateau, une fois un mouvement saisi par exemple.
		*	san est une variable rendant le code SAN, ou du moins la représentation utilisée dans notre modélisation, pour un coup. Ceci sera exploité et stocké en base.
		*/
	
		public $positions;
		private $nombreDeLignes;
		private $nombreDeColonnes;
		private $enPassant;
		private $html;
		private $couleurDuJoueurActuel;
		private $couleurDuJoueurConsultantLaPartie;
		private $codeFen;
		private $san;
		
		function setPositions($desPositions){
			$this->positions=$desPositions;
		}
		
		function getPositions(){
			return $this->positions;
		}
		
		function setNombreDeLignes($unNombreDeLignes){
			$this->nombreDeLignes=$unNombreDeLignes;
		}
		
		function getNombreDeLignes(){
			return $this->nombreDeLignes;
		}
		
		function setNombreDeColonnes($unNombreDeColonnes){
			$this->nombreDeColonnes=$unNombreDeColonnes;
		}
		
		function getNombreDeColonnes(){
			return $this->nombreDeColonnes;
		}

		function setEnPassant($unePosition){
			$this->enPassant=$unePosition;
		}
		
		function getEnPassant(){
			return $this->enPassant;
		}

		function getHtml(){
			return $this->html;
		}
		
		function setHtml($unCodeHtml){
			return $this->html=$unCodeHtml;
		}
		
		function setCouleurDuJoueurConsultantLaPartie($uneCouleur){
			$this->couleurDuJoueurConsultantLaPartie=$uneCouleur;
		}
		
		function getCouleurDuJoueurConsultantLaPartie(){
			return $this->couleurDuJoueurConsultantLaPartie;
		}
		
		function setCouleurDuJoueurActuel($uneCouleur){
			$this->couleurDuJoueurActuel=$uneCouleur;
		}
		
		function getCouleurDuJoueurActuel(){
			return $this->couleurDuJoueurActuel;
		}
		
		function setSan($unSan){
			$this->san=$unSan;
		}
		
		function getSan(){
			return $this->san;
		}
		
		function setCodeFen($unCodeFen){
			$this->codeFen=$unCodeFen;
		}
		
		function getCodeFen(){
			return $this->codeFen;
		}
		
		/*
		*	On pourrait imaginer des échecs à 10 lignes et 10 colonnes en changeant la valeur de retour de cette méthode.
		*	Les adaptations à faire dans le code serait mineures.
		*/
		
		function plateauDEchecA8Lignes8Colonnes(){
			return 8;
		}
		
		/*
		*	Indique si la position passée en paramètre est sur le plateau ou non.
		*	On pourrait passer par des variables temporaires plutôt que d'appeler 2 fois les méthodes getI(), getJ(), et plateauDEchecA8Lignes8Colonnes. Mais ces dernières sont très peu gourmandes.
		*/
		
		function surPlateau($unePosition){
			return (1<=$unePosition->getI() && 1<=$unePosition->getJ() && $this->plateauDEchecA8Lignes8Colonnes()>=$unePosition->getI() && $this->plateauDEchecA8Lignes8Colonnes()>=$unePosition->getJ());
		}
		
		/*
		*	Indique si une position est innocupée ou non.
		*/
		
		function estInoccupee($unePosition){
			return get_class($this->sePlacerALaPosition($unePosition))==='Position';
		}
		
		/*
		*	Retourne la Position (ou PositionOccupee) se situant à la position passée en paramètre.
		*	Ne vérifie pas si la position passée est sur le plateau.
		*/
		
		function sePlacerALaPosition($unePosition){
			return $this->positions[$unePosition->getI()][$unePosition->getJ()];
		}
		
		/*
		*	Même méthode mais prend en argument deux entiers plutôt qu'un type Position.
		*/
		
		function sePlacerALaPositionAvecEntiers($unI,$unJ){
			return $this->positions[$unI][$unJ];
		}
		
		/*
		*	Génère le plateau à partir du code FEN.
		* 	Prend en arguement un code FEN et le tableau des joueurs.
		*	La méthode distribue les pièces appartenant à chacun des joueurs.
		*/
		
		function generePlateauFromFen($codeFenActuelDuPlateau,$desJoueurs){
			//On scinde suivant l'espace.
			$fen=explode(' ',$codeFenActuelDuPlateau);
			
			//On scinde ensuite le premier morceau suivant le '/'. On récupère un tableau de lignes, qu'on a logiquement appelé lignes.
			$lignes=explode("/", $fen[0]);
			
			//Traitement pour chacune des lignes. Fait appel à la méthode statique convertitUnStringEtRetourneUnePiece de la classe Piece.
			foreach($lignes as $k => $v){
				$j=0;
				for($p=0;$p<strlen($v);$p++){
					$chr=substr($v,$p,1);
					if(is_numeric($chr)){
						for($i=0;$i<$chr;$i++) {
							$this->positions[$k+1][$j+1]=new Position($k+1,$j+1);
							$j++;
						}
					}
					else {
						$this->positions[$k+1][$j+1]=new PositionOccupee($k+1,$j+1,Piece::convertitUnStringEtRetourneUnePiece($chr,new Position($k+1,$j+1),$desJoueurs));
						$j++;
					}
				}
			}
			
			//Sette le joueurActuel. Si on a un 'w' dans le FEN, alors le joueur dont c'est le tour est le joueur blanc. Sinon, c'est au tour du Noir.
			$fen[1]=='w' ? $this->setCouleurDuJoueurActuel('Blanc') : $this->setCouleurDuJoueurActuel('Noir');
			
			$this->initialiseLesPossibilitesDeRoqueDesJoueurs($fen[2],$desJoueurs);
			
			//Sette la possibilité éventuelle de prise en passant. Si strpos renvoie un numérique, alors aucune prise en passant possible. Sinon, on stocke la prise en passant sous forme de Position.
			if(!is_numeric(strpos($fen[3],'-'))){
				$j=Conversion::$tableauColonnesEchecsVersColonnesModelisation[$fen[3][0]];
				$i=Conversion::$tableauLignesEchecsVersLignesModelisation[$fen[3][1]];
				$this->setEnPassant(new Position($i,$j));
			}
			else{
				$this->setEnPassant(null);
			}
		}
		
		//Sette les possibilités de roque des joueurs. strpos renvoie un numérique si la chaîne fournie matche. Par défaut, les roques sont à false. On ne les active que si le FEN nous l'indique.
		
		function initialiseLesPossibilitesDeRoqueDesJoueurs($unMorceauDeFenCorrespondantAuxRoques,$desJoueurs){
			if(is_numeric(strpos($unMorceauDeFenCorrespondantAuxRoques,'K'))){
				$desJoueurs[0]->setPetitRoqueOk(true);
			}
			if(is_numeric(strpos($unMorceauDeFenCorrespondantAuxRoques,'Q'))){
				$desJoueurs[0]->setGrandRoqueOk(true);
			}
			if(is_numeric(strpos($unMorceauDeFenCorrespondantAuxRoques,'k'))){
				$desJoueurs[1]->setPetitRoqueOk(true);
			}
			if(is_numeric(strpos($unMorceauDeFenCorrespondantAuxRoques,'q'))){
				$desJoueurs[1]->setGrandRoqueOk(true);
			}
		}
		
		/*
		* Permet de construire un plateau de jeu vide
		* etat est l'état de la partie en cours c'est-à-dire le code FEN.
		*/
	
		function buildBoard(){
		
			/*
			*	fenPourUpdate est simplement la première partie du code FEN du plateau sauf qu'au lieu de slash, on met des '.'.
			*	On stocke ce code Fen dans un attribut data-fen. Cela va nous permettre de comparer le code FEN actuel du plateau avec celui en base, à intervalles de temps régulier.
			*	Si un changement est détecté, la page est aussitôt raffraichie. Du coup, on voit directement quand son adversaire a joué. Il s'agit donc d'un FEN qui nous sert à savoir quand est-ce qu'il vaut rafraîchir la page.
			*/
			
			$fenPourUpdate=explode(' ', str_replace('/','.',$this->getCodeFen()));
			$fenPourUpdate=$fenPourUpdate[0];
			$html = '<table class="echiquier" data-fen="'.$fenPourUpdate.'">';
			for($x = 0; $x< 9; $x++) {
				$color=$x % 2 ? 'black' : 'white';
				$html .= '<tr>';
				for($y = 0; $y < 9;$y++){
							if($x==8){
								$table=Conversion::tableauColonnesModelisationVersColonnesEchecs();
								$html.='<td><strong>'.mb_strtoupper($table[$y]).'</strong></td>';
							}
							else if($y==0){
								$html.='<td><strong>'.(8-$x).'</strong></td>';
							}
							else{
								$html.='<td id="'.($x+1).''.$y.'" class="'.$color.'"><div>';
								$html.=get_class($this->positions[$x+1][$y])==='PositionOccupee' ? $this->positions[$x+1][$y]->getPiece()->image($this) : '';
								$html.='</div></td>';
								$color=($color == 'white') ? 'black' : 'white';
							}
				}
				$html.='</tr>';
			}
			$html.='</table>';

			$this->setHtml($html);
		}
			
		/*
		*	Permet de generer l'etat du jeu sous forme de code FEN.
		*	Retourne le code FEN.
		*/
		
		function toFen(){
			$fen=array();
			$tmp=0;
			foreach($this->positions as $i => $ligne){    
				foreach ($ligne as $position){
					if(get_class($position)==='Position'){
						$tmp++;
					}
					else{
						if($tmp != 0){
							if(!isset($fen[$i])){
								$fen = $fen + array($i => $tmp);
							}
							else{
								$fen[$i] .= $tmp;
							}
							$tmp = 0;
						}
						$unePiece=$position->getPiece();
						$tab=Conversion::pieceVersStringPiece();
						if(!isset($fen[$i])){
							if($unePiece->getCouleur()==='Noir'){
								$fen = $fen + array($i => $tab[get_class($position->getPiece())]);
							}
							else{
								$fen = $fen + array($i => mb_strtoupper($tab[get_class($position->getPiece())]));
							}
						}
						else{
							if($unePiece->getCouleur()==='Noir'){
								$joueurNoir=$unePiece->getJoueur();
								$fen[$i] .= $tab[get_class($position->getPiece())];
							}
							else{
								$joueurBlanc=$unePiece->getJoueur();
								$fen[$i] .= mb_strtoupper($tab[get_class($position->getPiece())]);
							}
						}   
					}
				}
				if($tmp > 0){
					if(!isset($fen[$i])){
						$fen = $fen + array($i => $tmp);
					}
					else{
						$fen[$i] .= $tmp;
					}
					$tmp = 0;
				}
				$tmp = 0;
			}
			$fen2=implode('/',$fen);
			$this->getCouleurDuJoueurActuel()==='Noir' ? $fen2 .=' b' : $fen2 .=' w' ;
			$roque=' ';
			$joueurBlanc->petitRoqueOk() ? $roque .='K' : 1 ;
			$joueurBlanc->grandRoqueOk() ? $roque .='Q' : 1 ;
			$joueurNoir->petitRoqueOk() ? $roque .='k' : 1 ;
			$joueurNoir->grandRoqueOk() ? $roque .='q' : 1 ;
			if($roque==' '){
				$roque=' -';
			}
			$fen2.=$roque;
			$pep=' ';
			if(!is_null($this->getEnPassant())){
				$tabConvCol=Conversion::tableauColonnesModelisationVersColonnesEchecs();
				$tabConvLig=Conversion::tableauLignesModelisationVersLignesEchecs();
				$pep.=$tabConvCol[$this->getEnPassant()->getJ()].''.$tabConvLig[$this->getEnPassant()->getI()];
			}
			if($pep==' '){
				$pep=' -';
			}
			$fen2.=$pep;
			return $fen2;
		}

		function __construct($codeFenActuelDuPlateau,$desJoueurs){
			$this->setCodeFen($codeFenActuelDuPlateau);
			$this->setNombreDeLignes($this->plateauDEchecA8Lignes8Colonnes());
			$this->setNombreDeColonnes($this->plateauDEchecA8Lignes8Colonnes());
			
			//Code FEN initial
			if($codeFenActuelDuPlateau==''){
				$codeFenActuelDuPlateau='rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -';
			}
			
			$this->generePlateauFromFen($codeFenActuelDuPlateau,$desJoueurs);
		}
		
	}
	
?>