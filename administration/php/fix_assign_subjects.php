 
 <?php
	include_once('connection.php');
		define('DB_HOST', 'localhost');
	define('DB_NAME', 'elmaasu1_db');
	define('DB_USER', 'root');
	define('DB_PASS', '');
	/* define('DB_NAME', 'elmaasu1_db');
	define('DB_USER', 'elmaasu1_ruser');
	define('DB_PASS', '_DU=yAUqdfuV'); */
	
	
	

	$db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4', DB_USER, DB_PASS);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
	date_default_timezone_set('Africa/Lagos');
	error_reporting(E_ALL);
	
	

	$section_id = 4;
	$session_id = 8;


	$ditinct_staff_stmt = $db->prepare("select distinct staff_info_id from staff_subjects where session_id = $session_id and status='1' ");
	$ditinct_staff_stmt->execute();
	if ($ditinct_staff_stmt->rowCount() > 0) {
		$rows = $ditinct_staff_stmt->fetchAll();
		foreach ($rows as $key => $staff_) {
			$staff_info_id = $staff_['staff_info_id'];

			$ditinct_staff_subject_stmt = $db->prepare("select distinct subject_id from staff_subjects where session_id = $session_id and staff_info_id = $staff_info_id and status='1' ");
			$ditinct_staff_subject_stmt->execute();
			if ($ditinct_staff_subject_stmt->rowCount() > 0) {
				$ditinct_staff_subject_stmt_rows = $ditinct_staff_subject_stmt->fetchAll();

				foreach ($ditinct_staff_subject_stmt_rows as $key => $subject_) {
					$subject_id = $subject_['subject_id'];

					$staff_subjects_stmt = $db->prepare("select * from staff_subjects where session_id = $session_id and staff_info_id = $staff_info_id  and subject_id = $subject_id and status='1' ");
					$staff_subjects_stmt->execute();
					if ($staff_subjects_stmt->rowCount() > 0) {
						$staff_subjects_stmt_rows = $staff_subjects_stmt->fetchAll();
						foreach ($staff_subjects_stmt_rows as $key => $staff_subject_) {
							$class_id = $staff_subject_['class_id'];
							$section_id = $staff_subject_['section_id'];
							$term_id_ = $staff_subject_['term_id'];

							for ($term_id = 1; $term_id <= 3; $term_id++) {
								if ($term_id_ != $term_id) {
									$stmtExist = $db->prepare("select * from staff_subjects where section_id = ? and staff_info_id = ? and subject_id = ? AND session_id = ? 
								and term_id = ? AND class_id = ? and status='1' ");
									$stmtExist->execute(
										array(
											$section_id,
											$staff_info_id,
											$subject_id,
											$session_id,
											$term_id,
											$class_id
										)
									);

									if ($stmtExist->rowCount() <= 0) {
										$stmt4 = $db->prepare("insert into staff_subjects (staff_info_id,subject_id,section_id,session_id,term_id,class_id) values 
										('$staff_info_id','$subject_id','$section_id','$session_id', $term_id,'$class_id') ");
										$stmt4->execute();
										echo 'Done...';
										echo '<hr/>';
									}
								}
							}
						}
					}
				}
			}


		}
	}

			
			
			
											

?>

						
					