<?php session_start(); ?>
<?php
	if(isset($_SESSION['staff_info_id'])){
		
	}
	else{
		header('location:../');
	}
	include_once('../php/connection.php');
	
	
	
		if(isset($_POST['session_id'])){
    	    //get current session id
    	    
    	    $session_id = $_POST['session_id'];
    			$get_current_session_id_query = mysqli_query($conn,"select * from session where section_id = '$session_id' ") or die(mysqli_error($conn));
    			$get_current_session_id_arr = mysqli_fetch_array($get_current_session_id_query);
    			$session_id = $get_current_session_id_arr['section_id'];
    			$current_session = $get_current_session_id_arr['section'];
    			
    		$term_id = $_POST['term_id'];
    			$term = mysqli_query($conn,"select * from term  where id='$term_id' ORDER BY status DESC") or die(mysqli_error($conn));
    			$term_array = mysqli_fetch_array($term);
    			$term = $term_array['term'];
    			$term_id = $term_array['id'];
    			$term_full = $term_array['description'];
    	}else{
    	    //get current session id
    			$get_current_session_id_query = mysqli_query($conn,"select * from session where status = '1' ") or die(mysqli_error($conn));
    			$get_current_session_id_arr = mysqli_fetch_array($get_current_session_id_query);
    			$session_id = $get_current_session_id_arr['section_id'];
    			$current_session = $get_current_session_id_arr['section'];
    			
    			// Get the current term
    			$term = mysqli_query($conn,"select * from term  where status = '1'") or die(mysqli_error($conn));
    			$term_array = mysqli_fetch_array($term);
    			$term = $term_array['term'];
    			$term_id = $term_array['id'];
    			$term_full = $term_array['description'];
    		
    			echo 'Current session n term';
    	}
	
			
					$total_for_session_sql = "select SUM(amount_paid) from school_fees where  ";
					$total_for_session_sql .= "session_id = '$session_id' and  (status = '1' or status = '2')";
					$total_for_session_query =  mysqli_query($conn,$total_for_session_sql) or die(mysqli_error($conn));
					$total_for_session_row = mysqli_fetch_row($total_for_session_query);		
					$total_for_session = $total_for_session_row[0];
				// realises this term		
				$sn=1;
				for($i=1;$i<=$term_id;$i++){
				
					$total_for_term_sql = "select SUM(amount_paid) from school_fees where  ";
					$total_for_term_sql .= "session_id = '$session_id' and term_id = '$i' and  (status = '1' or status = '2')";
					$total_for_term_query =  mysqli_query($conn,$total_for_term_sql) or die(mysqli_error($conn));
					$total_for_term_row = mysqli_fetch_row($total_for_term_query);		
					$total_for_term = $total_for_term_row[0];
								
								$term = mysqli_query($conn,"select * from term  where id = '$i'") or die(mysqli_error($conn));
								$term_array = mysqli_fetch_array($term);
								$term_full = $term_array['description'];
																$tr .='
																		<tr>
																			<td class="text-center" width="2%">'.$sn++.'</td>
																			<td class="text-left">'.$term_full.'</td>
																			<td class="text-center" width="20%">N'.$total_for_term.'</td>
																			
																			
																	</tr>
																	';	
				}
					
					
					
					
						
					
					
		
?>
<div id="">
					<h1 style="margin-top:10px;font-size:20px;text-align:center"><?php echo strtoupper($term_full).' '.$current_session.' ACADEMIC SESSION\'S PAYMENT SUMMARY '; ?></h1>
					
</div>
<table class="table table-bordered" style="margin:20px 5px">
	<?php echo $tr; ?>
</table>
<div id="ca_heading">
					<h1>Total = <?php echo 'N'.$total_for_session; ?></h1>
</div>
