<?php 

	/*require('Piece.class.php');
	require('Cheval.class.php');
	require('Pion.class.php');
	require('Plateau.class.php');
	require('Reine.class.php');
	require('Fou.class.php');
	require('Tour.class.php');
	require('Position.class.php');
	require('PositionOccupee.class.php');*/

	class Roi extends Piece{
	
		/*Renvoie un tableau de positions pouvant être théoriquement occupées par un roi*/
		
		function tableauCoupsTheoriquementPossibles($unJeuDEchec){
			$this->tabCoupsTheoriquementPossibles=array();
			for ($i=-1;$i<=1;$i++){
				for ($j=-1;$j<=1;$j++){
					if($i!=0 || $j!=0){
						$positionEventuelle=new Position($i+$this->getPosition()->getI(),$j+$this->getPosition()->getJ());
						if($unJeuDEchec->getPlateau()->surPlateau($positionEventuelle) && $this->nAtteritPasSurPieceAllieeSiOccupeLaPosition($positionEventuelle)){
							$this->tabCoupsTheoriquementPossibles[]=$positionEventuelle;
						}
					}
				}
			}
		}
		
		function tableauCoupsEffectivementPossibles($unJeuDEchec){
			parent::tableauCoupsEffectivementPossibles($unJeuDEchec);
			if(!is_null($this->coupPetitRoque($unJeuDEchec))){
				$this->tabCoupsEffectivementPossibles[]=$this->coupPetitRoque($unJeuDEchec);
			}
			if(!is_null($this->coupGrandRoque($unJeuDEchec))){
				$this->tabCoupsEffectivementPossibles[]=$this->coupGrandRoque($unJeuDEchec);
			}
		}
		
		function coupPetitRoque($unJeuDEchec){
			if($this->getJoueur()->petitRoqueOk()){
				if(!$this->getJoueur()->estEnEchec($unJeuDEchec) && get_class($unJeuDEchec->getPlateau()->sePlacerALaPosition(new Position($this->getPosition()->getI(),$this->getPosition()->getJ()+1)))==='Position' && get_class($unJeuDEchec->getPlateau()->sePlacerALaPosition(new Position($this->getPosition()->getI(),$this->getPosition()->getJ()+2)))==='Position' && in_array(new Position($this->getPosition()->getI(),$this->getPosition()->getJ()+1),$this->tabCoupsEffectivementPossibles) && !in_array(new Position($this->getPosition()->getI(),$this->getPosition()->getJ()+2),$unJeuDEchec->getJoueurSuivant($this->getJoueur())->getCoupsTheoriquementPossibles())){
					return new Position($this->getPosition()->getI(),$this->getPosition()->getJ()+2);
				}
			}
		}
		
		function coupGrandRoque($unJeuDEchec){
			if($this->getJoueur()->grandRoqueOk()){
				if(!$this->getJoueur()->estEnEchec($unJeuDEchec) && get_class($unJeuDEchec->getPlateau()->sePlacerALaPosition(new Position($this->getPosition()->getI(),$this->getPosition()->getJ()-1)))==='Position' && get_class($unJeuDEchec->getPlateau()->sePlacerALaPosition(new Position($this->getPosition()->getI(),$this->getPosition()->getJ()-3)))==='Position' && get_class($unJeuDEchec->getPlateau()->sePlacerALaPosition(new Position($this->getPosition()->getI(),$this->getPosition()->getJ()-2)))==='Position' && in_array(new Position($this->getPosition()->getI(),$this->getPosition()->getJ()-1),$this->tabCoupsEffectivementPossibles) && !in_array(new Position($this->getPosition()->getI(),$this->getPosition()->getJ()-2),$unJeuDEchec->getJoueurSuivant($this->getJoueur())->getCoupsTheoriquementPossibles())){
					return new Position($this->getPosition()->getI(),$this->getPosition()->getJ()-2);
				}
			}
		}
		
		function deplacerPourTester($unePosition,$unPlateau){
			parent::deplacerPourTester($unePosition,$unPlateau);
			$this->getJoueur()->setRoi($this);	
		}
		
		function deplacer($unePosition,$unPlateau){
			$positionInitiale=$this->getPosition();
			//Correspond à des situations de roque
			if(abs($unePosition->getJ()-$positionInitiale->getJ())==2){
				$tableauCorresp_1=Conversion::tableauColonnesModelisationVersColonnesEchecs();
				$tableauCorresp_2=Conversion::tableauLignesModelisationVersLignesEchecs();
				
				if(($unePosition->getJ()-$positionInitiale->getJ())==2){
					$this->setPosition($unePosition);
					$tourDroite=$unPlateau->sePlacerALaPosition(new Position($positionInitiale->getI(),$positionInitiale->getJ()+3))->getPiece();
					$cleTour=array_search($tourDroite,$this->getJoueur()->getPieces());
					$tourDroite->setPosition(new Position($positionInitiale->getI(),$positionInitiale->getJ()+1));
					
					//Transforme les positions initiales du Roi et de la Tour en Position (donc inoccupée)
					$unPlateau->positions[$positionInitiale->getI()][$positionInitiale->getJ()]=new Position($positionInitiale->getI(),$positionInitiale->getJ());
					$unPlateau->positions[$positionInitiale->getI()][$positionInitiale->getJ()+3]=new Position($positionInitiale->getI(),$positionInitiale->getJ()+3);
					
					//Transforme les positions d'arrivée du Roi et de la Tour en PositionOccupee
					$unPlateau->positions[$unePosition->getI()][$unePosition->getJ()]=new PositionOccupee($unePosition->getI(),$unePosition->getJ(),$this);
					$unPlateau->positions[$positionInitiale->getI()][$positionInitiale->getJ()+1]=new PositionOccupee($positionInitiale->getI(),$positionInitiale->getJ()+1,$tourDroite);
					$this->getJoueur()->pieces[$cleTour]=$tourDroite;
					
					//Actualise le San pour cette situation spécifique
					$unPlateau->setSan($tableauCorresp_1[$positionInitiale->getJ()].''.$tableauCorresp_2[$positionInitiale->getI()].' '.$tableauCorresp_1[$unePosition->getJ()].''.$tableauCorresp_2[$unePosition->getI()].' O-O');
				}
				else{
					$this->setPosition($unePosition);
					$tourGauche=$unPlateau->sePlacerALaPosition(new Position($positionInitiale->getI(),$positionInitiale->getJ()-4))->getPiece();
					$cleTour=array_search($tourGauche,$this->getJoueur()->getPieces());
					$tourGauche->setPosition(new Position($positionInitiale->getI(),$positionInitiale->getJ()-1));
					
					//Transforme les positions initiales du Roi et de la Tour en Position (donc inoccupée)
					$unPlateau->positions[$positionInitiale->getI()][$positionInitiale->getJ()]=new Position($positionInitiale->getI(),$positionInitiale->getJ());
					$unPlateau->positions[$positionInitiale->getI()][$positionInitiale->getJ()-4]=new Position($positionInitiale->getI(),$positionInitiale->getJ()-4);
					
					//Transforme les positions d'arrivée du Roi et de la Tour en PositionOccupee
					$unPlateau->positions[$unePosition->getI()][$unePosition->getJ()]=new PositionOccupee($unePosition->getI(),$unePosition->getJ(),$this);
					$unPlateau->positions[$positionInitiale->getI()][$positionInitiale->getJ()-1]=new PositionOccupee($positionInitiale->getI(),$positionInitiale->getJ()-1,$tourGauche);	
					$this->getJoueur()->pieces[$cleTour]=$tourGauche;
					$unPlateau->setSan($tableauCorresp_1[$positionInitiale->getJ()].''.$tableauCorresp_2[$positionInitiale->getI()].' '.$tableauCorresp_1[$unePosition->getJ()].''.$tableauCorresp_2[$unePosition->getI()].' O-O-O');
				}
			}
			else{
				parent::deplacer($unePosition,$unPlateau);
			}
			$this->getJoueur()->setRoi($this);
			$this->getJoueur()->setPetitRoqueOk(false);
			$this->getJoueur()->setGrandRoqueOk(false);
		}
		
		function __construct($unJoueur,$unePosition){
			parent::__construct($unJoueur,$unePosition);
			$this->getJoueur()->setRoi($this);
		}
		
	}
	
?>