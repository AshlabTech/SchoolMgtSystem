<?php 
			include_once('../php/connection.php');
			if(isset($_POST["class_id"])){
				$class_id  = $_POST["class_id"];
			}else{
				$query = mysqli_query($conn,"select * from classes LIMIT 1") or die(mysqli_error($conn));
											while($class_array = mysqli_fetch_array($query)){
												$class_id = $class_array['class_id'];
												
											}
			}

			$sql2 = $conn->query("SELECT * FROM session WHERE status = '1'");
			$ssm = $sql2->fetch_assoc();
			$session_id = $ssm['section_id'];	
			// control page number
				if(isset($_POST["pn"])){
								$pn = $_POST["pn"];
							}else{
								$pn = 1;
							}
					
					
					// i get the cout of rows i wish to select from the db
					$countt = "select COUNT(student_class_id) from student_classes where class_id = '$class_id' and (status = '1' or status = '2') AND session_id='$session_id'";
					$do_query = mysqli_query($conn,$countt);
					
					// whats the total row count? here is it
					$row = mysqli_fetch_row($do_query);
					
					// total row count
					$total_rows = $row[0];
					
					// how many items per page
					
					$item_pp = 100;

					// last page number_format
					$last = ceil($total_rows/$item_pp);

					//non - negative constraint on the last page number 
					if($last < 1)
					{
						$last = 1;
					}
					
						if($pn < 1)
						{
							$pn = 1;
						}
						else if($pn > $last)
						{
							$pn = $last;
						}
						
						$limit = ($pn - 1) * $item_pp.','.$item_pp;
						
				$sql_all_class1 = "select * from classes where class_id = '$class_id' and status = '1'";
				$query_all_class1 =  mysqli_query($conn,$sql_all_class1) or die(mysqli_error($conn));
				$array_all_class1 = mysqli_fetch_array($query_all_class1);
				$class_name = $array_all_class1['class_name'];
				
				$sql_all_class = "select * from student_classes where class_id = '$class_id' and (status = '1' or status = '2') AND session_id='$session_id' ORDER BY student_class_id LIMIT $limit";
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
											$religion = $arrray_student_info['religion'];
											$date_of_birth = $arrray_student_info['date_of_birth'];
											$state_id = $arrray_student_info['state_id'];
											$lga_id = $arrray_student_info['lga_id'];
											$tribe = $arrray_student_info['tribe'];
											$image_name = $arrray_student_info['image_name'];
											$residential_address = $arrray_student_info['residential_address'];
											
											//GET Age
											$dob_s = strtotime($date_of_birth);
											$current_date_s = strtotime(@date('Y-m-d'));
											$age_diff = $current_date_s - $dob_s;
											//$age_minute = $age_diff/60;
											$age = ceil($age_diff/(60*60*24*365)).' yrs';
										
											//get full_name
											$full_name = $first_name.' '.$other_name.' '.$last_name;
											
														//get state title
						$sql_get_state=mysqli_query($conn,"SELECT * FROM states where state_id = '$state_id'") or die(mysql_error());
						if($sql_get_state){
							$sql_get_state_row=mysqli_num_rows($sql_get_state);
							if($sql_get_state_row > 0){
								while($row=mysqli_fetch_assoc($sql_get_state)){
									$state_name = $row['name'];
								}}}
							
					$sql=mysqli_query($conn,"SELECT *FROM lga where local_id = '$lga_id'") ;
						if($sql){
							$sql_get_state_row=mysqli_num_rows($sql);
							if($sql_get_state_row > 0){
								while($row=mysqli_fetch_assoc($sql)){
									$lga_title = $row['title'];
								}}}
														
															if($image_name == ''){
															if($gender == 'M')
																$user_pic = '../images/default.jpg';
															else
																$user_pic = '../images/default_f.jpg';
														}else{
															
															if(file_exists("../students_image_upload/$image_name") == 1){
																$user_pic = "../students_image_upload/$image_name";
																}else{
																	if($gender == 'M')
																		$user_pic = '../images/default.jpg';
																	else
																		$user_pic = '../images/default_f.jpg';
																}
														}
														
														$delete_token = $student_info_id.','.$class_id.','.$pn;
															$tr .= '
																<tr>
																<td width="1%">
																       '.$sn++.'
																    </td>
																   <td width="1%">
																        <a href="#" onclick="load_student_payment_details('.$student_info_id.')" type="button" class="btn btn-default">
																            <span class="glyphicon glyphicon-th-large" aria-hidden="true"></span> Payment History
																        </a> 
																    </td>
																    <td width="10%" class="text-center">
																    <img class="img img-circle" src="'.$user_pic.'" onclick="load_change_student_pics('.$student_info_id.')" style="width:20px;height:20px"></td>
																	 <td width=""><strong>'.$full_name.'</td>
																	<td class="text-center"  >'.$gender.'</td>
																	<td class="text-center"  >'.$state_name.'</td>
																	<td class="text-center" >'.$lga_title.'</td>
																	<td class="text-center" >'.$religion.'</td>
																	<td class="text-center"  >'.$residential_address.'</td>
																   
																 </tr>';
														
									}
						}
					}else{
										 $error = "no student in the selected class...";
										
									}
				
				$prev = $class_id.','.($pn-1);
				$nex = $class_id.','.($pn+1);
		$paginationCtrls = "";
		if($last != 1)
		{
			if($pn > 1)
			{
				$paginationCtrls .= '	<li> <a href="#" onclick="load_all_student_inclass('.$prev.')" aria-label="Previous"><span aria-hidden="true">&laquo;</span> </a></li>';	
			}
				$paginationCtrls .= ' <li class="active"><a href="#">'.$pn.' <span class="sr-only">(current)</span></a></li>';
			if($pn !=$last)
			{
				$paginationCtrls .= '<li> <a href="#" onclick="load_all_student_inclass('.$nex.')" aria-label="Next"><span aria-hidden="true">&raquo;</span> </a></li>';
			}
		}
		
		
			
			

?>																		
																				<h4><i class="ace-icon fa fa-users home-icon" style="margin-left:20px"></i><a href="#"> Match Found <b><?php echo $class_name.' ('.$num_rows_all_class.')'; ?></b></a></h4>
																				
																			
												
															 <table class="table table-bordered table-hover" style="width:96%;margin:0px 20px 20px">
															<thead>
																 <tr>
																   <td width="1%"><strong>S/N</strong></td>
																   <td width=""><strong>PAYMENT H.</strong></td>
																    <td width=""><strong>PICTURE</strong></td>
																   <td class="text-center" width=""><strong>FULL NAME</strong></td>
																   <td class="text-center" width=""><strong>GENDER</strong></td>
																 <!--  <td class="text-center" width=""><strong>Language</strong></td>
																   <td class="text-center" width=""><strong>Age</strong></td>-->
																   <td class="text-center" width=""><strong>State</strong></td>
																   <td class="text-center" width=""><strong>LGA</strong></td>
																   <td class="text-center" width=""><strong>RELIGION</strong></td>
																   
																   <td class="text-center" width=""><strong>ADDRESS</strong></td>
																  
																 </tr>
																</thead>
																<tbody>
																	<?php echo $tr; ?>
																
																</tbody>
																	
															</table>
																<p>	
													
																<nav  style="margin-bottom:0px">
																			<ul class="pagination pagination-sm">
																					<?php echo $paginationCtrls; ?>
																				</ul>
																			</nav>
															</p>
															<?php echo $error; ?>
															