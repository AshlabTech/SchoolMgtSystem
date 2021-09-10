<?php
class Student{
	private $id;
	private $status;
    private $dbCon;
    private $table= 'student_info';


	function set_id($id){ $this->id = $id;}

	public function __construct(){
		 try{
				 $this->dbCon = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME,DB_USER,DB_PASS);
				 $this->dbCon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}catch(PDOException $e){
				echo 'Database Error: '.$e->getMessage();
			 }
	}

	public function all(){
      
    }

    public function find($id){
        return $this->fetch(['student_info_id' => $id]);
    }

    public function getAllStudentByClass($class_id, $session_id){
        return $this->fetchStudentClass(['class_id' => $class_id, 'session_id' => $session_id], true);
    }
    
     public function getStudentClasses($student_info_id){
        return $this->fetchStudentClass(['student_info_id' => $student_info_id], true);
    }

    public function formatName($student){
        return ucwords($student->first_name) . ' ' . ucwords($student->other_name) . ' ' . ucwords($student->last_name);
    }

    private function bySection($section){

    }

    private function byAdmissionNumber($no){

    }

   

    private function update($arr){

    }


    private function destroy($arr){

    }

    private function fetch($arr, $all = false)
    {

        ///arr exp. ['id' = 1, 'name' => 'Abdul']
        $where_clause = '';
        foreach ($arr as $key => $value) {
           $where_clause .= " AND $key = $value  ";
        }

        $sql= "SELECT * FROM $this->table WHERE status != '0' $where_clause ";
        $stmt = $this->dbCon->prepare($sql);
        $stmt->execute();

        return json_decode(json_encode($all ? $stmt->fetchAll(PDO::FETCH_ASSOC) : $stmt->fetch(PDO::FETCH_ASSOC)));
    }

    private function fetchStudentClass($arr, $all = false, $current = true)
    {

        ///arr exp. ['id' = 1, 'name' => 'Abdul']
        $where_clause = '';
        foreach ($arr as $key => $value) {
           $where_clause .= " AND sc.$key = $value  ";
        }

        $sql_curerent= "SELECT * FROM student_classes as sc INNER JOIN $this->table  as s ON sc.student_info_id= s.student_info_id WHERE (sc.status = '1' || sc.status = '2') $where_clause ";
        $sql_all= "SELECT * FROM student_classes as sc INNER JOIN $this->table  as s ON sc.student_info_id= s.student_info_id WHERE sc.status != '0' $where_clause ";
        $sql = $current ? $sql_curerent : $sql_all;
        $stmt = $this->dbCon->prepare($sql);
        $stmt->execute();

        return json_decode(json_encode($all ? $stmt->fetchAll(PDO::FETCH_ASSOC) : $stmt->fetch(PDO::FETCH_ASSOC)));
    }



}
