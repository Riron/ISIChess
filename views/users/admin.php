<div class="navbar navbar-googlenav">
  <div class="navbar-inner">
    <ul class="nav navbar-googlenav">
    <li>
      <a href="<?php echo $this->generateUrl('users', 'profile', $this->session->readEntry('user')->id_utilisateur); ?>">Profil</a>
    </li>
    <li><a href="<?php echo $this->generateUrl('users', 'games'); ?>">Mes parties</a></li>
    <li><a href="<?php echo $this->generateUrl('users', 'listUsers'); ?>">Nouvelle partie</a></li>
    <li><a href="<?php echo $this->generateUrl('users', 'invite'); ?>">Invitations</a></li>
    <?php
    if($this->session->isAdmin()){
    ?>
    <li class="active"><a href="#">Administration</a></li>
    <?php
    }
    ?>
  </ul>
  </div>
</div>
<a href="<?php echo $this->generateUrl('chess', 'add');?>" class="btn btn-success btn-primary"><i class="icon-retweet icon-white"></i> Ajouter une partie</a>
<div class="page-header">
  <h1>Utilisateurs <small>Gestion des utilisateurs</small></h1>
</div>
<table class="table table-bordered table-striped table-hover">
	<thead>
    <tr>
      <th>#</th>
      <th>Login</th>
      <th>Nom</th>
      <th>Prénom</th>
      <th>Email</th>
      <th>Admin</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    
<?php foreach ($users as $k => $v) {
	echo '<tr>';
  echo '<td>'.$v->id_utilisateur.'</td>';
	echo '<td>'.$v->login.'</td>';
  echo '<td>'.$v->nom.'</td>';
  echo '<td>'.$v->prenom.'</td>';
  echo '<td>'.$v->email.'</td>';
  echo '<td>'.$v->bl_admin.'</td>';
  echo '<td><a href="'.$this->generateUrl('users', 'edit', $v->id_utilisateur).'" title="Editer utilisateur"><i class="icon-edit"></i></a> - <a href="'.$this->generateUrl('users', 'delete', $v->id_utilisateur).'" title="Supprimer utilisateur"><i class="icon-trash"></i></a> - <a href="'.$this->generateUrl('users', 'makeAdmin', array($v->id_utilisateur,1-$v->bl_admin)).'" title="Rendre administrateur"><i class="icon-star"></i></a></td>';
  echo '</tr>';
}
?>
</table>
<div class="page-header">
  <h1>Parties <small>Gestion des parties</small></h1>
</div>
<table class="table table-bordered table-striped table-hover">
  <thead>
    <tr>
      <th>#</th>
      <th>Joueur blanc</th>
      <th>Joueur noir</th>
      <th>Etat</th>
      <th>Date de début</th>
      <th>Date de fin</th>
      <th>Publique</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    
<?php foreach ($parties as $k => $v) {
  echo '<tr>';
  echo '<td>'.$v->id_partie.'</td>';
  echo '<td>'.$v->id_utilisateur_blanc.'</td>';
  echo '<td>'.$v->id_utilisateur_noir.'</td>';
  echo '<td>'.$v->cod_etat.'</td>';
  echo '<td>'.$v->dat_debut.'</td>';
  echo '<td>'.$v->dat_fin.'</td>';
  echo '<td>'.$v->bl_public.'</td>';
  echo '<td><a href="'.$this->generateUrl('chess', 'makePublic', array($v->id_partie, 1-$v->bl_public)).'" title="Rendre publique/privée"><i class="icon-road"></i></a></td>';
  echo '</tr>';
}
?>
</table>