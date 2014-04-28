<div class="page-header">
	<h1>Editer son profil</h1>
</div>
<form class="form-horizontal" method="POST" action="<?php echo $this->generateUrl('users', 'edit', $id) ?>">
  <legend>Edition:</legend>
  <div class="control-group">
    <label class="control-label">Email</label>
    <div class="controls">
      <input type="text" name="email" placeholder="Email">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label">Nom</label>
    <div class="controls">
      <input type="text" name="nom" placeholder="Login">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label">Prénom</label>
    <div class="controls">
      <input type="text" name="prenom" placeholder="Login">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label">Password</label>
    <div class="controls">
      <input type="password" name="password" placeholder="Password">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label">Confirmez le password</label>
    <div class="controls">
      <input type="password" name="confirm" placeholder="Confirmation">
    </div>
  </div>
  <div class="control-group">
    <div class="controls">
      <button type="submit" class="btn">Valider</button>
    </div>
  </div>
</form>