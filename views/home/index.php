<div class="row">
	<div class="span9">
		<div class="hero-unit">
			<h1>Bienvenue sur ISIChess !</h1>
			<small>Site de jeu d'échecs en ligne.</small>
		</div>
	</div>
	<div class="span3">
		<div class="well">
			<h3>Statistiques</h3>
			<ul>
				<li class="nbP">Nb parties: </li>
				<li class="nbPP">Nb parties publiques: </li>
				<li class="nbC">Nb coups: </li>
				<li class="nbCMP">Nb de coups moyen par partie: </li>
				<li class="nbU">Nb d'utilisateurs: </li>
			</ul>
			<script>
			$(document).ready(function (){
				var url = '<?php echo $this->generateUrl('stats', 'statistiques');?>';
				$.get(url, function(data) {
				  data = jQuery.parseJSON(data);
				  $(".nbP").append(data.parties);
				  $(".nbPP").append(data.partiesPubliques);
				  $(".nbC").append(data.coups);
				  $(".nbCMP").append((data.coups/data.parties).toFixed(2));
				  $(".nbU").append(data.user);
				});
			});
			</script>
		</div>
	</div>
</div>
<div class="row">
	<div class="span4">
		<div class="img-home">
			<img src="<?php echo WEBROOT; ?>webroot/img/modifier-texte-icone.png" alt="Inscrivez-vous !">
		</div>
		<h2>Inscrivez vous !</h2>
		<p>Inscrivez vous pour profiter de l'ensemble de l'expérience ISIChess. L'inscription est rapide et vous permettra de profiter pleinement de l'ensemble des fonctionnalités disponibles.</p>
	</div>
	<div class="span4">
		<div class="img-home">
			<img src="<?php echo WEBROOT; ?>webroot/img/de-societe-jeux-forfait-icone.png" alt="Des échecs avant tout">
		</div>
		<h2>Des Echecs avant tout !</h2>
		<p>ISIChess, c'est avant tout des échecs sous toutes les formes. Jouez, visionnez des parties, discutez avec des passionnés... Et bien plus encore !</p>
	</div>
	<div class="span4">
		<div class="img-home">
			<img src="<?php echo WEBROOT; ?>webroot/img/bill-homme-personne-utilisateur-icone.png" alt="Simple visiteur?">
		</div>
		<h2>Simple visiteur ?</h2>
		<p>Simple visiteur? Casual chesser? Pas de problème ! Nous avons pensé à vous. Rendez vous dans la bibliothèque pour visionnez l'ensemble des parties publiques, dont certainnes jouées par les plus grands champions d'échecs !</p>
	</div>
</div>