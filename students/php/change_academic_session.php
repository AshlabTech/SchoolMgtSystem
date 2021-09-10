<?php 
			$session_id = $_POST['token'];
			include_once('connection.php');
			
			
			$sql_update = "UPDATE session SET status ='0'";
			$query_update = mysqli_query($conn,$sql_update) or die(mysqli_error($conn));
				if($query_update){
						$sql_update2 = "UPDATE session set status ='1' where section_id = '$session_id'";
						$query_update2 = mysqli_query($conn,$sql_update2) or die(mysqli_error($conn));
					echo  'session changed successfully...';
				}else{
					echo  '	Update failed.....';
				}
		
?>