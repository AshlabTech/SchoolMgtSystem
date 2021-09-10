 
 <?php
	include_once('connection.php');
	
	//get current session id
											$query2 = mysqli_query($conn,"select * from session where status = '1' ") or die(mysqli_error($conn));
											$class_array2 = mysqli_fetch_array($query2);
											$session_id = $class_array2['section_id'];
											$session_name = $class_array2['section'];
											
											
				echo '<hr/><h2> Payment Definition for '.$session_name.' Academic Session</h2>
				<div class="alert alert-info"> The payment definition here will affect student registration as much to pay will be callculated at registration point</div>';
											
				
		if(isset($_POST['token']))
			$school_section_id = mysqli_real_escape_string($conn,$_POST['token']);
		else
			$school_section_id = 1;
			
			$sql_section_name = "select * from school_section where school_section_id = '$school_section_id'";
			$query_section_name = mysqli_query($conn,$sql_section_name);
			$num_rows_name= mysqli_num_rows($query_section_name);
				if($num_rows_name > 0){
					$name_array =  mysqli_fetch_assoc($query_section_name);
					$section_name = $name_array['section_name'];
				}
			
			
			$sql_section_payment = "select distinct sex from payment_details where school_section_id = '$school_section_id' AND current_session_id='$session_id' order by sex desc";
			$query_section_payment = mysqli_query($conn,$sql_section_payment);
			$num_rows1= mysqli_num_rows($query_section_payment);
				if($num_rows1 > 0){
								
						
					while($section_array1 = mysqli_fetch_array($query_section_payment)){
						$gender = $section_array1['sex'];
								

								 $sql_section_payment2 = "select * from payment_details where school_section_id = '$school_section_id' and (sex='$gender') and status = '1'  AND current_session_id='$session_id'";
							
							$query_section_payment2 = mysqli_query($conn,$sql_section_payment2);
							$num_rows2= mysqli_num_rows($query_section_payment2);
								if($num_rows2 > 0){
									
											$sql_sum_amount = "select SUM(amount) from payment_details where school_section_id = '$school_section_id' and (sex='$gender') and status = '1'  AND current_session_id='$session_id'";
											$query_sum_amount = mysqli_query($conn,$sql_sum_amount);
											$sum_row = mysqli_fetch_row($query_sum_amount);
											$count = $sum_row[0];
										
										if($gender == 'M'){
											$gender = 'Male students';
											$st = '<b style="color:red">New</b>';
										}
										else if($gender == 'F'){
												$gender = 'Female students';
												$st = '<b style="color:red">New</b>';
										}
										else {
												$gender = 'students';
												$st = '<b style="color:blue">Old </b>';
										}
												
									echo '
										<table class="table table-bordered" style="width:100%">
											<caption>'.$section_name.' <b>('.$st.'  '.$gender.')</b></caption>
												<tr>
													<th style="width:2%">sn</th>
													<th>Description</th>
													<th class="text-center">Amount</th>
													<th></th>
									';
									$sn = 1;
									
									
										while($section_array2 = mysqli_fetch_array($query_section_payment2)){
										$payment_details_id = $section_array2['payment_details_id'];
										$payment_description = $section_array2['payment_description'];
										$amount = $section_array2['amount'];
											echo '
													<tr>
														<td class="text-center">'.$sn++.'</td>
														<td>'.$payment_description.'</td>
														<td class="text-center">'.$amount.'</td>
														<td class="text-center" style="width:25%">
														<button class="btn btn-default" onclick="load_update_payment_details('.$payment_details_id.')"><span class="fa fa-edit"></span> Edit</button>
														<button class="btn btn-danger" onclick="delete_payment_details('.$payment_details_id.','.$school_section_id .')"><span class="fa fa-trash"></span> Remove</button>
														</td>
													</tr>
												';
										}
									echo '	
										<tr>
											<td></td>
											<td colspan="3"><b> Total =  N'.$count.'</b></td>
										</tr>
									</table>';
								}
					}
				}
											

?>

						
					