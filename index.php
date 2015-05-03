<?php
session_start();
include("Setings/head.html");
require_once "./Controller/php/Controller.php";
$main=new Controller();
$main->load();
?>
