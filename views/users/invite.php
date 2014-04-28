<div class="navbar navbar-googlenav">
  <div class="navbar-inner">
    <ul class="nav navbar-googlenav">
	  <li>
	    <a href="<?php echo $this->generateUrl('users', 'profile', $this->session->readEntry('user')->id_utilisateur); ?>">Profil</a>
	  </li>
	  <li><a href="<?php echo $this->generateUrl('users', 'games'); ?>">Mes parties</a></li>
	  <li><a href="<?php echo $this->generateUrl('users', 'listUsers'); ?>">Nouvelle partie</a></li>
	  <li class="active"><a href="#">Invitations</a></li>
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
	<h1>Invitations à des parties</h1>
</div>

<table class="table table-bordered table-striped table-hover" >
	<thead>
		<tr>
			<td>#</td>
			<td>Adversaire</td>
			<td>Date de début</td>
			<td>Action</td>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ($invitations as $k => $v) {
			echo '<tr>';
				echo '<td>'.$v->id_partie.'</td>';
				echo '<td>'.$v->adversaire.'</td>';
				echo '<td>'.$v->dat_debut.'</td>';
				echo '<td><a href="'.$this->generateUrl('chess', 'confirmInvit', $v->id_partie).'" title="Accepter l\'invitation"><i class="icon-ok"></i></a> - <a href="'.$this->generateUrl('chess', 'delete', $v->id_partie).'" title="Refuser l\'invitation"><i class="icon-remove"></i></a></td>';
			echo '</tr>';
		}
		?>
	</tbody>
</table>