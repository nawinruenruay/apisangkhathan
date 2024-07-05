<?php
header("Content-Type: application/json; charset=UTF-8");
date_default_timezone_set("Asia/Bangkok");
class Product_model extends Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    function ShowProduct()
    {
        $sql = $this->db->prepare("
        SELECT tb_products.*, ptname , tb_products_pic.img FROM tb_products 
        LEFT JOIN tb_products_type on tb_products.ptid = tb_products_type.ptid
        LEFT JOIN tb_products_pic on tb_products.pid = tb_products_pic.pid");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data, JSON_PRETTY_PRINT);
        http_response_code(200);
    }
        
    function postShowproduct()
    {
        $json = file_get_contents("php://input");
        $dataJson = json_decode($json);
        $pid = $dataJson->pid;
        $sql = $this->db->prepare("
        SELECT tb_products.*, ptname , tb_products_pic.img FROM tb_products 
        LEFT JOIN tb_products_type on tb_products.ptid = tb_products_type.ptid
        LEFT JOIN tb_products_pic on tb_products.pid = tb_products_pic.pid
        WHERE tb_products.pid = '$pid'");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data, JSON_PRETTY_PRINT);
        http_response_code(200);
    }

    // function ProductCheck()
    // {
    //     $json = json_decode(file_get_contents("php://input"));
    //     $pid = $json->pid;
    //     $sql = $this->db->prepare("
    //     SELECT tb_products.*, ptname FROM tb_products 
    //     LEFT JOIN tb_products_type on tb_products.ptid = tb_products_type.ptid
    //     WHERE pid = '$pid'
    //     ");
    //     if ($sql->execute(array()) === true) {
    //         $data = $sql->fetchAll(PDO::FETCH_ASSOC);
    //         $nub = count($data);
    //         if ($nub > 0) {
    //             echo json_encode("have", JSON_PRETTY_PRINT);
    //         } else {
    //             echo json_encode("not", JSON_PRETTY_PRINT);
    //         }
    //     } else {
    //         $error = $sql->errorInfo();
    //         echo json_encode($error[2], JSON_PRETTY_PRINT);
    //         echo json_encode($sql, JSON_PRETTY_PRINT);
    //     }
    // }

    function ShowDetailEdit()
    {
        $json = file_get_contents("php://input");
        $dataJson = json_decode($json);
        $pid = $dataJson->pid;
        $sql = $this->db->prepare("
        SELECT tb_products.*, ptname , tb_products_pic.img FROM tb_products 
        LEFT JOIN tb_products_type on tb_products.ptid = tb_products_type.ptid
        LEFT JOIN tb_products_pic on tb_products.pid = tb_products_pic.pid 
        WHERE tb_products.pid = '$pid'");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    function DelProduct()
    {
        $json = json_decode(file_get_contents("php://input"));
        $pid = $json->pid;
        $sqldel = $this->db->prepare("
            DELETE FROM tb_products WHERE pid = '".$pid."'
        ");
        if ($sqldel->execute(array())) {
          
            $sqldel_pic = $this->db->prepare("
                DELETE FROM tb_products_pic WHERE pid = '".$pid."'
            ");
            if ($sqldel_pic->execute()) {
                echo json_encode("success", JSON_PRETTY_PRINT);
            } else {
                $error_pic = $sqldel_pic->errorInfo();
                echo json_encode($error_pic[2], JSON_PRETTY_PRINT);
            }
        } else {
            $error = $sqldel->errorInfo();
            echo json_encode($error[2], JSON_PRETTY_PRINT);
        }
    }

    function ShowTypeProduct()
    {
        $sql = $this->db->prepare("
        SELECT * FROM tb_products_type 
        ");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data, JSON_PRETTY_PRINT);
        // echo json_encode($data,JSON_PRETTY_PRINT);
    }

    function AddProduct()
    {
        $json = file_get_contents("php://input");
        $dataAray = json_decode($json);
        // $pid = $dataAray->pid;
        $ptid = $dataAray->ptid;
        $pname = $dataAray->pname;
        $qty = $dataAray->qty;
        $price = $dataAray->price;
        // $img = $dataAray->img;
   
        $SQLmaxpid = $this->db->prepare("SELECT max(pid) AS mxid FROM tb_products");
        $SQLmaxpid->execute();
        $maxpid = $SQLmaxpid->fetch(PDO::FETCH_ASSOC);
        $currentMaxPid = $maxpid['mxid'];
        $numericPart = intval(ltrim($currentMaxPid, 'P'));
        $pid = 'P' . str_pad($numericPart + 1, 3, '0', STR_PAD_LEFT);
    
        $sqladd = $this->db->prepare("
        INSERT INTO tb_products(pid,ptid,pname,price,qty) 
        VALUES('$pid','$ptid','$pname','$price','$qty')
        ");
            $result = $sqladd->execute(array());
            if ($result === true) {
                // $sqlinsert = $this->db->prepare("
                // INSERT INTO TB_IMG(pid,path) VALUES('$pid','$img')
                // ");
                // $sqlinsert->execute(array());
                $response = "ok";
            } else {
                $error = $sqladd->errorInfo();
                $response = "SQL ERROR:" . $error[2];
            }
            echo json_encode($response, JSON_PRETTY_PRINT);
    }


    function UploadIMG()
    {
        $data = json_decode(file_get_contents("php://input"));
        $typedocument = $_REQUEST['typeimg'];
        $file_name = $_FILES['file']['name'];
        $file_size = $_FILES['file']['size']; 
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_type = $_FILES['file']['type'];
        $filename = "public/uploadimg/products/";
        $filename_pid = "public/uploadimg/products/";
        $arr = array([
            "message" => "success",
            "data" => ''
        ]);
        if (!file_exists($filename)) {
            mkdir("public/uploadimg/products/", 0777);
        }
        if (!file_exists($filename_pid)) {
            mkdir("public/uploadimg/products/", 0777);
        }
        
        if ($typedocument === 'add') {
            // $SQLmaxpid = $this->db->prepare("
            // SELECT max(pid) AS mxid FROM tb_products
            // ");
            // $SQLmaxpid->execute(array());
            // $maxpid = $SQLmaxpid->fetchAll(PDO::FETCH_ASSOC);
            // $maxpid = $maxpid[0]['mxid'];
            // $PID = 0;
            // if ($maxpid === null) {
            //     $PID = 1;
            // } else {
            //     $PID = $maxpid + 1;
            // }
            $SQLmaxpid = $this->db->prepare("SELECT max(pid) AS mxid FROM tb_products");
            $SQLmaxpid->execute();
            $maxpid = $SQLmaxpid->fetch(PDO::FETCH_ASSOC);
            $currentMaxPid = $maxpid['mxid'];
            $numericPart = intval(ltrim($currentMaxPid, 'P'));
            $PID = 'P' . str_pad($numericPart + 1, 3, '0', STR_PAD_LEFT);
    
            
            if ($file_type === "image/png" || $file_type === "image/jpg" || $file_type === "image/jpeg") {
                $files_upload = basename($_FILES["file"]["name"]);
                $imageFileType = strtolower(pathinfo($files_upload, PATHINFO_EXTENSION));
                $file_path = $_FILES["file"]["tmp_name"];
                $image_hash = hash_file('sha256', $file_path);
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $filename_pid . "$PID." . $imageFileType)) {
                    $arr = array([
                        "message" => "success",
                        "data" => $filename_pid . "$PID." . $imageFileType
                    ]);
                    $path = $filename_pid . "$PID." . $imageFileType;
                    $sqlinsert = $this->db->prepare("
                    INSERT INTO tb_products_pic(pid,img,status) VALUES('$PID','$path','T')
                    ");
                    if ($sqlinsert->execute(array()) === true) {
                        echo json_encode($arr, JSON_PRETTY_PRINT);
                    } else {
                        $error = $sqlinsert->errorInfo();
                        echo json_encode($error, JSON_PRETTY_PRINT);
                    }
                }
            }
        } else if ($typedocument === 'update') {
            $PID = $_REQUEST['pid'];
            $typee = "update" . date("dmy");

            if ($file_type === "image/png" || $file_type === "image/jpg" || $file_type === "image/jpeg") {
                $files_upload = basename($_FILES["file"]["name"]);
                $imageFileType = strtolower(pathinfo($files_upload, PATHINFO_EXTENSION));
                $delete = $filename_pid . "$PID$typee." . $imageFileType;
                if (file_exists($delete)) {
                    unlink($delete);
                }
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $filename_pid . "$PID$typee." . $imageFileType)) {
                    $path = $filename_pid . "$PID$typee." . $imageFileType;
                    $sqlUpdate = $this->db->prepare("
                    UPDATE tb_products_pic SET  img = '$path' WHERE pid = '$PID'
                    ");
                    $sqlUpdate->execute(array());
                    echo json_encode($arr, JSON_PRETTY_PRINT);
                    $arr = array([
                        "message" => "success",
                        "data" => $filename_pid . "$PID$typee." . $imageFileType
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

    function ShowIMG()
    {
        $json = file_get_contents("php://input");
        $dataJson = json_decode($json);
        $pid = $dataJson->pid;
        $sql = $this->db->prepare("
        SELECT * FROM tb_products_pic WHERE pid = '$pid'
        ");
        $sql->execute(array());
        $send = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($send, JSON_PRETTY_PRINT);
    }

    function SaveEdit()
    {
        $json = file_get_contents("php://input");
        $data = json_decode($json);
        $pid = $data->pid;
        $ptid = $data->ptid;
        $pname = $data->pname;
        $price = $data->price;
        $qty = $data->qty;
        $sqlUpdate = $this->db->prepare("
        UPDATE tb_products SET ptid = '$ptid',pname = '$pname',price = '$price',qty = '$qty'
        WHERE pid = '$pid' 
        ");
        $sqlUpdate->execute(array());
        $sqlUpdate->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode('success', JSON_PRETTY_PRINT);
        http_response_code(200);
    }
}