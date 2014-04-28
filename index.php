<?php 
$debut = microtime(true);
define('ROOT', dirname($_SERVER['SCRIPT_FILENAME']).'/');
define('WEBROOT', dirname($_SERVER['SCRIPT_NAME']).'/');

require('core/functions.php');
require(ROOT.'config/config.php');

require('core/session.php');
require('core/controller.php');
require('core/model.php');
require('core/request.php');
require('core/router.php');
require('core/dispatcher.php');

$dispatcher = new Dispatcher();
?>
<?php if(Config::$debug > 0): ?>
<div class="debugInfos">
<?php echo 'Généré en '. round(microtime(true) - $debut, 5).' s. - '.$dispatcher->request()->controller.'Controller::'.$dispatcher->request()->action.'Action - Nb de requêtes: '.Model::$compteur; ?>
</div>
<?php endif; ?>