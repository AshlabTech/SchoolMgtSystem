<?php
	require_once('fpdf181/fpdf.php');
	session_start();
	// Create instance of the class_alias
	$pdf = new FPDF();
	//var_dump(get_class_methods($pdf));

	//$pdf  -> AddPage('paper orientation 1.e 1. P or Portrait 2. L or Landscape','size :A3,A4,A5,Letter', rotation: multiple of 90);
	$pdf  -> AddPage('P','A4',360);
	

	//$pdf-> setTitle('Name of the doc. or name that must appear on the web tab');
	$pdf-> setTitle('nghmb');
	
	//$pdf-> setFont('Arial','','18');
		$staffid = $_SESSION['staffid_4printout'];
		include_once('get_staff_infomation_by_cno.php');
		if($email == ''){
			$email = 'no email address';
		}
		
	$pdf->Image('../images/logo.png',10,20,150,18);
	$pdf->Image('../images/passport.png',170,17,30);

	$pdf-> setFont('Helvetica','','11');
	$pdf->Ln(20);
	$pdf-> Cell(0,10,' Staff Information Slip ',0,1,'C');
	$pdf->Ln(3);
	
	$pdf-> setFont('Helvetica','','42');
	$pdf-> Cell(5,3,'. ',0,0,'L');
	$pdf-> setFont('Helvetica','','11');
	$pdf-> Cell(70,10,'Staff Bio-Data Info',0,0,'L');
	$pdf-> Cell(0,10,'C/NO :'.$cno.'  PSN :'.$psn,0,1,'R');
	
	$pdf-> setFont('Arial','','8');
		$pdf-> Cell(75,8,'Name : '.$fullname,1,0,'L');
		$pdf-> Cell(75,8,'Sex  : '.$gender,1,0,'L');
		$pdf-> Cell(0,8,'D.O.B : '.$dob,1,1,'L');
		$pdf-> Cell(55,8,'Marital status : '.$marital_status,1,0,'L');
		$pdf-> Cell(40,8,'Religion : '.$religion,1,0,'L');
		$pdf-> Cell(35,8,'Tribe : '.$Language_tribe,1,0,'L');
		$pdf-> Cell(0,8,'Nationality : '.$Citizenship,1,1,'L');
		
		
		$pdf-> Cell(35,8,'State : '.$state_title,1,0,'L');
		$pdf-> Cell(35,8,'L.G.A : '.$lga_title,1,0,'L');
		$pdf-> Cell(65,8,'Email  : '.$email,1,0,'L');
		$pdf-> Cell(0,8,'Sen/Zone : '.$zone,1,1,'L');
	
	
		
		$pdf-> Cell(45,8,'Phone No. : '.$pno,1,0,'L');
		$pdf-> Cell(0,8,'Address : '.$address,1,1,'L');
	
		
		$pdf-> Cell(70,8,'Next of Kin : '.$next_of_kin_name,1,0,'L');
		$pdf-> Cell(65,8,'Relationship : '.$next_of_kin_relationship,1,0,'L');
		$pdf-> Cell(0,8,'Next of Kin Phone N0. : '.$next_of_kin_number,1,1,'L');
		$pdf-> Cell(0,8,'Next of Kin Address : '.$next_of_kin_address,1,1,'L');
	
			$pdf->Ln(2);
	
	$pdf-> setFont('Helvetica','','42');
	$pdf-> Cell(5,3,'. ',0,0,'L');
	$pdf-> setFont('Helvetica','','11');
	$pdf-> Cell(0,8,' Official Employment Info ',0,1,'L');
	
	$pdf-> setFont('Arial','','8');
		$pdf-> Cell(100,8,'Organization Name  :  Niger State Hospitals Management Board',1,0,'L');
		$pdf-> Cell(0,8,'Org. Address  : Block \'F\' Old Secretariat Minna ',1,1,'L');
		
		
		$pdf-> Cell(55,8,' First Appointment  :  '.$date_of_first_appointment,1,0,'L');
		$pdf-> Cell(55,8,' Present Appointment  : '.$last_promotion_date,1,0,'L');
		$pdf-> Cell(30,8,$department_level_code.' : '.$level_step,1,0,'L');
		$pdf-> Cell(0,8,' Qualifications : '.$other_qualification,1,1,'L');
		
		$pdf-> Cell(85,8,'Specialty : '.$specialization,1,0,'L');
		$pdf-> Cell(45,8,'Rank : '.$rank_title,1,0,'L');
		$pdf-> Cell(0,8,'Department : '.$department,1,1,'L');
		
		
		$pdf-> Cell(20,8,'PFN: '.$fno,1,0,'L');
		$pdf-> Cell(65,8,'Station : '.$station_name,1,0,'L');
		$pdf-> Cell(50,8,'Date Posted Station: '.$date_posted_to_station,1,0,'L');
		$pdf-> Cell(0,8,'Date of Retirement By Age : '.$retirement_by_age,1,1,'L');
		
		$pdf-> Cell(75,8,'Date of Retirement By Service  :  '.$retirement_by_service,1,0,'L');
		$pdf-> Cell(45,8,'P & P Date  : '.$appointment_type_pensionable_date,1,0,'L');
		$pdf-> Cell(0,8,'Confirmation Date : '.$appointment_type_confirmation_date,1,1,'L');
		
		$pdf-> Cell(45,8,'Year/Service  :  '.$year_in_service,1,0,'L');
		$pdf-> Cell(110,8,'Schedule Of Duty  : '.$schedule_of_duty,1,0,'L');
		$pdf-> Cell(0,8,'Staff Category : '.$staff_category,1,1,'L');
		
	
	$pdf->Ln(3);
	
	$pdf-> setFont('Helvetica','','42');
	$pdf-> Cell(5,3,'. ',0,0,'L');
	$pdf-> setFont('Helvetica','','11');
	$pdf-> Cell(0,8,'Academic/Edu. Info ',0,1,'L');
	
	$pdf-> setFont('Arial','','8');
		$pdf-> Cell(45,8,'Highest Qualification : '.$qualification_title,1,0,'L');
		$pdf-> Cell(55,8,'Date Obtained : '.$year_of_qualification,1,0,'L');
		$pdf-> Cell(0,8,'Specialty  : '.$specialization,1,1,'L');
		
		$pdf-> Cell(100,8,'Institution : '.$college,1,0,'L');
		$pdf-> Cell(0,8,'Other Specialties : '.$other_spacilazation,1,1,'L');
	
	$pdf->Ln(3);
			$pdf-> setFont('Helvetica','','42');
	$pdf-> Cell(5,3,'. ',0,0,'L');
	$pdf-> setFont('Helvetica','','11');
	$pdf-> Cell(0,8,'Financial/Salary Info ',0,1,'L');
	
	$pdf-> setFont('Arial','','8');
		//$pdf-> Cell(55,8,'Basic Salary: '.$basic_salary,1,0,'L');
		//$pdf-> Cell(75,8,'Total Allowance : '.$total_allowance,1,0,'L');
		
		$pdf-> Cell(45,8,'Bank: '.$bank_name,1,0,'L');
		$pdf-> Cell(85,8,'Account Name : '.$account_name,1,0,'L');
		$pdf-> Cell(0,8,'Account Number : '.$account_number,1,1,'L');
		
		$pdf-> Cell(50,8,'Account Type : '.$account_type,1,0,'L');
		$pdf-> Cell(50,8,'BVN : '.$bvn,1,0,'L');
		$pdf-> Cell(50,8,'Sort Code : '.$bank_sortcode,1,0,'L');
		$pdf-> Cell(0,8,'Signature : '.$staff_signature,1,1,'L');

	
		$pdf-> Cell(75,8,'PFA Name  : '.$PFA_Name,1,0,'L');
		$pdf-> Cell(75,8,'PFA Pin  : '.$PFA_Pin,1,0,'L');
		$pdf-> Cell(0,8,'Basic Salary : '.$basic_salary,1,1,'L');
		
		$pdf-> Cell(25,8,'Allowances  :',1,0,'L');
		$pdf-> Cell(0,8,' '.$allowances,1,1,'L');
	
	$pdf->Ln(3);
	
	$pdf-> setFont('Helvetica','','42');
	$pdf-> Cell(5,3,'. ',0,0,'L');
	$pdf-> setFont('Helvetica','','11');
	$pdf-> Cell(0,8,'Practice/Prof. License Info ',0,1,'L');
	
	$pdf-> setFont('Arial','','8');
		$pdf-> Cell(55,8,'License Name  :  '.$professional_license_name,1,0,'L');
		$pdf-> Cell(55,8,'Issuing Body  : '.$license_issuing_body,1,0,'L');
		$pdf-> Cell(35,8,'Issued Date : '.$license_issuing_date,1,0,'L');
		$pdf-> Cell(0,8,'Expire Date : '.$license_expiry_date,1,1,'L');
		
	
	$pdf->Ln(3);
	
	$pdf-> setFont('Helvetica','','42');
	$pdf-> Cell(5,3,'. ',0,0,'L');
	$pdf-> setFont('Helvetica','','11');
	$pdf-> Cell(0,8,'In-Service Info',0,1,'L');
	
	$pdf-> setFont('Arial','','8');
	
		$pdf-> Cell(75,8,'Course Title :'.$CourseOf_Study,1,0,'L');
		$pdf-> Cell(0,8,'Institution :'.$OnCourse_Institution_Name,1,1,'L');
		
		$pdf-> Cell(35,8,'Duration :'.$Course_Duration,1,0,'L');
		$pdf-> Cell(35,8,'Start Date :'.$Start_Date_course,1,0,'L');
		$pdf-> Cell(35,8,'End Date :'.$End_Date_course,1,0,'L');
		$pdf-> Cell(0,8,'Exp. Qual. : '.$Course_Expected_Qualification,1,1,'L');
	
	$pdf->PageNo(1)	;
	
	$pdf->Output();

?>