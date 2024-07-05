<?php
header("Content-Type: application/json; charset=UTF-8");
date_default_timezone_set("Asia/Bangkok");
class Cart_model extends Model
{
    function __construct()
    {
        parent::__construct();
    }

    function Showcart()
    {
        $json = file_get_contents("php://input");
        $dataJson = json_decode($json);
        $username = $dataJson->username;
    
        $sql1 = $this->db->prepare("
        SELECT cid FROM tb_customers WHERE username = '$username'
        ");
        $sql1->execute(array());
        $row1 = $sql1->fetch(PDO::FETCH_ASSOC);
        $cid = $row1['cid'];
    
        $sql2 = $this->db->prepare("
        SELECT tb_cart.*, pname , img , (tb_cart.price*tb_cart.qty) as total FROM tb_cart
        LEFT JOIN tb_products on tb_cart.pid = tb_products.pid
        LEFT JOIN tb_products_pic on tb_products.pid = tb_products_pic.pid where cid = '$cid'
        ");
        $sql2->execute(array());
        $data = $sql2->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data, JSON_PRETTY_PRINT);
        http_response_code(200);
    }

    function Addcart() 
    {
        $json = file_get_contents("php://input");
        $dataJson = json_decode($json);
        $qty = $dataJson->qty;
        $price = $dataJson->price;
        $pid = $dataJson->pid;
        $username = $dataJson->username;

        $sql_cid = $this->db->prepare("
            SELECT cid FROM tb_customers WHERE username = '$username'
        ");
        $sql_cid->execute(array());
        $row_cid = $sql_cid->fetch(PDO::FETCH_ASSOC);
        $cid = $row_cid['cid'];
    
        $sql_cart_qty = $this->db->prepare("
            SELECT qty FROM tb_cart WHERE cid = '$cid' AND pid = '$pid'
        ");
        $sql_cart_qty->execute(array());
        $row_cart_qty = $sql_cart_qty->fetch(PDO::FETCH_ASSOC);
        $current_cart_qty = $row_cart_qty ? $row_cart_qty['qty'] : 0;
    
        $sql_product_qty = $this->db->prepare("
            SELECT qty FROM tb_products WHERE pid = '$pid'
        ");
        $sql_product_qty->execute(array());
        $row_product_qty = $sql_product_qty->fetch(PDO::FETCH_ASSOC);
        $product_qty = $row_product_qty['qty'];
    
        if ($current_cart_qty + $qty > $product_qty) {
            $data = [
                'status' => 400,
                'message' => 'ขออภัย คุณเพิ่มสินค้าเกินจำนวนที่มีอยู่ในคลังสินค้า'
            ];
            echo json_encode($data, JSON_PRETTY_PRINT);
            return;
        }
    
        if ($current_cart_qty > 0) {
            $sql_update = $this->db->prepare("
                UPDATE tb_cart SET qty = qty + '$qty' WHERE cid = '$cid' AND pid = '$pid'
            ");
            $sql_update->execute(array());
        } else {
            $sql_insert = $this->db->prepare("
                INSERT INTO tb_cart (cid, qty, pid, price) VALUES ('$cid', '$qty', '$pid', '$price')
            ");
            $sql_insert->execute(array());
        }
    
        $data = 200;
        echo json_encode($data, JSON_PRETTY_PRINT);
        http_response_code(200);
    }
    
    function Delcart() 
    {
        $json = file_get_contents("php://input");
        $dataJson = json_decode($json);
        $qty = $dataJson->qty;
        $price = $dataJson->price;
        $pid = $dataJson->pid;
        // username
        $username = $dataJson->username;
        $sql = $this->db->prepare("
        SELECT cid FROM tb_customers WHERE username = '$username'
        ");
        $sql->execute(array());
        $row1 = $sql->fetch(PDO::FETCH_ASSOC);
        $cid = $row1['cid'];

        $sql_del = $this->db->prepare("
        DELETE FROM tb_cart WHERE cid = '$cid' AND pid = '$pid' AND price = '$price' AND qty = '$qty'
        ");
        $sql_del->execute(array());        
    
        $data = 200;
        echo json_encode($data, JSON_PRETTY_PRINT);
        http_response_code(200);
    }

    // function Plus() 
    // {
    //     $json = file_get_contents("php://input");
    //     $dataJson = json_decode($json);
    //     $pid = $dataJson->pid;
    //     $qty = $dataJson->qty;
    //     // username
    //     $username = $dataJson->username;
    //     $sql = $this->db->prepare("
    //     SELECT cid FROM tb_customers WHERE username = '$username'
    //     ");
    //     $sql->execute(array());
    //     $row1 = $sql->fetch(PDO::FETCH_ASSOC);
    //     $cid = $row1['cid'];

    //     $sql_plus = $this->db->prepare("
    //     UPDATE tb_cart 
    //     SET qty = qty + 1 
    //     WHERE cid = '$cid' AND pid = '$pid'
    //     ");
    //     $sql_plus->execute(array());        
    
    //     $data = 200;
    //     echo json_encode($data, JSON_PRETTY_PRINT);
    //     http_response_code(200);
    // }

    function Plus() 
    {
        $json = file_get_contents("php://input");
        $dataJson = json_decode($json);
        $pid = $dataJson->pid;
        $qty = $dataJson->qty;
        $username = $dataJson->username;
        
        // Get customer id (cid) from username
        $sql_cid = $this->db->prepare("
            SELECT cid FROM tb_customers WHERE username = '$username'
        ");
        $sql_cid->execute(array());
        $row_cid = $sql_cid->fetch(PDO::FETCH_ASSOC);
        $cid = $row_cid['cid'];
        
        // Get current quantity in cart
        $sql_cart_qty = $this->db->prepare("
            SELECT qty FROM tb_cart WHERE cid = '$cid' AND pid = '$pid'
        ");
        $sql_cart_qty->execute(array());
        $row_cart_qty = $sql_cart_qty->fetch(PDO::FETCH_ASSOC);
        $current_qty = $row_cart_qty['qty'];
        
        // Get product quantity in tb_products
        $sql_product_qty = $this->db->prepare("
            SELECT qty FROM tb_products WHERE pid = '$pid'
        ");
        $sql_product_qty->execute(array());
        $row_product_qty = $sql_product_qty->fetch(PDO::FETCH_ASSOC);
        $product_qty = $row_product_qty['qty'];
        
        if ($current_qty + 1 > $product_qty) {
            $data = [
                'status' => 400,
                'message' => 'ขออภัย คุณสามารถซื้อสินค้านี้ได้เพียง ' . $product_qty . ' ชิ้น'
            ];
            echo json_encode($data, JSON_PRETTY_PRINT);
            // http_response_code(400);
            return;
        }
        
        $sql_update = $this->db->prepare("
            UPDATE tb_cart 
            SET qty = qty + 1 
            WHERE cid = '$cid' AND pid = '$pid'
        ");
        $sql_update->execute(array());
        
        $data = 200;
        echo json_encode($data, JSON_PRETTY_PRINT);
        http_response_code(200);
    }


    
    function Minus() 
    {
        $json = file_get_contents("php://input");
        $dataJson = json_decode($json);
        $pid = $dataJson->pid;
        $qty = $dataJson->qty;
        // username
        $username = $dataJson->username;
        $sql = $this->db->prepare("
            SELECT cid FROM tb_customers WHERE username = '$username'
        ");
        $sql->execute(array());
        $row1 = $sql->fetch(PDO::FETCH_ASSOC);
        $cid = $row1['cid'];
   
        $sql_update = $this->db->prepare("
            UPDATE tb_cart 
            SET qty = qty - 1 
            WHERE cid = '$cid' AND pid = '$pid'
        ");
        $sql_update->execute(array());   
    
        $data = 200;
        echo json_encode($data, JSON_PRETTY_PRINT);
        http_response_code(200);
    }
    
    

    function Cartsum()
    {
        $json = file_get_contents("php://input");
        $dataJson = json_decode($json);
        $username = $dataJson->username;
    
        $sql1 = $this->db->prepare("
        SELECT cid FROM tb_customers WHERE username = '$username'
        ");
        $sql1->execute(array());
        $row1 = $sql1->fetch(PDO::FETCH_ASSOC);
        $cid = $row1['cid'];
    
        $sql2 = $this->db->prepare("
        SELECT COUNT(pid) AS Sum FROM tb_cart WHERE cid = '$cid'
        ");
        $sql2->execute(array());
        $row2 = $sql2->fetch(PDO::FETCH_ASSOC);
        $Sum = isset($row2['Sum']) ? $row2['Sum'] : 0;
    
        $data = array('sum' => $Sum);
        echo json_encode($data, JSON_PRETTY_PRINT);
        http_response_code(200);
    }
    
        
}