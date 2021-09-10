<?php 
			$term_id = $_POST['token'];
			include_once('connection.php');
			
			
			$sql_update = "UPDATE term SET status ='0'";
			$query_update = mysqli_query($conn,$sql_update) or die(mysqli_error($conn));
				if($query_update){
						$sql_update2 = "UPDATE term set status ='1' where id = '$term_id'";
						$query_update2 = mysqli_query($conn,$sql_update2) or die(mysqli_error($conn));
					echo  'Term changed successfully...';
				}else{
					echo  '	Change failed.....';
				}
		
?>