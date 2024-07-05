<?php
header("Content-Type: application/json; charset=UTF-8");
date_default_timezone_set("Asia/Bangkok");
class Device_Model extends Model
{
    function __construct()
    {
        parent::__construct();
    }
    function Test()
    {
        echo "Testt";
    }

    function CountInfo() {
        $totalOrders = 0;
        $pendingPayment = 0;
        $awaitingVerification = 0;
        $paid = 0;
    
        $sqlOrders = $this->db->prepare("
            SELECT 
                COUNT(order_id) AS total_orders,
                SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) AS pending_payment,
                SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) AS awaiting_verification,
                SUM(CASE WHEN status = 3 THEN 1 ELSE 0 END) AS paid
            FROM 
                tb_orders_detail
        ");
        $sqlOrders->execute();
        $orderCounts = $sqlOrders->fetch(PDO::FETCH_ASSOC);
    
        if ($orderCounts) {
            $totalOrders = $orderCounts['total_orders'];
            $pendingPayment = $orderCounts['pending_payment'];
            $awaitingVerification = $orderCounts['awaiting_verification'];
            $paid = $orderCounts['paid'];
        }
    
        $dataA = [
            [
                "label" => "ออเดอร์ทั้งหมด",
                "value" => $totalOrders
            ],
            [
                "label" => "รอชำระเงิน",
                "value" => $pendingPayment
            ],
            [
                "label" => "รอตรวจสอบการชำระเงิน",
                "value" => $awaitingVerification
            ],
            [
                "label" => "ชำระเงินแล้ว",
                "value" => $paid
            ],
        ];
    
        echo json_encode($dataA, JSON_PRETTY_PRINT);
    }
    
}