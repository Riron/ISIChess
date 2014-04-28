<div class="pull-right connexion">
	<?php if($this->session->isLogged()): ?>
	<a href="<?php echo $this->generateUrl('users', 'profile', $this->session->readEntry('user')->id_utilisateur); ?>" class="pull-right">
		<img src="<?php echo WEBROOT ?>webroot/img/Avatars/<?php echo $this->session->readEntry('user')->avatar;?>.png" alt="" class="media-object">
	</a>
	<div class="pull-left boutonCompte">
		<div class="btn-group">
		  <a class="btn dropdown-toggle btn-danger btn-small" data-toggle="dropdown" href="#">
			<i class="icon-cog icon-white"></i> Bienvenue <?php echo $this->session->readEntry('user')->login;?>
		    <span class="caret"></span>
		  </a>
		  <ul class="dropdown-menu">
		    <li><a href="<?php echo $this->generateUrl('users', 'profile', $this->session->readEntry('user')->id_utilisateur); ?>"><i class="icon-user"></i> Profil</a></li>
		    <li><a href="<?php echo $this->generateUrl('users', 'edit', $this->session->readEntry('user')->id_utilisateur); ?>"><i class="icon-pencil"></i> Editer mon profil</a></li>
		    <li><a href="<?php echo $this->generateUrl('users', 'logout'); ?>"><i class="icon-off"></i> Deconnexion</a></li>
		  </ul>
		</div>
	</div>
	<?php else: ?>
		    <p><a href="<?php echo $this->generateUrl('users', 'login'); ?>" class="btn btn-primary btn-large" >Rejoignez nous !</a></p>
	<?php endif; ?>
</div>
