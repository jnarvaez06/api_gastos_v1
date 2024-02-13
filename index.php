<?php
require_once "config/config.inc.php";
require_once "controllers/routes_controller.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$index = new RoutesController();
$index->index();

?>