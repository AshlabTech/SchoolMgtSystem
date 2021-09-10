<?php 
include_once('../php/connection.php');
	
	$sec = 2013;
	for($sec;$sec <= 2025;$sec++){
		$section = $sec.'/'.($sec+1);
		
		$sql = "insert into session (section) values ('$section') ";
		$query_insert = mysqli_query($conn,$sql) or die(mysqli_error($conn));
	}
		
											
?>