<?php
	class Test extends Controller {
	    function __construct(){
	    		parent::__construct();
			}
	    function index(){
				$REQUEST_METHOD = $_SERVER["REQUEST_METHOD"];
				switch($REQUEST_METHOD){
					case 'GET' : //แสดงข้อมูล
							$this->model->show();
							break;
					default:
							$this->model->error();
							break;
				}
	    }

		function routers($id=null){
				$REQUEST_METHOD = $_SERVER["REQUEST_METHOD"];
				switch($REQUEST_METHOD){
					case 'GET' : //แสดงข้อมูล
						if($id==null){
							$this->model->showroutes();
						} else{
							$this->model->selectroutes($id);
						}
							break;
					case 'POST' : //แสดงข้อมูล
							$this->model->insertroutes();
							break;
					case 'PUT' : //แสดงข้อมูล
							$this->model->updateroutes($id);
							break;	
					case 'DELETE' : //แสดงข้อมูล
							$this->model->deleteroutes($id);
							break;		
					default:
							$this->model->error();
							break;
				}
	    }
		function gg(){
			$this->model->gg();
		}
	}
?>