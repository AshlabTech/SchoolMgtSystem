<div style="margin:0" >
														<center><img src="../../images/e_portal.png" style="width:60px;height:60px;margin-top:10px"></center>
														<ul class="nav nav-list" style="margin-top:10px">
																	<li class="active option_btn" onmouseup="load_staff_dashboard(<?php echo $staff_info_id; ?>)">
																	<a href="#">
																		<i class="menu-icon fa fa-tachometer"></i>
																		<span class="menu-text">     Dashboard </span>
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
																			if($check_staff_access_num_rows > 0){
																				echo '
																						<li class=" option_btn" onmouseup="'.$nav_function.'">
																						<a href="#">
																							<i class="menu-icon '.$nav_icon.'"></i>
																							<span class="menu-text">   '.$nav_tittle.' </span>
																						</a>
																						<b class="arrow"></b>
																						</li>
																				';
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