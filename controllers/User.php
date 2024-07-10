<?php
	class User extends Controller {
	    function __construct(){
	    		parent::__construct();
			}
         
        function ShowUser(){
            $this->model->ShowUser();
        }

        function Showorderbuy(){
            $this->model->Showorderbuy();
        }

        function Showorderbuydetail(){
            $this->model->Showorderbuydetail();
        }
        
        function Updatedata(){
            $this->model->Updatedata();
        }

        function UploadIMG(){
            $this->model->UploadIMG();
        }

    }
?>