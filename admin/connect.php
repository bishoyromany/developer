<?php
$dbname = "shop";
$dblocal = "mysql:host=localhost;dbname=".$dbname;
$dbuser = "root";
$dbpass = "";
$dboption = array(
	PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
);
try
{
	$con = new PDO ($dblocal , $dbuser , $dbpass , $dboption);
	$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e)
{
	echo "faild to connect" . $e->getMessage();
}