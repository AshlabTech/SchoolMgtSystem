<?php
class Class_{
	private $id;
	private $status;
    private $dbCon;
	private $table = 'classes';


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
        return $this->fetch(['class_id' => $id]);
    }


    private function bySection($section){

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



}
