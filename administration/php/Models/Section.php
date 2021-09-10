<?php
class Section{
	private $id;
	private $status;
	private $table= 'school_section';


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

    private function find($id){

    }



}
