<?php
session_start();

			include_once('connection.php');
		// Set Session data to an empty array
		//$_SESSION = array();

		// Destroy the session variables
		//session_destroy();
		unset($_SESSION['staff_info_id']);
		echo $staff_info_id = $_SESSION['staff_info_id'];
		//logout 
		
		$logout = mysqli_query($conn,"update online_staffs set status = '0' where staff_info_id = '5'");
		if($logout){
			
			// re-direct back to index page
			header("location: ../?logout=you.have.been.logout");
			
		}else{
			
		}
		


?>