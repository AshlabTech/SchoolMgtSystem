<?php 
			include_once('connection.php');
			$class_id = $_POST["token"];
							
				$sql_all_class = "select * from student_classes where class_id = '$class_id' and status = '1'";
				$query_all_class =  mysqli_query($conn,$sql_all_class) or die(mysqli_error($conn));
					$num_rows_all_class = mysqli_num_rows($query_all_class);
					if($num_rows_all_class > 0){
						$sn = 1;
						while($array_all_class = mysqli_fetch_array($query_all_class)){
							$student_info_id = $array_all_class['student_info_id'];
													
								$sql_student_info = "select * from student_info where student_info_id = '$student_info_id'";
								$query_student_info =  mysqli_query($conn,$sql_student_info) or die(mysqli_error($conn));
								$num_rows_student_info = mysqli_num_rows($query_student_info);		
									
									if($num_rows_student_info > 0){
											$arrray_student_info = mysqli_fetch_array($query_student_info);
											$first_name = $arrray_student_info['first_name'];
											$last_name = $arrray_student_info['last_name'];
											$other_name = $arrray_student_info['other_name'];
											$gender = $arrray_student_info['gender'];
											$image_name = $arrray_student_info['image_name'];
											$full_name = $first_name.' '.$other_name.' '.$last_name;
											if($sn%2 == 0) 	$typ = "default";	else 	$typ = "primary";
														
														if($image_name == ''){
															$user_pic = '../images/default.jpg';
														}else{
															$user_pic = "../images/$image_name";
														}
															$tr .= '
																<tr>
																   <td width="1%"><a href="#" onclick="alert('.$class_id.')" type="button" class="btn btn-'.$typ.'"> <span class="glyphicon glyphicon-th-large" aria-hidden="true"></span> '.$sn++.'</a> </td>
																   <td width=""><strong>'.$full_name.'</strong></td>
																   <td class="text-center"  width="3%"><strong>'.$gender.'</strong></td>
																    <td width="10%" class="text-center"><img class="img img-circle" src="'.$user_pic.'" style="width:80%"></td>
																 </tr>';
														
									}else{
										$error = "no student in the selected class...";
									}
						}
					}
					
		
		
		
			
			

?>															
												<div>	
												<button class="btn btn-primary" id="add_new_student_btn" onclick="load_add_new_student_form()" style='float:right;margin:5px 5px;margin-right:30px;margin-bottom:0px'><span class="fa fa-plus-circle" style="font-size:20px"></span> Add Student</button></div><br><br><br>
															 <table class="table table-bordered table-hover" width="100%" border="1px">
															<thead>
																 <tr>
																   <td width="1%"><strong>S/N</strong></td>
																   <td class="text-center" width=""><strong>CLASS</strong></td>
																   <td width=""><strong>Total Students</strong></td>
																 </tr>
																</thead>
																<tbody>
																	<?php echo $tr; ?>
																
																</tbody>
																	
															</table>
															<?php echo $error; ?>
															