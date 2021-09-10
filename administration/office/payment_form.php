<?php 
	if(isset($_POST['token'])){
		$student_info_id = $_POST['token'];
		include_once('../php/connection.php');
	include_once('../php/student_data.php');
	}else{
		echo '<h3>Access denied...</h3>';
		exit();
	}
		

?>

<div class="col-lg-4">
					<button class='btn btn-lg btn-primary' onclick="return false" onmousedown="load_student_payment_details(<?php echo $student_info_id;?>)"> 
						<span class='glyphicon glyphicon-arrow-left' style="float:left;margin-right:10px">Back</span>
					</button>
</div>
<div  class="col-lg-4" style="border:1px solid #ccc;font-size:10px;color:#06c">
				
			<!-- Payment form beginning -->
				<div style="margin:20px auto">
				
				<div id="" style="margin:10px;">
						<p class="text-center"> Class</p>
						<p class="text-center">
						<select id="class_pay_for" class="form-control" style="width:300px;display:inline-block">
								<option value="">Select class</option>
									<?php 
									$cc = $class_id;
											$query = mysqli_query($conn,"select * from student_classes where student_info_id = '$student_info_id' order by student_class_id desc") or die(mysqli_error($conn));
											while($class_array = mysqli_fetch_array($query)){
												$id = $class_array['class_id'];
												
													$query2 = mysqli_query($conn,"select * from classes where class_id = '$id'") or die(mysqli_error($conn));
														$class_array2 = mysqli_fetch_array($query2);
															$class_id = $class_array2['class_id'];
															$class = $class_array2['class_name'];
															if($cc == $class_id)
																echo '<option value="'.$class_id.'" selected>'.$class.'</option>';
															else
																echo '<option value="'.$class_id.'">'.$class.'</option>';
											
											}
									?>
							</select>
						</p>
						<p class="text-center"> Term	 		  Payment Type  </p>
						<p  class="text-center"><select id="school_fee_term" class="form-control"  style="width:150px;display:inline-block" >
									<?php 
											$term_qr = mysqli_query($conn,"select * from term order by status desc") or die(mysqli_error($conn));
											while($term_arr = mysqli_fetch_array($term_qr)){
												$id = $term_arr['id'];
												$description = $term_arr['description'];
												
												echo '<option value="'.$id.'">'.$description.'</option>';
										
										
											}
									?>
							
							</select>
						
							
						<select id="payment_typee" class="form-control" style="width:150px;display:inline-block" onchange="payment_number_showHide(this.value)">
								<option value=2>Cash  </option>
								<option value=1>Bank </option>
								
						</select>
						
						
							</p>
						<p id="school_fee_date_label"  class="text-center"> Payment Date	E.g 21-02-2017</span>
						<p class="text-center"><select id="school_fee_day" class="form-control"  style="width:100px;display:inline-block" >
								<option value=""> day </option>
										<?php 
											$dy = 1;
											$day = @date('d');
											while($dy < 32){
												if($dy < 10){
													$dy = '0'.$dy;
												}
													echo '<option value="'.$dy.'" >'.$dy.'</option>';
													if($day==$dy)
														echo '<option value="'.$dy.'" selected >'.$dy.'</option>';
													else
														echo '<option value="'.$dy.'" >'.$dy.'</option>';
													$dy++;
											}
												
									?>
							
							</select>
							<select id="school_fee_monthh" class="form-control"  style="width:100px;display:inline-block">
								<option value="">Month </option>
										<?php 
											$month = @date('m');
											for($mn = 1;$mn < 13;$mn++){
												if($mn < 10){
													$mn = '0'.$mn;
												}
													if($month==$mn)
														echo '<option value="'.$mn.'" selected>'.$mn.'</option>';
													else
														echo '<option value="'.$mn.'">'.$mn.'</option>';
													
												
											}
												
									?>
							
							</select>
							<select id="school_fee_yearr" class="form-control" style="width:100px;display:inline-block">
							
										<?php 
											$year = mysqli_query($conn,"select * from year ORDER BY status ASC") or die(mysqli_error($conn));
											while($year_array = mysqli_fetch_array($year)){
												$years = $year_array['year'];
												echo '<option value="'.$years.'" >'.$years.'</option>';
											}
												
									?>
							
							</select></p>
						<p class="text-center"><label> Amount Paid	</label></p>
						<p class="text-center"><input type="text"  id="amount_paid" class="form-control"  style="width:300px;display:inline-block" placeholder="Amount"></p>
						<div id="payment_number_wrap" style="display:none">
						<p class="text-center"><label> Teller Number	</label><br></p>
						<p class="text-center"><input type="text" id="payment_number" class="form-control" style="width:300px;display:inline-block" placeholder="Teller Number"></p>
						</div>
						<p class="text-center"><label> Depositor / Name of the person who's making the payment	</label></p>
						<p class="text-center"><input type="text" id="payee_name" class="form-control" style="width:300px;display:inline-block" placeholder="Payee Name"></p>
						<p class="text-center">
							<span id="make_payment_status"></span>
							<button   id="make_payment_btn"  class="btn btn-primary" onclick="make_payment(<?php echo $student_info_id; ?>)">Make Payment</button>
						</p>
					
				</div>
				</div >
				<!-- Payment form ending -->
				
			
		</div>
<div class="col-lg-4"></div>