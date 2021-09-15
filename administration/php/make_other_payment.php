<?php
		//  make mysqli database connection
	  include_once('connection.php');
	  
		//variables
		$student_info_id = 0;
		$class = 0;
		$term = 0;
		$month = 0;
		$day = 0;
		$amount = 0;
		$payee_name = '';
		$session_id = 0;
		$payment_number = 0;
		$total_amount = 0;
		$ballance = 0;
		$total_amount_paid = 0;
		$new_total_paid = 0;
		$status = '0';


		//gather all ajax posted data
    $paidfor = $_POST['paidfor'];
	 $student_info_id = mysqli_real_escape_string($conn,$_POST['student_info_id']);
	 $class = mysqli_real_escape_string($conn,$_POST['class_id']);
	 $term = 0;	 
	 $day = date('d');
	 $month = date('m');
	 $year = date('Y');
	 $amount = $_POST['paidamount'];
	 $type = $_POST['type'];
	 $payee_name = preg_replace('#[^a-zA-Z]#', '', $_POST['paidby']);
	 $session_id = mysqli_real_escape_string($conn,$_POST['session_id']);
	 $payment_type = mysqli_real_escape_string($conn,$_POST['payment_type']);
	 $ballance = mysqli_real_escape_string($conn,$_POST['balance']);
	 $description = mysqli_real_escape_string($conn,$_POST['description']);
     
		$date = date('Y-m-d');
if($_POST['whichPayment']=='make'){


		//if payment is cash generate payment number
		if($payment_type==2){
			$payment_number = 'OPT'.@date('y').@date('m').@date('d').(@date('h')-1).@date('i').@date('s'); 
		}else{
	        $payment_number = mysqli_real_escape_string($conn,$_POST['teller_no']);
        }
         $run = $conn->query("SELECT * FROM misc_payments as m INNER JOIN misc_payment_details as d ON d.id= m.misc_payment_detail_id WHERE (d.category='$type' AND d.id = '$paidfor') AND m.student_info_id= '$student_info_id' AND m.year='$year' ") or die (mysqli_error($conn));         
         if($run->num_rows<1){
             $sql2 = "INSERT INTO `misc_payments`(`id`, `student_info_id`, `class_id`, `year`, `month`, `day`, `dateTime`, `payment_type`,
             `session_id`, `payment_number`, `session_paid`, `payment_madeBy`, `amount_paid`, `ballance`, `status`, `misc_payment_detail_id`, `description`)
             VALUES (null, '$student_info_id','$class','$year','$month','$day',now(),'$payment_type','$session_id',
             '$payment_number','0','$payee_name','$amount','$ballance','1', '$paidfor', '$description')";        
             $run = $conn->query($sql2);
 
             $search = 'Duplicate entry';
             if(preg_match("/{$search}/i", mysqli_error($conn))) {
                 echo  201;
                 exit();
             }        
             $last_id = $conn->insert_id;        
             $run3 = $conn->query("SELECT payment_number FROM misc_payments WHERE id = '$last_id'");
             $rw = $run3->fetch_assoc();
             $payment_number = $rw['payment_number'];
             $sql = "INSERT INTO `misc_payment_history`(`id`, `misc_payment_id`,payment_number, `amount`, `balance`, `payment_date`, `status`) VALUES (
                 null, '$last_id','$payment_number', '$amount', '$ballance', '$date', '1')";
             mysqli_query($conn,$sql) or die(mysqli_error($conn));
             echo 200;
             //session_id 			
        }else{
            echo 207;
        }         
}else{
    
    $run = $conn->query("SELECT *,m.id as mid FROM misc_payments as m INNER JOIN misc_payment_details as d ON d.id= m.misc_payment_detail_id WHERE (d.category='$type' AND d.id = '$paidfor') AND m.student_info_id= '$student_info_id' AND m.year='$year' LIMIT 1 ") or die (mysqli_error($conn));         
    if($run->num_rows>0){
        $row = $run->fetch_assoc();
        $payment_number = $row['payment_number'];
        $total_amount_paid = $row['amount_paid'] + $amount;
        $id = $row['mid'];
        $expectedamount = $_POST['expectedamount'];
        if($payment_type==2){
			$payment_number = $payment_number; 
		}else{
            $run3 = $conn->query("SELECT DISTINCT payment_number FROM misc_payment_history");
            while($rowx = $run3->fetch_assoc()){
                if ($rowx['payment_number']== mysqli_real_escape_string($conn,$_POST['teller_no'])) {
                    echo 201;
                    exit();
                }
            }
	        $payment_number = mysqli_real_escape_string($conn,$_POST['teller_no']);
        }
        
        $sql2 = "UPDATE `misc_payments` SET amount_paid = '$total_amount_paid', `description`='$description',  `ballance` = ('$expectedamount' - '$total_amount_paid' ) WHERE payment_number='$payment_number'";        
        $run = $conn->query($sql2);
            
        $sql = "INSERT INTO `misc_payment_history`(`id`, `misc_payment_id`,payment_number, `amount`, `balance`, `payment_date`, `status`) VALUES (
            null, '$id','$payment_number', '$amount', '$ballance', '$date', '1')";
        mysqli_query($conn,$sql) or die(mysqli_error($conn));
        echo 200;
        //session_id 			
   }else{
       echo 2027;
   }         

}
?>