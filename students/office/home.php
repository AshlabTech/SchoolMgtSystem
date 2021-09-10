		<div class="row" style="margin-top:0;">
											<div class="col-lg-2 myOp" style="margin:0;padding-right:0">
												<div style="margin:0" >
												<ul class="nav nav-list">
															<li class="active option_btn" onmouseup="load_staff_dashboard()">
															<a href="#">
																<i class="menu-icon fa fa-tachometer"></i>
																<span class="menu-text">   Dashboard </span>
															</a>
															<b class="arrow"></b>
															</li>
															<?php 
															
															
																$get_assigned_nav = mysqli_query($conn,"select * from nav order by sort asc") or die(mysqli_error($conn));
																$num_rows = mysqli_num_rows($get_assigned_nav);
																	if($num_rows > 0){
																		while($navs = mysqli_fetch_array($get_assigned_nav)){
																			$nav_id = $navs['id'];
																			$nav_tittle = $navs['nav_tittle'];
																			$nav_function = $navs['nav_function'];
																			$nav_icon = $navs['nav_icon'];
																			
																			$check_staff_access =  mysqli_query($conn,"select * from staff_access where staff_info_id = '$staff_info_id' and nav_id = '$nav_id' and status ='1'") or die(mysqli_error($conn));
																			$check_staff_access_num_rows =  mysqli_num_rows($check_staff_access);
																			if($check_staff_access_num_rows > 0 or $staff_status=='4'){
																				echo '
																						<li class=" option_btn" onmouseup="'.$nav_function.'">
																						<a href="#">
																							<i class="menu-icon '.$nav_icon.'"></i>
																							<span class="menu-text">   '.$nav_tittle.' </span>
																						</a>
																						<b class="arrow"></b>
																						</li>
																				';
																			}else{
																				
																			}
																			
																		}
																	}
															
															?>
													
															<li class="option_btn">
															<a href="#">
																<i class="menu-icon glyphicon glyphicon-off"></i>
																<span class="menu-text" style="color:red" onclick="window.location.assign('../php/logout.php')"> Logout </span>
															</a>
															<b class="arrow"></b>
														</li>
													</ul>
													
														
														
															</div>
															</div>
															<div class="col-lg-10" id="display_content">
													
															<?php 
															
																include_once('load_staff_dashboard.php');
															?>
													</div>
												</div>
												
										
										
									