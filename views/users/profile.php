<div class="navbar navbar-googlenav">
  <div class="navbar-inner">
    <ul class="nav navbar-googlenav">
	  <li class="active">
	    <a href="#">Profil</a>
	  </li>
	  <li><a href="<?php echo $this->generateUrl('users', 'games'); ?>">Mes parties</a></li>
	  <li><a href="<?php echo $this->generateUrl('users', 'listUsers'); ?>">Nouvelle partie</a></li>
	  <li><a href="<?php echo $this->generateUrl('users', 'invite'); ?>">Invitations</a></li>
	  <?php
	  if($this->session->isAdmin()){
	  ?>
	  <li><a href="<?php echo $this->generateUrl('users', 'admin'); ?>">Administration</a></li>
	  <?php
	  }
	  ?>
	</ul>
  </div>
</div>
<div class="page-header">
	<h1>Profil</h1>
</div>

<p><a href="<?php echo $this->generateUrl('users', 'edit', $user->id_utilisateur); ?>" class="btn btn-primary btn-small"><i class="icon-pencil icon-white"></i> Editer mon profil</a>  <a href="<?php echo $this->generateUrl('users', 'logout'); ?>" class="btn btn-danger btn-small"><i class="icon-off icon-white"></i> Se déconnecter</a></p>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Informations personnelles</th>
			<th>Statistiques</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>Pseudo: <?php echo $user->login;?></td>
			<td>Nombre de victoires: <?php echo $user->nb_victoire;?></td>
		</tr>
		<tr>
			<td>Nom: <?php echo $user->nom;?></td>
			<td>Nombre de défaites: <?php echo $user->nb_defaite;?></td>
		</tr>
		<tr>
			<td>Prénom: <?php echo $user->prenom;?></td>
			<td>Nombre de pats: <?php echo $user->nb_abandon;?></td>
		</tr>
		<tr>
			<td>Email: <?php echo $user->email;?></td>
			<td>Nombre d'abandons: <?php echo $user->nb_pat;?></td>
		</tr>
		<tr>
			<td>Niveau: <?php echo $user->indice_niveau;?></td>
			<td></td>
		</tr>

	</tbody>

</table>