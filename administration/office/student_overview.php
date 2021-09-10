<?php
	$student_info_id = $_POST['token'];
	include_once('../php/connection.php');
	include_once('../php/student_data.php');
	
?>
<table class="table table-bordered" style="border-bottom:1px solid #ccc;margin:0">
				<tr>
					<td>Surname : <?php echo $first_name;?></td>
					<td>Last Name : <?php echo $last_name;?></td>
					<td>Other Name : <?php echo $other_name;?></td>
				</tr>
				<tr>
					<td>Gender : <?php echo $gender;?></td>
					<td>Religion : <?php echo $religion;?></td>
					<td>Age : <?php echo $age.' yrs old';?></td>
				</tr>
				<tr>
					<td>State of origin : <?php echo $state_name;?></td>
					<td>LGA : <?php echo $lga_title;?></td>
					<td>Tribe : <?php echo $tribe;?></td>
				</tr>
				<tr>
					<td>Sponsor Name : <?php echo $guidian_name;?></td>
					<td>Sponsor Tel: <?php echo $guidian_phone_number;?></td>
					<td>Sponsor Mobile: <?php echo $guidian_other_phone_number;?></td>
				</tr>
				<tr>
					<td>Sponsor Occupation : <?php echo $guidain_occupation;?></td>
					<td>Sponsor Address: <?php echo $guidian_address;?></td>
					<td>Date Of Enrolment: <?php echo $date_enrolled;?></td>
				</tr>
				<tr>
					<td colspan="3" class="text-center">
					<button type="button" class="btn btn-primary"><span class="glyphicon glyphicon-download-alt"></span> Download</button>
					<button type="button" class="btn btn-primary" onclick="load_edit_student_form(<?php echo $student_info_id;?>)"><span class="glyphicon glyphicon-edit" ></span>  Edit </button>
					<button class="btn btn-success" id="promote_student_btn" style="float:;" onclick="promote_student(<?php echo $student_info_id; ?>)" ><span class="glyphicon glyphicon-ok promote_student_btn" ></span>  Promote</button>
			
					
				<?php 
						
					if($class_id == 9 or $class_id == 12 or $class_id == 15){
						echo '<button class="btn btn-danger" id="demote_student_btn" style="float:;" onclick="promote_student('.$student_info_id.')" ><span class="glyphicon glyphicon-remove" ></span>  Remove Student</button>';
					}
				?>
					</td>
				</tr>
			</table>