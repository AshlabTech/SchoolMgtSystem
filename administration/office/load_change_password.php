<?php
	$staff_info_id = $_POST['token'];
	include_once('../php/staff_data.php');

?>
<div id="add_edit_staff_form">
	<div class="form-group">
		<p class="text-center"><input type="password" class="form-control" id="new_password" placeholder="New Password"></p>
		<p class="text-center"><input type="password" class="form-control" id="confirm_new_password"  placeholder="Confirm New Password"></p>
	</div>
	<div ><button class="btn btn-lg btn-primary" onclick="change_password(<?= $staff_info_id;?>)"> Change Password</button></div>
		<hr/>
	<h2 id="loading_status"> </h2>
	
</div>