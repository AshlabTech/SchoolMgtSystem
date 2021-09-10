<?php
	include_once('../php/connection.php');

if (isset($_POST['headsaveer'])) {
	$category = mysqli_real_escape_string($conn,$_POST['header']);
	$name1 = mysqli_real_escape_string($conn,$_POST['staff']);
	$chk = $conn->query('select staff_info_id from staff_info where category = "$category"');
	if ($chk->num_rows>0) {
		$ch = $chk->fetch_assoc();
		$ch_id = $ch['staff_info_id'];
		$up = $conn->query('update staff_info set staff_info_id="0" where staff_info_id ="$ch_id"');
	}

	$up = $conn->query('update staff_info set category="$category" where staff_info_id ="$ch_id"');
	if ($up) {
		echo '<script type="text/javascript">alert("Updated Succeffully"); </script>';
		}else{
			echo '<script type="text/javascript">alert("error update"); </script>';			
		}



}

?>

	<head>		
			
		<link rel="shortcut icon" href="../../images/e_portal.png">
	   <meta name="viewport" content="width=device-width, initial-scale=1" >
		<link rel="stylesheet" href="../../css/bootstrap.css">
		<link rel="stylesheet" href="../../css/bootstrap.min.css">
		<link rel="stylesheet" href="../../css/bootstrap-theme.css">
		<link rel="stylesheet" href="../../css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="../../css/font-awesome-4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="../../css/styles.css">
		
				
				
				<!-- inlcude all javascript files -->
				<script type="text/javascript" src="../../js/jquery-1.10.2.js"></script>
				<script type="text/javascript" src="../js/admin_script.js"></script>
				<script type="text/javascript" src="../js/admin_reg_script.js"></script>
				<script type="text/javascript" src="../js/timing.js"></script>

		
		<style>
			
		</style>

	</head>
<form action="" method="post" id="signupForm1" class="form-horizontal">
        <div class="panel-body">		
        <div class="form-group">
				<label class="col-sm-4 control-label" for="nameOfOrg1">School Head</label>
				<div class="col-sm-5">
					<select name="staff" class="form-control">
						<option value="5"></option>
						<?php
						$staffs = $conn->query('select staff_info_id, first_name, other_name, last_name  from staff_info where status = "1"');
							if ($staffs->num_rows >0) {	
								
								while ($row = $staffs->fetch_assoc()) {
									?>
										<option value="<?= @$row['staff_info_id'] ?>"><?= @$row['first_name'].' '. $row['other_name']. ' '. $row['last_name']?></option>
									<?php
								}
							}
						?>
					</select>
				</div>
			</div>				
			<div class="form-group">
				<label class="col-sm-4 control-label" for="nameOfOrg1">School Head</label>
				<div class="col-sm-5">
					<select name="header" class="form-control">
						<option value="5">Principal</option>
						<option value="6">Head Master/Misstres for Primary</option>
						<option value="4">Head Master/Misstres for Nursery</option>
					</select>
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-9 col-sm-offset-4">
					<button type="submit" name="headsaver" class="btn btn-primary">Save </button>
				</div>
			</div>
        </div>
	</form>