<?php include_once('../php/connection.php'); ?>
	<h4><i class="menu-icon fa fa-desktop"></i> System Setup</h4>
	<div class="breadcrumb ace-save-state" id="breadcrumbs" style="margin:0; border-color: #2e6da4;border-radius:0">
		<div  class="" id="sub_nav" >
			<i class="ace-icon fa fa-user home-icon"></i><a href="#">   <b> Setup system</b></a>
								
		</div>
	</div>
	

								<div class="panel-group" >
									  <div class="panel">
										<div class="panel-heading" onclick="setup('session_wrap','session_caret')" style="padding:2px">
										  <h4 class="panel-title" name="collapseOne"  style='font-size:12px'>
										  	<span class="btn"><span id="session_caret" class="glyphicon glyphicon-minus"></span></span> 
											<a href="#">
												Session
											</a>
										  </h4>
										</div>
										
										  <div class="panel-body" id="session_wrap" style="display:block">
											<?php include_once('system_session_year.php'); ?>
										  </div>
										
									  </div>
									  <div class="panel">
										<div class="panel-heading" onclick="setup('system_users_body','users_caret')" style="padding:2px">
										  <h4 class="panel-title" style='font-size:12px'>
												  <span class="btn "><span id="users_caret" class="glyphicon glyphicon-plus"></span></span> 
											<a  href="#">
											System  Users
											</a>
										  </h4>
										</div>
										
										  <div class="panel-body" id="system_users_body" style="display:none">
											
															<div> <button style="float:right"class="btn btn-default" onclick="add_user()"><span class="glyphicon glyphicon-plus-sign"></span></button></div>
															<div id="system_users">
															<?php include_once('system_users.php');?>
												
															</div>
										  </div>
										</div>
										 <div class="panel ">
										<div class="panel-heading" onclick="setup('subject_collapse','sub_caret')" style="padding:2px">
										  <h4 class="panel-title"  style='font-size:12px'>
												  <span class="btn"><span id="sub_caret" class="glyphicon glyphicon-plus"></span></span> 
											<a  href="#">
												Subjects
											</a>
										  </h4>
										</div>
										  <div class="panel-body" id="subject_collapse" style="display:none">
										
												<?php include_once('system_subjects.php');?>
											
										  </div>
										</div>
									  </div>
				
	
	<div id="abdul_android_pageOverlay"></div>
	<div id="abdul_android_alertBox">
		<div id="abdul_android_boxContent" class="text-center"></div>
		
	</div>