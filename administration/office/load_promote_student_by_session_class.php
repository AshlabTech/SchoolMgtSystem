<?php
include_once('../php/connection.php');

    if(isset($_POST['student_toremove'])){
        $student_info_id = $_POST['student_toremove'];
        	$delete_sql = "UPDATE student_info set status = '0' where student_info_id = '$student_info_id'";
			$do_query = mysqli_query($conn,$delete_sql);
			
			$delete_sql2 = "UPDATE student_classes set status = '0' where student_info_id = '$student_info_id' ORDER BY student_class_id desc limit 1";
			$do_query2 = mysqli_query($conn,$delete_sql2);
			
			$delete_sql3 = "UPDATE student_login_info set status = '0' where student_id = '$student_info_id' ";
			$do_query3 = mysqli_query($conn,$delete_sql3);
        echo 'removed successfully...';
        exit;
    }
?>

<div id="tranferbox"></div>
<script type="text/javascript" src="../../js/jquery-1.10.2.js"></script>
<style>
	#mcl {
		margin: 0px;
		padding: 14px;
		border:1px solid #999;
	}
	#mcl li{
		margin: 0px;
		padding: 5px;
		width:170px;
		border-bottom: 1px solid #aaa;
		list-style: none;
		cursor: pointer;
		display: flex;
		justify-content: space-between;
	}
	#mcl li:hover{
		background: #222;
		color: #fff;
	}
	#mcl li:focus{
		background: blue !important;
	}

	#mcl1 {
		margin: 0px;
		padding: 14px;
		border:1px solid #999;
	}
	#mcl1 li{
		margin: 0px;
		padding: 5px;
		width:170px;
		border-bottom: 1px solid #aaa;
		list-style: none;
		cursor: pointer;
		display: flex;
		justify-content: space-between;
	}
	#mcl1 li:hover{
		background: #222;
		color: #fff;
	}
	#mcl1 li:focus{
		background: blue !important;
	}
	.s3{
		display: block;
		font-size: 2em;
		margin-top: 15px;
	}
	#si1{
		font-size: 0.96em;
	}
	br{
		margin: 0px !important;
		padding: 0px;
	}
		iframe{
		height: 600px;
		border: none;
		width: 400px;
	}
	#iframeContInn{
		position: absolute; 
		background: white;
		top: 2%;
		left: 35%;
		z-index: 999;
		box-shadow: 0px 3px 15px #aaa;
		border: none;
	}
	#iframeCont{
		display: none;
		position: fixed;
		width: 100%;
		 top: 0;
		 left: 0; 
		
		z-index: 3;
	}
</style>

<?php 
/*header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.
*/

	$sql2 = $conn->query("SELECT * FROM session WHERE status = '1'");
    $sql3 = $conn->query("SELECT * FROM term WHERE status = '1'");			
    $session = '';
    $session_id = '';
    $term_id = '';
    $term = '';
    if ($sql2->num_rows> 0 and $sql3->num_rows> 0) {
    	$ssm = $sql2->fetch_assoc();
    	$tm = $sql3->fetch_assoc();
    	$session_id = $ssm['section_id'];
    	$session = $ssm['section'];
    	$term = $tm['description'];
    	$term_id = $tm['id'];
    }

			$class_id="";
			if(isset($_POST["p_class"])){
				$class_id  = $_POST["p_class"];
				$session_id  = $_POST["p_session"];

			}else{
				echo 'Class is not found!';
                exit;
			}
				
				
				
				if(isset($_POST["pn"])){
								$pn = $_POST["pn"];
							}else{
								$pn = 1;
							}
						
					echo $class_id;
					
					// i get the cout of rows i wish to select from the db
					//$countt = "select COUNT(student_class_id) from student_classes where class_id = '$class_id' and status = '1'";
					$countt = "select COUNT(student_class_id) from student_classes where class_id = '$class_id' and (status != '0'  )  and session_id = '$session_id' ";

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
				$sql_all_class1 = "select * from classes where class_id = '$class_id' and status != '0'";
						
				//$sql_all_class1 = "select * from classes where class_id = '$class_id' and status = '1'";
				$query_all_class1 =  mysqli_query($conn,$sql_all_class1) or die(mysqli_error($conn));
				$array_all_class1 = mysqli_fetch_array($query_all_class1);
				$class_name = $array_all_class1['class_name'];
				$sql_all_class = "select * from student_classes where class_id = '$class_id' and (status != '0' ) and session_id = '$session_id' ORDER BY student_class_id";
				
				/*$sql_all_class = "select * from student_classes where class_id = '$class_id' and (status = '1' or status = '2') ORDER BY student_class_id LIMIT $limit";*/
				$query_all_class =  mysqli_query($conn,$sql_all_class) or die(mysqli_error($conn));
					$num_rows_all_class = mysqli_num_rows($query_all_class);
					if($num_rows_all_class > 0){
						$sn = 1;
						while($array_all_class = mysqli_fetch_array($query_all_class)){
							$student_info_id = $array_all_class['student_info_id'];
								$sql_student_info = "select * from student_info as i inner join student_login_info as l on l.student_id = i.student_info_id where i.student_info_id = '$student_info_id' ";
								/*	
								$sql_student_info = "select * from student_info as i inner join student_login_info as l on l.student_id = i.student_info_id where i.student_info_id = '$student_info_id' ";*/
								$query_student_info =  mysqli_query($conn,$sql_student_info) or die(mysqli_error($conn));
								$num_rows_student_info = mysqli_num_rows($query_student_info);		
									
									if($num_rows_student_info > 0){
											$arrray_student_info = mysqli_fetch_array($query_student_info);
											$first_name = $arrray_student_info['first_name'];
												$adm_no = $arrray_student_info['adm_no'];
											$last_name = $arrray_student_info['last_name'];
											$other_name = $arrray_student_info['other_name'];
											$gender = $arrray_student_info['gender'];
											$religion = $arrray_student_info['religion'];
											$date_of_birth = $arrray_student_info['date_of_birth'];
											$state_id = $arrray_student_info['state_id'];
											$lga_id = $arrray_student_info['lga_id'];
											$tribe = $arrray_student_info['tribe'];
											$image_name = $arrray_student_info['image_name'];
											$residential_address = $arrray_student_info['residential_address'];
											$login_no = $arrray_student_info['student_no'];
											//GET Age
												$dob_s = strtotime($date_of_birth);
											$current_date_s = strtotime(@date('Y-m-d'));
											$age_diff = $current_date_s - $dob_s;
											//$age_minute = $age_diff/60;
											$age = ceil($age_diff/(60*60*24*365));
											if($age < 1){
												$age = '<b style="color:red">not valid</b>';
											}else{
												$age = $age.'yrs';
											}
											//get full_name
											$full_name = $first_name.' '.$other_name.' '.$last_name;
											
														//get state title
						$sql_get_state=mysqli_query($conn,"SELECT * FROM states where state_id = '$state_id'") or die(mysql_error());
						if($sql_get_state){
							$sql_get_state_row=mysqli_num_rows($sql_get_state);
							if($sql_get_state_row > 0){
								while($row=mysqli_fetch_assoc($sql_get_state)){
									$state_name = $row['name'];
								}}}
							
					$sql=mysqli_query($conn,"SELECT *FROM lga where local_id = '$lga_id'") ;
						if($sql){
							$sql_get_state_row=mysqli_num_rows($sql);
							if($sql_get_state_row > 0){
								while($row=mysqli_fetch_assoc($sql)){
									$lga_title = $row['title'];
								}}}
														
															if($image_name == ''){
															if($gender == 'M')
																$user_pic = '../images/default.jpg';
															else
																$user_pic = '../images/default_f.jpg';
														}else{
															
															if(file_exists("../students_image_upload/$image_name") == 1){
																$user_pic = "../students_image_upload/$image_name";
																}else{
																	if($gender == 'M')
																		$user_pic = '../images/default.jpg';
																	else
																		$user_pic = '../images/default_f.jpg';
																}
														}
														/*$class_id = $class_id;
														$current_class_name = $class_id;*/
														$delete_token = $student_info_id.','.$class_id.','.$pn;
														$obj = "#ck1".$student_info_id;
															$tr .= '
																<tr>
																<td>
																	<input type="checkbox" name="chk" value="'.$student_info_id.'" id="ck1'.$student_info_id.'" class="chkclass" onclick="(function(){selected = selChk1( $( \'#ck1'.$student_info_id.'\'),selected );console.log(selected);})()"></td>

																   <td width="1%"><a href="#" onclick="load_student_info_data('.$student_info_id.')" type="button" class="btn btn-default"> <span class="glyphicon glyphicon-th-large" aria-hidden="true"></span> '.$sn++.'</a> </td>
																    <td width="10%" class="text-center"><img class="img img-circle" src="'.$user_pic.'" onclick="load_change_student_pics('.$student_info_id.')" style="width:20px;height:20px"></td>
																    <td width="">'.$adm_no.'</td>
																    <td width="">'.$login_no.'</td>
																	 <td width="">'.$full_name.'</td>
																	<td class="text-center"  >'.$gender.'</td>
																	<td class="text-center"  >'.$tribe.'</td>
																	<td class="text-center"  >'.$state_name.'</td>
																	<td class="text-center" >'.$lga_title.'</td>
																	<td class="text-center" >'.$religion.'</td>
																	<td class="text-center"  >'.$residential_address.'</td>
																	<td class="text-center"  ><button  class="btn btn-sm btn-danger" onclick="remove_student_inclass('.$student_info_id.','.$class_id.')"><i class="fa fa-trash"></i></button></td>
																   
																 </tr>';
														
									}
						}
					}else{
										 $error = "no student in the selected class...";
										
									}
				
				$prev = $class_id.','.($pn-1);
				$nex = $class_id.','.($pn+1);
		$paginationCtrls = "";
		if($last != 1)
		{
			if($pn > 1)
			{
				$paginationCtrls .= '	<li> <a href="#" onclick="load_all_student_inclass('.$prev.')" aria-label="Previous"><span aria-hidden="true">&laquo;</span> </a></li>';	
			}
				$paginationCtrls .= ' <li class="active"><a href="#">'.$pn.' <span class="sr-only">(current)</span></a></li>';
			if($pn !=$last)
			{
				$paginationCtrls .= '<li> <a href="#" onclick="load_all_student_inclass('.$nex.')" aria-label="Next"><span aria-hidden="true">&raquo;</span> </a></li>';
			}
		}
		
		
			
			

?>	
<div id="iframeCont" style="background: rgba(0,0,0,.3) !important;width: 100%; height: 100vh;">	
	<div id="iframeContInn">
		<button type="button" class="btn btn-danger" style="width:50px;position: absolute;top:5%; right: 5%;" onclick="(function(){$('#iframeCont').fadeOut(400);})()">&times;</button>
		<iframe src="ques/transferbox.php" style="" id="iframe"></iframe>
	</div>														
</div>	
		<h4>
		
			<i class="ace-icon fa fa-users home-icon" style="margin-left:20px"></i>
			<a href="#"> Match Found <b><?php echo $class_name.' ('.$num_rows_all_class.')'; ?></b></a>
			
			<div style="width: 12px;float:right;padding: 5px;"></div>			
		<!-- 	<button disabled=""  class="btn btn-success" style="float:right;" onclick="importMB()" ><span class="glyphicon glyphicon-download" ></span> Import
				<div style="width: 12px;float:right;padding: 5px;"></div>
			</button> -->
			<button  class="btn btn-success" onclick="transM('<?= $class_name ?>',selected,<?= $term_id ?>,<?= $class_id ?>,<?= $session_id ?>)" id="transM" data-toggle="modal" data-target="#transModal" style="float:right;" >
				<span class="glyphicon glyphicon-ok" ></span> Transfer
			</button>
			
		</h4>
																						
<script>var class_sel = "<?php echo $class_name ;?>"; var termId=<?php echo $term_id;?>;var sessionId =<?php echo $session_id;?>; var classId=<?php echo $class_id;?>;</script>				
												
															 <table class="table table-bordered table-hover" width="100%" border="1px">
															<thead>
																 <tr>
																 	<th style="padding: 9px;"><input type="checkbox" id="chkA" onclick="(function(){cm+=1;selected = chkAll(cm,'chkclass'); console.log(selected);})()" ></th>
																   <td width="1%"><strong>S/N</strong></td>
																    <td width=""><strong>PICTURE</strong></td>
    															 <td class="text-center" width=""><strong>Admission No</strong></td>
    															 	 <td class="text-center" width=""><strong>Login info</strong></td>
																   <td class="text-center" width=""><strong>FULL NAME</strong></td>
																   <td class="text-center" width=""><strong>GENDER</strong></td>
																   <td class="text-center" width=""><strong>Language</strong></td>
																   <td class="text-center" width=""><strong>State</strong></td>
																   <td class="text-center" width=""><strong>LGA</strong></td>
																   <td class="text-center" width=""><strong>RELIGION</strong></td>
																   
																   <td class="text-center" width=""><strong>ADDRESS</strong></td>
																    <td class="text-center" width=""><strong>ACTION</strong></td>
																  
																 </tr>
																</thead>
																<tbody>
																	<?php echo $tr; ?>
																
																</tbody>
																	
															</table>
																<p>	
													
																<nav  style="margin-bottom:0px">
																			<ul class="pagination pagination-sm">
																					<?php echo $paginationCtrls; ?>
																				</ul>
																			</nav>
															</p>
															<?php echo $error; ?>
																														
																														

<!-- Add Modal -->
  <div class="modal fade" id="addModal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Students to <?php echo $class_name;?></h4>
        </div>
        <div class="modal-body" id="formcontent">
        	<table class="table table-condensed table-striped table-hover tSortable221" id="table">
				<thead>
					<th style="padding: 9px;"><input type="checkbox" id="chkA12" onclick="selchecked('#chkA12','#chkA12:checkbox','chkA123','.chkA123:checkbox')" ></th>
					<th>Student Adm.</th>
					<th>Name</th>
					<th>Gender</th>
					<th>Class</th>
				</thead>
			<tbody>

    <?php
  
    $sql1 = "SELECT * FROM student_info";
	$q1 = $conn->query($sql1);
	if($q1->num_rows>0){
        	while($r = $q1->fetch_assoc()){
        		$rid = $r['student_info_id']; //id
        		$id = '#ck'.$rid;
			$sqlex = "SELECT m.*, s.*, s.student_info_id as stid2, b.class_id as b_id, b.class_name as classb  FROM student_classes AS m INNER JOIN student_info AS s ON m.student_info_id=s.student_info_id INNER JOIN classes AS b ON m.class_id=b.class_id WHERE m.session_id='$session_id' AND m.term_id='$term_id' AND s.student_info_id='$rid' AND m.status != '0'";
				$qex = $conn->query($sqlex);
				if($qex->num_rows>0){
					while($rex = $qex->fetch_assoc()){
						$ridex = $rex['id']; //id
						if($bid!= $rex['b_id']){

						?>
							<tr>
							<td><input type="checkbox" id="<?php echo 'ck'.$rid;?>" onclick="checkUpdate('<?php echo $id;?>')" value="<?php echo $rid;?>" class="chkA123"></td>								
								<td><?php echo $rex['adm_no'];?></td>
								<td><?php echo $rex['first_name'] .' '.$rex['other_name'].' '.$rex['last_name'];?></td>
								<td><?php echo $rex['gender'];?></td>
								<td><?php echo $rex['classb'];?></td>
							</tr>
						<?php
						}
					}
				}else{	
			?>
										<tr>
						<td><input type="checkbox" id="<?php echo 'ck'.$rid;?>" onclick="checkUpdate('<?php echo $id;?>')" value="<?php echo $rid;?>" class="chkA123"></td>
											
											<td><?php echo $r['adm_no'];?></td>
											<td><?php echo  $r['first_name'] .' '.$r['other_name'].' '.$r['last_name'];?></td>
											<td><?php echo $r['gender'];?></td>
											<td>Nill</td>
										</tr>
			<?php
				}
			}
		}
			?>
			<div id="load1" style="display: none;"><img src="../../images/ajax-loader.gif" width="70%;"></div>
        </div>
        <div class="modal-footer">
        	<script>
        		//alert(selected);
        	</script>
        	<button class="btn btn-success ActionBtn" class="ActionBtn" disabled="" onclick="importD(Selected,<?php echo $class_id;?>,<?php echo $term_id;?>,<?php echo $session_id;?>)">import</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <!--The end add modal-->																														
  	  <div id="ImOut12" style="display: none;" class="alert alert-success"></div>
      <div id="ImOut2" style="display: none;" class="alert alert-danger"></div>
  	<script>
selected ='';
    </script>