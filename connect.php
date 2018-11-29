<?php

$servername = "localhost";
$user = "root";
$password = "";
try
{
	$connect = new PDO("mysql:host=$servername;dbname=posts",$user , $password);
	$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	echo "<h1 style='text-align:center;color:green;padding:5px;marging:10px auto;font:bold 25px Tahoma;'> Connected successfully </h1>";

}catch(PDOException $e)
{
	echo "<h1 style='text-align:center;color:red;padding:5px;marging:10px auto;font:bold 25px Tahoma;'> faild to connect </h1>: " . $e->getMessage();

};
try
{
	$connect = new PDO("mysql:host=$servername;dbname=matches",$user , $password);
	$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	echo "<h1 style='text-align:center;color:green;padding:5px;marging:10px auto;font:bold 25px Tahoma;'> Connected successfully </h1>";

}catch(PDOException $e)
{
	echo "<h1 style='text-align:center;color:red;padding:5px;marging:10px auto;font:bold 25px Tahoma;'> faild to connect </h1>: " . $e->getMessage();

};
