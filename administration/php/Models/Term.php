<?php
class Term{
	private $id;
	private $status;
	private $table= 'term';


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
		return $this->fetch(['id'=> $id]);
    }


	private function fetch($arr, $all = false)
	{

		///arr exp. ['id' = 1, 'name' => 'Abdul']
		$where_clause = ' ';
		$sn = 1;
		foreach ($arr as $key => $value) {
			if ($sn == 1) {
				$where_clause = " WHERE  $key = $value   ";
			}else{
				$where_clause .= " AND $key = $value  ";
			}
			
			$sn++;
		}

		$sql = "SELECT * FROM $this->table   $where_clause ";
		$stmt = $this->dbCon->prepare($sql);
		$stmt->execute($arr);

		return json_decode(json_encode($all ? $stmt->fetchAll(PDO::FETCH_ASSOC) : $stmt->fetch(PDO::FETCH_ASSOC)));
	}

}
