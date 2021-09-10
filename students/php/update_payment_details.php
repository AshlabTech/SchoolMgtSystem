<?php 
			$payment_details_id = $_POST['token'];
			include_once('../php/connection.php');
			
			$section = mysqli_real_escape_string($conn,$_POST['section']);
			$payment_description = mysqli_real_escape_string($conn,$_POST['payment_description']);
			$payment_amount = mysqli_real_escape_string($conn,$_POST['payment_amount']);
			$gender = mysqli_real_escape_string($conn,$_POST['gender']);
			$category = mysqli_real_escape_string($conn,$_POST['category']);
				if($category == 1){
				$gender  = 'All';
			}
		
			$token_n = $payment_details_id.','.$section;
			
			$sql_update = "UPDATE payment_details SET sex ='$gender',payment_description = '$payment_description',category ='$category',amount = '$payment_amount' where payment_details_id = '$payment_details_id' and status = '1'";
			$query_update = mysqli_query($conn,$sql_update) or die(mysqli_error($conn));
				if($query_update){
					echo  '	<label for="" style="color:green">Updated successfully..</label><br>
						<button type="submit" class="btn btn-info" onclick="add_payment_details()">Update</button>
					';
				}else{
					echo  '	<label for="" style="color:red">Updated Failed...</label><br>
							<button type="submit" class="btn btn-info" onclick="update_payment_details('.$token_n.')">Try again</button>
					';
				}
		
?>