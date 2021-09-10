<?php 
			include_once('connection.php');
											$sql_state = mysqli_query($conn,"SELECT * FROM states");
											if(mysqli_num_rows($sql_state) > 0){
												while($state_array = mysqli_fetch_array($sql_state)){
														$state_id = $state_array['state_id'];
														$state = $state_array['name'];
													
													echo '	<option value='.$state_id.'>'.$state.'</option>';
												}
											}
									?>