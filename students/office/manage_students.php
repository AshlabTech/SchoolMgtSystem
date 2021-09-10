<div class="row" id="admin_body_content">
												<div class="col-lg-2" style="" id="myOp">
												<ul class="nav nav-list">
															<li class="option_btn">
															<a href="#">
																<i class="menu-icon fa fa-tachometer"></i>
																<span class="menu-text">   Dashboard </span>
															</a>
															<b class="arrow"></b>
															</li>
															<li class="active option_btn">
															<a href="#">
																<i class="menu-icon fa fa-tachometer"></i>
																<span class="menu-text"> All Classes </span>
															</a>
															<b class="arrow"></b>
														</li>
															<li class="option_btn">
															<a href="#">
																<i class="menu-icon fa fa-picture-o"></i>
																<span class="menu-text"> Dashboard </span>
															</a>
															<b class="arrow"></b>
														</li>
														<li class="option_btn">
															<a href="#">
																<i class="menu-icon fa fa-picture-o"></i>
																<span class="menu-text"> Dashboard </span>
															</a>
															<b class="arrow"></b>
														</li>
														<li class="option_btn">
															<a href="#">
																<i class="menu-icon fa fa-picture-o"></i>
																<span class="menu-text"> Dashboard </span>
															</a>
															<b class="arrow"></b>
														</li>
															<li class="option_btn">
															<a href="#">
																<i class="menu-icon fa fa-desktop"></i>
																<span class="menu-text"> Dashboard </span>
															</a>
															<b class="arrow"></b>
														</li>
													</ul>
													
													</div>
													<div class="col-lg-10" style="" id="display_content">
															<?php 
															
															if($_GET['students'] == 'info')
																include_once('../php/all_students.php'); 
															else{
																
															}
															
															?>
													</div>
										
									
									</div>