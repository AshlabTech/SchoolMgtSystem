<?php
	$staff_info_id = $_POST['token'];
	include_once('../php/staff_data.php');

?>

													<h4><i class="menu-icon fa fa-desktop"></i> Staff Information</h4>
																		<div class="breadcrumb ace-save-state" id="breadcrumbs">
																				<div  class="" id="sub_nav" style="padding-bottom:10px">
																				<i class="ace-icon fa fa-home home-icon"></i><a href="#">  Staff Information</a>
																				<button class="btn btn-primary" style="float:right" onmouseup="load_all_staff(1)" ><span  ></span> Back</button>
																				</div>
																		</div>

								<div class="row">
									<div class="col-lg-4">
											<div class="myOp" style="margin:10px;box-shadow:1px 1px 1px 1px #ccc;height:350px;border-radius:0px 0px 10px 10px">
													<div id="user_fullname_hold"><strong><?php echo @date('M-D-Y');?></strong></div>
													
													<div id="staff_user_pics_hold">
														<img class="img img-circle" onclick="load_change_staff_pics(<?php echo $staff_info_id; ?>)"src="<?php echo $user_pic;?>" style="width:100px;100px">
													</div>
													
												<div class="text-center" style=""><h4><?php echo $full_name; ?></h4> STAFF</div>
												
												<ul class="nav nav-list ">
															<li class="active option_btn" onmouseup="staff_overview(<?php echo $staff_info_id; ?>)">
															<a href="#">
																<i class="menu-icon fa fa-home"></i>
																<span class="menu-text">   Overview </span>
															</a>
															<b class="arrow"></b>
															</li>
															<li class=" option_btn" onmouseup="staff_account_setup(<?php echo $staff_info_id; ?>)">
															<a href="#">
																<i class="menu-icon glyphicon glyphicon-cog"></i>
																<span class="menu-text">   Account Settings </span>
															</a>
															<b class="arrow"></b>
															</li>
												</ul>
											</div>
									</div>
									
									<div class="col-lg-8" id="staff_account_setup_wrap">
										<?php include_once('staff_overview.php'); ?>
									</div >
								</div >
															