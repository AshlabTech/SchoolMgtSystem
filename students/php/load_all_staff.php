<?php 
			include_once('connection.php');
			
							if(isset($_POST["pn"])){
								$pn = $_POST["pn"];
							}else{
								$pn = 1;
							}
						
						if(isset($_POST['sn'])){
						$sn = $_POST['sn'];
					}else{
						$sn=1;
					}
					
					// i get the cout of rows i wish to select from the db
					$countt = "select COUNT(staff_info_id) from staff_info where status='1'";
					$do_query = mysqli_query($conn,$countt);
					
					// whats the total row count? here is it
					$row = mysqli_fetch_row($do_query);
					
					// total row count
					$total_rows = $row[0];
					
					// how many items per page
					
					$item_pp = 5;

					// last page number_format
					$last = ceil($total_rows/$item_pp);

					if($sn < 1){
						$sn = 1;
					}else if($sn > $total_rows){
						$sn = $total_rows;
					}
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
					//get the staff id from the staff_info table
				//get the staff id from the staff_info table
				$sql_all_staff = "select * from staff_info where status='1' ORDER BY first_name LIMIT $limit";
				
				$php_process_sql_all_staff =  mysqli_query($conn,$sql_all_staff) or die(mysqli_error($conn));
				$num_rows_all_staff = mysqli_num_rows($php_process_sql_all_staff);
					if($num_rows_all_staff > 0){
								$n=0;
						while($all_staff_array = mysqli_fetch_array($php_process_sql_all_staff)){
							$staff_info_id = $all_staff_array['staff_info_id'];
							$first_name = $all_staff_array['first_name'];
							$last_name = $all_staff_array['last_name'];
							$other_name = $all_staff_array['other_name'];
							$gender = $all_staff_array['gender'];
							$religion = $all_staff_array['religion'];
							$marital_status = $all_staff_array['marital_status'];
							$date_of_birth = $all_staff_array['date_of_birth'];
							$state_id = $all_staff_array['state_id'];
							$lga_id = $all_staff_array['lga_id'];
							$tribe = $all_staff_array['tribe'];
							$email_address = $all_staff_array['email_address'];
							$phone_number = $all_staff_array['phone_number'];
							$other_phone_number = $all_staff_array['other_phone_number'];
							$residential_address = $all_staff_array['residential_address'];
							$postal_code = $all_staff_array['postal_code'];
							$next_of_kin = $all_staff_array['next_of_kin'];
							$next_of_kin_phone_number = $all_staff_array['next_of_kin_phone_number'];
							$relationship_with_next_of_kin = $all_staff_array['relationship_with_next_of_kin'];
							$next_of_kin_residential_address = $all_staff_array['next_of_kin_residential_address'];
							$next_of_kin_postal_code = $all_staff_array['next_of_kin_postal_code'];
							$highest_qualification = $all_staff_array['highest_qualification'];
							$level = $all_staff_array['level'];
							$school = $all_staff_array['school'];
							$date_obtained = $all_staff_array['date_obtained'];
							$refree = $all_staff_array['refree'];
							$refree_hone_number = $all_staff_array['refree_hone_number'];
							$date_staff_employed = $all_staff_array['date_staff_employed'];
							$image_name = $all_staff_array['image_name'];
							
							
							//staff login_id
							$sql_all_staff_login_id = "select * from  staff_login_info where staff_info_id = '$staff_info_id' and status!='0'";
							$php_process_sql_all_staff_login_id =  mysqli_query($conn,$sql_all_staff_login_id) or die(mysqli_error($conn));
							$num_rows_all_staff_login_id = mysqli_num_rows($php_process_sql_all_staff_login_id);
							$staff_info_login_id_array = mysqli_fetch_assoc($php_process_sql_all_staff_login_id);
							$staff_login_id = $staff_info_login_id_array['staff_login_id'];
							$full_name = $first_name.' '.$last_name.' '.$other_name;
							
							
														if($image_name == ''){
															if($gender == 'M')
																$user_pic = '../staff_image_uploads/default.jpg';
															else
																$user_pic = '../staff_image_uploads/default_f.jpg';
														}else{
															
															if(file_exists("../staff_image_uploads/$image_name") == 1){
																$user_pic = "../staff_image_uploads/$image_name";
																}else{
																	if($gender == 'M')
																		$user_pic = '../staff_image_uploads/default.jpg';
																	else
																		$user_pic = '../staff_image_uploads/default_f.jpg';
																}
														}
							$tr .= '<tr>
										<td>'.$sn.'</td>
										 <td width="10%" class="text-center"><img class="img img-circle" onclick="load_change_staff_pics('.$staff_info_id.')" src="'.$user_pic.'" style="width:50px;height:50px"></td>
										<td>'.$full_name.'</td>
							
										<td class="text-center">'.$staff_login_id.'</td>
										<td class="text-center" style="cursor:pointer">
											<button class="btn btn-primary" onclick="load_view_staff_details('.$staff_info_id.')"><span class="fa fa-eye"></span> view datails</button>
											<button class="btn btn-default"><span class="fa fa-edit"></span> Edit</button>
											<button class="btn btn-danger"><span class="fa fa-trash"></span> Remove</button>
										</td>
										
								</tr>';
								$sn++;
									$n++;
						}
					}
					
		$paginationCtrls = "";
		if($last != 1)
		{
			if($pn > 1)
			{
				$paginationCtrls .= '	<li> <a href="#" onclick="load_all_staff('.($pn-1).','.($sn-($n+$item_pp)).')" aria-label="Previous"><span aria-hidden="true">&laquo;</span> </a></li>';	
			}
				$paginationCtrls .= ' <li class="active"><a href="#">'.$pn.' <span class="sr-only">(current)</span></a></li>';
			if($pn !=$last)
			{
				$paginationCtrls .= '<li> <a href="#" onclick="load_all_staff('.($pn+1).','.($sn).')" aria-label="Next"><span aria-hidden="true">&raquo;</span> </a></li>';
			}
		}
		
		
			
			

?>																<h4><i class="menu-icon fa fa-desktop"></i> All Staff Information</h4>
																		<div class="breadcrumb ace-save-state" id="breadcrumbs">
																				<div  class="" id="sub_nav" style="padding-bottom:10px">
																						<button class="btn btn-info" id="add_new_staff_btn" style="float:right;" onclick="load_add_new_staff_form()" ><span class="fa fa-plus-circle" ></span> Add Staff</button>
																				<i class="ace-icon fa fa-home home-icon"></i><a href="#"> All Staff Information</a>
																				
																				</div>
																		</div>

					
															<table class="table table-stripped table-responsive">
																<thead>
																	<tr>
																		<th>#</th>
																		<th class="text-center"> Image  </th>
																		<th class="text-center">Staff Full name</th>
												
																		<th class="text-center">Staff ID Number</th>
																		<th class="text-center">Action</th>
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