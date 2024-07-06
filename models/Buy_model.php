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

        foreach ($row_x as $row) {
            $pid = $row['pid'];
            $price =  $row['price'];
            $qty =  $row['qty'];

            if ($qty > 0 ) {
                $sql_insert_tb_orders = $this->db->prepare("
                INSERT INTO tb_orders(order_id,pid,qty,price) Value('$order_id', '$pid', '$qty', '$price')
                ");
                $sql_insert_tb_orders->execute(array());

                $order_date = date("Y-m-d");
                $status = '1';
                $sql_insert_tb_orders_detail = $this->db->prepare("
                INSERT into tb_orders_detail(order_id, userid, order_date, status, pay_date, pay_time, pay_total, pay_slip)
                values('$order_id', '$userid', '$order_date', '$status', '', '00:00', '', '')
                ");
                $sql_insert_tb_orders_detail->execute(array());
    
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
    
}