<?php
	//error_reporting(E_ALL);
	date_default_timezone_set('Africa/Lagos');

	
		$servername = "localhost";
	$username = "elmaasu1_user";
	$password = "_DU=yAUqdfuV";

	$db = "elmaasu1_db";

	//create login connection and login
	$conn =  mysqli_connect($servername,$username,$password,$db) or die(mysqli_error($conn));
	error_reporting(0);

	$Fschool = mysqli_query($conn,"SELECT * FROM school LIMIT 1") or die(mysqli_error($conn));
	$school = mysqli_fetch_array($Fschool);
	$school_name = $school['name'];
	$school_address = $school['address'];
	$school_abbr = $school['abbr'];
	$school_image = $school['image'];



	define('DB_HOST', $servername);
	define('DB_NAME', $db);
	define('DB_USER', $username);
	define('DB_PASS', $password);

	$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4', DB_USER, DB_PASS);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);

	//////////
	
	
?>
