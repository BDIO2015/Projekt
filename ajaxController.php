<?php
session_start();
require_once "./Controller/php/goscController.php";
$goscController= new goscController();
echo $goscController->goscLoad();
?>