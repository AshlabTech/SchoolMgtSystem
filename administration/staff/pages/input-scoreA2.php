<?php

session_start();
include_once('../php/connection.php');
$errormsg = '';

$action = "add";
if(isset($_SESSION['staff_info_id'])){
	$user_id =  $_SESSION['staff_info_id'];
}else{
	?>
	<script>
		window.top.location.reload();
	</script>
	<?php
}
$branch='';
$amount='';
$detail = '';
$id= '';
	$session = $conn->query("SELECT * FROM session WHERE status = '1'") or die(mysqli_error($conn));
		$term = $conn->query("SELECT * FROM term WHERE status = '1'") or die(mysqli_error($conn));
		if ($session->num_rows>0) {
			if ($term->num_rows>0) {
				$ss1 = $session->fetch_assoc();
				$tt1 = $term->fetch_assoc();
	 			$session_id = $ss1['section_id'];
	 			$term_id = $tt1['id'];
	 			$term = $tt1['description'];
	 			$session = $ss1['section'];
			
			}else{
				echo "term not set";
				exit();
			}	
		}else{
			echo "session not set";
			exit();
		}


$action = "add";
if(isset($_GET['action']) && $_GET['action']=="edit" ){
$id = isset($_GET['id'])?mysqli_real_escape_string($conn,$_GET['id']):'';

$sqlEdit = $conn->query("SELECT * FROM branch WHERE id='".$id."'");
if($sqlEdit->num_rows)
{
$rowsEdit = $sqlEdit->fetch_assoc();
extract($rowsEdit);
$action = "update";
}else
{
$_GET['action']="";
}

}


if(isset($_REQUEST['act']) && @$_REQUEST['act']=="1")
{
$errormsg = "<div class='alert alert-success'><strong>Success!</strong> Class Add successfully</div>";
}else if(isset($_REQUEST['act']) && @$_REQUEST['act']=="2")
{
$errormsg = "<div class='alert alert-success'><strong>Success!</strong> Class Edit successfully</div>";
}
else if(isset($_REQUEST['act']) && @$_REQUEST['act']=="3")
{
$errormsg = "<div class='alert alert-success'><strong>Success!</strong> Class Delete successfully</div>";
}

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    

    <!-- BOOTSTRAP STYLES-->
    <link href="../css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="../css/font-awesome.css" rel="stylesheet" />
       <!--CUSTOM BASIC STYLES-->
    <link href="../css/basic.css" rel="stylesheet" />
    <!--CUSTOM MAIN STYLES-->
    <link href="../css/custom.css" rel="stylesheet" />
    <link href="../js/listjs/PagingStyle.css" rel="stylesheet" />
	
	<link rel="stylesheet" href="../../css/font-awesome-4.7.0/css/font-awesome.min.css">

		 <link href="../css/datatable/datatable1.css" rel="stylesheet" />

    <!-- GOOGLE FONTS-->
   
	
	 <script src="../js/jquery-1.10.2.js"></script>
	 <script src="../js/sweetalert.js"></script>
	 <script src="../js/listjs/paging.js"></script>

<style>
	#mcl {
		margin: 0px;
		padding: 14px;
		border:1px solid #ddd;
		height: 500px;
	}
	#mcl li{
		margin: 0px;
		padding: 5px;
		width:190px;
		border-bottom: 1px solid #ddd;
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
	.headerC{
		box-shadow: #ccc 0px 1px 4px ;
		padding: 8px;
		display: flex;
	}
	.headerC button{
		margin: 3px;
		font-size: 0.94em;
		font-family: arial;
	}
	.bodyC{
		box-shadow: #ccc 0px 1px 4px ;
		padding: 8px;
	}
	.loader {
  border: 10px solid #bfb; /* Light grey */
  border-top: 10px solid #444; /* Blue */
  border-radius: 50%;
  width: 80px;
  height: 80px;
  animation: spin 2s linear infinite;
  position: relative;
  left: 50%;
  top: 190px;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
	
</style>

</head>
<?php
/*include("../php/headerF.php");*/
?>
<div id="ploader" on >
	<!-- <div class="loader"></div> -->
<!--	<h4 style='text-align:center'><img src='../../../images/ajax-loader.gif'></h4>-->
</div>
<script>
	
</script>
	<div id="page-wrapper" style="background: #fff !important;margin:0px;padding:10px 0px;" onload="(function(){})()" style="display: none;" >
		<div id="page-inner">
			<div class="container-fluid" style=" font-size: 2em;font-weight: bolder; font-family: arial;">Input Score For All CA</div>
			<hr>
			<div class="row" style="padding:10px 0px 0px 0px; margin: 0px; display: flex;">
				<div class="col-xs-3 col-sm-5 col-md-3 col-lg-2" style="padding: 0px; width: 220px;">
					<ul id="mcl">
						<?php							
						/*echo 233223;*/
						//$c_teacher_subject = $conn->query("SELECT * FROM staff_subjects as ss INNER JOIN classes as c ");
							$c_teacher_subject = $conn->query("SELECT *, sr.class_id as bid, sr.id as srid, t.id as tid, s.section_id as sid,sb.id as sbid FROM staff_subjects as sr INNER JOIN classes AS b ON sr.class_id=b.class_id 
							INNER JOIN subject AS sb ON sr.subject_id=sb.id INNER JOIN term AS t ON t.id=sr.term_id INNER JOIN session AS s ON s.section_id='$session_id' WHERE sr.staff_info_id='$user_id'");
							echo mysqli_error($conn);
							if($c_teacher_subject->num_rows>0){
								while ($row = $c_teacher_subject->fetch_assoc()) {
									$srid = $row['srid'];
									$session =$row['section'];
									$term =$row['description'];
						?>
						<li id="<?php echo 'list'.$srid; ?>" ><span class="fa fa-book s3"></span><span id="si1"><?php echo ucwords($row['subject']);?><br><?php echo ucwords($row['class_name']);?><br><?php echo ucwords($row['description']);?>, <?php echo ucwords($row['section']);?><br>session</br></span></li>
							<script>
								$(document).ready(function(){
									$('#list<?php echo $srid?>').click(function(){
											document.getElementById('ploader').style.display = 'block';
										//alert();
										var user_id = <?php echo $row['bid']; ?>;
										var b = "<?php echo $row['class_name']; ?>";
										var tid = <?php echo $row['tid']; ?>;
										var sid = <?php echo $row['sid']; ?>;
										var sbid = <?php echo $row['sbid']; ?>;
											document.cookie="openC="+user_id+'-'+tid+'-'+sid+'-'+sbid;
											window.location='input-scoreA.php?openC='+user_id+'-'+tid+'-'+sid+'-'+sbid;

											/*
										$.get('misc/subject_role.php',{id:lid,type:'subjectRole',session:sid,term:tid, b:b}, function(data){
											//alert(data);
											$('#class-cont').html(data);
										});*/
									});
								});
							</script>
						<?php
								}
							}
						?>
						
					</ul>
				</div>
				<script>
					//alert();
     	$(function() {
		  $("#mcl").JPaging();
		});
     	
			
    </script>
				<div class="col-xs-3 col-sm-7 col-md-8 col-lg-8" style="min-width: 600px;">
					<div id="">
						<p style="font-size: 1.3em; font-family: arial; font-weight: bold; color: #288;"><?php echo ucwords($term);?>,<?php echo ' '.$session;?></p><div style="position: absolute;top:-21.3%; left: 0; width: 100%;  overflow-y: none;"><div class="loader" id="load1" style="margin-left: 0px; margin-right: 0px; display:none;"></div></div>
						<div id="class-cont">
						<p style="border-bottom: 1pt solid black;" id="showClass">Class:</p>
						<center><p id="showSubject"></p></center>
						<div style="border: 1px solid #eee; padding: 8px;">
							<?php
								if(isset($_REQUEST['openC'])){
									$opPHP = "'".$_REQUEST['openC']."'";

					
									?>
									<script>
										//use cookie for some checking (security) so that whe alternate data passed to url page reload;
										function getCookie(name) {
										    var dc = document.cookie;
										    var prefix = name + "=";
										    var begin = dc.indexOf("; " + prefix);
										    if (begin == -1) {
										        begin = dc.indexOf(prefix);
										        if (begin != 0) return null;
												//window.location='input-scoreA.php';

										    }
										    else
										    {
										        begin += 2;
										        var end = document.cookie.indexOf(";", begin);
										        if (end == -1) {
										        end = dc.length;
										        }
										    }
										    // because unescape has been deprecated, replaced with decodeURI
										    //return unescape(dc.substring(begin + prefix.length, end));
										    return decodeURI(dc.substring(begin + prefix.length, end));
										} 

									
								    	var myCookie = getCookie("openC");
									    if (myCookie == null) {
									        // do cookie doesn't exist stuff;
									       //alert(2);
									    }
									    else {
									    	//if url changed or cookie destroy
									    	var script_op = myCookie.split(';');
									       if(script_op[0]!= <?php echo $opPHP;?>){
												window.location='input-scoreA.php';
									       }
									    }

									</script>

									<?php

									$op = explode('-', $_REQUEST['openC']);
									$bid = $op[0];
									$tid = $op[1];
									$sid = $op[2];
									$sbid = $op[3];

								/*	$sql = $conn->query("SELECT * FROM score_time_frame as st INNER JOIN branch as b ON b.id='$bid' WHERE st.term_id='$tid' AND st.section_id =b.section_id");
									$caOpen = array();
									$cA1O= $cA2O = $cA3O =$examO ='';
									$cA1C= $cA2C = $cA3C =$examC =0;
									$ca1Close =array();
									//= $ca2Close = $ca3Close =$examClose ='';
									if ($sql->num_rows>0) {
										while($timeframe = $sql->fetch_assoc()){
											$caOpen[$timeframe['ca_id']] = $timeframe['start_date'];
											$caClose[$timeframe['ca_id']] = $timeframe['end_date'];
										}
										foreach ($caOpen as $key => $value) {
											if($key==1){
												$cA1O = $value;
											}elseif($key==2){
												$cA2O = $value;
											}elseif($key==3){
												$cA3O = $value;
											}elseif($key==4){
												$examO = $value;
											}
										}
										foreach ($caClose as $key => $value) {
											if($key==1){
												$cA1C = $value;
											}elseif($key==2){
												$cA2C = $value;
											}elseif($key==3){
												$cA3C = $value;
											}elseif($key==4){
												$examC = $value;
											}
										}
										//if(sizeof($ca)==1){}
										//echo sizeof($caOpen);
										//echo var_dump($caOpen);
									}
									*/
									?>
									<style >
									</style>
									<script>
										/*(function(){

										setInterval(function(){ 
											$.post('misc/timeframe_lock.php',{cdate:<?php //echo $cA1C; ?>}, function(data){
												if(data==1){
												}else if(data==0){
													var c1 = document.getElementsByClassName('ca1');
													for (var i = 0; i < c1.length; i++) {
														//c1[i].style.cursor ='not-allowed';
														c1[i].disabled = true;
													}
												}
											});
											$.post('misc/timeframe_lock.php',{cdate:<?php // echo $cA2C; ?>}, function(data){
												if(data==1){
												}else if(data==0){
													var c2 = document.getElementsByClassName('ca2');
													for (var i = 0; i < c2.length; i++) {
														//c1[i].style.cursor ='not-allowed';
														c2[i].disabled = true;
													}
												}
											});
											$.post('misc/timeframe_lock.php',{cdate:<?php// echo $cA3C; ?>}, function(data){
												if(data==1){
												}else if(data==0){
													var c3 = document.getElementsByClassName('ca3');
													for (var i = 0; i < c3.length; i++) {
														//c1[i].style.cursor ='not-allowed';
														c3[i].disabled = true;
													}
												}
											});
											$.post('misc/timeframe_lock.php',{cdate:<?php //echo $examC; ?>}, function(data){
												if(data==1){
												}else if(data==0){
													var ee = document.getElementsByClassName('cae');
													for (var i = 0; i < ee.length; i++) {
														//c1[i].style.cursor ='not-allowed';
														ee[i].disabled = true;
													}
												}
											});

										 }, 100);
										})();*/
									</script>
									<?php
									//echo checkDate1($cA1C);

									$get_class= $conn->query("SELECT * FROM classes WHERE class_id='$bid'");
									$get_classq = $get_class->fetch_assoc();
									$selected_class = $get_classq['class_name'];
									$get_subject= $conn->query("SELECT * FROM subject WHERE id='$sbid'");
									$get_subjectq = $get_subject->fetch_assoc();
									$subject_section = $get_subjectq['school_section'];
									$selected_subject = ucwords($get_subjectq['subject']);
									echo "<script>
											var bid = ".$bid.";
											var tid = ".$tid.";
											var sid = ".$sid.";
											var sbid = ".$sbid.";
											document.getElementById('showClass').innerHTML='Class: ";
									echo $selected_class;
									echo"';
										document.getElementById('showSubject').innerHTML='";
									echo $selected_subject;
									echo"';
									</script>";
							?>
								 <script>var cm=0; selected1 = ''; selected=''; var all_student_info_id ='';</script>
								<div class="headerC" style="">
								<button>Download template</button>
								<button>Upload From template</button>
								<form class="p-0 m-0 d-inline-block" method="post" action="subject_ca_pdf.php">	
									<input type="text" name="data" id="dataValue" style="display: none;">
									<input type="text" name="subject" style="display: none;" value="<?= $selected_subject; ?>">
									<input type="text" name="class" style="display: none;" value="<?= $selected_class; ?>">
									<input type="text" name="term" style="display: none;" value="<?php echo ucwords($term);?>">
									<input type="text" name="section" style="display: none;" value="<?php echo ucwords($subject_section);?>">
									<input type="text" name="session" style="display: none;" value="<?= $session; ?>">
									<button type="submit">Print</button>
								</form>
								<button class="btn btn-info" onclick="saveResultBtn();">save</button>
							</div>
							<div class="bodyC">
								<table class="table table-condensed table-striped table-hover">
									<thead>
										<?php 
											$get_set_score =$conn->query("SELECT * FROM score WHERE section_id = '$subject_section' AND activate='1'");
											$scorep = $get_set_score->fetch_assoc();
											$ca1Data = $scorep['ca1'];
											$ca2Data = $scorep['ca2'];
											$ca3Data = $scorep['ca3'];
											$examData = $scorep['exam'];
										?>
										<th style="cursor: pointer;"><input type="checkbox" id="chkA" onclick="(function(){cm+=1;selected =chkAll(cm,'chkclass');})()" ></th>
										<th>s/n</th>
										<th>Adm No.</th>
										<th colspan="10">Name</th>
										<th>CA 1 <br>(<?php echo $ca1Data; ?>%)</th>
										<?php
											if ($ca2Data != 0) {
											?><th>CA 2 <br>(<?php echo $ca2Data; ?>%)</th><?php
											}
										?>		
										<?php
											if ($ca3Data != 0) {
											?><th>CA 3 <br>(<?php echo $ca3Data; ?>%)</th><?php
											}
										?>		
										<th>Exam <br>(<?php echo $scorep['exam']; ?>%)</th>
										<th>Total <br>100%</th>
									</thead>
									<body>
										<?php
										//echo $bid;
										$dataValueForPDF ='';
											$get_student_in_class = $conn->query("SELECT * FROM student_classes as m INNER JOIN student_info as s ON m.student_info_id= s.student_info_id WHERE m.session_id='$sid' AND m.term_id='$tid' AND m.class_id='$bid'");
											if (!$get_student_in_class) {
												echo "<script>alert(".mysqli_error($conn).");</script>";
											}
										if($get_student_in_class->num_rows>0){
												$n =1;
												$studentIds = array();
												while($row = $get_student_in_class->fetch_assoc()){
													$stid = $row['student_info_id'];
													$studentIds[] = $stid;
													$ca1=$ca2=$ca3=$exam=$stotal ='';
													$get_score=$conn->query("SELECT * FROM contineous_accessment  WHERE student_info_id='$stid' AND subject_id='$sbid' AND class_id='$bid' AND session_id='$sid' AND term_id='$tid'");
													if($get_score->num_rows>0){
														$rr = $get_score->fetch_assoc();
														$ca1 = $rr['ca1'];$ca2 = $rr['ca2'];$ca3 = $rr['ca3'];$exam= $rr['exam'];$stotal = $rr['total'];
														$dataValueForPDF .= $row['adm_no'].';'.ucwords($row['first_name']).' '.ucwords($row['other_name']).' '.ucwords($row['last_name']).';'.$ca1.';'.$ca2.';'.$ca3.';'.$exam.';'.$rr['total'].';'.$rr['grade'].';'.$rr['position'].';';
													}
												?>		
													<tr>
														<td ><input type="checkbox" name="chk" value="<?php echo $stid;?>" id="<?php echo 'ck'.$stid; ?>" class="chkclass" onclick="(function(){ selected = selChk($('<?php echo "#ck".$stid;?>'),selected);})()"></td>

														<td><?php echo $n.'.';?></td>
														<td><?php echo $row['adm_no'];?></td>
														<td colspan="10"><?php echo ucwords($row['first_name']).' '.ucwords($row['other_name']).' '.ucwords($row['last_name']);?></td>
														<td>
															<input class="ca1" type="text" value="<?php if($ca1!='' && $ca1!=0){echo $ca1;}?>" max="20" min="0" name="ca1" id="ca1_<?php echo $stid;?>" style="width: 60px;"  onkeypress="return isNumber(event)" >
														</td>
														<?php
															if ($ca2Data != 0) {
															?>
															<td>
																<input class="ca2" type="text" value="<?php if($ca2!='' && $ca2!=0){echo $ca2;}?>" max="20" min="0" name="ca2" id="ca2_<?php echo $stid;?>" style="width: 60px;" onkeypress="return isNumber(event)">
															</td>

															<?php
															}else{
																?> <td style="display: none"><input class="ca2" type="text" value="<?php if($ca2!='' && $ca2!=0){echo $ca2;}?>" max="20" min="0" name="ca2" id="ca2_<?php echo $stid;?>" style="width: 60px;" onkeypress="return isNumber(event)"></td><?php
															}
														?>
														<?php
															if ($ca3Data != 0) {
															?>
															<td>
																<input class="ca3" type="text" value="<?php if($ca3!='' && $ca3!=0){echo $ca3;}?>" max="20" min="0" name="ca3" id="ca3_<?php echo $stid;?>" style="width: 60px;" onkeypress="return isNumber(event)">
															</td>
															<?php
															}else{
																?><td style="display: none;"><input class="ca3" type="text" value="<?php if($ca3!='' && $ca3!=0){echo $ca3;}?>" max="20" min="0" name="ca3" id="ca3_<?php echo $stid;?>" style="width: 60px;" onkeypress="return isNumber(event)"></td><?php
															}
														?>
														
														<td>
															<input class="cae" type="text" value="<?php if($exam!='' && $exam!=0){echo $exam;}?>" name="exam" id="exam_<?php echo $stid;?>" style="width: 60px;" onkeypress="return isNumber(event)">
														</td>
														<td><input type="text" value="<?php if($stotal!='' && $stotal!=0){echo $stotal;}?>" name="total" id="total_<?php echo $stid;?>" readonly style="width: 60px; cursor: not-allowed; background: rgba(100,100,100,.2);"></td>
													</tr>
													<script>
														$('#ca1_<?php echo $stid;?>,#ca2_<?php echo $stid;?>,#ca3_<?php echo $stid;?>,#exam_<?php echo $stid;?>').keyup(function(){
															$.post('misc/add_student_to_reult_table.php',{bid:bid,tid:tid,sid:sid,sbid:sbid,stid:<?php echo $stid;?>, teacher:<?php echo $user_id;?>}, function(data){
																//alert(data);
															});
															var a = $('#ca1_<?php echo $stid;?>').val();
															var b = $('#ca2_<?php echo $stid;?>').val();
															var c = $('#ca3_<?php echo $stid;?>').val();
															var d = $('#exam_<?php echo $stid;?>').val();
															if (a=='' || a==null){
																a = 0;
															}
															if (b=='' || b==null){
																b = 0;
															}
															if (c=='' || c==null){
																c = 0;
															}
															if (d=='' || d==null){
																d = 0;
															}
															var sum = parseFloat(a) + parseFloat(b) + parseFloat(c) + parseFloat(d);
															document.getElementById('total_<?php echo $stid;?>').value =sum;
															//alert(<?php// echo $stid;?>);
															//if ca1 is empty and not equals to \zero the student has not been added to result table;

														});
													</script>
													<?php
													$n++;
											}
											?>	
											<script>
												all_student_info_id = <?php echo json_encode($studentIds);?>;
												 document.getElementById('dataValue').value = <?php echo json_encode($dataValueForPDF);?>;												
											</script>
											<?php

											
												// mysqli_error($conn);
											}else{
												//echo "string";
											}

										?>
									</body>
								</table>
								
							</div>


							<?php
								}else{
							?>
							<div class="headerC" style="">
								<button>Assign Subject</button>
								<button>Remove Subject</button>
								<button>Generate Report</button>
							</div>
							<div class="bodyC">
								<table>
									<thead>
										<th>Teacher</th>
									</thead>
								</table>
								
							</div>
							<?php
							}
							?>
						</div>
						</div>
					</div>
				</div>

				
			</div>

	<script src="../js/dataTable/jquery.dataTables.min.js"></script>
     <script>
     	
		$("#tSortable22").dataTable();
	

    </script>
				
		<!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg" style="width: 400px;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Select Teacher-Subject</h4>
        </div>
        <div id="assinS" style="padding: 40px;">
          
        <div class="modal-body" id="formcontent">
        
        </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

            
            </div>
            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
    <!-- /. WRAPPER  -->

    <div id="footer-sec">
       Rainbow English Classes | Developed By : <a href="../http://www.codexking.com/" target="_blank">Codexking.com</a>
    </div>
   
  
    <!-- BOOTSTRAP SCRIPTS -->
    <script src="../js/bootstrap.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="../js/jquery.metisMenu.js"></script>
       <!-- CUSTOM SCRIPTS -->
    <script src="../js/custom1.js"></script>
    <script>
    	//start checkbox
		function chkAll(source,name){
			selArray = '';
			chkname = document.getElementsByClassName(name);
			for (var i = 0; i < chkname.length; i++) {
			//alert(source.checked);
				if(source % 2==1){
					chkname[i].checked = true;
					selArray+= chkname[i].value +',';
				}else{
					chkname[i].checked = false;
					selArray='';
				}

		}
				return selArray;
	}
    	function selChk(s,p){
		var chkAll = document.getElementById('chkA').checked;
		var id = s.val();
		//alert();
		var selct = '';
		if(chkAll==true){
			if(s.prop('checked')==true){
				p += id+',';
				//alert(p);
				return p;
			}else if(s.prop('checked')==false){
				if(p.length != ''){
					var selV = p.split(",");
				}else{
					var selV;
				}
				for (var i = 0; i < selV.length-1; i++) {
					var len = id.length;
					var index = selV.indexOf(id);
					if(id == selV[i]){
						selV.splice(index,1);
					}
				}
					p ='';
				for (var i = 0; i < selV.length-1; i++) {
					p += selV[i]+',';
				}
				//alert(p);
				return p;
			}
		}else if(chkAll==false){
			if(s.prop('checked')==true){
				p += id+',';
				//alert(p);
				return p;
			}else if(s.prop('checked')==false){
				if(p.length != ''){
					var selV = p.split(",");
				}else{
					var selV;
				}
				for (var i = 0; i < selV.length-1; i++) {
					var len = id.length;
					var index = selV.indexOf(id);
					if(id == selV[i]){
						selV.splice(index,1);
					}
				}
					p ='';
				for (var i = 0; i < selV.length-1; i++) {
					p += selV[i]+',';
				}
				//alert(p);
				return p;
			}
		}
	}
	function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
	}
	function isNumberCA(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
     
        return false;
    }
    return true;
	}
	function disableALL(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode >0) {
        return false;
    }
    return true;
	}
	function saveResultBtn(){
		if(all_student_info_id == ''){
			Swal.fire('No Student in class');
		}else{
			var data = [];
			//alert(all_student_info_id.length);
			for (var i = 0; i < all_student_info_id.length; i++) {
				var r = all_student_info_id[i];
				var s = $('#ca1_'+all_student_info_id[i]).val();
				var p = $('#ca2_'+all_student_info_id[i]).val();
				var d = $('#ca3_'+all_student_info_id[i]).val();
				var f = $('#exam_'+all_student_info_id[i]).val();
				var t = $('#total_'+all_student_info_id[i]).val();
				dat = {id:r,ca1:s,ca2:p,ca3:d,exam:f,total:t};
				data.push(dat);

			}
			$.post('misc/save_input_score.php',{data:data,bid:bid,tid:tid,sid:sid,sbid:sbid},function(data){
				$('#load1').hide();
				//alert(data);
				if(data==1){
					Swal.fire({
						type: 'success',
						title: 'save successfully',
						showConfirmButton: true
					});
				}else{
					let dataArr = data.split('');
					let dataSum = dataArr.reduce((a, b) => a + b) / dataArr.length;
					if(dataSum==1){
						Swal.fire({
							type: 'success',
							title: 'save successfully',
							showConfirmButton: true
						});
					}else{
						alert(data);
					}
				}
			});
			$('#load1').show();
		}
	}
	$(document).ready(function(){
		document.getElementById('ploader').style.display = 'none';
		$('#page-wrapper').show();
	});
	/*$('.table').DataTable({saveState:true,});*/
    </script>

    
</body>
</html>