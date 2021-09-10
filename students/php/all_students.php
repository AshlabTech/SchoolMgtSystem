<?php 
			include_once('connection.php');
			
							
					
				$sql_all_class = "select * from classes";
				
				$query_all_class =  mysqli_query($conn,$sql_all_class) or die(mysqli_error($conn));
				$num_rows_all_class = mysqli_num_rows($query_all_class);
					if($num_rows_all_class > 0){
						$sn = 1;
						while($array_all_class = mysqli_fetch_array($query_all_class)){
							$class_name = $array_all_class['class_name'];
							$description = $array_all_class['description'];
							$class_id = $array_all_class['class_id'];
							
									$sql_all_class2 = "select * from student_classes where class_id = '$class_id' and (status = '1' or status = '2')";
									$query_all_class2 =  mysqli_query($conn,$sql_all_class2) or die(mysqli_error($conn));
									$num_rows_all_class2 = mysqli_num_rows($query_all_class2);
										if($sn%2 == 0) 	$typ = "default";	else 	$typ = "primary";
														
																$tr .= '
														
																 	<div class="panel panel-default summary_block" style="background-image:url(../images/summary_bg.png);width:200px;cursor:pointer;border-right:4px solid #B8B8B8">
																	 
																	 <div class="panel-body" onclick="load_all_student_inclass('.$class_id.',1)">
																			<h1 style="text-align:right">'.$num_rows_all_class2.'</h1>
																			<h4 style="text-align:right">'.$class_name.'</h4>
																			<h6><marquee>'.$description.'</marquee></h6>
																	  </div>
																	</div>';
														
						}
					}
					
		
		
		
			
			
				
?>													<h4><i class="menu-icon fa fa-desktop"></i> All STUdents Information</h4>
												<div class="breadcrumb ace-save-state" id="breadcrumbs">
																				<div  class="" id="sub_nav" style="padding-bottom:10px">
																						<button class="btn btn-info" id="add_new_staff_btn" style="float:right;" onclick="load_add_new_student_form()" ><span class="fa fa-plus-circle" ></span> Add Student</button>
																				<i class="ace-icon fa fa-home"></i><a href="#"> Home  <b></b></a>
																				
																				</div>
																		</div>
														
																 	<div class="panel panel-default" >
																	 
																	 <div class="panel-body text-center" style="background-image:url('../images/world.png');">
																			<?php echo $tr; ?>
																	  </div>
																	</div>
																	
															
															