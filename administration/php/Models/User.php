<?php
class User
{
    private $id;
    private $status;
    private $dbCon;
    private $table = 'staff_info';


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

    public function all()
    {
    }

    public function find($id)
    {
       return $this->fetch(['staff_info_id'=> $id]);
    }

    public function get($data)
    {
       return $this->fetch(['staff_info_id'=> $data],true);
    }


    public function bySection($section)
    {
    }

    public   function auth()
    {
        if (isset($_SESSION['staff_info_id'])) {
            $id = $_SESSION['staff_info_id'];
            return $this->find($id);
        } else {
            return null;
        }
    }

    public function byAdmissionNumber($no) {


    }

    private function fetch($arr, $all = false)
    {

        ///arr exp. ['id' = 1, 'name' => 'Abdul']
        $where_clause = '';
        foreach ($arr as $key => $value) {
           $where_clause .= " AND $key = $value  ";
        }

        $sql= "SELECT * FROM $this->table WHERE status = '1' $where_clause ";
        $stmt = $this->dbCon->prepare($sql);
        $stmt->execute();

        return json_decode(json_encode($all ? $stmt->fetchAll(PDO::FETCH_ASSOC) : $stmt->fetch(PDO::FETCH_ASSOC)));
    }


    private function update($arr)
    {
    }


    private function destroy($arr)
    {
    }
}
