
<?php 

		
?>
<div class="col-lg-4"></div>
<div class="col-lg-4">
						
					  <div class="form-group">
						<label for="highest_qualification">Assign this staff to class</label>
							<?php 
											$query = mysqli_query($conn,"select * from classes where school_section_id = '3' or school_section_id = '4'") or die(mysqli_error($conn));
											$sn = 1;
											echo '<table class="table table-bordered">';
											while($class_array = mysqli_fetch_array($query)){
												$class_id = $class_array['class_id'];
												$class = $class_array['class_name'];
												echo '<tr><td>'.$sn.'</td>';
												echo '<td>'.$class.'</td>';
												echo ' <td><input type="checkbox" onclick="alert('.$staff_info_id.')" value="'.$class_id.'"></td></tr>';
												$sn++;
											}
											echo '</table>';
									?>
					  </div>

</div>
<div class="col-lg-4"></div>