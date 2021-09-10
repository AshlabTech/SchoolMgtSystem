															<h4><i class="menu-icon fa fa-desktop"></i> AdMIN Dashboard</h4>
															<div class="breadcrumb ace-save-state" id="breadcrumbs" style="margin:0">
																				<div  class="" id="sub_nav" >
																				<i class="ace-icon fa fa-user home-icon"></i><a href="#">   <b>Dashboard</b></a>
																				<span style="float:right;font-family:;"><span id="timing"></span><strong><?php echo @date('M-D-Y');?></strong></span>
																				</div>
																		</div>
<?php 
	include_once('staff_summary.php');

?>
	<div class="rows" style="overflow-x:hidden;">
			<div class="col-md-12" style="background-image:url('../images/world.png');height:400px;overflow-y:auto;padding:20px">
						<div class="panel panel-default summary_block" style="background-image:url('../images/summary_bg.png');">
						  <div class="panel-body">
								<h1 style="text-align:right"><?php echo $total_number_of_staff; ?></h1>
								<h4 style="text-align:right"><?php echo 'Total Staffs'; ?></h4>
						  </div>
						</div>
						
						<div class="panel panel-default summary_block" style="background-image:url('../images/summary_bg.png');">
						  <div class="panel-body">
								<h1 style="text-align:right"><?php echo $total_students; ?></h1>
								<h4 style="text-align:right"><?php echo 'Total Students'; ?></h4>
						  </div>
						</div>
						
						<div class="panel panel-default summary_block" style="background-image:url('../images/summary_bg.png');">
						  <div class="panel-body">
								<h1 style="text-align:right"><?php echo $total_number_of_sec; ?></h1>
								<h4 style="text-align:right"><?php echo 'Sec. Staffs'; ?></h4>
						  </div>
						</div>
						<div class="panel panel-default summary_block" style="background-image:url('../images/summary_bg.png');">
						  <div class="panel-body">
								<h1 style="text-align:right"><?php echo $total_number_of_pry; ?></h1>
								<h4 style="text-align:right"><?php echo 'Total Pry Staffs'; ?></h4>
						  </div>
						</div>
						
					
				</div>
			</div>
	
