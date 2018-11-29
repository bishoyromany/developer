<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "admin/connect.php";
// Routes
$functions = 'includes/functions/';		// functions includer
$tpl = 'includes/template/';			// this is includes folder 
$css = 'layout/css/';					// css path
$js = 'layout/js/';						// js path
$images = 'layout/images/';				// images

// include Important Files
	include $functions.'functions.php'; // include the function
	include $tpl.'header.php'; 			// the header include
	include $tpl.'navbar.php'; 			// the header include
