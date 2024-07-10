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
        SELECT 
            tb_users_detail.name, tb_users_detail.phone, tb_users_detail.email, tb_users_detail.sex, tb_users_detail.birthday, tb_users_detail.img, 
            tb_users_address.address, tb_users_address.ad_name, tb_users_address.ad_phone, tb_users_address.ad_province, tb_users_address.ad_amphure,  tb_users_address.ad_tambon, tb_users_address.zip_code
        FROM tb_users 
        LEFT JOIN 
            tb_users_detail on tb_users.userid = tb_users_detail.userid
        LEFT JOIN 
            tb_users_address on tb_users.userid = tb_users_address.userid
        WHERE 
            tb_users.userid = '$userid';
        ");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data, JSON_PRETTY_PRINT);
        http_response_code(200);
    }


    function Showorderbuy()
    {
        $json = file_get_contents("php://input");
        $dataArray = json_decode($json);
        $userid = $dataArray->userid;
        $sql = $this->db->prepare("
        SELECT tb_orders_detail.*, name from tb_orders_detail 
        LEFT JOIN tb_users_detail on tb_orders_detail.userid = tb_users_detail.userid WHERE tb_users_detail.userid = '$userid'  order by order_id desc
        ");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data, JSON_PRETTY_PRINT);
        http_response_code(200);
    }

    function Showorderbuydetail()
    {
        $json = file_get_contents("php://input");
        $dataArray = json_decode($json);
        $userid = $dataArray->userid;
        $order_id = $dataArray->order_id;
        $sql = $this->db->prepare("
        SELECT tb_orders.*, tb_products.pname, tb_orders.price, tb_products_pic.img, tb_orders.qty , (tb_orders.price*tb_orders.qty) as total from tb_orders
        LEFT JOIN tb_products on tb_orders.pid = tb_products.pid 
        LEFT JOIN tb_orders_detail on tb_orders.order_id = tb_orders_detail.order_id 
        LEFT JOIN tb_products_pic on tb_products.pid = tb_products_pic.pid 
        where tb_orders.order_id = '$order_id' AND tb_orders_detail.userid = '$userid'
        ");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data, JSON_PRETTY_PRINT);
        http_response_code(200);
    }

    function Updatedata() 
    {
        $json = file_get_contents("php://input");
        $dataJson = json_decode($json);
        $userid = $dataJson->userid;
        $typeadd = $dataJson->typeadd;
        if ($typeadd === 'email') {
            $email = $dataJson->email;
            $sql_update = $this->db->prepare("
                UPDATE tb_users_detail 
                SET email = '$email'
                WHERE userid = '$userid'
            ");
            $sql_update->execute(array());   
        } else if ($typeadd === 'phone') {
            $phone = $dataJson->phone;
            $sql_update = $this->db->prepare("
                UPDATE tb_users_detail 
                SET phone = '$phone'
                WHERE userid = '$userid'
            ");
            $sql_update->execute(array());   
        } else if ($typeadd === 'birthday') {
            $birthday = $dataJson->birthday;
            $sql_update = $this->db->prepare("
                UPDATE tb_users_detail 
                SET birthday = '$birthday'
                WHERE userid = '$userid'
            ");
            $sql_update->execute(array());   
        } else if ($typeadd === 'name_sex') {
            $name = $dataJson->name;
            $sex = $dataJson->sex;
            $sql_update = $this->db->prepare("
            UPDATE tb_users_detail 
            SET name = '$name' , sex = '$sex'
            WHERE userid = '$userid'
            ");
            $sql_update->execute(array());   
        } else if ($typeadd === 'address') {
            $address = $dataJson->address;
            $ad_name = $dataJson->ad_name;
            $ad_phone = $dataJson->ad_phone;
            $ad_province = $dataJson->ad_province;
            $ad_amphure = $dataJson->ad_amphure;
            $ad_tambon = $dataJson->ad_tambon;
            $zip_code = $dataJson->zip_code;
            $sql_update = $this->db->prepare("
            UPDATE tb_users_address 
            SET address = '$address' , ad_name = '$ad_name', ad_phone = '$ad_phone', ad_province = '$ad_province', ad_amphure = '$ad_amphure', ad_tambon = '$ad_tambon', zip_code = '$zip_code'
            WHERE userid = '$userid'
            ");
            $sql_update->execute(array());   
        }

        $data = 200;
        echo json_encode($data, JSON_PRETTY_PRINT);
        http_response_code(200);
    }

    function UploadIMG()
    {
        $data = json_decode(file_get_contents("php://input"));
        $userid = $_REQUEST['userid'];
        $typedocument = $_REQUEST['typeimg'];
        $file_name = $_FILES['file']['name'];
        $file_size = $_FILES['file']['size']; 
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_type = $_FILES['file']['type'];
        $filename = "public/uploadimg/userimg/";
        $filename_userid = "public/uploadimg/userimg/";
        $arr = array([
            "message" => "success",
            "data" => ''
        ]);
        if (!file_exists($filename)) {
            mkdir("public/uploadimg/userimg/", 0777);
        }
        if (!file_exists($filename_userid)) {
            mkdir("public/uploadimg/userimg/", 0777);
        }
        
        if ($typedocument === 'update') {
            if ($file_type === "image/png" || $file_type === "image/jpg" || $file_type === "image/jpeg") {
                $files_upload = basename($_FILES["file"]["name"]);
                $imageFileType = strtolower(pathinfo($files_upload, PATHINFO_EXTENSION));
                $delete = $filename_userid . "$userid." . $imageFileType;
                if (file_exists($delete)) {
                    unlink($delete);
                }
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $filename_userid . "$userid." . $imageFileType)) {
                    $path = $filename_userid . "$userid." . $imageFileType;
                    $sqlUpdate = $this->db->prepare("
                    UPDATE tb_users_detail SET img = '$path' WHERE userid = '$userid'
                    ");
                    $sqlUpdate->execute(array());
                    echo json_encode($arr, JSON_PRETTY_PRINT);
                    $arr = array([
                        "message" => "success",
                        "data" => $filename_userid . "$userid." . $imageFileType
                    ]);
                }
            } else {
                $arr = array([
                    "message" => "error",
                    "data" => $file_type
                ]);
                echo json_encode($arr, JSON_PRETTY_PRINT);
            }
        }
    }


   
}
