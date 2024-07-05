<?php
class User_Model extends Model
{
    function __construct()
    {
        parent::__construct();
    }
    function ShowUser()
    {
        $json = file_get_contents('php://input');
        $dataArray = json_decode($json);
        $main = $dataArray->main_aid;
        $sql = $this->db->prepare("
        SELECT *,ustatus_name AS stname,sub_aname AS sname FROM users LEFT JOIN ustatus ON users.ustatus_id = ustatus.ustatus_id LEFT JOIN sub_agen ON users.sub_aid = sub_agen.sub_aid WHERE users.main_aid = $main 
        ");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
    function ShowUserEdit($id)
    {
        $sql = $this->db->prepare("
        SELECT user_id,username,name,users.ustatus_id , ustatus_name AS stname,role,sub_aid FROM users LEFT JOIN ustatus ON users.ustatus_id = ustatus.ustatus_id  WHERE user_id = $id
        ");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data[0], JSON_PRETTY_PRINT);
    }
    function ShowUstatus()
    {
        $sql = $this->db->prepare("
        SELECT * FROM ustatus 
        ");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
    function SaveEdit()
    {
        $json = file_get_contents('php://input');
        $dataArray = json_decode($json);
        $userid = $dataArray->user_id;
        $ustatus = $dataArray->ustatus;
        $sql = $this->db->prepare("
        UPDATE users SET ustatus_id = $ustatus WHERE user_id =  $userid
        ");
        $result =  $sql->execute(array());
        if ($result === false) {
            echo "SQL Error: ";
        } else {
            $rowCount = $sql->rowCount();
            echo $rowCount;
        }
    }
    function SaveRole()
    {
        $json = file_get_contents('php://input');
        $dataArray = json_decode($json);
        $userid = $dataArray->user_id;
        $ustatus = $dataArray->ustatus;
        $role = $dataArray->role;
        $sql = $this->db->prepare("
        UPDATE users SET ustatus_id = $ustatus,role = '$role' WHERE user_id =  $userid
        ");
        $result =  $sql->execute(array());
        if ($result === false) {
            echo "SQL Error: ";
        } else {
            $rowCount = $sql->rowCount();
            echo $rowCount;
        }
    }
    function SaveUser()
    {
        $json = file_get_contents('php://input');
        $dataArray = json_decode($json);
        $username = $dataArray->username;
        $password = $dataArray->password;
        $name = $dataArray->name;
        $mid = $dataArray->mid;
        $sid = $dataArray->sid;
        $chkUsername = $this->db->prepare("
        SELECT count(*) AS chk FROM users WHERE username = '$username'
        ");
        $chkUsername->execute(array());
        $chk = $chkUsername->fetchAll(PDO::FETCH_ASSOC);
        $c = $chk[0]['chk'];
        if ($c != 0) {
            echo "No";
        } else {
            $sql = $this->db->prepare("
            INSERT INTO users (username,password,name,ustatus_id,main_aid,role,sub_aid) VALUES('$username','$password','$name','1','$mid','2','$sid')
            ");
            $result =  $sql->execute(array());
            if ($result === false) {
                $error = $sql->errorInfo();
                echo "ERROR :" . $error[2];
            } else {
                $count = $sql->rowCount();
                echo json_encode("success", JSON_PRETTY_PRINT);
            }
        }
        //  echo json_encode($chk[0]['chk'],JSON_PRETTY_PRINT); 
    }
    function SaveEDTail()
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json);
        $userid = $data->user_id;
        $name = $data->name;
        $suba = $data->subaid;
        $sql = $this->db->prepare("
        UPDATE users SET name = '$name',sub_aid = '$suba' WHERE user_id = $userid
        ");
        $sql->execute(array());
        echo json_encode("success", JSON_PRETTY_PRINT);
    }
}
