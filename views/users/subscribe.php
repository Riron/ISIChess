<form class="form-horizontal" method="POST" action="<?php echo $this->generateUrl('users', 'subscribe') ?>">
  <legend>Enregistrez vous:</legend>
  <div class="control-group">
    <label class="control-label">Email</label>
    <div class="controls">
      <input type="text" name="email" placeholder="Email">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label">Login</label>
    <div class="controls">
      <input type="text" name="login" placeholder="Login">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label">Nom</label>
    <div class="controls">
      <input type="text" name="nom" placeholder="Nom">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label">Prénom</label>
    <div class="controls">
      <input type="text" name="prenom" placeholder="Prénom">
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
    <label class="control-label">Choisissez un avatar</label>
    <div class="controls">
      <label class="radio inline">
        <input type="radio" name="optionsAvatar" id="inlineCheckbox1" value="homme" checked> <img src="<?php echo WEBROOT ?>webroot/img/Avatars/homme.png" alt="Homme">
      </label>
      <label class="radio inline">
        <input type="radio" name="optionsAvatar" id="inlineCheckbox2" value="femme"> <img src="<?php echo WEBROOT ?>webroot/img/Avatars/femme.png" alt="Femme">
      </label>
      <label class="radio inline">
        <input type="radio" name="optionsAvatar" id="inlineCheckbox3" value="prof"> <img src="<?php echo WEBROOT ?>webroot/img/Avatars/prof.png" alt="Prof">
      </label>
    </div>
  </div>
  <div class="control-group">
    <div class="controls">
      <button type="submit" class="btn">S'enregistrer</button>
    </div>
  </div>
</form>