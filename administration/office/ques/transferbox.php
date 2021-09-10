 <?php
include_once('../../php/connection.php');
if(isset($_GET['branch'])){
 $Fromclass =  $_GET['branch'];

 ?>
 <script>
 	//alert();
 </script>
<link rel="stylesheet" href=".../../../../css/font-awesome-4.7.0/css/font-awesome.min.css">	  
  
  <link href="../../js/listjs/PagingStyle.css" rel="stylesheet" />
	 <script src="../../js/sweetalert.js"></script>
	 <script src="../../../js/jquery-1.10.2.js"></script>
	 <script src="../../js/listjs/paging.js"></script>
	 <!-- inlcude all javascript files -->
	<!-- <script type="text/javascript" src="../../js/admin_script.js"></script>
	<script type="text/javascript" src="../../js/admin_script2.js"></script>
	<script type="text/javascript" src="../js/admin_script3.js"></script>
	<script type="text/javascript" src="../../js/admin_reg_script.js"></script> -->
				
<div id="coveri">
<center>
 <style type="text/css">
 
	#mcl1 {
		margin: 0px;
		padding: 14px;
		border:1px solid #999;
		width: 200px;
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
		text-align: left;
	}
	#mcl1 li:hover{
		background: #222;
		color: #fff;
	}
	#mcl1 li:focus{
		background: blue !important;
	}
	.pagingL{
		width: 200px !important;
		padding: 10px 0px 10px 20px !important;
	}
	#coveri{
		margin-top: 30px;
	}
	.s3{
		display: block;
		font-size: 2em;
		margin-top: 15px;
	}
	#si1{
		font-size: 0.96em;
	}

 </style>
 <!-- Transfer Modal -->
 <p style="font-weight: bold; font-family: arial; font-size: 1.2em;"> From <?php echo $Fromclass; ?></p>
  <div class="modal fade" style="" id="transModal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Click on a class to Trasfer Student</h4>
        </div>
        <div class="modal-body" id="formcontent1">
        	<ul id="mcl1">
						<?php
						 $select1 =  $_GET['sel'];
						 $t1 =  $_GET['t'];
						 $s1 =  $_GET['s'];
						// echo $t1;
						 $current_class =  $_GET['b'];
							$sql = "SELECT * FROM classes WHERE status='1'";
							$q = $conn->query($sql);

							$sql1 = "SELECT * FROM session WHERE status='1'";
							$rs1 = $conn->query($sql1);
							$s = $rs1->fetch_assoc();
							
							$sql2 = "SELECT * FROM term WHERE status='1'";
							$rs2 = $conn->query($sql2);
							$t = $rs2->fetch_assoc();
								
							if($q->num_rows>0){
								while($r = $q->fetch_assoc()){
									$lid = $r['class_id'];
						?>
						<li id="<?php echo 'list1'.$lid; ?>" onclick="transferF(<?php echo $lid; ?>,<?php echo $t1;?>,<?php echo $s1;?>,'<?php echo $select1;?>')" ><span class="fa fa-book s3"></span><span id="si1"><?php echo $r['class_name'];?><br><?php echo $t['description'];?>, <?php echo $s['section'];?><br>session</br></span></li>
							<script>
								/**/
							</script>
						<?php
								}
							}
						?>
						
					</ul>
          <script>
     	//	alert(1);
     	$(function() {
		  $("#mcl1").JPaging();
		});
     	function transferF(newclass1,term1,session1,select1){
     		$.get('transportQuery.php',{select:select1,newclass:newclass1,term:term1,session:session1,type:'transferS'}, function(data){
     			console.log(data);
     			if(data==1){
     				Swal.fire({
							  type: 'success',
							  text: 'successfully transfer Student to new class',
							  showConfirmButton: false,
							  timer: 3000
							}).then((result) => {
																
								parent.load_all_student_inclass(<?= $current_class?>,1);
								//location.reload(true);
								$('#iframeCont').fadeToggle(600);
					})
     			}else if(data == 2){
     				Swal.fire('No payment has been defined for the selected class section');
     				
     			}else if(data == 3){
     				Swal.fire('student already in that class');     				
     			}else if(data == 0){
     				Swal.fire('please try again');
     			}
     		});
     	}
    </script>
</center>
</div>
<?php
}
?>