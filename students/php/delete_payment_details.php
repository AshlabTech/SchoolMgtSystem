<?php 
			$payment_details_id = $_POST['token'];
			include_once('../php/connection.php');
			
			$sql_update = "UPDATE payment_details SET status = '0' where payment_details_id = '$payment_details_id' and status = '1'";
			$query_update = mysqli_query($conn,$sql_update) or die(mysqli_error($conn));
				if($query_update){
					echo  'deleted successfully..';
				}else{
					echo  'Not deleted..';
				}
		
?>