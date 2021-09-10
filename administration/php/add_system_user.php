<?php 
			$staff_login_id = $_POST['staff_login_id'];
			include_once('connection.php');
			
			
			$sql_update = "UPDATE staff_login_info SET 	type ='1' where staff_login_id = '$staff_login_id'";
			$query_update = mysqli_query($conn,$sql_update) or die(mysqli_error($conn));
				if($query_update){
						
					echo  'Added successfully...';
				}else{
					echo  '	adding failed.....';
				}
		
?>