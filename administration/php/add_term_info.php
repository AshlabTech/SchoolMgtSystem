<?php
		//  make mysqli database connection
	  include_once('connection.php');	


		//gather all ajax posted data     
	$date = date('Y-m-d');
    $term_id = $_POST['term_id'];
    $session_id = $_POST['session_id'];
    $section_id = $_POST['section_id'];
    $resumption = $_POST['resumption'];
    $vacation = $_POST['vacation'];
    $next_resumption = $_POST['next_resumption'];
    $toAll = $_POST['toAll'];    
    $id = $_POST['id'];
    $type = $_POST['type'];
    if($_POST['type']=='add'){
        if($toAll == 'all'){
        $run = $conn->query("SELECT * FROM `school_section`") or die (mysqli_error($conn));
        $sections = [];
        if($run->num_rows >0){
            while($row = $run->fetch_assoc()){
                $section_id = $row['school_section_id'];
                $runx = $conn->query("SELECT * FROM manage_term_info WHERE  `section_id`= '$section_id' AND `term_id`='$term_id' AND `session_id`='$session_id'");
                if($runx->num_rows < 1){                    
                    $sql = "INSERT INTO `manage_term_info`(`id`, `section_id`, `term_id`, `session_id`, `resumption`, `vacation`, `next_resumption`, `status`)
                        VALUES (NULL, '$section_id', '$term_id', '$session_id', '$resumption', '$vacation', '$next_resumption', '1')";
                    $run = $conn->query($sql) or die(mysqli_error($conn));                
                }
            }
        }
        
    }else{
        $runx = $conn->query("SELECT * FROM manage_term_info WHERE  `section_id`= '$section_id' AND `term_id`='$term_id' AND `session_id`='$session_id'");
        if($runx->num_rows < 1){
            $sql = "INSERT INTO `manage_term_info`(`id`, `section_id`, `term_id`, `session_id`, `resumption`, `vacation`, `next_resumption`, `status`)
                     VALUES (NULL, '$section_id', '$term_id', '$session_id', '$resumption', '$vacation', '$next_resumption', '1')";
            $run = $conn->query($sql) or die(mysqli_error($conn));
        }
    }
  $last_id = $conn->insert_id;        
   echo json_encode(['id'=>$last_id, 'success'=>200]);
}else{    
    $sql = "UPDATE `manage_term_info` SET `section_id`='$section_id',
                `resumption`='$resumption',`vacation`='$vacation',`next_resumption`='$next_resumption' WHERE id  ='$id'";
    $run = $conn->query($sql) or die(mysqli_error($conn));
    echo 200;

}
?>