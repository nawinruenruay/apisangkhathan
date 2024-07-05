<?php
class User_Model extends Model
{
    function __construct()
    {
        parent::__construct();
    }

    function Showuser()
    {
        $json = file_get_contents("php://input");
        $dataArray = json_decode($json);
        $userid = $dataArray->userid;
        $sql = $this->db->prepare("
        SELECT tb_users.*, name , tel , email , sex , birthday , img , address1 , address2 FROM tb_users 
        LEFT JOIN tb_users_detail on tb_users.userid = tb_users_detail.userid
        LEFT JOIN tb_users_address on tb_users.userid = tb_users_address.userid
        WHERE tb_users.userid = '$userid'
        ");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data, JSON_PRETTY_PRINT);
        http_response_code(200);
    }
   
}
