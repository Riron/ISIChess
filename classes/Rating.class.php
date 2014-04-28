<?php

	class Rating{
		
		/*
		*	Resultat est un nombre pouvant valoir 0, 0.5, ou 1. resultat doit valoir 0.5 si la partie est nulle, 1 si la partie est gagnée par le joueur 1 et 0 si la partie est gagnée par le joueur 2.
		*	donneNouveauRating renvoie un tableau de deux entiers. Le premier correspond au nouveau rating du joueur 1, le deuxième correspond au nouveau rating du second joueur.
		*/
		
		public static function donneNouveauRating($ratingInitialJoueur1,$ratingInitialJoueur2,$resultat){
			$estimationJoueur1=Rating::donneEstimationDuJoueur1FaceAuJoueur2($ratingInitialJoueur1,$ratingInitialJoueur2);
			$estimationJoueur2=Rating::donneEstimationDuJoueur1FaceAuJoueur2($ratingInitialJoueur2,$ratingInitialJoueur1);
			$nouveauRatingJoueur1=(int) ($ratingInitialJoueur1+16*($resultat-$estimationJoueur1));
			$nouveauRatingJoueur2=(int) ($ratingInitialJoueur2+16*((1-$resultat)-$estimationJoueur2));
			$tableauRating=array();
			$tableauRating[]=$nouveauRatingJoueur1;
			$tableauRating[]=$nouveauRatingJoueur2;
			return $tableauRating;
		}
		
		public static function donneEstimationDuJoueur1FaceAuJoueur2($ratingInitialJoueur1,$ratingInitialJoueur2){
			return (1/(1+pow(10,(($ratingInitialJoueur2-$ratingInitialJoueur1)/400))));
		}
		
	}
	
?>