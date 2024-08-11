<?php
header("Content-Type: application/json; charset=UTF-8");
date_default_timezone_set("Asia/Bangkok");
class Buy_model extends Model
{
    function __construct()
    {
        parent::__construct();
    }

    function Buyproduct() 
    {
        $json = file_get_contents("php://input");
        $dataJson = json_decode($json);
        $userid = $dataJson->userid;
       
        $sql_orderid = $this->db->prepare("
        select max(order_id) as mxid from tb_orders_detail
        ");
        $sql_orderid->execute(array());
        $row_orderid = $sql_orderid->fetch(PDO::FETCH_ASSOC);
        $order_id = $row_orderid['mxid'] + 1;

        $sql_x = $this->db->prepare("
        SELECT * from tb_cart where userid = '$userid'
        ");
        $sql_x->execute(array());
        $row_x = $sql_x->fetchAll(PDO::FETCH_ASSOC);

        if (empty($row_x)) {
            $data = 400;
            echo json_encode($data, JSON_PRETTY_PRINT);
            return;
        }

        $order_date = date("Y-m-d");
        $status = '1';
        $sql_insert_tb_orders_detail = $this->db->prepare("
        INSERT into tb_orders_detail(order_id, userid, order_date, status, pay_date, pay_time, pay_total, pay_slip)
        values('$order_id', '$userid', '$order_date', '$status', '', '00:00', '', '')
        ");
        $sql_insert_tb_orders_detail->execute(array());

        foreach ($row_x as $row) {
            $pid = $row['pid'];
            $price =  $row['price'];
            $qty =  $row['qty'];
            if ($qty > 0 ) {
                $sql_insert_tb_orders = $this->db->prepare("
                INSERT INTO tb_orders(order_id,pid,qty,price) Value('$order_id', '$pid', '$qty', '$price')
                ");
                $sql_insert_tb_orders->execute(array());

                $sql_delete_cart = $this->db->prepare("
                DELETE from tb_cart where userid='$userid' and pid='$pid' and qty='$qty' and price='$price'
                ");
                $sql_delete_cart->execute(array());
            } else {
                $data = 400;
                echo json_encode($data, JSON_PRETTY_PRINT);
                return;
            }
        }
        $data = 200;
        echo json_encode($data, JSON_PRETTY_PRINT);
        http_response_code(200);
    }

    function Checkout() 
    {
        $json = file_get_contents("php://input");
        $dataJson = json_decode($json);
        $order_id = $dataJson->order_id;
        $pay_date = $dataJson->pay_date;
        $pay_time = $dataJson->pay_time;
        $pay_total = $dataJson->pay_total;
        $typeadd = $dataJson->typeadd;
        if ($typeadd === 'checkout') {
            $email = $dataJson->email;
            $sql_update = $this->db->prepare("
                UPDATE tb_orders_detail 
                SET pay_date = '$pay_date' , pay_time = '$pay_time' ,  pay_total = '$pay_total'
                WHERE order_id = '$order_id'
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
        $order_id = $_REQUEST['order_id'];
        $typedocument = $_REQUEST['typeimg'];
        $file_name = $_FILES['file']['name'];
        $file_size = $_FILES['file']['size']; 
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_type = $_FILES['file']['type'];
        $filename = "public/uploadimg/slip/";
        $filename_slip = "public/uploadimg/slip/";
        $arr = array([
            "message" => "success",
            "data" => ''
        ]);
        if (!file_exists($filename)) {
            mkdir("public/uploadimg/slip/", 0777);
        }
        if (!file_exists($filename_slip)) {
            mkdir("public/uploadimg/slip/", 0777);
        }
        
        if ($typedocument === 'update') {
            if ($file_type === "image/png" || $file_type === "image/jpg" || $file_type === "image/jpeg") {
                $files_upload = basename($_FILES["file"]["name"]);
                $imageFileType = strtolower(pathinfo($files_upload, PATHINFO_EXTENSION));
                $delete = $filename_slip . "$order_id." . $imageFileType;
                if (file_exists($delete)) {
                    unlink($delete);
                }
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $filename_slip . "$order_id." . $imageFileType)) {
                    $path = $filename_slip . "$order_id." . $imageFileType;
                    $sqlUpdate = $this->db->prepare("
                    UPDATE tb_orders_detail SET pay_slip = '$path' WHERE order_id = '$order_id'
                    ");
                    $sqlUpdate->execute(array());
                    echo json_encode($arr, JSON_PRETTY_PRINT);
                    $arr = array([
                        "message" => "success",
                        "data" => $filename_slip . "$order_id." . $imageFileType
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

    function CancelOrder() 
    {
        $json = file_get_contents("php://input");
        $dataJson = json_decode($json);
        $order_id = $dataJson->order_id;

        $sql_update = $this->db->prepare("
        UPDATE tb_orders_detail 
        SET status = '5' 
        WHERE order_id = '$order_id'
        ");
        $sql_update->execute(array());   
        

        $data = 200;
        echo json_encode($data, JSON_PRETTY_PRINT);
        http_response_code(200);
        
    }
    
}