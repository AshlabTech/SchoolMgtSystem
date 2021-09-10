<?php  

/*
	include_once('connection.php');
	if(isset($_POST['token'])){
		  $staff_info_id= mysqli_real_escape_string($conn,$_POST['token']);
	}
		
		
			//check bank details if any is not provided yet
			$bank_details_sql = "select * from staff_bank_details where staff_info_id = '$staff_info_id' limit 1";
			$bank_details_run = mysqli_query($conn,$bank_details_sql);
				if(mysqli_num_rows($bank_details_run) > 0){
					$bank_details = mysqli_fetch_array($bank_details_run);
					$account_name	= $bank_details['account_name'];
					$account_number	= $bank_details['account_number'];
					$account_bvn	= $bank_details['account_bvn'];
					$account_type		= $bank_details['account_type'];
					$bank		= $bank_details['bank'];
					
					if(empty($account_name) and empty($account_number) and empty($account_bvn) and empty($bank)){
						echo '<ul class="list-group" style="margin:0;border-radius:0;box-shadow:none">
							  <li class="list-group-item"> <span class="fa fa-briefcase" ></span> 			<a href="#" style="margin-left:20px">    We need you to complete all your bank details so that we can better serve you.</a></li>
							  <li class="list-group-item"> <span class="fa fa-briefcase" ></span> 			<a href="#" style="margin-left:20px">    We need you to complete all your bank details so that we can better serve you.</a></li>
							  <li class="list-group-item"> <span class="glyphicon glyphicon-gift" ></span> 			<a href="#" style="margin-left:20px">    Your birth day is tomorrow. We wish you happy Birthday in advance. New age with grace</a></li>
							  <li class="list-group-item"> <span class="fa fa-briefcase" ></span> 			<a href="#" style="margin-left:20px">    We need you to complete all your bank details so that we can better serve you.</a></li>
							  <li class="list-group-item"> <span class="fa fa-briefcase" ></span> 			<a href="#" style="margin-left:20px">    We need you to complete all your bank details so that we can better serve you.</a></li>
							  <li class="list-group-item"> <span class="glyphicon glyphicon-education" ></span> 			<a href="#" style="margin-left:20px">    We need you to complete all your educational  details so that we can better serve you.</a></li>

							</ul>';
					}else if(empty($account_name)){
							echo '<ul class="list-group" style="margin:0;border-radius:0;box-shadow:none">
							  <li class="list-group-item"> <span class="fa fa-briefcase" ></span> 			<a href="#" style="margin-left:20px">    <b>[ Bank - Details] </b>You have\'not provided your bank account name yet..</a></li>
							
							</ul>';
					}
					else if(empty($account_number)){
							echo '<ul class="list-group" style="margin:0;border-radius:0;box-shadow:none">
							  <li class="list-group-item"> <span class="fa fa-briefcase" ></span> 			<a href="#" style="margin-left:20px">    <b>[ Bank - Details] </b>You have\'not provided your bank account number yet..</a></li>
							
							</ul>';
					}
					else if(empty($account_bvn)){
							echo '<ul class="list-group" style="margin:0;border-radius:0;box-shadow:none">
							  <li class="list-group-item"> <span class="fa fa-briefcase" ></span> 			<a href="#" style="margin-left:20px">    <b>[ Bank - Details] </b>You have\'not provided your BVN  yet..</a></li>
							
							</ul>';
					}
					else if(empty($account_type)){
							echo '<ul class="list-group" style="margin:0;border-radius:0;box-shadow:none">
							  <li class="list-group-item"> <span class="fa fa-briefcase" ></span> 			<a href="#" style="margin-left:20px">    <b>[ Bank - Details] </b>You have\'not provided your account_type  yet.</a></li>
							
							</ul>';
					}
				}
			
			
	
			echo $option;
			*/

?>