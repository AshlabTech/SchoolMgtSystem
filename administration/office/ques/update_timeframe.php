<?php
include_once('../../php/connection.php');

$id = $_POST['id'];
$tid = $_POST['term'];
$sid = $_POST['session'];
$scid = $_POST['section'];
$sqlr = $conn->query("SELECT * FROM score_time_frame WHERE id='$id'");
echo mysqli_error($conn);
$trow = $sqlr->fetch_assoc();
?>


<form action="score-entry-frame.php" method="POST">
            <center>
            <table>
              <tr>
                <td><label>term:</label></td>
                <td>
                  <input type="text" name="id" value="<?php echo $trow['id'];?>" style='display: none;'>
                  <select class="form-control" style="margin-bottom: 10px;" name="term">

                  <?php
                    $sqlterm = $conn->query("SELECT *, t.id as tid, s.section_id as sid FROM term as t INNER JOIN session AS s");
                    $sqlterm1 = $conn->query("SELECT *, t.id as tid, s.section_id as sid FROM term as t INNER JOIN session AS s ");
                    if ($sqlterm1->num_rows>0) {
                  /*    while ($tr1 = $sqlterm->fetch_assoc()) {
                            if($trow['term_id'] == $tr1['tid']){
                              $tidn = $tr1['tid'];
                              $sidn = $tr1['sid'];
                              ?>
                          <option value="<?php echo $tidn.';'.$sidn; ?>" <?php $tidn  ?>  ><?php echo ucwords($tr1['description']).' '.$tr1['section'];?></option>
                            <?php
                            }
                       }    */
                      while ($tr = $sqlterm1->fetch_assoc()) {
                       // if($trow['term_id'] != $tr['tid']){
                        $tidn = $tr['tid'];
                        $sidn = $tr['sid'];
                        ?>
                          <option value="<?php echo $tidn.';'.$sidn; ?>" <?php 
                            if ($tid == ucwords($tr['description']) && $sid==$tr['section']) {
                              echo "selected";
                            }

                          ?>><?php echo ucwords($tr['description']).' '.$tr['section'];?></option>
                        <?php
                    // }
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
                    
                    <?php
                      $sll = $conn->query("SELECT * FROM school_section");
                      $sll1 = $conn->query("SELECT * FROM school_section");
                      if ($sll->num_rows>0) {
                     /*   while ($sl = $sll->fetch_assoc()) {
                          $sl_id = $sl['school_section_id'];
                          if($trow['school_section_id'] == $sl['id']){
                            ?>
                            <option value="<?php echo $sl_id;?>"><?php echo $sl['section_name'];?></option>
                          <?php
                          }
                        }*/
                        while ($sl = $sll1->fetch_assoc()) {
                         // if($trow['school_section_id'] != $sl['id']){
                          $sl_id = $sl['school_section_id'];
                          ?>
                            <option value="<?php echo $sl_id;?>" <?php if($scid== $sl['section_name']){echo "selected";} ?>><?php echo $sl['section_name'];?></option>
                          <?php
                       // }else{}
                        }
                      }
                    ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td><label>Assessment:</label></td>
                <td>
                  <select name="ca" id="caAll" class="form-control" required=""  style="margin-bottom: 10px;">
                    <?php 
                    if ($trow['ca_id'] == 1)
                      {
                        ?>
                        <option value="1">CA 1</option>
                        <option value="2">CA 2</option>
                        <option value="3">CA 3</option>
                        <option value="4">Exam</option>
                      <?php
                      }
                      elseif($trow['ca_id'] ==2)
                      {?>
                    <option value="2">CA 2</option>
                    <option value="1">CA 1</option>
                    <option value="3">CA 3</option>
                    <option value="4">Exam</option>
                    <?php
                      }
                      else if($trow['ca_id'] ==3)
                      {?>
                    <option value="3">CA 3</option>
                    <option value="1">CA 1</option>
                    <option value="2">CA 2</option>
                    <option value="4">Exam</option>
                    <?php
                      }
                      elseif($trow['ca_id'] ==4)
                      {?>
                    <option value="4">Exam</option>
                     <option value="1">CA 1</option>
                    <option value="2">CA 2</option>
                    <option value="3">CA 3</option>
                     <?php }?>
                  </select>
                </td>
              </tr>
              <tr>
                <td><label>Start Date:</label></td>
                <td><input type="text" name="sdate" class="form-control jdate" value="<?php echo $trow['start_date']?>" style="width: 300px; margin-bottom: 10px;" required></td>
              </tr>
              <tr>
                <td><label>End Date:</label></td>
                <td><input type="text" name="edate" class="form-control jdate" value="<?php echo $trow['end_date']?>" style="width: 300px; margin-bottom: 10px;" required></td>
              </tr>
            </table>              
              <input type="submit" name="timeframeup" class="form-control w-50" style="width: 150px; margin-top: 10px;">
            </center>
          </form>
          <?php?>