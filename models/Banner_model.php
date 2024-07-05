<?php
header("Content-Type: application/json; charset=UTF-8");
date_default_timezone_set("Asia/Bangkok");
class Banner_model extends Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    function ShowBanner()
    {
        $sql = $this->db->prepare("
        SELECT * FROM tb_banners 
        ");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data, JSON_PRETTY_PRINT);
        http_response_code(200);
    }


    function DelBanner()
    {
        $json = json_decode(file_get_contents("php://input"));
        $banner_id = $json->banner_id;
        $sqldel = $this->db->prepare("
		    delete from tb_banners where banner_id = '".$banner_id."'
		");
        if ($sqldel->execute(array()) === true) {
            echo json_encode("success", JSON_PRETTY_PRINT);
        } else {
            $error = $sqldel->errorInfo();
            json_encode($error[2], JSON_PRETTY_PRINT);
        }
    }
    
    // function AllImg()
    // {
    //     $json = json_decode(file_get_contents("php://input"));
    //     $act_id = $json->act_id;
    //     $sql = $this->db->prepare("
    //     SELECT * FROM tb_activity_gallery  WHERE act_id = '$act_id' AND status = 'T' ORDER BY gal_id DESC  
    //     ");
    //     $sql->execute(array());
    //     $data = $sql->fetchAll(PDO::FETCH_ASSOC);
    //     echo json_encode($data, JSON_PRETTY_PRINT);
    // }

    // function ActivityDetail()
    // {
    //     $json = file_get_contents("php://input");
    //     $dataJson = json_decode($json);
    //     $act_id = $dataJson->act_id;
    //     $sqldetail = $this->db->prepare("
    //     SELECT tb_activity.*, gal_pic from tb_activity
    //     LEFT JOIN tb_activity_gallery on tb_activity.act_id = tb_activity_gallery.act_id
    //     WHERE tb_activity.act_id = '$act_id'
    //     ");
    //     $sqldetail->execute(array());
    //     $data = $sqldetail->fetchAll(PDO::FETCH_ASSOC);
    //     echo json_encode($data, JSON_PRETTY_PRINT);
    //     http_response_code(200);
    // }

    // function DelImage()
    // {
    //     $json = json_decode(file_get_contents("php://input"));
    //     $act_id = $json->act_id; 
    //     $gal_id = $json->gal_id; 
    //     $sqldele = $this->db->prepare("
    //         UPDATE tb_activity_gallery SET status = 'F' WHERE act_id = '$act_id' AND gal_id = '$gal_id'
    //     ");
    //     if ($sqldele->execute(array()) === true) {
    //         echo json_encode("success", JSON_PRETTY_PRINT);
    //     } else {
    //         $error = $sqldele->errorInfo();
    //         echo json_encode($error[2], JSON_PRETTY_PRINT); 
    //     }
    // }

    // // ################################# เอาไว้ใช้ บน SERVER  #################################
    // // function DelImage()
    // // {
    // //     $json = json_decode(file_get_contents("php://input"));
    // //     $act_id = $json->act_id;
    // //     $gal_id = $json->gal_id;
    // //     $gal_pic = $json->gal_pic;
    // //     $filepath = substr($gal_pic, 0, 1000);
    // //     if (file_exists($filepath)) {
    // //         if (unlink($filepath)) {
    // //             $sqldele = $this->db->prepare("
    // //         UPDATE tb_activity_gallery SET status = 'F' WHERE act_id = '$act_id' AND gal_id = '$gal_id' AND gal_pic = '$filepath' 
    // //         ");
    // //             if ($sqldele->execute(array()) === true) {
    // //                 echo json_encode("success", JSON_PRETTY_PRINT);
    // //             } else {
    // //                 $error = $sqldele->errorInfo();
    // //                 json_encode($error[2], JSON_PRETTY_PRINT);
    // //             }
    // //         }
    // //     } else {
    // //         echo "ไม่มี";
    // //     }
    // // }
    // // ################################# เอาไว้ใช้ บน SERVER  #################################

    function ShowDetailEdit()
    {
        $json = file_get_contents("php://input");
        $dataJson = json_decode($json);
        $banner_id = $dataJson->banner_id;
        $sql = $this->db->prepare("
        SELECT * FROM tb_banners WHERE banner_id = '$banner_id'
        ");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    function UploadIMG()
    {
        $data = json_decode(file_get_contents("php://input"));
        $typedocument = $_REQUEST['typeimg'];
        $file_name = $_FILES['file']['name'];
        $file_size = $_FILES['file']['size']; 
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_type = $_FILES['file']['type'];
        $filename = "public/uploadimg/banners/";
        $filename_banner = "public/uploadimg/banners/";
        $arr = array([
            "message" => "success",
            "data" => ''
        ]);
        if (!file_exists($filename)) {
            mkdir("public/uploadimg/banners/", 0777);
        }
        if (!file_exists($filename_banner)) {
            mkdir("public/uploadimg/banners/", 0777);
        }
        
        if ($typedocument === 'add') {
            $SQLmaxid = $this->db->prepare("
            SELECT max(banner_id) AS mxid FROM tb_banners
            ");
            $SQLmaxid->execute(array());
            $maxbannerid = $SQLmaxid->fetchAll(PDO::FETCH_ASSOC);
            $maxbannerid = $maxbannerid[0]['mxid'];
            $banner_id = 0;
            if ($maxbannerid === null) {
                $banner_id = 1;
            } else {
                $banner_id = $maxbannerid + 1;
            }

            if ($file_type === "image/png" || $file_type === "image/jpg" || $file_type === "image/jpeg") {
                $files_upload = basename($_FILES["file"]["name"]);
                $imageFileType = strtolower(pathinfo($files_upload, PATHINFO_EXTENSION));
                $file_path = $_FILES["file"]["tmp_name"];
                $image_hash = hash_file('sha256', $file_path);
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $filename_banner . "banner$banner_id." . $imageFileType)) {
                    $path = $filename_banner . "banner$banner_id." . $imageFileType;
                    $sqlinsert = $this->db->prepare("
                    INSERT INTO tb_banners(banner_id,banner_pic,banner_status) VALUES('$banner_id','$path','T')
                    ");
                    $arr = array([
                        "message" => "success",
                        "data" => $filename_banner . "banner$banner_id." . $imageFileType
                    ]);
                    if ($sqlinsert->execute(array()) === true) {
                        echo json_encode($arr, JSON_PRETTY_PRINT);
                    } else {
                        $error = $sqlinsert->errorInfo();
                        echo json_encode($error, JSON_PRETTY_PRINT);
                    }
                }
            }
        } else if ($typedocument === 'update') {
            $banner_id = $_REQUEST['banner_id'];
            $typee = "update" . date("dmy");

            if ($file_type === "image/png" || $file_type === "image/jpg" || $file_type === "image/jpeg") {
                $files_upload = basename($_FILES["file"]["name"]);
                $imageFileType = strtolower(pathinfo($files_upload, PATHINFO_EXTENSION));
                $delete = $filename_banner . "$PID$typee." . $imageFileType;
                if (file_exists($delete)) {
                    unlink($delete);
                }
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $filename_banner . "$banner_id$typee." . $imageFileType)) {
                    $path = $filename_banner . "$banner_id$typee." . $imageFileType;
                    $sqlUpdate = $this->db->prepare("
                    UPDATE tb_banners SET  banner_pic = '$path' WHERE banner_id = '$banner_id'
                    ");
                    $sqlUpdate->execute(array());
                    echo json_encode($arr, JSON_PRETTY_PRINT);
                    $arr = array([
                        "message" => "success",
                        "data" => $filename_banner . "$PID$typee." . $imageFileType
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

    // function ShowIMG()
    // {
    //     $json = file_get_contents("php://input");
    //     $dataJson = json_decode($json);
    //     $act_id = $dataJson->act_id;
    //     $sql = $this->db->prepare("
    //     SELECT act_pic FROM tb_activity WHERE act_id = '$act_id'
    //     ");
    //     $sql->execute(array());
    //     $send = $sql->fetchAll(PDO::FETCH_ASSOC);
    //     echo json_encode($send, JSON_PRETTY_PRINT);
    // }

    function SaveEdit()
    {
        $json = file_get_contents("php://input");
        $data = json_decode($json);
        $banner_id = $data->banner_id;
        $banner_status = $data->banner_status;
       
        $sqlUpdate = $this->db->prepare("
        UPDATE tb_banners SET banner_status = '$banner_status'
        WHERE banner_id = '$banner_id' 
        ");
        $sqlUpdate->execute(array());
        $sqlUpdate->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode('success', JSON_PRETTY_PRINT);
        http_response_code(200);
    }
}