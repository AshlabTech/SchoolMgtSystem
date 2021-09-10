															<h4><i class="menu-icon fa fa-desktop"></i> Manage Payment</h4>
															<div class="breadcrumb ace-save-state" id="breadcrumbs" style="border-bottom:1px solid #888;border-radius:0px;margin:20px 0px">
																				<div  class="" id="sub_nav" style="padding:0" >
																				
																				<a href="#" class="ppp2" ><i class="ace-icon fa fa-home home-icon"></i>  <b>New student</b></a>
																				
				
																				</div>
																		</div>
							<div class="row" id = "payment_details_wrap">
								<div class="col-lg-12" >
								
											<div class="form-inline">
											 <div class="form-group col-lg-2" >
											   <label for="section">Section</label><br>
									<select  id="section" class="fee_search_input" onchange="load_section_payment(this.value)">
												<?php 
													include_once('../php/connection.php');
													$sql_section = "select * from school_section where status = '1'";
													$query_section = mysqli_query($conn,$sql_section);
													$num_rows_section = mysqli_num_rows($query_section);
														if($num_rows_section > 0){
															while($section_array = mysqli_fetch_array($query_section)){
																	$section_name = $section_array['section_name'];
																	$school_section_id = $section_array['school_section_id'];
																	$section_name_abr = $section_array['section_name_abr'];
																	echo '<option value="'.$school_section_id.'">'.$section_name_abr.'</option>';
															}
														
														}
												
												?>
									</select>
									
									 </div>
									<div class="form-group col-lg-2" >
									<label for="section">Category</label><br>
									<select style=""  id="category" class="fee_search_input" onchange="payment_gender(this.value)">
											 <option value="2">New</option>
												  <option value="1">Old</option>	
									</select>
									
									 </div>
										
										  <div class="form-group  col-lg-2" style="" >
											<label for="payment_description">Description</label><br>
											<input type="text" class="fee_search_input" id="payment_description" placeholder="Description">
										  </div>
										  <div class="form-group col-lg-2">
											<label for="payment_amount">Amount</label><br>
											<input type="text" class="fee_search_input" id="payment_amount" placeholder="Amount">
										  </div>
										   <div class="form-group col-lg-2" id="payment_gn">
										   <label for="gender">Gender</label><br>
												  <select style="" id="gender" class="fee_search_input">
												  <option value="M">Male</option>
												  <option value="F">Female</option>
												
										
													</select>
											  </div>
											  	   <div class="form-group col-lg-2" id="add_payment_output">
														<label for=""></label><br>
														<button type="submit" class="btn btn-info" onclick="add_payment_details()">Add Payment</button>
												</div>
										</div>
								</div>
							</div>
							
																
							<div class="row">
								<div class="col-lg-12" id="payment_details_display">
									<?php include_once('../php/load_section_payment.php'); ?>
								</div>
							</div>
							
							