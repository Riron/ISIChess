<?php

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
	
	class Plateau{
	
		private $desPositions;
		private $nombreLignes;
		private $nombreColonnes;
		
		function setDesPositions($desPos){
			$this->desPositions=$desPos;
		}
		
		function getDesPositions(){
			return $this->desPositions;
		}
		
		function setNombreLignes($unNombreDeLignes){
			$this->nombreLignes=$unNombreDeLignes;
		}
		
		function getNombreLignes(){
			return $this->nombreLignes;
		}
		
		function setNombreColonnes($unNombreDeColonnes){
			$this->nombreColonnes=$unNombreDeColonnes;
		}
		
		function getNombreColonnes(){
			return $this->nombreColonnes;
		}
		
		function jeuDEchecA8Lignes8Colonnes(){
			return 8;
		}
		
		function surPlateau($unePosition){
			return (1<=$unePosition->getI() && 1<=$unePosition->getJ() && $this->jeuDEchecA8Lignes8Colonnes()>=$unePosition->getI() && $this->jeuDEchecA8Lignes8Colonnes()>=$unePosition->getJ());
		}
		
		//Trois méthodes qui suivent à mettre avec les pièces !
		//ATTENTION INTERDICTION DE METTRE EN ECHEC SON ROI EN BOUGEANT UNE PIECE !!!!!!
		
		function pasSurPieceAlliee($unePiece,$unePosition){
			$desPos=$this->getDesPositions();
			$positionSurJeuDEchec=$desPos[$unePosition->getI()][$unePosition->getJ()];
			if(get_class($positionSurJeuDEchec)==='PositionOccupee' && $positionSurJeuDEchec->getUnePiece()->getCouleur() === $unePiece->getCouleur()){
				return false;
			}
			return true;
		}
		
		function surPieceAlliee($unePiece,$unePosition){
			return !$this->pasSurPieceAlliee($unePiece,$unePosition);
		}
		
		function surPieceEnnemie($unePiece,$unePosition){
			$desPos=$this->getDesPositions();
			$positionSurJeuDEchec=$desPos[$unePosition->getI()][$unePosition->getJ()];
			if(get_class($positionSurJeuDEchec)==='PositionOccupee' && $positionSurJeuDEchec->getUnePiece()->getCouleur() != $unePiece->getCouleur()){
				return true;
			}
			return false;
		}
		
		function estInoccupee($unePosition){
			return get_class($this->sePlacerALaPosition($unePosition))==='Position';
		}
		
		function sePlacerALaPosition($unePosition){
			$mesPos=$this->getDesPositions();
			return $mesPos[$unePosition->getI()][$unePosition->getJ()];
		}
		
		function roiNeSeMetPasEnEchec($unePiece,$unePosition){
			$plateau2=new Plateau($this);
			$plateau2->sePlacerALaPosition($unePiece->getPosition())=new Position($unePiece->getPosition()->getI(),$unePiece->getPosition()->getJ());
			$unePiece->deplacer($unePosition);
			$plateau2->sePlacerALaPosition($unePosition)=new PositionOccupee($unePosition->getI(),$unePosition->getJ(),$unePiece);
			return $unePiece->getSonRoi()->NEstPasEnEchec();
		}
		
		function parseEtat($etat){
			$lignes=explode("/", $etat);
			foreach($lignes as $k => $v){
				$j=0;
				for($p=0; $p<strlen($v); $p++){
					$chr=substr($v, $p, 1);
					if(is_numeric($chr)){
						for($i=0; $i<$chr; $i++) {
							$this->desPositions[$k+1][$j+1] = new Position($k+1,$j+1);
							$j++;
						}
					}
					else {
						$this->desPositions[$k+1][$j+1]=new PositionOccupee($k+1,$j+1,Piece::convertitUnStringEtRetourneUnePiece($chr,new Position($k+1,$j+1)));
						$j++;
					}
				}
			}
		}

		function __construct($etatOuUnPlateau){
			if(is_null($etatOuUnPlateau) || is_string($etatOuUnPlateau)){
				$this->setNombreLignes($this->jeuDEchecA8Lignes8Colonnes());
				$this->setNombreColonnes($this->jeuDEchecA8Lignes8Colonnes());
				$this->desPositions=array();
				if($etatOuUnPlateau==null){
					$etatOuUnPlateau='1n3bnr/ppppppp1/kbrq3p/8/8/8/PPPPPPPP/RNBQKBNR';
				}
				$this->parseEtat($etatOuUnPlateau);
			}
			else{
				$this->setNombreLignes($etatOuUnPlateau->getNombreLignes());
				$this->setNombreColonnes($etatOuUnPlateau->getNombreColonnes());
				$this->setDesPositions($etatOuUnPlateau->getDesPositions());
			}
		}
		
	}
	
	$monPlateauFavoris=new Plateau(null);
	/*$mesPos=$monPlateauFavoris->getDesPositions();
	$coupPossible1=$mesPos[7][2]->getUnePiece()->tableauDeplacementsAutorises($monPlateauFavoris);
	echo $coupPossible1[1]->getI();
	echo $coupPossible1[1]->getJ();*/
	$monPlateauFavoris->roiNeSeMetPasEnEchec();
?>