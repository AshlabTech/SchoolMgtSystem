<?php 
			$id = $_POST['id'];
			include_once('connection.php');
			
			$sql_update = "UPDATE staff_subjects SET status = '0' where id = '$id' and status = '1'";
			$query_update = mysqli_query($conn,$sql_update) or die(mysqli_error($conn));
				if($query_update){
					echo  'Removed successfully..';
				}else{
					echo  'Not Removed..';
				}
		
?>