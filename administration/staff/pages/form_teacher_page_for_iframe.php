<?php
session_start(); 
?>
	<head>
		<title> Staff Portal </title>

		
	   <meta name="viewport" content="width=device-width, initial-scale=1" >
 <link href="../../../css/basic.css" rel="stylesheet" />
		<link rel="stylesheet" href="../../../css/bootstrap.css">
		<link rel="stylesheet" href="../../../css/bootstrap.min.css">
		<link rel="stylesheet" href="../../../css/bootstrap-theme.css">
		<link rel="stylesheet" href="../../../css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="../../../css/font-awesome-4.7.0/css/font-awesome.min.css">
	
		
		<!-- inlcude all javascript files -->
				<script type="text/javascript" src="../../../js/jquery-1.10.2.js"></script>

				
		<style>
			
		</style>
<style>
.trt{
		border-bottom: 1px solid #aaa;
		color:black;
		font-size: 0.9em;
		font-weight: bold;
		font-family: arial;
		margin: 1px !important;
	}
		.row{	
			margin-left: 5px;
		}
		input[type='radio']{
			margin: 3px;
			cursor: pointer;
		}
		.bd:hover{
			background: #555 !important;
			color:white;
			box-shadow: 2px 2px 3px #aaa;
		}
	.co{
		font-size: 0.9em;
		font-family: arial;
		padding: 2px;
	}
	.bd:nth-child(2n+2){
		background: #eee; 
	}
	.bd{
		margin-right: 5px;
	}
</style>
	</head>
	<?php 
	if(isset($_SESSION['staff_info_id'])){
	 	$type = $_SESSION['type'];
			if($type == 1){
			//	header('location:../staff');
			}
			$staff_info_id = $_SESSION['staff_info_id'];
		
	}
	else{
	    ?>
	    <script>
	       parent.location.reload();
	    </script>
	    
	    <?php
	  
	}
	
	
	?>
<?php
include '../php/connection.php';
	$get_session = mysqli_query($conn, "select * from session WHERE status = '1'");
	$current_session = mysqli_fetch_array($get_session);
	$current_session_id = $current_session['section_id'];


	if(isset($_SESSION['staff_info_id'])){
		$staff_info_id = $_SESSION['staff_info_id'];
	}
	else{
		header('location:../? token=2');
	}
	include_once('../php/staff_data.php');
$class_id = '';
$term_id = '';
$stid =0;
?>
<?php
	
		//$staff_info_id = $_POST['token'];
	include_once('../../php/staff_data.php');

	

		
			
			
	
	?>

	<h4><i class="menu-icon fa fa-desktop"></i> Form Teacher</h4>
		<div class="breadcrumb ace-save-state" id="breadcrumbs" style="margin:0">
			<div  class="" id="sub_nav" >
				<i class="ace-icon fa fa-cog home-icon"></i><a href="#">   <b>form teacher</b></a>
				
		</div>
	</div>
 <div class="row" style="color:#067;margin:20px;" id="access_options_wrap">
	<div class="col-lg-12">
		<form class="form-horizontal" method="POST" action="">
			  <div class="form-group">
				<label for="inputEmail3" class="col-sm-1 control-label">CLASS</label>
				<div class="col-sm-3">
				    <input type="text" value="" id="classname" name="classname" style="display: none;">
				    <input type="text" value="" id="termname" name="termname" style="display: none;">
				<select required="" class='form-control' name="class_id" onchange="(function(){document.getElementById('classname').value= document.getElementById('class_idd').options[document.getElementById('class_idd').selectedIndex].text; })()"  style="margin:5px;color:#000" id="class_idd">
					<option value="">-- SELECT CLASS -- </option>
					<?php 
								//check if the class is already assign but status = 1			
						$check_if_assigned = mysqli_query($conn,"select * from staff_classes where staff_info_id = '$staff_info_id'  and status = '1'") or die(mysqli_error($conn));
						$check_if_assigned_num_rows = mysqli_num_rows($check_if_assigned);
							if($check_if_assigned_num_rows > 0){
							$mm = 1;
								while($class_rows = mysqli_fetch_array($check_if_assigned)){
									$class_id=$class_rows['class_id'];
										
											
											$class_name= "select * from classes where class_id='$class_id'";
											$class_name_run =  mysqli_query($conn,$class_name) or die(mysqli_error($conn));
											$class_name_rows = mysqli_fetch_array($class_name_run);
												
												 $class_n = $class_name_rows['class_name'];
										
										echo '<option value='.$class_id.'>'.$class_n.'</option>';
										
								}
							}
					?>
				</select>
			</div>
		<label for="inputEmail3" class="col-sm-1 control-label">Term</label>
		<div class="col-sm-3">
		<select class='form-control' name="term" style="margin:5px;"  id="term_id" onchange="(function(){document.getElementById('termname').value= document.getElementById('term_id').options[document.getElementById('term_id').selectedIndex].text; })()">
			<option value="1">first term</option>
			<option value="2">second term</option>
			<option value="3">third term</option>
			<?php 
			/*	//$term = mysqli_query($conn,"select * from term") or die(mysqli_error($conn));
				$term = $conn->query("select * from term") or die(mysqli_error($conn));
				while($term_array = $term->fetch_assoc()){
				    		$term = $term_array['term'];
							$id = $term_array['id'];
							$description = $term_array['description'];
							echo '<option value="'.$id.'" selected>'.$description.'</option>';						
				}*/
				
					
			?>
			
		
			
		</select>
		</div>	
		<div class="col-sm-3">
			<input type="submit" name="enterT" value="load" class="btn btn-info btn-sm mt-3">
		</div>										
	  </div>
</form>
		

	</div>
	<div class="row">
			<div class="col-sm-6 col-md-6 col-lg-6" style="min-width: 400px;">
					<div id="">
						<div style="position: absolute;top:-21.3%; left: 0; width: 100%;  overflow-y: none;"><div class="loader" id="load1" style="margin-left: 0px; margin-right: 0px; display:none;"></div></div>
						<div id="class-cont" style="color:#666;"><p style="" id="showClass"></p>
						
						<div style="border: 1px solid #eee; padding: 30px;">
						    <?php
								        if (isset($_POST['enterT'])) {
								        	$classname =$_POST['classname'];
											$termname =$_POST['termname'];
										echo '<h4 style="margin:0px;padding:0px;">'.$classname.' '.ucwords($termname).'</h4>';
							
								        }
								    
								    ?>
								<div class="headerC" style="">
								    
								<!--<button class="btn btn-info" onclick="saveResultBtn();">save</button>-->
							</div>
							<div class="bodyC">
								<table class="table table-condensed table-striped table-hover" id="table">
									<thead style="background: #fff !important;">
										<th style="background: #fff !important;">Student ID</th>
										<th style="background: #fff !important;" colspan="10">Name</th>
									</thead>
									<tbody>
										<?php
										//echo $bid;
										if (isset($_POST['enterT'])) {
											$class_id =$_POST['class_id'];
											$term_id =$_POST['term'];
										


$get_student_in_class = mysqli_query($conn,"SELECT * FROM student_classes as s INNER JOIN student_info as i on i.student_info_id = s.student_info_id WHERE s.class_id = '$class_id' AND s.session_id ='$current_session_id'")or die(mysqli_error($conn));
											
											$num_rows_all_class = mysqli_num_rows($get_student_in_class);

									
											if (!$get_student_in_class) {
												echo "<script>alert(".mysqli_error($conn).");</script>";
											}
											if($num_rows_all_class>0){
												$n =1;
												//$studentIds = array();
												while($row = $get_student_in_class->fetch_assoc()){
													$stid = $row['student_info_id'];
													//$studentIds[] = $stid;
												?>		
													<tr rel="<?php echo $stid; ?>">
														<td><?php echo $row['adm_no'];?></td>
														<td><?php echo $row['first_name'].' '.$row['other_name'].' '. $row['last_name'];?></td>
													</tr>
													<?php
											}
											?>	
											<script>
												all_student_id = <?php echo json_encode($studentIds);?>;
											</script>
											<?php

											
												// mysqli_error($conn);
											}else{
												//echo "string";
											}

										}
										?>
									</tbody>
								</table>
								
							</div>

						</div>
						</div>
					</div>
				</div>
			<div class="col-sm-6 col-xs-12 col-md-6 col-lg-6" style="border: 1px solid #eee; padding: 4px; min-width: 345px;">
				<div id="traits" style="width: 100%;">
					<button class="btn btn-info" onclick="saveResultBtn();">save</button>
					<div style="margin: 10px 0px 0px 10px; border:1px solid #eee; box-shadow: 0px 1px 1px #ccc;">
						<select style=""></select>
					</div>	

				</div>
				
			</div>	
		
	</div>

</div>

 <div class="row" style="color:#067;margin:0px;">
	<div class="col-lg-12" id="accessment_sheet">

	
		

	</div>

</div>

<script type="text/javascript">
	
	$("#table tbody tr").click(function(){
	
		$(this).addClass('selected').siblings().removeClass('selected');    
		//var value=$(this).find('td:first').html();
		var value = $(this).attr('rel');
		$.post('traits.php',{stid:value, tid:'<?php echo $term_id; ?>', sid:'<?php echo $current_session_id; ?>', bid:'<?php echo $class_id; ?>'}, function(data){
			$('#traits').html(data);
		});
		
	});
</script>
