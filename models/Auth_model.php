<?php
// require 'libs/CheckToken.php';
class Auth_Model extends Model{
    function __construct(){
            parent::__construct();
    }
    
    function LoginUser(){
        $json = file_get_contents('php://input');
        $dataArray = json_decode($json);
        $username = $dataArray->username;
        $password = $dataArray->password;

        $sql = $this->db->prepare("
        SELECT tb_users.*, name from tb_users 
        LEFT JOIN tb_users_detail on tb_users.userid = tb_users_detail.userid
        WHERE username = '$username' AND status = '2'
        ");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
    
        if(count($data) == 0 || !password_verify($password, $data[0]['password'])){
            $arr = array(
                'data'=>"",
                'message'=>"error",
                'status'=>400
            );
            
        }else if(count($data)!= '0'){
            $userid = $data[0]['userid'];
            $token = GenarateToken($userid);
            $arr = array(
                'data'=>$data,
                'token'=>$token,
                'message'=>"success",
                'status'=>200
            );
        }
        echo json_encode($arr, JSON_PRETTY_PRINT);
    }

    function Register(){
        $json = file_get_contents('php://input');
        $dataArray = json_decode($json);
        $username = $dataArray->username;
        $password = $dataArray->password;
        $name = $dataArray->name;
        
        $SQLmaxuserid = $this->db->prepare("SELECT max(userid) AS mxid FROM tb_users");
        $SQLmaxuserid->execute();
        $x = $SQLmaxuserid->fetch(PDO::FETCH_ASSOC);
        $y = $x['mxid'];
        $z = intval(ltrim($y, 'UID'));
        $userid = 'UID' . str_pad($z + 1, 5, '0', STR_PAD_LEFT);

        // tb_users
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $sql_insert_tb_users = $this->db->prepare("
        INSERT INTO tb_users(userid,username,password,status) 
        VALUES('$userid','$username','$hashed_password','2')
        ");
        $sql_insert_tb_users->execute(array());

        // tb_users_detail
        $sql_insert_tb_users_detail = $this->db->prepare("
        INSERT INTO tb_users_detail(userid,name,tel,email,sex,birthday,img) 
        VALUES('$userid','$name','','','','','')
        ");
        $sql_insert_tb_users_detail->execute(array());

        // tb_users_address
        $sql_insert_tb_users_address = $this->db->prepare("
        INSERT INTO tb_users_address(userid,address1,address2) 
        VALUES('$userid','','')
        ");
        $sql_insert_tb_users_address->execute(array());

        
        $data = 200;
        echo json_encode($data, JSON_PRETTY_PRINT);
        http_response_code(200);
    }

    function verifyToken(){
        $json = file_get_contents('php://input');
        $dataArray = json_decode($json);
        $token = $dataArray->token;
        $result = CheckToken($token);
        if ($result) {
            $response = array(
                'status' => 200,
                'message' => 'Token is valid',
                'data' => $result
            );
        } else {
            $response = array(
                'status' => 401,
                'message' => 'Token is invalid or expired'
            );
        }

        echo json_encode($response);
    }
}
?>