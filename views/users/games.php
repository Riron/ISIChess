<div class="navbar navbar-googlenav">
  <div class="navbar-inner">
    <ul class="nav navbar-googlenav">
	  <li>
	    <a href="<?php echo $this->generateUrl('users', 'profile', $this->session->readEntry('user')->id_utilisateur); ?>">Profil</a>
	  </li>
	  <li class="active"><a href="#">Mes parties</a></li>
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
	<h1>Parties en cours</h1>
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
		foreach ($games as $k => $v) {
			echo '<tr>';
				echo '<td>'.$v->id_partie.'</td>';
				echo '<td>'.$v->adversaire;
				echo ($v->monTour == 1) ? " \t".' <span class="label label-important">A votre tour</span>' : '';
				echo '</td>';
				echo '<td>'.$v->dat_debut.'</td>';
				echo '<td><a href="'.$this->generateUrl('chess', 'play', $v->id_partie).'" title="Reprendre la partie"><i class="icon-step-forward"></i></a></td>';
			echo '</tr>';
		}
		?>
	</tbody>
</table>

<div class="page-header">
	<h1>Parties terminées</h1>
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
		foreach ($gamesEnded as $k => $v) {
			echo '<tr>';
				echo '<td>'.$v->id_partie.'</td>';
				echo '<td>'.$v->adversaire.'</td>';
				echo '<td>'.$v->dat_debut.'</td>';
				echo '<td><a href="'.$this->generateUrl('chess', 'view', $v->id_partie).'" title="Visionner la partie"><i class="icon-step-forward"></i></a></td>';
			echo '</tr>';
		}
		?>
	</tbody>
</table>