<div class="page-header">
  <h1>Ajouter une partie <small>A partir d'un code SAN</small></h1>
</div>
<form method="POST" action="<?php echo $this->generateUrl('chess', 'add');?>">
  <fieldset>
    <legend>Formulaire d'ajout</legend>
    <label>Joueur 1</label>
    <select name="player1">
    	<?php 
    	foreach ($utilisateurs as $k => $v) {
    		echo '<option value="'.$v->id_utilisateur.'">'.$v->login.'</option>';
    	}
    	?>
	</select>
	<label>Joueur 2</label>
    <select name="player2">
    	<?php 
    	foreach ($utilisateurs as $k => $v) {
    		echo '<option value="'.$v->id_utilisateur.'">'.$v->login.'</option>';
    	}
    	?>
	</select>
    <span class="help-block"><i class="icon-exclamation-sign"></i> Seul des joueurs existants peuvent être ajoutés à une partie</span>
    <label>Code SAN</label>
    <input type="text" name="codeSan" class="span5" placeholder="Code SAN…">
    <span class="help-block"><i class="icon-ok-circle"></i> La vérification du code SAN sera faite à l'éxécution</span>
    <button type="submit" class="btn">Envoyer</button>
  </fieldset>
</form>