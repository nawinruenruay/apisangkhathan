<?php
header("Content-Type: application/json; charset=UTF-8");
date_default_timezone_set("Asia/Bangkok");
class Order_model extends Model
{
    function __construct()
    {
        parent::__construct();  
    }
    
    function ShowOrder()
    {
        $sql = $this->db->prepare("
        SELECT tb_orders_detail.*, cname from tb_orders_detail 
        LEFT JOIN tb_users on tb_orders_detail.userid = tb_users.userid order by order_id desc
        ");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data, JSON_PRETTY_PRINT);
        http_response_code(200);
    }

    function OrderDetail()
    {
        $json = json_decode(file_get_contents("php://input"));
        $order_id = $json->order_id;
        $sql = $this->db->prepare("
        SELECT tb_products.pname, tb_orders.price, tb_orders.qty , (tb_orders.price*tb_orders.qty) as total from tb_orders
        LEFT JOIN tb_products on tb_orders.pid = tb_products.pid where order_id = '$order_id'
        ");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data, JSON_PRETTY_PRINT);
        http_response_code(200);
    }

    function Confirmpay()
    {
        $json = json_decode(file_get_contents("php://input"));
        $order_id = $json->order_id;
        $sql = $this->db->prepare("UPDATE tb_orders_detail SET status = '3' WHERE order_id = :order_id");
        $sql->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        if ($sql->execute()) {
            $sql_products = $this->db->prepare("SELECT pid, qty FROM tb_orders WHERE order_id = :order_id");
            $sql_products->bindParam(':order_id', $order_id, PDO::PARAM_INT);
            $sql_products->execute();
            $products = $sql_products->fetchAll(PDO::FETCH_ASSOC);
            foreach ($products as $product) {
                $pid = $product['pid'];
                $qtynew = $product['qty'];
    
                $sql_old_qty = $this->db->prepare("SELECT qty FROM tb_products WHERE pid = :pid");
                $sql_old_qty->bindParam(':pid', $pid, PDO::PARAM_INT);
                $sql_old_qty->execute();
                $old_qty = $sql_old_qty->fetchColumn();
    
                $new_qty = $old_qty - $qtynew;
    
                $sql_update_qty = $this->db->prepare("UPDATE tb_products SET qty = :new_qty WHERE pid = :pid");
                $sql_update_qty->bindParam(':new_qty', $new_qty, PDO::PARAM_INT);
                $sql_update_qty->bindParam(':pid', $pid, PDO::PARAM_INT);
                $sql_update_qty->execute();
            }
            echo json_encode("success", JSON_PRETTY_PRINT);
        } else {
            $error = $sql->errorInfo();
            echo json_encode($error[2], JSON_PRETTY_PRINT); 
        }
    }
}