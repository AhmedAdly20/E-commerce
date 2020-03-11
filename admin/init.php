<?php
$tpl = 'includes/templates/';
$css = 'layout/css/';
	include 'includes/functions/functions.php';
	include 'connect.php';
	include "includes/languages/en.php";
	include $tpl . "header.php";
	if (!isset($noNavBar)) {include $tpl . "navbar.php";}