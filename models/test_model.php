<?php
 	header("Content-Type: application/json; charset=UTF-8");
	class Test_Model extends Model{
		function __construct(){
				parent::__construct();
		}

		function show(){

			$token = GenarateToken("test","T","1001");
			$arr = array(
				'status' => 200,
				'status_name' => header_status(200),
				'data' => array(
					'success' => "OK",
					'token' => $token
				)
			);
			echo json_encode($arr);
			http_response_code(200);
		}

		function updateroutes($id){
			$data = json_decode(file_get_contents("php://input"));
			$name = $data->name;
			$lname = $data->lname;
			$sex = $data->sex;
			$sth = $this->db->prepare("
				update  tb_test 
				set
					name = '".$name."',
					lname = '".$lname."',
					sex = '".$sex."'
				
				where
					id = '".$id."'
			");
			$sth->execute(array());
			$arr = array(
				'status' => 200,
				'status_name' => header_status(200),
				'data' => array(
					'success' => "update success"
				)
			);
			echo json_encode($arr);
			http_response_code(200);
		}

		function insertroutes(){
			$data = json_decode(file_get_contents("php://input"));
			$name = $data->name;
			$lname = $data->lname;
			$sex = $data->sex;
			$sth = $this->db->prepare("
				insert into tb_test 
				(
					name,
					lname,
					sex
				)
				values
				(
					'".$name."',
					'".$lname."',
					'".$sex."'
				)

			");
			$sth->execute(array());
			$arr = array(
				'status' => 200,
				'status_name' => header_status(200),
				'data' => array(
					'success' => "insert success"
				)
			);
			echo json_encode($arr);
			http_response_code(200);
		}

		function deleteroutes($id){
			$sth = $this->db->prepare("
				delete  from tb_test where id = '".$id."'
			");
			$sth->execute(array());
			$arr = array(
				'status' => 200,
				'status_name' => header_status(200),
				'data' => array(
					'success' => "Delete Success",
				)
			);
			echo json_encode($arr);
			http_response_code(200);
		}

		function selectroutes($id){
			$sth = $this->db->prepare("
				select * from tb_test where id = '".$id."'
			");
			$sth->execute(array());
			$data = $sth->fetchAll(PDO::FETCH_ASSOC);
			$arr = array(
				'status' => 200,
				'status_name' => header_status(200),
				'data' => array(
					'success' => "OK Routers",
					'data' => $data
				)
			);
			echo json_encode($arr);
			http_response_code(200);
		}
		function showroutes(){
			// print_r($token);
			// $token['userid']
			$sth = $this->db->prepare("
				select * from tb_student
			");
			$sth->execute(array());
			$data = $sth->fetchAll(PDO::FETCH_ASSOC);
			$arr = array(
				'status' => 200,
				'status_name' => header_status(200),
				'data' => array(
					'success' => "OK Routers",
					'data' => $data
				)
			);
			echo json_encode($arr);
			http_response_code(200);
		}

		function errorAuthorization(){
			$arr = array(
				'status' => 401,
				'status_name' => header_status(200),
				'data' => array(
					'success' => "Unauthorized!"
				)
			);
			echo json_encode($arr);
			http_response_code(401);
		}

		function error(){
			$arr = array(
				'status' => 405,
				'status_name' => header_status(200),
				'data' => array(
					'success' => "Method not Allows!"
				)
			);
			echo json_encode($arr);
			http_response_code(405);
		}
		function gg(){
			echo "GG";
			http_response_code(200);
		}
		
	}
?>