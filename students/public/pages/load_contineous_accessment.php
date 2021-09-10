<?php session_start(); ?>
<?php
	if(isset($_SESSION['staff_info_id'])){
		$staff_info_id = $_SESSION['staff_info_id'];
	}
	else{
		header('location:../? token=2');
	}
	include_once('../php/staff_data.php');

?>
<?php
	
		//$staff_info_id = $_POST['token'];
	include_once('../../php/staff_data.php');

	

		
			
			
	
	?>
 <div class="row" style="color:#067;margin:20px;" id="access_options_wrap">
	<div class="col-lg-12">
		<form class="form-horizontal">
										  <div class="form-group">
											<label for="inputEmail3" class="col-sm-1 control-label">CLASS</label>
											<div class="col-sm-3">
											<select class='form-control' style="margin:5px;color:#000" id = "class_idd" onchange="load_subs(<?php echo $staff_info_id;?>,this.value)">
												<option value="">-- SELECT CLASS -- </option>
												<?php 
															//check if the class is already assign but status = 1			
													$check_if_assigned = mysqli_query($conn,"select * from staff_classes where staff_info_id = '$staff_info_id'  and status = '1'") or die(mysqli_error($conn));
													$check_if_assigned_num_rows = mysqli_num_rows($check_if_assigned);
														if($check_if_assigned_num_rows > 0){
														$mm = 1;
															while($class_rows = mysqli_fetch_array($check_if_assigned)){
																$class_id=$class_rows['class_id'];
																	
																		
																		$class_name= "select * from classes where class_id='$class_id'";
																		$class_name_run =  mysqli_query($conn,$class_name) or die(mysqli_error($conn));
																		$class_name_rows = mysqli_fetch_array($class_name_run);
																			
																			 $class_n = $class_name_rows['class_name'];
																	
																	echo '<option value='.$class_id.'>'.$class_n.'</option>';
																	
															}
														}
												?>
											</select>
											</div>
											<label for="inputEmail3" class="col-sm-1 control-label">Term</label>
											<div class="col-sm-3">
											<select class='form-control' style="margin:5px;"  id="term_id" onchange="load_accessment_sheet()">
												
												<?php 
													$term = mysqli_query($conn,"select * from term  where status = '1' order by status desc") or die(mysqli_error($conn));
													$term_array = mysqli_fetch_array($term);
																$term = $term_array['term'];
																$id = $term_array['id'];
																$description = $term_array['description'];
																echo '<option value="'.$id.'" selected>'.$description.'</option>';						
												?>
												
											
												
											</select>
											</div>
											<label for="inputEmail3" class="col-sm-1 control-label">SUBJECT</label>
											<div class="col-sm-3">
											<select class='form-control' style="margin:5px;"  id="sub_id" onchange="load_accessment_sheet()">
												<option value="">-- SELECT SUBJECT -- </option>
												<?php 
													if($staff_section==1){
														$sql_all_subject= "select * from subject where school_section <=2 and status ='1'";
																					$php_process_sql_all_subject =  mysqli_query($conn,$sql_all_subject) or die(mysqli_error($conn));
																					
																							while($all_subject_array = mysqli_fetch_array($php_process_sql_all_subject)){
																											$subject_id = $all_subject_array['id'];
																										$subject = $all_subject_array['subject'];
																										$subject_code = $all_subject_array['subject_code'];
																										$school_section = $all_subject_array['school_section'];
																										
																										$get_section_name  = mysqli_query($conn,"select * from school_section where school_section_id = '$school_section'") or die(mysqli_error($conn));
																										$row = mysqli_fetch_array($get_section_name);
																										$section_name = $row['abr'];
																								
																							echo  '<option value="'.$subject_id.'">'.$subject_code.'</option>';
																							}
																							
													}else{
																													$sub_check = "select * from staff_subjects where staff_info_id = '$staff_info_id' and status ='1'";
															$sub_check_run =  mysqli_query($conn,$sub_check) or die(mysqli_error($conn));
															$num_rows_sub_check= mysqli_num_rows($sub_check_run);
															if($num_rows_sub_check > 0){
																
																	while($rows = mysqli_fetch_array($sub_check_run)){
																			$id = $rows['id'];
																			$subject_id = $rows['subject_id'];
																			
																			
																					$sql_all_subject= "select * from subject where id = '$subject_id' and status ='1' LIMIT 1";
																					$php_process_sql_all_subject =  mysqli_query($conn,$sql_all_subject) or die(mysqli_error($conn));
																					
																							$all_subject_array = mysqli_fetch_array($php_process_sql_all_subject);
																										$subject_id = $all_subject_array['id'];
																										$subject = $all_subject_array['subject'];
																										$subject_code = $all_subject_array['subject_code'];
																										$school_section = $all_subject_array['school_section'];
																										
																										$get_section_name  = mysqli_query($conn,"select * from school_section where school_section_id = '$school_section'") or die(mysqli_error($conn));
																										$row = mysqli_fetch_array($get_section_name);
																										$section_name = $row['abr'];
																								
																							echo  '<option value="'.$subject_id.'">'.$subject_code.'</option>';
																							
																	}
															}
													}

																										
												?>
											</select>
											</div>
										  </div>
										  
			</form>
		

	</div>

</div>

 <div class="row" style="color:#067;margin:0px;">
	<div class="col-lg-12" id="accessment_sheet">

	
		

	</div>

</div>