<form class="form-horizontal" method="POST" action="<?php echo $this->generateUrl('users', 'login') ?>">
	<legend>Connexion:</legend>
  <div class="control-group">
    <label class="control-label">Login</label>
    <div class="controls">
      <input type="text" name="login" placeholder="Login">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label">Password</label>
    <div class="controls">
      <input type="password" name="password" placeholder="Password">
    </div>
  </div>
  <div class="control-group">
    <div class="controls">
      <button type="submit" class="btn">Se connecter</button>
    </div>
  </div>
</form>
<p>Pas encore inscrit ? <a href="<?php echo $this->generateUrl('users', 'subscribe'); ?>">Inscrivez vous !</a></p>