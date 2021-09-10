<?php 
			include_once('../php/connection.php');
?>
	<h4><i class="menu-icon fa fa-desktop"></i> User Settings</h4>
		<div class="breadcrumb ace-save-state" id="breadcrumbs" style="margin:0">
			<div  class="" id="sub_nav" >
				<i class="ace-icon fa fa-cog home-icon"></i><a href="#">   <b>User Settings</b></a>
				
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
				<form class="form-inline" style="margin-top:0px">
					<fieldset>
					<hr/>
					  <div class="form-group">
						<label for="inputEmail3" class=" control-label">Class </label>
						
								<select  class="form-control " id="class">
								<option value=''>--select class--</option>
									<?php 
											$query = mysqli_query($conn,"select * from classes") or die(mysqli_error($conn));
											while($class_array = mysqli_fetch_array($query)){
												$class_id = $class_array['class_id'];
												$class = $class_array['class_name'];
												echo '<option value="'.$class_id.'">'.$class.'</option>';
											}
									?>
							</select>
						
					  </div>
					 
					<fieldset>
					</form>
		</div>
	</div>