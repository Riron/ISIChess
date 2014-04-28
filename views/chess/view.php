<script>
/*
La fonction permet de créer un échiquier. Cette échiquier est différent de celui utilisé pour faire les parties car les 'id' sont différents.
Ici chaque div du jeu possède un 'id' qui correspond à la vraie case de l'échiquier. De plus la fonction met toutes les images des pièces à la bonne place
*/
function createGrid(lignes, espace){
	$("#grilleInsert").empty();
	var screenWidth = $("#grilleInsert").width();
	var screenHeight = $("#grilleInsert").height();
	taille =  Math.floor(((screenWidth * 90 /100) - (lignes * espace * 2))  / lignes);
	var mini = screenWidth * 90 / 100;

	var parent = $('<div />', {
			        class: 'grid1',
			        width: mini, 
			        height: mini,
			        display:'inline-block',
			    }).addClass('grid1').appendTo('#grilleInsert');

	for(var i = lignes; i >0; i--){
		for(var j = 0; j < lignes; j++){
			
			var asciiNum=96+j;
			if(j==0){
				asciiNum=asciiNum+26;
			}

			$('<div />', {
                width: taille - 1, 
                height: taille - 1,
                id:String.fromCharCode(asciiNum)+(i-1),
        	}).appendTo(parent);
		}
	}
	$('.grid1').addClass("pull-left");
	$('.grid1 div').css({margin: espace});
	$("#z0").css("visibility", "hidden")

	for(var i = 0; i < lignes; i++){
		for(var j = 0; j < lignes; j++){	
			if(j==0 && i!=0){
				$("#z"+i).text(i).css({"text-align":"center","border-width":"0px 1px 0px 0px"})
			}	
			else if(i==0 && j!=0){
				$("#"+String.fromCharCode(96+j)+"0").text(String.fromCharCode(64+j)).css({"text-align":"center","border":"0"})
			}				
			else if((i+j)%2 ==1 && i!=0 && j!=0) {
				$("#"+String.fromCharCode(96+j)+i).css("background-color","white")
			}
			else if((i+j)%2 ==0 && i!=0 && j!=0){
				$("#"+String.fromCharCode(96+j)+i).css("background-color", "#e3e3e3")
			}

			if(i==7 && j!=0){

				$('<img />', {
					src:"webroot/img/pieces/black_pion.png",
                	width: taille - 1, 
                	height: taille - 1,
                	alt:"pion noir",
        		}).appendTo("#"+String.fromCharCode(96+j)+i);

			}
			else if(i==2 && j!=0){
				$('<img />', {
					src:"webroot/img/pieces//white_pion.png",
                	width: taille - 1, 
                	height: taille - 1,
                	alt:"pion blanc",
        		}).appendTo("#"+String.fromCharCode(96+j)+i);
			}
			else if(i==8 && (j==1 || j==8)){
				$('<img />', {
					src:"webroot/img/pieces//black_tour.png",
                	width: taille - 1, 
                	height: taille - 1,
                	alt:"tour noir",
        		}).appendTo("#"+String.fromCharCode(96+j)+i);
			}
			else if(i==8 && (j==2 || j==7)){
				$('<img />', {
					src:"webroot/img/pieces/black_cheval.png",
                	width: taille - 1, 
                	height: taille - 1,
                	alt:"cheval noir",
        		}).appendTo("#"+String.fromCharCode(96+j)+i);
			}
			else if(i==8 && (j==3 || j==6)){
				$('<img />', {
					src:"webroot/img/pieces/black_fou.png",
                	width: taille - 1, 
                	height: taille - 1,
                	alt:"fou noir",
        		}).appendTo("#"+String.fromCharCode(96+j)+i);
			}
			else if(i==1 && (j==1 || j==8)){
				$('<img />', {
					src:"webroot/img/pieces/white_tour.png",
                	width: taille - 1, 
                	height: taille - 1,
                	alt:"tour blanc",
        		}).appendTo("#"+String.fromCharCode(96+j)+i);
			}
			else if(i==1 && (j==2 || j==7)){
				$('<img />', {
					src:"webroot/img/pieces/white_cheval.png",
                	width: taille - 1, 
                	height: taille - 1,
                	alt:"cheval blanc",
        		}).appendTo("#"+String.fromCharCode(96+j)+i);
			}
			else if(i==1 && (j==3 || j==6)){
				$('<img />', {
					src:"webroot/img/pieces/white_fou.png",
                	width: taille - 1, 
                	height: taille - 1,
                	alt:"fou blanc",
        		}).appendTo("#"+String.fromCharCode(96+j)+i);

			}
		}
	}
	$('<img />', {
		src:"webroot/img/pieces/black_reine.png",
        width: taille - 1, 
        height: taille - 1,
        alt:"reine noir",
    }).appendTo("#d8");

	$('<img />', {
		src:"webroot/img/pieces/black_roi.png",
        width: taille - 1, 
        height: taille - 1,
        alt:"roi noir",
    }).appendTo("#e8");

	$('<img />', {
		src:"webroot/img/pieces/white_reine.png",
        width: taille - 1, 
        height: taille - 1,
        alt:"reine blanche",
    }).appendTo("#d1");

	$('<img />', {
		src:"webroot/img/pieces/white_roi.png",
        width: taille - 1, 
        height: taille - 1,
        alt:"roi blanc",
    }).appendTo("#e1");

	$('.grid1 div img').css("position","relative");
}

/*
La fonction permet de fabriquer le tableau des coups qui est en paramêtre de la fonction 'arrayDivided'
*/

function displayArrayWithAllMovementV2 (arrayDivided, joueurBlanc, joueurNoir, tableauGagnant){

	var divTableau = $('<table />').appendTo('.tableauCodeSan');
	var divLigneTableau = $('<tr />').appendTo(divTableau);
	var increment=1;
	$("table").addClass("table table-bordered table-striped");

	$('<th > N° coup </th>').appendTo(divLigneTableau);
	$('<th> Joueur blanc: '+joueurBlanc+' </th>').appendTo(divLigneTableau);
	$('<th> Joueur noir: '+joueurNoir+' </th>').appendTo(divLigneTableau);

	if(arrayDivided[arrayDivided.length-1]["cod_san_noir"]==null)
		arrayDivided[arrayDivided.length-1]["cod_san_noir"]="";
	
	// var chiffre=0;
	for(key in arrayDivided) {
		var divLigneTableau = $('<tr class=ligneTab/>').appendTo(divTableau);
		$("<td>"+arrayDivided[key]["num_coup"]+"</td>").appendTo(divLigneTableau);
		$("<td id=id_tab_"+(2*key)+">"+arrayDivided[key]["cod_san_blanc"]+"</td>").appendTo(divLigneTableau);
		// chiffre++;
		$("<td id=id_tab_"+(2*key+1)+">"+arrayDivided[key]["cod_san_noir"]+"</td>").appendTo(divLigneTableau);
		// chiffre++;
		}

	if(tableauGagnant["bl_joueur_blanc"] && tableauGagnant["bl_joueur_noir"])
		var gagnant;
		var divLigneTableau = $('<tr />').appendTo(divTableau);
		$("<td> Fin </td>").appendTo(divLigneTableau);
		if(tableauGagnant["bl_joueur_blanc"]=="0" && tableauGagnant["bl_joueur_noir"]=="1")
			gagnant="Joueur Noir gagnant";
		else if(tableauGagnant["bl_joueur_blanc"]=="1" && tableauGagnant["bl_joueur_noir"]=="0")
			gagnant="Joueur Blanc gagnant";
		else if(tableauGagnant["bl_joueur_blanc"]=="2" && tableauGagnant["bl_joueur_noir"]=="2")
			gagnant="Partie nulle";

		$("<td colspan='2' id='divFin'>"+gagnant +"</td>").appendTo(divLigneTableau);
		$("#divFin").css("text-align","center");

}

/*
La fonction permet de trouver le coup qui correspond au numero du bon coup et du bon joueur.
Elle retourne un tableau ou, par exemple, la chaîne 'a2 b2 x' va être couper en trois morceaux: 'a2', 'b2' et 'x'
*/
function lectureCoup (tableau, indexNumeroCoup, indexJoueur){

	var joueur="";

	// indexJoueur permet de savoir le coup de quel joueur est voulu
	if(indexJoueur==0){
		joueur="cod_san_blanc";
	}
	else if(indexJoueur ==1){
		joueur="cod_san_noir";
	}

	//On retourne un tableau avec dans chaque case une case
	return (tableau[indexNumeroCoup][joueur]).split(" ");
}

/*
La fonction va calculer de combien de case doit se déplacer le pion en haut et à gauche et retourne un tableau contenant ces deux information
*/
function calculNombrePasDeDeplacement(caseD, caseA){

	var tabDepart=caseD.split("");
	var tabArrive=caseA.split("");
	
	var coupDepartLettre=tabDepart[0].charCodeAt(0);
	var coupArriveLettre=tabArrive[0].charCodeAt(0);

	var coupDepartNum=tabDepart[1].charCodeAt(0);
	var coupArriveNum=tabArrive[1].charCodeAt(0);
	
	return([coupDepartLettre-coupArriveLettre, coupArriveNum-coupDepartNum]);

}
/*
La fonction permet de gérer tous les types de coup qu'il peut y avoir en appelant la fonction qui gère ce cas cad le roque (petit ou grand, le petit ou le grand),
 la promotion, la prise, la prise en passant.
*/
function dispatcherCoup(caseDepart,caseDArrivee, caractereSpecial, estJoueursBlanc, enAvant, tableauDesPiecesPrises, tableauConversionPieceAdresseUrl){
	//si dans la troisième case du tableau d'un coup d'un joueur, il n'y a rien. Alors on joue un coup normal
	if(caractereSpecial=="rien")
		coupNormal(caseDepart,caseDArrivee);

	//Sinon c'est un coup spécial
	else{
		//Petit roque
		if(caractereSpecial=="O-O"){
			if(estJoueursBlanc)
				coupPetitRoqueBlanc(enAvant);
			else
				coupPetitRoqueNoir(enAvant);
		}
		//Grand roque
		else if(caractereSpecial=="O-O-O"){
			if(estJoueursBlanc)
				coupGrandRoqueBlanc(enAvant);
			else
				coupGrandRoqueNoir(enAvant);
		}
		//Il y a prise lors du déplacement
		else if(caractereSpecial=="x")
			coupAvecPrise(enAvant, caseDepart, caseDArrivee,tableauDesPiecesPrises);
		//Coup en passant
		else if(caractereSpecial=="e.p.")
			coupPriseEnPassant(enAvant, caseDepart, caseDArrivee);	
		//Promotion
		else if(caractereSpecial[0]=="=" || caractereSpecial[1]=="=")
			//Si le premier caractère spécial est un 'x' alors avec la promotion, il y a une prise
			if(caractereSpecial[0]=="x")
				coupAvecPromotion(caseDepart, caseDArrivee, tableauConversionPieceAdresseUrl[caractereSpecial[2]], enAvant, true, tableauDesPiecesPrises);
			//Sinon c'est une simple promotion
			else
				coupAvecPromotion(caseDepart, caseDArrivee, tableauConversionPieceAdresseUrl[caractereSpecial[1]], enAvant, false, tableauDesPiecesPrises);
	}
}

/*
Fonction qui gère un 'coup simple' ie déplacer un pion d'une case à l'autre
*/
function coupNormal(caseDepart, CaseDArrivee){
	var tabPasDeMouvementPiece=calculNombrePasDeDeplacement(caseDepart, CaseDArrivee);
	var temp=$('#'+caseDepart+' img').clone(true);
	$('#'+caseDepart+' img').animate({
		right: tabPasDeMouvementPiece[0]*taille+'px',
		bottom: tabPasDeMouvementPiece[1]*taille+'px'
		}, 500, function(){
			temp.clone().appendTo('#'+CaseDArrivee);
			$('#'+caseDepart+' img').remove()
		});	
}

/*
Fonction qui gère un coup avec une prise au bout
*/
function coupAvecPrise(estCoupSuivant, caseDepart, CaseDArrivee,tableauDesPiecesPrises){
	if(estCoupSuivant){
		var image=$('#'+CaseDArrivee+' img').attr("src");
		$('#'+CaseDArrivee).css("background-image",'url('+image+')');
		$('#'+CaseDArrivee+' img').remove();
		coupNormal(caseDepart,CaseDArrivee);
		window.setTimeout(function(){$('#'+CaseDArrivee).css("background-image","none")},500);
		tableauDesPiecesPrises.push(image);
	}
	else{
		coupNormal(caseDepart,CaseDArrivee);
		window.setTimeout(
			function(){
				$('<img />', {
					src:tableauDesPiecesPrises[tableauDesPiecesPrises.length-1],
        			width: taille - 1, 
        			height: taille - 1,
        			alt:"piece",
   		 		}).appendTo("#"+caseDepart),
				$('#'+caseDepart+' img').css("position", "relative");
				tableauDesPiecesPrises.pop();
			},600);
	}
}

/*
Fonction qui gère spécifiquement le 'Petit roque' du joueur noir
*/
function coupPetitRoqueNoir(enAvant){
	if(enAvant){
		coupNormal('e8','g8');
		coupNormal('h8','f8');
	}
	else{
		coupNormal('g8','e8');
		coupNormal('f8','h8');
	}
}

/*
Fonction qui gère spécifiquement le 'Petit roque' du joueur blanc
*/
function coupPetitRoqueBlanc(enAvant){
	if(enAvant){
		coupNormal('e1','g1');
		coupNormal('h1','f1');
	}
	else{
		coupNormal('g1','e1');
		coupNormal('f1','h1');
	}
}

/*
Fonction qui gère spécifiquement le 'Grand roque' du joueur noir
*/
function coupGrandRoqueNoir(enAvant){
	if(enAvant){
		coupNormal('e8','c8');
		coupNormal('a8','d8');
	}
	else{
		coupNormal('c8','e8');
		coupNormal('d8','a8');
	}
}

/*
Fonction qui gère spécifiquement le 'Grand roque' du joueur blanc
*/
function coupGrandRoqueBlanc(enAvant){
	if(enAvant){
		coupNormal('e1','c1');
		coupNormal('a1','d1');
	}
	else{
		coupNormal('c1','e1');
		coupNormal('d1','a1');
	}
}

/*
Fonction qui gère la 'promotion simple' mais aussi la 'promotion avec prise'
*/
function coupAvecPromotion(caseDepart, caseDArrivee, urlPiecePromotion, enAvant, avecPrise, tableauDesPiecesPrises){
	if(enAvant){
		if(avecPrise)
			coupAvecPrise(enAvant, caseDepart, caseDArrivee,tableauDesPiecesPrises);
		else
			coupNormal(caseDepart,caseDArrivee);
		window.setTimeout(function(){$('#'+caseDArrivee+' img').attr("src",urlPiecePromotion)},650);
	}
	else{
		if(caseDepart[1]=="8")
			$('#'+caseDepart+' img').attr("src","webroot/img/pieces/white_pion.png");
		else if(caseDepart[1]=="1")
			$('#'+caseDepart+' img').attr("src","webroot/img/pieces/black_pion.png");
		if(avecPrise)
			coupAvecPrise(enAvant, caseDepart, caseDArrivee,tableauDesPiecesPrises);
		else
			coupNormal(caseDepart,caseDArrivee);
	}
}

/*
Fonction qui gère la 'prise en passant'
*/
function coupPriseEnPassant(enAvant, caseDepart, caseDArrivee){
	if(enAvant){
		coupNormal(caseDepart,caseDArrivee);
		window.setTimeout(function(){$('#'+caseDArrivee[0]+caseDepart[1]+' img').remove()},300);
	}
	else{
		if(caseDepart[1]==6){
			$('<img />', {
					src:"webroot/img/pieces/black_pion.png",
        			width: taille - 1, 
        			height: taille - 1,
        			alt:"pion noir",
   		 		}).appendTo('#'+caseDepart[0]+'5'),
				$('#'+caseDepart[0]+'5 img').css("position", "relative");
		}
		else if(caseDepart[1]==3){
			$('<img />', {
					src:"webroot/img/pieces/white_pion.png",
        			width: taille - 1, 
        			height: taille - 1,
        			alt:"pion blanc",
   		 		}).appendTo('#'+caseDepart[0]+'4'),
				$('#'+caseDepart[0]+'4 img').css("position", "relative");
		}
		coupNormal(caseDepart,caseDArrivee);
	}
}

/*
Fonction principale qui se charge que si tout les élèments sont bien chargés
*/
$(document).ready(function(evt){
	//On crée la grille du jeu d'echec
	createGrid(9, 0);

	//On fait des requettes à la base pour importer les coups de la partie qu'on veut voir, on importe le nom du gagnant de la partie et les noms des joueurs
	var tableauAvecCodeSan = <?php echo $tableauSan;?>;
	var tableauPartie = <?php echo $partie; ?>;
	var userBlanc = '<?php echo $userBlanc; ?>';
	var userNoir = '<?php echo $userNoir; ?>';

	console.log(tableauAvecCodeSan);

	//On crée le tableau des coups des joueurs avec les données importées et on l'affiche sur le côté de l'échiquier
	displayArrayWithAllMovementV2(tableauAvecCodeSan,userBlanc,userNoir,tableauPartie);
	
	/*
	On crée différentes variables pour faire gérer la position dans le parcour du tableau, un tableau de correspondance lettre->pièce
	les index des joueurs et coups et savoir si on est à la fin du tableau
	*/
	var tableauConversionPieceAdresseUrl={"K":"webroot/img/pieces/white_roi.png","Q":"webroot/img/pieces/white_reine.png","R":"webroot/img/pieces/white_tour.png","B":"webroot/img/pieces/white_fou.png","N":"webroot/img/pieces/white_cheval.png","k":"webroot/img/pieces/black_roi.png","q":"webroot/img/pieces/black_reine.png","r":"webroot/img/pieces/black_tour.png","b":"webroot/img/pieces/black_fou.png","n":"webroot/img/pieces/black_cheval.png"};
	var processing=false;
	var lectureOn=false;
	var indexMouvementJoueurEnCours=0;
	var indexMouvementCoupEnCours=0;
	var tableauDesPiecesPrises=[];
	var fin=false;
	var numero=0;

	/*
	Fonction qui gère le clic sur le bouton "suivant" et qui donne le coup suivant dans le tableau
	*/
	$("#Suivant").click(function(event){
		//Si on est pas à la fin du tableau et si on a fini de traiter un clic précédent, alors on traite le coup "suivant"
		if (!fin && !processing) {
			processing=true;

			//On scroll le tableau pour un bon ajustement à la bonne ligne et une lecture plus agréable
			$(".tableauCodeSan").scrollTop(Math.round((numero-3)/2)*$(".ligneTab").height());
			//On fabrique le tableau avec les coups depart et arrivé avec une case spéciale pour les caractères particuliers
			var tableauChaineDepartArriveEtSpecial=lectureCoup(tableauAvecCodeSan,indexMouvementCoupEnCours,indexMouvementJoueurEnCours);

			//Si la case du tableau précédent réservé pour le caractère spécial est vide, on écrit le mot "rien" dedans
			if(!tableauChaineDepartArriveEtSpecial[2]){
				tableauChaineDepartArriveEtSpecial[2]="rien";
			}

			//On surligne la case dans le tableau qui correspond au coup joué
			$("#id_tab_"+numero).css("background-color","yellow");

			if(!(indexMouvementJoueurEnCours==0 && indexMouvementCoupEnCours==0))
				$("#id_tab_"+(numero-1)).css("background-color","");
			numero++;

			//On appelle la fonction dispatcherCoup qui gère l'appel de la bonne fonction
			dispatcherCoup(tableauChaineDepartArriveEtSpecial[0],tableauChaineDepartArriveEtSpecial[1], tableauChaineDepartArriveEtSpecial[2], !indexMouvementJoueurEnCours, true, tableauDesPiecesPrises, tableauConversionPieceAdresseUrl);
			
			//On bouge les indexs pour le coup suivant
			indexMouvementCoupEnCours+=indexMouvementJoueurEnCours;
			indexMouvementJoueurEnCours=(indexMouvementJoueurEnCours+1)%2;

			//On vérifie qu'on a pas joué le dernier coup
			if((indexMouvementJoueurEnCours == 1 && indexMouvementCoupEnCours==tableauAvecCodeSan.length-1 && tableauAvecCodeSan[indexMouvementCoupEnCours]["cod_san_noir"]=="") || (indexMouvementCoupEnCours == tableauAvecCodeSan.length && indexMouvementJoueurEnCours==0))
				fin=true;
			window.setTimeout(function(){processing=false},900);
		}							
	});

	/*
	Fonction qui gère le clic sur le bouton "précédent" et qui donne le coup précédent dans le tableau
	*/
	$("#Précédent").click(function(event){
		//Si on est pas au début du tableau et si on a pas déjà cliqué sur le bouton on rentre dans la boucle
		if ((indexMouvementCoupEnCours!=0 || (indexMouvementCoupEnCours==0 && indexMouvementJoueurEnCours==1)) && !processing) {

			processing=true;

			//Si on recule, c'est qu'on forcement plus à la fin du tableau
			fin=false;

			//On scroll le tableau pour une lecture plus agréable et un ajustement automatique sur la bonne ligne
			$(".tableauCodeSan").scrollTop(Math.round((numero-5)/2)*$(".ligneTab").height());

			//On change nos indexs car on lit le coup précédent à celui qui était prévu
			indexMouvementJoueurEnCours=(indexMouvementJoueurEnCours+1)%2;
			indexMouvementCoupEnCours=indexMouvementCoupEnCours-indexMouvementJoueurEnCours;

			//On change le surlignement des cases
			$("#id_tab_"+(numero-1)).css("background-color","");
			$("#id_tab_"+(numero-2)).css("background-color","yellow");
			numero--;

			//On fabrique le tableau avec case départ, arrivé et coup spécial
			var tableauChaineDepartArriveEtSpecial=lectureCoup(tableauAvecCodeSan,indexMouvementCoupEnCours,indexMouvementJoueurEnCours);

			//S'il n'y a rien comme coup spécial, alors on écrit le mot "rien"
			if(!tableauChaineDepartArriveEtSpecial[2]){
				tableauChaineDepartArriveEtSpecial[2]="rien";
			}

			//On appelle la fonction qui gère la bonne fonction d'animation
			dispatcherCoup(tableauChaineDepartArriveEtSpecial[1],tableauChaineDepartArriveEtSpecial[0], tableauChaineDepartArriveEtSpecial[2], !indexMouvementJoueurEnCours, false, tableauDesPiecesPrises, tableauConversionPieceAdresseUrl);
			window.setTimeout(function(){processing=false},900);
		}							
	});

	//Fonction qui gère la lecture en continue donc qui clic à intervalle régulier sur le bouton suivant
	$("#Lecture").click(function(event){

		if(!lectureOn){
			lectureOn=true;
			function click_pour_lecture(){
					$("#Suivant").trigger("click");
					$("#Lecture").html("<i class=\"icon-pause icon-white\"></i> Pause");
					if(fin){
						lectureOn=false;
						$("#Lecture").html("<i class=\"icon-play icon-white\"></i> Lecture");
					}
					else if(lectureOn && !fin)
						setTimeout(click_pour_lecture, 1000);
					else
						setTimeout(function(){$("#Lecture").html("<i class=\"icon-play icon-white\"></i> Lecture")},500);
			}
			click_pour_lecture();
		}
		else{
			lectureOn=false;			
		}
	});


});

//Fin du script JS
</script>


<div data-role="page" id="LecteurSAN">
	<div class="row">
		<div class="span9">
			<!-- Div de la grille, qui sera remplie par le script JS -->
			<div id="grilleInsert"></div>
		</div>
		<div class="span3">
			<div class="tableauCodeSan"></div>
			
			<div data-role="controlgroup" data-type="horizontal" class="ui-corner-all ui-controlgroup ui-controlgroup-horizontal">
				<div class="ui-controlgroup-controls">
					<a class="btn btn-primary btn-small" href="#" id="Précédent"><i class="icon-backward icon-white"></i> Précédent</a>

					<a class="btn btn-primary btn-small" href="#" id="Lecture"><i class="icon-play icon-white"></i> Lecture</a>

					<a class="btn btn-primary btn-small" href="#" id="Suivant"><i class="icon-forward icon-white"></i> Suivant</a>

				</div>
			</div>
		</div>

	</div>
	
</div>		