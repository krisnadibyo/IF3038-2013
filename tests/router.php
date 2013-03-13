<?php
require_once dirname(__FILE__) . '/../core/Core.php';
import('core.Router');
Router::init();

header('Content-Type: text/plain');
?>
Route:
<?php print_r(Router::getRoute()); ?>


Controller:
<?php print_r(Router::getController()); ?>


Action:
<?php print_r(Router::getAction()); ?>


Params:
<?php print_r(Router::getParams()); ?>


Queries:
<?php print_r(Router::getQueries()); ?>


Queries ($_REQUEST):
<?php print_r($_REQUEST); ?>
