	<div class="form-inline">
	
	<div class="row">
		<div class="col-md-3">
			  <div class="form-group">
				<div class="col-sm-12">
				Subject
				  <input type="text" class="form-control" id="subject_name" placeholder="Subject">
				</div>
			  </div>
		</div>
		<div class="col-md-3">
		 <div class="form-group">
			<div class="col-sm-12">
					Subject Code
			  <input type="text" class="form-control" id="subject_code" placeholder="Subject Code">
			</div>
		</div>
		</div>
		<div class="col-md-3">
				 <div class="form-group">
					<div class="col-sm-12">
						School section
						<select  class="form-control" id="school_section">
															<?php 
																include_once('../php/connection.php');
																$sql_section = "select * from school_section where status = '1'";
																$query_section = mysqli_query($conn,$sql_section);
																$num_rows_section = mysqli_num_rows($query_section);
																	if($num_rows_section > 0){
																		while($section_array = mysqli_fetch_array($query_section)){
																				$section_name = $section_array['section_name'];
																				$school_section_id = $section_array['school_section_id'];
																				$section_name_abr = $section_array['section_name_abr'];
																				echo '<option value="'.$school_section_id.'">'.$section_name.'</option>';
																		}
																	
																	}
															
															?>
							</select>
					 </div>
		 
		</div>
		</div>
		<div class="col-md-3">
				<div class="form-group text-center" style="margin-top:10px">
					<div class="col-sm-12">
					<label></label>
					<button type="submit" class="btn btn-info" onclick="add_subject()">Add subject</button>	
				 </div>
				 </div>
		</div>

</div>
    </div>
	
		<div id="all_sub">
		<?php include_once('all_subjects.php');?>
		</div>
  