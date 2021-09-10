<?php 
session_start();
$specified_class_encode = base64_encode($specified_class);
		include_once('../php/connection.php');
		$session = $conn->query("SELECT * FROM session WHERE status = '1'") or die(mysqli_error($conn));
		$term = $conn->query("SELECT * FROM term WHERE status = '1'") or die(mysqli_error($conn));
		if ($session->num_rows>0) {
			if ($term->num_rows>0) {
				$ss1 = $session->fetch_assoc();
				$tt1 = $term->fetch_assoc();
	 			$session_id = $ss1['section_id'];
	 			$term_id = $tt1['id'];
			
			}else{
				echo "term not set";
				exit();
			}	
		}else{
			echo "session not set";
			exit();
		}


		$staffid = $_SESSION['staff_subject'];
						if(isset($_POST["pn"])){
								$pn = $_POST["pn"];
							}else{
								$pn = 1;
							}
						
					if(isset($_POST['sn'])){
						$sn = $_POST['sn'];
					}else{
						$sn=1;
					}
					
					
					
					// i get the cout of rows i wish to select from the db
					$countt = "select COUNT(id) from  subject where school_section	='$staff_section' and status = '1'";
					$do_query = mysqli_query($conn,$countt);
					
					// whats the total row count? here is it
					$row = mysqli_fetch_row($do_query);
					
					// total row count
					$total_rows = $row[0];
					
					// how many items per page
					
					$item_pp = 20;

					// last page number_format
					$last = ceil($total_rows/$item_pp);

					//non - negative constraint on the last page number 
					if($sn < 1){
						$sn = 1;
					}else if($sn > $total_rows){
						$sn = $total_rows;
					}
					
					if($last < 1)
					{
						$last = 1;
					}
					
						if($pn < 1)
						{
							$pn = 1;
						}
						else if($pn > $last)
						{
							$pn = $last;
						}
						
						$limit = ($pn - 1) * $item_pp.','.$item_pp;
				
		$sql_all_subject= "select * from subject where school_section	= '$staff_section' and status ='1' LIMIT $limit";
		//$sql_all_subject= "select * from subject where school_section	= 3 OR school_section= 4 and status ='1' LIMIT $limit";
		$php_process_sql_all_subject =  mysqli_query($conn,$sql_all_subject) or die(mysqli_error($conn));
		$num_rows_all_subject = mysqli_num_rows($php_process_sql_all_subject);
			if($num_rows_all_subject > 0){
				$n=0;
					while($all_subject_array = mysqli_fetch_array($php_process_sql_all_subject)){
						$subject_id = $all_subject_array['id'];
						$subject = $all_subject_array['subject'];
						$subject_code = $all_subject_array['subject_code'];
						$school_section = $all_subject_array['school_section'];
						
						$get_section_name  = mysqli_query($conn,"select * from school_section where school_section_id = '$school_section'") or die(mysqli_error($conn));
						$row = mysqli_fetch_array($get_section_name);
						$section_name = $row['abr'];
					$trr .= '<tr>
						<td>'.$sn.'</td>
						<td width="" class="" style="text-transform:uppercase">'.$subject.'</td>
						<td style="text-transform:uppercase">'.$subject_code.'</td>										
						<td style="text-transform:uppercase">'.$section_name.$subject_id.' Classes</td>										
						<td style="text-transform:uppercase;color:#06c;font-weight:bold;cursor:pointer" onclick="assign_this_subject_out('.$staffid.','.$subject_id.','.$session_id.','.$term_id.',\''.$specified_class_encode.'\')">ADD</td>										
				</tr>';
				$sn++;
				$n++;
					
					}
		
		}
					
		$paginationCtrls = "";
		if($last != 1)
		{
			if($pn > 1)
			{
				$paginationCtrls .= '	<li> <a href="#" onclick="load_sub_to_assign('.($pn-1).','.($sn-($n+$item_pp)).')" aria-label="Previous"><span aria-hidden="true">&laquo;</span> </a></li>';	
			}
				$paginationCtrls .= ' <li class="active"><a href="#">'.$pn.' <span class="sr-only">(current)</span></a></li>';
			if($pn !=$last)
			{
				$paginationCtrls .= '<li> <a href="#" onclick="load_sub_to_assign('.($pn+1).','.($sn).')" aria-label="Next"><span aria-hidden="true">&raquo;</span> </a></li>';
			}
		}
							

?>															
<table class="table table-bordered  table-hover" >	

					<tr>
						<td>sn</td>
						<td  class="text-center">Subject</td>
						<td style="text-transform:uppercase">Subject Code</td>											
						<td style="text-transform:uppercase">Section</td>											
						<td style="text-transform:uppercase"></td>											
				</tr>

	<tbody>
		<?php echo $trr; ?>
	</tbody>
</table>	
		<p>	
													
																<nav  style="margin-bottom:0px">
																			<ul class="pagination pagination-sm">
																					<?php echo $paginationCtrls; ?>
																				</ul>
																			</nav>
															</p>													
																	
																
															