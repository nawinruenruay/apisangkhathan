<?php
header("Content-Type: application/json; charset=UTF-8");
date_default_timezone_set("Asia/Bangkok");
class Activity_model extends Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    function ShowActivity()
    {
        $sql = $this->db->prepare("
        SELECT * FROM tb_activity where act_status = 'T'
        ");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data, JSON_PRETTY_PRINT);
        http_response_code(200);
    }
    
    function postShowactivity()
    {
        $json = file_get_contents("php://input");
        $dataJson = json_decode($json);
        $act_id = $dataJson->act_id;
        $sqldetail = $this->db->prepare("
        SELECT tb_activity.*, gal_pic from tb_activity
        LEFT JOIN tb_activity_gallery on tb_activity.act_id = tb_activity_gallery.act_id
        WHERE tb_activity.act_id = '$act_id' AND tb_activity_gallery.status = 'T'
        ");
        $sqldetail->execute(array());
        $data = $sqldetail->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data, JSON_PRETTY_PRINT);
        http_response_code(200);
    }

    function AllImg()
    {
        $json = json_decode(file_get_contents("php://input"));
        $act_id = $json->act_id;
        $sql = $this->db->prepare("
        SELECT * FROM tb_activity_gallery  WHERE act_id = '$act_id' AND status = 'T' ORDER BY gal_id DESC  
        ");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    function ActivityDetail()
    {
        $json = file_get_contents("php://input");
        $dataJson = json_decode($json);
        $act_id = $dataJson->act_id;
        $sqldetail = $this->db->prepare("
        SELECT tb_activity.*, gal_pic from tb_activity
        LEFT JOIN tb_activity_gallery on tb_activity.act_id = tb_activity_gallery.act_id
        WHERE tb_activity.act_id = '$act_id'
        ");
        $sqldetail->execute(array());
        $data = $sqldetail->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data, JSON_PRETTY_PRINT);
        http_response_code(200);
    }

    function DelImage()
    {
        $json = json_decode(file_get_contents("php://input"));
        $act_id = $json->act_id; 
        $gal_id = $json->gal_id; 
        $sqldele = $this->db->prepare("
            UPDATE tb_activity_gallery SET status = 'F' WHERE act_id = '$act_id' AND gal_id = '$gal_id'
        ");
        if ($sqldele->execute(array()) === true) {
            echo json_encode("success", JSON_PRETTY_PRINT);
        } else {
            $error = $sqldele->errorInfo();
            echo json_encode($error[2], JSON_PRETTY_PRINT); 
        }
    }

    // ################################# เอาไว้ใช้ บน SERVER  #################################
    // function DelImage()
    // {
    //     $json = json_decode(file_get_contents("php://input"));
    //     $act_id = $json->act_id;
    //     $gal_id = $json->gal_id;
    //     $gal_pic = $json->gal_pic;
    //     $filepath = substr($gal_pic, 0, 1000);
    //     if (file_exists($filepath)) {
    //         if (unlink($filepath)) {
    //             $sqldele = $this->db->prepare("
    //         UPDATE tb_activity_gallery SET status = 'F' WHERE act_id = '$act_id' AND gal_id = '$gal_id' AND gal_pic = '$filepath' 
    //         ");
    //             if ($sqldele->execute(array()) === true) {
    //                 echo json_encode("success", JSON_PRETTY_PRINT);
    //             } else {
    //                 $error = $sqldele->errorInfo();
    //                 json_encode($error[2], JSON_PRETTY_PRINT);
    //             }
    //         }
    //     } else {
    //         echo "ไม่มี";
    //     }
    // }
    // ################################# เอาไว้ใช้ บน SERVER  #################################

    function ShowDetailEdit()
    {
        $json = file_get_contents("php://input");
        $dataJson = json_decode($json);
        $act_id = $dataJson->act_id;
        $sql = $this->db->prepare("
        SELECT * FROM tb_activity WHERE act_id = '$act_id'
        ");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    function AddActivity()
    {
        $json = file_get_contents("php://input");
        $dataAray = json_decode($json);
        $act_name = $dataAray->act_name;
        $act_detail = $dataAray->act_detail;
        $act_date = $dataAray->act_date;
        $img = $dataAray->img;
   
        $SQLmaxactid = $this->db->prepare("
        SELECT max(act_id) AS mxid FROM tb_activity
        ");
        $SQLmaxactid->execute(array());
        $maxactid = $SQLmaxactid->fetchAll(PDO::FETCH_ASSOC);
        $maxactid = $maxactid[0]['mxid'];
        $act_id = 0;
        if ($maxactid === null) {
            $act_id = 1;
        } else {
            $act_id = $maxactid + 1;
        }

        $sqladd = $this->db->prepare("
        INSERT INTO tb_activity(act_id,act_name,act_detail,act_pic,act_date) 
        VALUES('$act_id','$act_name','$act_detail','$img','$act_date')
        ");
            $result = $sqladd->execute(array());
            if ($result === true) {
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
        $SQLmaxactid = $this->db->prepare("
        SELECT max(act_id) AS mxid FROM tb_activity
        ");
        $SQLmaxactid->execute(array());
        $maxactid = $SQLmaxactid->fetchAll(PDO::FETCH_ASSOC);
        $maxactid = $maxactid[0]['mxid'];
        $act_id = 0;
        if ($maxactid === null) {
            $act_id = 1;
        } else {
            $act_id = $maxactid + 1;
        }
        $typedocument = $_REQUEST['typeimg'];
        $file_name = $_FILES['file']['name'];
        $file_size = $_FILES['file']['size']; 
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_type = $_FILES['file']['type'];
        $filename = "public/uploadimg/activities/";
        $filename_actid = "public/uploadimg/activities/" . $act_id;
        $arr = array([
            "message" => "success",
            "data" => ''
        ]);
        if (!file_exists($filename)) {
            mkdir("public/uploadimg/activities/", 0777);
        }
        if (!file_exists($filename_actid)) {
            mkdir("public/uploadimg/activities/". $act_id, 0777);
        }
        
        if ($typedocument === 'add') {
            $name = $_REQUEST['name'];
            if ($file_type === "image/png" || $file_type === "image/jpg" || $file_type === "image/jpeg") {
                $files_upload = basename($_FILES["file"]["name"]);
                $imageFileType = strtolower(pathinfo($files_upload, PATHINFO_EXTENSION));
                $file_path = $_FILES["file"]["tmp_name"];
                $image_hash = hash_file('sha256', $file_path);
          
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $filename_actid . "/$name." . $imageFileType)) {
                    $arr = array([
                        "message" => "success",
                        "data" => $filename_actid . "/$name." . $imageFileType
                    ]);
                    $path = $filename_actid . "/$name." . $imageFileType;
                    $sqlinsert = $this->db->prepare("
                    INSERT INTO tb_activity_gallery(act_id,gal_pic,status) VALUES('$act_id','$path','T')
                    ");
                    if ($sqlinsert->execute(array()) === true) {
                        echo json_encode($arr, JSON_PRETTY_PRINT);
                    } else {
                        $error = $sqlinsert->errorInfo();
                        echo json_encode($error, JSON_PRETTY_PRINT);
                    }
                }
            }
        }
    }

    function UploadGallery()
    {
        $data = json_decode(file_get_contents("php://input"));
        $actid = $_REQUEST['act_id'];

        $typedocument = $_REQUEST['typeimg'];
        $file_name = $_FILES['file']['name'];
        $file_size = $_FILES['file']['size']; 
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_type = $_FILES['file']['type'];
        $filename = "public/uploadimg/activities/";
        $filename_actid = "public/uploadimg/activities/" . $actid;
        $arr = array([
            "message" => "success",
            "data" => ''
        ]);
        if (!file_exists($filename)) {
            mkdir("public/uploadimg/activities/", 0777);
        }
        if (!file_exists($filename_actid)) {
            mkdir("public/uploadimg/activities/". $actid, 0777);
        }
        
        if ($typedocument === 'add') {
            $name = $_REQUEST['name'];
            if ($file_type === "image/png" || $file_type === "image/jpg" || $file_type === "image/jpeg") {
                $files_upload = basename($_FILES["file"]["name"]);
                $imageFileType = strtolower(pathinfo($files_upload, PATHINFO_EXTENSION));
                $file_path = $_FILES["file"]["tmp_name"];
                $image_hash = hash_file('sha256', $file_path);
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $filename_actid . "/$name." . $imageFileType)) {
                    $arr = array([
                        "message" => "success",
                        "data" => $filename_actid . "/$name." . $imageFileType
                    ]);
                    $path = $filename_actid . "/$name." . $imageFileType;
                    $sqlinsert = $this->db->prepare("
                    INSERT INTO tb_activity_gallery(act_id,gal_pic,status) VALUES('$actid','$path','T')
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
            $name = $_REQUEST['name'];
            if ($file_type === "image/png" || $file_type === "image/jpg" || $file_type === "image/jpeg") {
                $files_upload = basename($_FILES["file"]["name"]);
                $imageFileType = strtolower(pathinfo($files_upload, PATHINFO_EXTENSION));
                $delete = $filename_actid . "/$name." . $imageFileType;
                if (file_exists($delete)) {
                    unlink($delete);
                }
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $filename_actid . "/$name." . $imageFileType)) {
                    $path = $filename_actid . "/$name." . $imageFileType;
                    $sqlUpdate = $this->db->prepare("
                    UPDATE tb_activity SET act_pic = '$path' WHERE act_id = '$actid'
                    ");
                    $sqlUpdate->execute(array());
                    echo json_encode($arr, JSON_PRETTY_PRINT);
                    $arr = array([
                        "message" => "success", 
                        "data" => $filename_actid . "/$name." . $imageFileType
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
        $act_id = $dataJson->act_id;
        $sql = $this->db->prepare("
        SELECT act_pic FROM tb_activity WHERE act_id = '$act_id'
        ");
        $sql->execute(array());
        $send = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($send, JSON_PRETTY_PRINT);
    }

    function SaveEdit()
    {
        $json = file_get_contents("php://input");
        $data = json_decode($json);
        $act_id = $data->act_id;
        $act_name = $data->act_name;
        $act_detail = $data->act_detail;
        $act_date = $data->act_date;
        $sqlUpdate = $this->db->prepare("
        UPDATE tb_activity SET act_name = '$act_name',act_detail = '$act_detail',act_date = '$act_date'
        WHERE act_id = '$act_id' 
        ");
        $sqlUpdate->execute(array());
        $sqlUpdate->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode('success', JSON_PRETTY_PRINT);
        http_response_code(200);
    }
}