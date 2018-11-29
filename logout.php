<?php
ob_start();	
	session_start(); // start session
	session_unset(); // unset the data
	session_destroy(); // Destroy the session

	header('location: index.php'); // link you should go 

	exit();
 ob_end_fluch(); 
