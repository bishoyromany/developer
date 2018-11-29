<?php
include "connect.php";
// Routes
$functions = 'includes/functions/';		// functions includer
$tpl = 'includes/template/';			// this is includes folder 
$css = 'layout/css/';					// css path
$js = 'layout/js/';						// js path
$images = 'layout/images/';				// images

// include Important Files
	include $functions.'functions.php'; // include the function
	include $tpl.'header.php'; 			// the header include
										// include navbar withall pages instead the one with nonavbar var
	if (!isset($noNavbar)) {
	include $tpl.'navbar.php'; 			// the header include
	}