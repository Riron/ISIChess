<div class="navbar navbar-googlenav">
  <div class="navbar-inner">
    <ul class="nav navbar-googlenav">
    <li>
      <a href="<?php echo $this->generateUrl('users', 'profile', $this->session->readEntry('user')->id_utilisateur); ?>">Profil</a>
    </li>
    <li><a href="<?php echo $this->generateUrl('users', 'games'); ?>">Mes parties</a></li>
    <li class="active"><a href="#">Nouvelle partie</a></li>
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
  <h1>Utilisateurs <small>Liste des utilisateurs inscrits</small></h1>
</div>
<table class="table table-bordered table-striped table-hover tablesorter hasFilters tablesorter-bootstrap">
	<thead>
    <tr>
      <th>#</th>
      <th>Login</th>
      <th>Email</th>
      <th>Niveau</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    
<?php foreach ($users as $k => $v) {
	echo '<tr>';
  echo '<td>'.$v->id_utilisateur.'</td>';
	echo '<td>'.$v->login.'</td>';
  echo '<td>'.$v->email.'</td>';
  echo '<td>'.$v->indice_niveau.'</td>';
  if($this->session->isLogged()){
    echo '<td>';
    if($this->session->readEntry('user')->id_utilisateur != $v->id_utilisateur){
      echo '<a href="'.$this->generateUrl('chess', 'invite', $v->id_utilisateur).'" title="Inviter à jouer"><i class="icon-plus-sign"></i></a></td>';
    }
    else{
      echo 'Une partie contre toi même??';
    }
  }
  else{
    echo '<td><a href="'.$this->generateUrl('users', 'login').'" title="Editer utilisateur">Identifiez vous !</a></td>';
  }
  
  echo '</tr>';
}
?>
</table>

<script src="<?php echo WEBROOT ?>webroot/js/jquery.tablesorter.js"></script>
<script src="<?php echo WEBROOT ?>webroot/js/jquery.tablesorter.widgets.js"></script>
<script src="<?php echo WEBROOT ?>webroot/js/app.js"></script>
