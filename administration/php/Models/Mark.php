<?php
class Mark
{
	private $id;
	private $status;
	private $dbCon;
	private $table = 'contineous_accessment';
	private $grades = ['A', 'B', 'C', 'D', 'E', 'F'];


	function set_id($id)
	{
		$this->id = $id;
	}

	public function __construct()
	{

		try {
			$this->dbCon = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
			$this->dbCon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			echo 'Database Error: ' . $e->getMessage();
		}
	}

	public function exist($data)
	{
		$arr = [
			'student_info_id'=>$data['student_info_id'],
			'class_id'=>$data['class_id'],
			'session_id'=> $data['session_id'],
			'subject_id'=>$data['subject_id'],
			'term_id'=>$data['term_id']
		];
		
		return $this->fetch($arr);
	}

	public function get($data)	{
		return $this->fetch($data);
	}

	public function allAffectiveDomain()	{
		$sql = "SELECT * FROM affective_domain";
		$stmt = $this->dbCon->prepare($sql);
		$stmt->execute();
		return json_decode(json_encode($stmt->fetchAll(PDO::FETCH_ASSOC) ));
	}


	public function allPsycomotor()	{
		$sql = "SELECT * FROM psycomotor";
		$stmt = $this->dbCon->prepare($sql);
		$stmt->execute();
		return json_decode(json_encode($stmt->fetchAll(PDO::FETCH_ASSOC) ));
	}


	public function getStudentPsycomotor($arr)	{
		$sql = "SELECT * FROM student_p_traits WHERE student_id = ? AND session_id = ? AND term_id = ? AND psycomotor_id = ? ";
		$stmt = $this->dbCon->prepare($sql);
		$stmt->execute($arr);
		return json_decode(json_encode($stmt->fetch(PDO::FETCH_ASSOC) ));
	}

	public function getStudentAffectiveDomain($arr)	{
		$sql = "SELECT * FROM student_a_traits WHERE student_id = ? AND session_id = ? AND term_id = ? AND 	affective_domain_id = ?";
		$stmt = $this->dbCon->prepare($sql);
		$stmt->execute($arr);
		return json_decode(json_encode($stmt->fetch(PDO::FETCH_ASSOC) ));
	}


	public function getGrade($total)
	{

		switch ($total) {
			case $total >= 75 && $total <= 100:
				return $this->grades[0];
				break;
			case $total >= 60 && $total <= 75:
				return $this->grades[1];
				break;
			case $total >= 50 && $total <= 59:
				return $this->grades[2];
				break;
			case $total >= 40 && $total <= 49:
				return $this->grades[3];
				break;
			case $total >= 30 && $total <= 39:
				return $this->grades[4];
				break;
			case $total <= 29:
				return $this->grades[5];
				break;
		}
	}
	
		public function rate($total)
	{

		switch ($total) {
			case 5:
				return 'A';
				break;
			case 4:
				return 'B';
				break;
			case 3:
				return 'C';
				break;
			case 2:
				return 'D';
				break;
			case 1:
				return 'E';
				break;
			case '':
				return '-';
				break;
		}
	}

	public function formatPosition($postition)
	{
		$last_no = substr($postition, -1);
	
		switch ($last_no) {
			case 1:
				return $postition.'st';
				break;
			case 2:
				return $postition.'nd';
				break;
			case 3:
				return $postition.'rd';
				break;
			default:
			return $postition.'th';
				break;
		}
	}
	
	
public function classDistinctScores($arr){
		$sql = "SELECT DISTINCT SUM(total) total FROM `contineous_accessment` WHERE session_id = ? AND term_id = ? AND class_id = ? GROUP BY student_info_id ORDER by total DESC";
		$stmt = $this->dbCon->prepare($sql);
		$stmt->execute($arr);
		return json_decode(json_encode($stmt->fetchAll(PDO::FETCH_ASSOC)));
	}
	
	
		public function getStudentTotalAttendance($arr)	{
		$sql = "SELECT COUNT(attendance_id) total FROM student_attendance WHERE student_info_id = ? AND session_id = ? AND term_id = ? AND class_id = ? AND  status = '1'";
		$stmt = $this->dbCon->prepare($sql);
		$stmt->execute($arr);
		$row = json_decode(json_encode($stmt->fetch(PDO::FETCH_ASSOC) ));
		return $row->total;
	}
	
	
	public function save($data)
	{
		return $this->insert($data);
	}

	public function change($data, $data2)
	{
		return $this->update($data, $data2);
	}

	private function insert($arr)
	{
		$columns = '';
		$values = '';
		$sn = 1;
		foreach ($arr as $key => $value) {
			$columns .= " " . ($sn == 1 ? ' ' : ' , ') . "  $key    ";
			if ($sn == count($arr))
				$values .= " " . ($sn == 1 ? ' ' : ' , ') . "  '$value' ";
			else
				$values .= " " . ($sn == 1 ? ' ' : ' , ') . "  $value ";
			$sn++;
		}
		$sql = "INSERT INTO $this->table ($columns)  VALUES ($values) ";
		

		$stmt = $this->dbCon->prepare($sql);
		$stmt->execute($arr);
	}


	private function update($fields_to_update_arr, $where_to_update_arr){
		$fields_to_update = '';
		$where_to_update = '';
		$sn = 1;
		foreach ($fields_to_update_arr as $key => $value) {
			if ($sn == count($fields_to_update_arr))
				$fields_to_update .= " " . ($sn == 1 ? ' ' : ' , ') . "   $key = '$value'  ";
			else
				$fields_to_update .= " " . ($sn == 1 ? ' ' : ' , ') . "   $key = $value  ";
			$sn++;
		}

		$sn = 1;
		foreach ($where_to_update_arr as $key => $value) {
			$where_to_update .= " " . ($sn == 1 ? ' ' : ' , ') . "   $key = $value  ";
			$sn++;
		}
		$sql = "UPDATE $this->table SET $fields_to_update  WHERE $where_to_update ";
		$stmt = $this->dbCon->prepare($sql);
		$stmt->execute();
	}

public function getStudentTotalScore($arr)	{
		$sql = "SELECT SUM(total) total_score FROM contineous_accessment WHERE student_info_id = ? AND session_id = ? AND term_id = ? AND class_id = ? AND  status = '1'";
		$stmt = $this->dbCon->prepare($sql);
		$stmt->execute($arr);
		return json_decode(json_encode($stmt->fetch(PDO::FETCH_ASSOC) ));
	}

	private function bySection($section)
	{
	}

	private function fetch($arr, $all = false)
	{

		///arr exp. ['id' = 1, 'name' => 'Abdul']
		$where_clause = '';
		foreach ($arr as $key => $value) {
			$where_clause .= " AND $key = $value  ";
		}

		$sql = "SELECT * FROM $this->table WHERE status != '0' $where_clause ";
		$stmt = $this->dbCon->prepare($sql);

		$stmt->execute();

		return json_decode(json_encode($all ? $stmt->fetchAll(PDO::FETCH_ASSOC) : $stmt->fetch(PDO::FETCH_ASSOC)));
	}
}
