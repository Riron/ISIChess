<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>ISIChess</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="icon" type="image/png" href="<?php echo WEBROOT ?>webroot/img/favicon.png" />

    <!-- Le styles -->
    <link href="<?php echo WEBROOT ?>webroot/css/perso.css" rel="stylesheet">
    <link href="<?php echo WEBROOT ?>webroot/css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo WEBROOT ?>webroot/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="<?php echo WEBROOT ?>webroot/css/todc-bootstrap.css" rel="stylesheet">
    <?php if(Config::$debug>0): ?>
    	<link href="<?php echo WEBROOT ?>webroot/css/debug.css" rel="stylesheet">
	<?php endif; ?>

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->
    <script src="http://code.jquery.com/jquery-latest.js"></script>
  </head>

  <body>
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li <?php if($this->request->controller == 'home'){ echo 'class="active"';} ?>><a href="<?php echo WEBROOT; ?>">Accueil</a></li>
              <li <?php if($this->request->controller == 'bibliotheque'){ echo 'class="active"';} ?>><a href="<?php echo $this->generateUrl('bibliotheque', 'index') ?>">Bibliothèque</a></li>
              <li <?php if($this->request->controller == 'users'){ echo 'class="active"';} ?>><a href="<?php echo $this->generateUrl('users', 'login') ?>">Mon compte</a></li>
              <li class="notifDiv"><div class="btn-group">
                    <a class="btn btn-mini btn-link dropdown-toggle" data-toggle="dropdown" href="#">
                      <span class="badge badge-success notif">0</span>
                    </a>
                    <ul class="dropdown-menu">
                    </ul>
                  </div>
              </li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
    <header class="subhead">
      <div class="container"> 
        <?php include('user.php'); ?>
        <a href="<?php echo WEBROOT; ?>"><img class="logo" src="<?php echo WEBROOT ?>webroot/img/logo.png" alt=""></a>
      </div>
    </header>
    <div class="container">
      <?php $this->session->flash(); ?>
      <?php echo $content_for_layout; ?>
    	
    </div> <!-- /container -->

    <div id="footer">
      <div class="container">
        <p class="muted credit">©ISIChess</p>
      </div>
    </div>

  </body>
  <script src="<?php echo WEBROOT ?>webroot/js/bootstrap.min.js"></script>
  <script>
    $(document).ready(function (){
      $('.notifDiv').hide();
      var url = '<?php echo $this->generateUrl('ajax', 'notifications');?>';
      $.get(url, function(data) {
        console.log(data);
        data = jQuery.parseJSON(data);
        if(data.nb_invit > 0 || data.mon_tour > 0){
          $('.notifDiv').show();
          $('.notif').text(parseInt(data.nb_invit) + parseInt(data.mon_tour));
          $('.notifDiv .dropdown-menu').append('<li><a href="<?php echo $this->generateUrl('users', 'invite');?>">Vous avez '+data.nb_invit+' invitation(s) en attente</a></li>');
          $('.notifDiv .dropdown-menu').append('<li><a href="<?php echo $this->generateUrl('users', 'games');?>">Vous avez '+data.mon_tour+' partie(s) où c\'est votre tour</a></li>');
        }
      });
    });
  </script>
</html>
