<?php
session_start();
include("Settings/head.html");
require_once "./Controller/php/Controller.php";
$main=new Controller();
$main->load();
?>
