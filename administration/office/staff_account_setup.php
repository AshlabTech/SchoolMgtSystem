<img src="../../images/ajax-loader.gif" style="display: none;" id="loader">
<?php
session_start();

	$sn;
	$navs;
	$query;
	$nav_id;
	$num_rows;
	$class_id;
	$class_array;
	$nav_tittle;
	$nav_icon;
	$access_status;
	$staff_info_id;
	$get_assigned_nav;
	$staff_section;
	$nav_function;
	$check_if_activated;
	$check_if_assigned;
	$check_if_assigned_num_rows;
	$check_if_activated_rows;
	
	
																		
	
	$staff_info_id = $_POST['token'];
	include_once('../php/staff_data.php');
	$_SESSION['staff_subject'] = $staff_info_id;
    //echo $staff_info_id;
	$select_section = $conn->query("SELECT section FROM staff_info WHERE staff_info_id = $staff_info_id");
		$staff_section1 = $select_section->fetch_assoc();
	 $staff_section = $staff_section1['section'];
		

?>


<div class="panel panel-default" style="margin:5px 10px">
 
  <div class="panel-body">
    1. Staff Account Status
	<?php 
			$check_if_activated = mysqli_query($conn,"select * from staff_login_info where staff_info_id = '$staff_info_id'  and status = '1'");
			$check_if_activated_rows = mysqli_num_rows($check_if_activated);
				if($check_if_activated_rows > 0){
					echo '<input type="checkbox"  checked style="margin-left:20px" onclick="activate_account('.$staff_info_id.')"> <b style="color:green">Activated</b>';
				}else{
					echo '<input type="checkbox"  onclick="activate_account('.$staff_info_id.')" style="margin-left:20px">  <b style="color:red">Not Activated</b>';
				}
				
	?>
	
  </div>
</div>
<style>
    .border{
        box-shadow:1px 2px 5px #ccc;
        padding:6px;
        text-transform:capitalize;
        display:inline-block;
        margin: 7px 3px;
        border-radius:5px;
    }
</style>
<div class="panel panel-default" style="margin:5px 10px">
 
  <div class="panel-body">
   2.<br>
       <span class="border">KINDER-GATIN <input type="radio" name="section"  <?php if($staff_section  == 5) {echo 'checked'; }?> onclick="(function(){$.post('change_staff_section.php',{id:<?= $staff_info_id ?>,sec:5},function(data){$('#loader').hide(); if(data==200){confirm('section changed succefully');staff_account_setup(<?= $staff_info_id ?>);}else{console.log(data);} }); $('#loader').show()})()" style="margin:0px 20px"></span>
       <span class="border"> Nusery  School <input type="radio" name="section"  <?php if($staff_section  == 1) {echo 'checked';} ?> onclick="(function(){$.post('change_staff_section.php',{id:<?= $staff_info_id ?>,sec:1},function(data){$('#loader').hide(); if(data==200){confirm('section changed succefully');staff_account_setup(<?= $staff_info_id ?>);}else{console.log(data);} }); $('#loader').show()})()" style="margin:0px 20px"></span>
       <span class="border"> Primary School <input type="radio" name="section"  <?php if($staff_section  == 2) {echo 'checked'; }?> onclick="(function(){$.post('change_staff_section.php',{id:<?= $staff_info_id ?>,sec:2},function(data){$('#loader').hide(); if(data==200){confirm('section changed succefully');staff_account_setup(<?= $staff_info_id ?>);}else{console.log(data);} }); $('#loader').show()})()" style="margin:0px 20px"></span>
        <span class="border">Junior Secondary School <input type="radio" name="section" <?php if($staff_section  == 3) {echo 'checked';} ?>  onclick="(function(){$.post('change_staff_section.php',{id:<?= $staff_info_id ?>,sec:3},function(data){$('#loader').hide(); if(data==200){confirm('section changed succefully');staff_account_setup(<?= $staff_info_id ?>);}else{console.log(data);} }); $('#loader').show()})()" style="margin-left:20px"></span>
		<span class="border">Secondary School <input type="radio" name="section" <?php if($staff_section  == 4){echo 'checked'; }?>  onclick="(function(){$.post('change_staff_section.php',{id:<?= $staff_info_id ?>,sec:4},function(data){$('#loader').hide(); if(data==200){confirm('section changed succefully');staff_account_setup(<?= $staff_info_id ?>);}else{console.log(data);} }); $('#loader').show()})()" style="margin-left:20px"></span>
  </div>
</div>
<div class="panel panel-default" style="margin:5px 10px">
 
  <div class="panel-body">
   3. Assign this staff to class
		<?php 
									/*	if($staff_section  == 1)
											$query = mysqli_query($conn,"select * from classes where school_section_id = '1' or school_section_id = '2'") or die(mysqli_error($conn));
										else*/
											$classes_to_assign_subjectT = [];
											$query = mysqli_query($conn,"select * from classes where school_section_id = '$staff_section' ") or die(mysqli_error($conn.' line 74'));
												//$query = mysqli_query($conn,"select * from classes where school_section_id = '3' or school_section_id = '4'") or die(mysqli_error($conn));
											$sn = 1;
											echo '<table class="table table-bordered">';
											while($class_array = mysqli_fetch_array($query)){
												$class_id = $class_array['class_id'];
												$class = $class_array['class_name'];
												//$objT = .':'.;
												$classes_to_assign_subjectT[$class_id] = $class;
												//check if the class is already assign but status = 1			
													$check_if_assigned = mysqli_query($conn,"select * from staff_classes where staff_info_id = '$staff_info_id' and class_id = '$class_id' and status = '1'");
													$check_if_assigned_num_rows = mysqli_num_rows($check_if_assigned);
														
												echo '<tr><td>'.$sn.'</td>';
												echo '<td>'.$class.'</td>';
												if($check_if_assigned_num_rows > 0)
													echo ' <td><input type="checkbox" checked onclick="assign_classes('.$staff_info_id.','.$class_id.')"  value="'.$class_id.'"></td>';
												else
													echo ' <td><input type="checkbox"  onclick="assign_classes('.$staff_info_id.','.$class_id.')"  value="'.$class_id.'"></td>';
												
												
												echo '</tr>';
												$sn++;
										}
											echo '</table>';
									?>
	
  </div>
</div>

<!-- Container that hold subject that has been added for the staff -->
<?php 
//echo json_encode(json_decode(json_encode($classes_to_assign_subjectT),true));
if (!empty($classes_to_assign_subjectT)) {
	$specified_class = json_encode(json_decode(json_encode($classes_to_assign_subjectT),true));
	//$specified_class = str_replace(' ', '', preg_replace('/[{}\[\]"]/', '',json_encode($classes_to_assign_subjectT)));
}else{
	$specified_class ='';
}
//echo $specified_class;
//if($staff_section  == 2){
	echo '<div class="panel panel-default" style="margin:5px 10px;display:block">
			<div class="panel-body" id="mySubjects">';
 
					include_once('../php/assigned_subjects.php');
			
		echo '</div></div>';

//}
?>


<!-- Container that hold subject that can be added-->
<?php 
	echo '<div class="panel panel-default" id="subss" style="margin:5px 10px;display:">
  <div class="panel-body" id="load_subjects_to_assign">';
	include_once('load_subjects_to_assign.php');
	
	echo '</div></div>';

?>

<!-- Staff Access Container -->
<div class="panel panel-default" id="staff-access-panel" style="margin:5px 10px;">
	<div class="panel-body" id="staff-access-body-panel">
		<h4>Staff Previlages Or Access</h4>
		<p id="access_output"></p>
		<table class="table  table-responsive">
		<?php 
																$get_assigned_nav = mysqli_query($conn,"select * from nav order by sort asc") or die(mysqli_error($conn));
																$num_rows = mysqli_num_rows($get_assigned_nav);
																	if($num_rows > 0){
																		while($navs = mysqli_fetch_array($get_assigned_nav)){
																			$nav_id = $navs['id'];
																			$nav_tittle = $navs['nav_tittle'];
																			$nav_function = $navs['nav_function'];
																			$nav_icon = $navs['nav_icon'];
																				
																				$check_staff_access =  mysqli_query($conn,"select * from staff_access where staff_info_id = '$staff_info_id' and nav_id = '$nav_id' and status ='1'") or die(mysqli_error($conn));
																				$check_staff_access_num_rows =  mysqli_num_rows($check_staff_access);
																					if($check_staff_access_num_rows > 0){
																							$access_status = '<span id="nav_'.$nav_id.'" class="glyphicon glyphicon-ok  text-success" onclick="remove_staff_access('.$staff_info_id.','.$nav_id.')"></span>';
																					}
																					else{
																						
																						$access_status = '<span id="nav_'.$nav_id.'" class="glyphicon glyphicon-remove  text-danger" onclick="add_access_toStaff('.$staff_info_id.','.$nav_id.')"></span>';
																					}
																			echo '
																				<tr>
																					<td width="90%">
																						<i class="menu-icon '.$nav_icon.'"></i>
																					
																						<span class="menu-text">   '.$nav_tittle.' </span>
																					</td>
																					<td class="text-center "  style="cursor:pointer">
																						'.$access_status.'
																					</td>
																				<tr>
																			
																			';
																		}
																	}
															
															?>
		</table>
	
	</div>
</div>
<label for="section"> Category</label>
	
						<select id="staff_category" class="form-control add_new_staff_form_input">
							<option value="">-- Category --</option>
							<option value="1">Administrator</option>
							<option value="2">Bursar</option>
							<option value="3">Examiner</option>
							<option value="4">Teacher</option>
							<option value="4">Head Master</option>
							<option value="4">Head Mistress</option>
							<option value="4">Director</option>														
						</select>