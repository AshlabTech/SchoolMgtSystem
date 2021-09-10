<?php
	session_start();
?> 
<?php
	$staff_info_id = $_POST['token'];
	include_once('../php/connection.php');
	include_once('../php/staff_data.php');
	$_SESSION['staff_infoid_4passport'] = $staff_info_id;
	
?>

<div class="panel " >
	<div class="panel-heading" style="background-color: #337ab7;color:#fff;border-radius:0px">
    <h3 class="panel-title"><?php echo $full_name;?></h3>
  </div>
  <div class="panel-body">
		<div id="staff_user_pics_hold">
			<img id="staff_picture" class="img img-circle" src="../staff_image_uploads/<?php echo $image_name;?>"  style="cursor:pointer;width:100px;100px">
		</div>
		  <div class="form-group">
			<label for="">Upload</label>
			<input type="file" id="staffpassport" class="form-control" onchange="change_staff_picture(<?php echo $staff_info_id ; ?>)">
			
		  </div>
		   <div class="form-group">
				<!--<button class="btn btn-primary" onclick="change_staff_picture(<?php echo $staff_info_id ; ?>)">Change Picture</button>-->
				<button class="btn btn-default" onclick="myAlert.close(<?php echo $staff_info_id ; ?>)">close</button>
			
		  </div>
  </div>
 
</div>