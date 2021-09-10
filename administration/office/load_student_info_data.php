<?php
	$student_info_id = $_POST['token'];
	include_once('../php/connection.php');
	include_once('../php/student_data.php');
	
?>
<div class="breadcrumb ace-save-state" id="breadcrumbs">			
	<div  class="" id="sub_nav" style="padding-bottom:10px">
<button class="btn btn-primary"  onclick="load_all_student_inclass(<?php echo $class_id; ?>)" style='float:right;margin-right:30px;'><span class="glyphicon glyphicon-backward" style=""></span>  BACK</button>
		<i class="ace-icon fa fa-user home-icon"></i><a href="#"> <?php echo "<strong>$full_name</strong>"; ?> Information</a>
																				
	</div>
</div>
<div class="row">

		<div class="col-md-4" style="">
				<div class="myOp" style="margin:10px;box-shadow:1px 1px 1px 1px #ccc;height:350px;border-radius:0px 0px 10px 10px">
													<div id="user_fullname_hold"><strong><?php echo @date('M-D-Y');?></strong></div>
													
													<div id="staff_user_pics_hold">
														<img class="img img-circle" src="<?php echo $user_pic;?>" onclick="load_change_student_pics(<?php echo $student_info_id ; ?>)" style="cursor:pointer;width:100px;height:100px">
													</div>
													
												<div class="text-center" style=""><h4><?php echo $staff_title. ' '. $full_name; ?></h4> STUDENT</div>
												
												<ul class="nav nav-list ">
															<li class="active option_btn" onmouseup="student_overview(<?php echo $student_info_id ; ?>)">
															<a href="#">
																<i class="menu-icon fa fa-home"></i>
																<span class="menu-text">   Overview </span>
															</a>
															<b class="arrow"></b>
															</li>
															<li class=" option_btn" onmouseup="student_account_setup(<?php echo $student_info_id;?>)">
															<a href="#">
																<i class="menu-icon glyphicon glyphicon-cog"></i>
																<span class="menu-text"  >   Account Settings </span>
															</a>
															<b class="arrow"></b>
															</li>
												</ul>
											</div>
		</div >
		<div class="col-md-8" style="" id="student_account_setup_wrap">
			<?php include_once('student_overview.php');  ?>
			
			
			
			
	</div>
	
</div>