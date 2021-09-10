<?php
class Result{
	private $id;
	private $status;
	private $table= '';


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

    public function isCodeValid($data){
        $stmt = $this->dbCon->prepare("select * from code where name = ? and class_id = ?");
		$stmt->execute($data);
		return json_decode(json_encode($stmt->fetch(PDO::FETCH_ASSOC) ));
    }

    public function isCodeUsed($code){
        $stmt = $this->dbCon->prepare("select * from result_code where code =?");
		$stmt->execute(array($code));
		return json_decode(json_encode($stmt->fetch(PDO::FETCH_ASSOC) ));
    }

    public function isCodeUsedByMe($used_obj, $student_info_id){
      return $used_obj->student_id === $student_info_id;
    }

    public function saveResultCode($arr){
        $stmt = $this->dbCon->prepare("INSERT INTO result_code (student_id, class_id, code, term_id, session_id, life, status) VALUES (?, ?, ?, ?, ?, ?, ?) ");
		return $stmt->execute($arr);

    }

    public function updateResultCode($arr){
        $stmt = $this->dbCon->prepare("UPDATE result_code SET life = ? WHERE student_id=? AND class_id = ? AND code = ? AND term_id = ?");
		return $stmt->execute($arr);

    }
    



}
