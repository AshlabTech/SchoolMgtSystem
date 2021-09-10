<?php

use function PHPSTORM_META\type;

session_start();
include_once('../php/connection.php');
include_once('../../php/objects.php');



$errormsg = '';
$action = "add";

if (empty($user_obj->auth())) {
?>
	<script>
		window.top.location.reload();
	</script>
<?php
}

$user_id = $user_obj->auth()->staff_info_id;
$query_string = isset($_GET['string']) ? $_GET['string'] : '' ;


$tr  = '';
$sn =1;
if(isset($_POST['student_id'])){
    $student_ids = $_POST['student_id'];
    $term_id = $_POST['term_id'];
    $class_id = $_POST['class_id'];
    $subject_id = $_POST['subject_id'];
    $session_id = $_POST['session_id'];
    
    foreach ($student_ids as $key => $student_id) {
        $student_name = $student_obj->formatName($student_obj->find($student_id));
        $ca1 = empty($_POST['ca1'][$student_id]) ? 0 : intval($_POST['ca1'][$student_id]);
         $ca2 = empty($_POST['ca2'][$student_id]) ? 0 : intval($_POST['ca2'][$student_id]);
         $ca3 = empty($_POST['ca3'][$student_id]) ? 0 : intval($_POST['ca3'][$student_id]);
         $ca4 = empty($_POST['ca4'][$student_id]) ? 0 : intval($_POST['ca4'][$student_id]);
         $ca5 = empty($_POST['ca5'][$student_id]) ? 0 : intval($_POST['ca5'][$student_id]);
         $exam = empty($_POST['exam'][$student_id]) ? 0 : intval($_POST['exam'][$student_id]);
         $total = $ca1 + $ca2 + $ca3 + $ca4 + $ca5 + $exam;

        
        $data['student_info_id'] = $student_id;
        $data['session_id'] = $session_id;
        $data['class_id'] = $class_id;
        $data['term_id'] = $term_id;
        $data['subject_id'] = $subject_id;
        $data['ca1'] = $ca1;
        $data['ca2'] = $ca2;
        $data['ca3'] = $ca3;
        $data['ca4'] = $ca4;
        $data['ca5'] = $ca5;
        $data['exam'] = $exam;
        $data['total'] = $total;
        $data['grade'] = $mark_obj->getGrade($total);
        

            if(!empty($mark_obj->exist($data))){
               
                $id = $mark_obj->exist($data)->id;
                $update['id'] = $id;
                $arr = [
                    'ca1'=>$data['ca1'],
                    'ca2'=>$data['ca2'],
                    'ca3'=> $data['ca3'],
                    'ca4'=>$data['ca4'],
                    'ca5'=>$data['ca5'],
                    'exam'=>$data['exam'],
                    'total'=>$data['total'],
                    'grade'=>$data['grade'],
                ];
                $save_marks = $mark_obj->change($arr, $update);
            }else {
               
                $save_marks = $mark_obj->save($data);
            }

            
        
                $tr .='
                    <tr>
                        <td class="text-center">'.$sn++.'</td>
                        <td>'.$student_name.'</td>
                        <td class="text-center">'.$ca1.'</td>
                        <td class="text-center">'.$ca2.'</td>
                        <td class="text-center">'.$ca3.'</td>
                        <td class="text-center">'.$ca4.'</td>
                        <td class="text-center">'.$ca5.'</td>
                        <td class="text-center">'.$exam.'</td>
                        <td class="text-center">'.$total.'</td>
                    </tr>
                ';
        
     
    }
}else {
    ?>
	<script>
		window.top.location = 'input-scoreA.php';
	</script>
<?php
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/bootstrap.css">
    <title>Preview Result</title>

    <style>
           .container{
            width: 100%;
            margin: 20px auto;
           }
    </style>
</head>
<body>
    
    <?php 
        if(!empty($tr)){
?>
        <div class="container">
            <div class="card">
            <p class="text-center"><img src="../../../images/<?= $school_image; ?>" alt="" width="100px"></p>
            <h3 class="text-center"><?= $school_name; ?>
            <br>  ASSESSMENT SHEET
            </h3>
                <table class="table table-bordered table-stripped" >
                    <thead>
                        <tr>
                            <th class="text-center">SN</th>
                            <th class="text-center">NAME</th>
                            <th class="text-center">CA1</th>
                            <th class="text-center">CA2</th>
                            <th class="text-center">CA3</th>
                            <th class="text-center">PROJECT</th>
                            <th class="text-center">ASS</th>
                            <th class="text-center">EXAM</th>
                            <th class="text-center">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?= $tr; ?>
                    </tbody>
                </table>
            </div>
        </div>

<?php
        }
    ?>
</body>
</html>