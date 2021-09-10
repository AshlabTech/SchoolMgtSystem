<?php
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.
include_once('../php/connection.php');
$error = '';
if (isset($_POST['timeframe'])) {
  $term_s = explode(';',mysqli_real_escape_string($conn, $_POST['term']));
  $caid = mysqli_real_escape_string($conn, $_POST['ca']);
  $sdate1 = explode('/',mysqli_real_escape_string($conn, $_POST['sdate']));
  $edate1 = explode('/',mysqli_real_escape_string($conn, $_POST['edate']));
  $sdate = $sdate1[2].'-'.$sdate1[1].'-'.$sdate1[0];
  $edate = $edate1[2].'-'.$edate1[1].'-'.$edate1[0];
  $sdate = ltrim($sdate,'--');
  $edate = ltrim($edate,'--');  
  $sectionid = mysqli_real_escape_string($conn, $_POST['section']);
  $termid = $term_s[0];
  $session_id = $term_s[1];
  //$sessionid = $term_s[1];
  $sqlr = $conn->query("SELECT * FROM score_time_frame WHERE term_id='$termid' AND section_id='$sectionid' AND session_id='$session_id' AND ca_id='$caid'");
  if ($sqlr->num_rows>0) {
    echo '<script type="text/javascript">window.location="score-entry-frame.php?act=3"; </script>';
    
  }else{

  //$q_chk = $conn->query("SELECT * FROM score_time_frame WHERE term_id='$term'")
  $q = $conn->query("INSERT INTO `score_time_frame`(`id`, `term_id`, `section_id`, `ca_id`,session_id, `start_date`, `end_date`) VALUES (NULL,'$termid', '$sectionid','$caid','$session_id', '$sdate','$edate')");
  if ($q) {
   // echo '<script type="text/javascript">window.location="score-entry-frame.php?act=1"; </script>';
  }else{
    $error = '<div class="alert alert-danger">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      <strong>Error!</strong> Cannot Create time please try again
    </div>';
  }
  }
}
if (isset($_POST['timeframeup'])) {
  $term_s = explode(';',mysqli_real_escape_string($conn, $_POST['term']));
  $id = mysqli_real_escape_string($conn, $_POST['id']);
  $caid = mysqli_real_escape_string($conn, $_POST['ca']);
  $sdate = mysqli_real_escape_string($conn, $_POST['sdate']);
  $edate = mysqli_real_escape_string($conn, $_POST['edate']);
  $sectionid = mysqli_real_escape_string($conn, $_POST['section']);
  $termid = $term_s[0];
  $session_id = $term_s[1];
  //$sessionid = $term_s[1];

  //$q_chk = $conn->query("SELECT * FROM score_time_frame WHERE term_id='$term'")
  $q = $conn->query("UPDATE score_time_frame SET `term_id`='$termid', session_id='$session_id', `section_id`='$sectionid', `ca_id`='$caid', `start_date`='$sdate',`end_date`='$edate' WHERE id='$id'");
  if ($q) {
    echo '<script type="text/javascript">window.location="score-entry-frame.php?act=2"; </script>';
  }else{
    $error = '<div class="alert alert-danger">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      <strong>Error!</strong> Cannot Create time please try again
    </div>';
  }
}




?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <!-- BOOTSTRAP STYLES-->
    <link href="../css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="../css/font-awesome.css" rel="stylesheet" />
       <!--CUSTOM BASIC STYLES-->
    <link href="../css/basic.css" rel="stylesheet" />
    <!--CUSTOM MAIN STYLES-->
    <link href="../css/custom.css" rel="stylesheet" />
    <!-- GOOGLE FONTS-->
    <link rel="stylesheet" href="../../css/font-awesome-4.7.0/css/font-awesome.min.css">
    
    
    <link href="../css/ui.css" rel="stylesheet" />
    <link href="../css/datepicker.css" rel="stylesheet" /> 
    <link href="../css/datatable/datatable1.css" rel="stylesheet" />
       
    <script src="../js/jquery-1.10.2.js"></script> 
    <script src="../js/jquery.dataTables.min.js"></script>

    <script type="text/javascript" src="../js/sweetalert.js"></script>
 
    <style>
        button{
            font-size: 1.4em !important;
            margin-right: 5px;

        }
        tbody tr{
            cursor: pointer;
        }
        #tSortable22_filter{
          margin-top: 5px;
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
.paging_two_button a{
  background:#439;
  color:white;
  padding: 5px 10px;
  border-radius: 5px;
}
.btnU{
    display: inline;
    padding: 8px !important;
    margin:0px 0px 4px 2px;
    color:white !important; 
    background: #666 !important;
    font-size: 0.9em !important;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
table tbody tr:focus{
  outline-color: #7c7;
  
}
    </style>
  
</head>
<body>
  <div id="ploader" on >  
  <h4 style='text-align:center'><img src='../../images/ajax-loader.gif' width="5%"></h4>
</div>
<?php
include("../php/headerF.php");
?>
            <div id="page-wrapper1" style="margin: 0px !important; display: none;">
                <div id="">
                    <div class="row" style="width: 96%;">
                        <div class="col-md-12">
                            <h1 class="page-head-line">Score Entry Time Frame</h1>
                         
    <?php
    if(isset($_REQUEST['act']) &&  @$_REQUEST['act']=='1')
    {
    echo '<div class="alert alert-success">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      <strong>Success!</strong> Timeframe Created Successfully.
    </div>';

    }else if(isset($_REQUEST['act']) &&  @$_REQUEST['act']=='2'){
      echo '<div class="alert alert-success">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      <strong>Success!</strong> timeframe updated Successfully.
    </div>';      
    }else if(isset($_REQUEST['act']) &&  @$_REQUEST['act']=='3'){
      echo '<div class="alert alert-warning">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      <strong>warning!</strong> Timeframe for that Section Already Created.
    </div>';      
    }
    echo $error;
    ?>
                    </div>
                </div>
                <!-- /. ROW  -->
                <script>
                  var timeId = '';
                </script>

          <div style="position: absolute;top: 0; left: 0; width: 100%;  overflow-y: none;"><div class="loader" id="load1" style="margin-left: 0px; margin-right: 0px; display:none;"></div>
          </div>
        <div style="margin:10px;">
          <div>
              <button class="btn btn-dark" style="background: #444; color:white; padding: 7px;font-size: 1em !important;" data-toggle="modal" data-target="#addModal"><i class="glyphicon glyphicon-plus"></i> Add </button>
          </div>
         </div>
         <div class="row" style="margin-left:-2px;padding: 10px;border-radius: 2px; border: 1px solid #ddd; font-family: arial;font-size: 1em; width: 100%;">
            <div class="col-sm-12" style="border: 1px solid #eee; box-shadow: 1px 2px 3px #ccc;">
              <table class="table table-hover table-condensed table-striped">
                <thead>
                  <th>Term</th>
                  <th>Section</th>
                  <th>Assessment</th>
                  <th>Start Date</th>
                  <th>End Date</th>
                  <th>Action</th>
                </thead>
                <tbody>
                  <?php
                      $get_time_frame = $conn->query("SELECT *, s.id as stfid FROM score_time_frame as s INNER JOIN term as t ON s.term_id=t.id INNER JOIN session as se ON s.session_id=se.section_id INNER JOIN school_section as sc ON s.section_id= sc.school_section_id");
                      if ($get_time_frame->num_rows>0) {
                        while($row = $get_time_frame->fetch_assoc()){
                          $stfid = $row['stfid'];
                          if($row['ca_id']==1){
                            $ca = 'CA 1';
                          }elseif ($row['ca_id']==2) {
                            $ca = 'CA 2';
                          }elseif ($row['ca_id']==3) {
                            $ca = 'CA 3';
                          }
                          elseif ($row['ca_id']==4) {
                            $ca = 'Exam';
                          }
                          ?>
                            <tr onclick="(function(){ timeId= <?php echo $stfid;?>;})();" tabindex='0' >
                              <td ><?php echo $row['description'].' '.$row['section'];?> Session</td>
                              <td><?php echo $row['section_name'];?></td>
                              <td style="width: 70px;"><?php echo $ca; ?></td>
                              <td><?php echo $row['start_date']; ?></td>
                              <td><?php echo $row['end_date']; ?></td>
                              <td style="width: 100px;">
                                <center>
                                      <button onclick="(function(){updateTimeframe(<?php echo $stfid; ?>,'<?php echo $row['description'];?>' , '<?php echo $row['section']; ?>','<?php echo $row['section_name'];?>');})()" class="fa fa-upload text-warning"></button>
                                      <button class="fa fa-remove text-danger" id="<?php echo 'del'.$stfid; ?>"></button>
                                  </center>
                              </td>
                            </tr>
                             <script>
                                         $("#<?php echo 'del'.$stfid; ?>").click(function(){
                                            var delid = <?php echo $stfid; ?>;
                                            var subject_name = "<?php echo $row['description'].' '.$row['section'].' for '.$row['section_name'].' session';?>";
                                            Swal.fire({
                                               title: 'Are you sure? You want to delete',
                                               text: subject_name,
                                               type: 'warning',
                                               showCancelButton: true,
                                               confirmButtonColor: '#3085d6',
                                               cancelButtonColor: '#d33',
                                               confirmButtonText: 'Yes'
                                               }).then((result) => {
                                                  if (result.value) {
                                                    //------send data to accept result-------------------------
                                                    $.post('ques/delete-timeframe.php',{id:delid}, function(data){
                                                      //$('#Out').html(data);
                                                      //alert(data);
                                                      if (data==0) {
                                                        Swal.fire({
                                                          type: 'error',
                                                          title: 'Cannot perform this action',
                                                          text: 'maybe server down or no network',
                                                          showConfirmButton: true,
                                                          });
                                                      }else if(data==1){
                                                        Swal.fire({
                                                          type: 'success',
                                                          title: 'Deleted.',
                                                          showConfirmButton: true,
                                                          }).then((result) => {
                                                           window.location="score-entry-frame.php";
                                                            });
                                                      }
                                                    });
                                          }
                                       });
                                    });
                                      </script>
                          <?php
                        }
                      }
                  ?>
                </tbody>
              </table>
            </div>
         </div>
                <!-- /. ROW  -->

<!-- Modal -->
  <div class="modal fade" id="addModal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add term</h4>
        </div>
        <div class="modal-body" id="formcontent">
          <form action="" method="POST">
            <center>
            <table>
              <tr>
                <td><label>term:</label></td>
                <td>
                  <select class="form-control" style="margin-bottom: 10px;" name="term">
                  <?php
                    $sqlterm = $conn->query("SELECT *, t.id as tid, s.section_id as sid FROM term as t INNER JOIN session as s");
                    if ($sqlterm->num_rows>0) {
                      while ($tr = $sqlterm->fetch_assoc()) {
                        $tidn = $tr['tid'];
                        $sidn = $tr['sid'];
                        ?>
                          <option value="<?php echo $tidn.';'.$sidn; ?>"><?php echo ucwords($tr['description']).' '.$tr['section'];?></option>
                        <?php
                      }
                      # code...
                    }
                  ?>
                  </select>
                    
                </td>
              </tr>

              <tr>
                <td> <label>Section:</label></td>
                <td>
                  <select class="form-control" name="section" id="section" style="margin-bottom: 10px;" required>
                    <option></option>
                    <?php
                      $sll = $conn->query("SELECT * FROM school_section");
                      if ($sll->num_rows>0) {
                        while ($sl = $sll->fetch_assoc()) {
                          $sl_id = $sl['school_section_id'];
                          ?>
                            <option value="<?php echo $sl_id;?>"><?php echo $sl['section_name'];?></option>
                          <?php
                        }
                      }
                    ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td><label>Assessment:</label></td>
                <td>
                  <select name="ca" id="caAll" class="form-control" required="">
                    <option></option>
                    <option value="1">CA 1</option>
                    <option value="2">CA 2</option>
                    <option value="3">CA 3</option>
                    <option value="4">Exam</option>
                  </select>
                  <br>
                </td>
              </tr>
              <tr>
                <td><label>Start Date:</label></td>
                <td><input type="date" name="sdate"  class="form-control jdate" style="width: 300px; margin-bottom: 10px;" required></td>
                <!-- <td><input type="text" name="sdate"  class="datepicker-here form-control" value=""data-language='en'  data-min-view="months" data-view="months" data-date-format="yyyy-mm-dd" style="width: 300px; margin-bottom: 10px;" required></td> -->
              </tr>
              <tr>
                <td><label>End Date:</label></td>
                <td><input type="date" name="edate" class="form-control jdate" value="" style="width: 300px; margin-bottom: 10px;" required></td>
                <!-- <td><input type="text" name="edate" class="form-control datepicker-here jdate" data-language="en" data-multiple-dates="3" data-multiple-dates-separator="- " data-position="top left" value="" style="width: 300px; margin-bottom: 10px;" required></td> -->
              </tr>
            </table>              
              <input type="submit" name="timeframe" class="form-control w-50" style="width: 150px; margin-top: 10px;">
            </center>
            <script src="../css/datepicker.js"></script> 

            <script >
             
            </script>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal -->
  <div class="modal fade" id="ModalUpdate" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add term</h4>
        </div>
        <div class="modal-body" id="outModal">
          
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
 
    <script>
        $(document).ready(function(){
        $('.table').dataTable({
           stateSave: true,
            "pageLength": 6
        });
        $('#tr11').children('td').css('background-color','#9d9');

       
       
        });

  function deleteT(){
    if(timeId==''){
      Swal.fire('select time frame');
    }else{
      alert(timeId);
      Swal.fire({
        type:'warning',
        title: 'are you sure you want to delete time frame',
        showCancelButton: true
        }).then((result) => {
          if (result.value) {
          $.post('ques/delete-timeframe.php',{id:timeId},function(data){
            console.log(data);
            if(data==1){
             Swal.fire({
              type: 'success',
              title: 'Deleted Successfully.',
              showConfirmButton: true,
              }).then((result) => {
              window.location="score-entry-frame.php?";
              });
            }else{
              Swal.fire('Cannot delete, server down or network problem');
            }
          });
          }
        })
    }
  }
  function updateTimeframe(e, term, session,section){
      
      $.post('ques/update_timeframe.php',{id:e, term:term, session:session,section:section}, function(data){
        
        $('#outModal').html(data);
        $("#ModalUpdate").modal({backdrop: "static"});
      });
  }
  $(document).ready(function(){
    document.getElementById('ploader').style.display = 'none';
    $('#page-wrapper1').show();
  });
  </script>
  
    <!-- BOOTSTRAP SCRIPTS -->
    <script src="../js/bootstrap.js"></script>
    <!-- METISMENU SCRIPTS -->
    

</body>
</html>
  