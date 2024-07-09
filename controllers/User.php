<?php
	class User extends Controller {
	    function __construct(){
	    		parent::__construct();
			}
         
        function ShowUser(){
            $this->model->ShowUser();
        }
        
        function Updatedata(){
            $this->model->Updatedata();
        }

        function UploadIMG(){
            $this->model->UploadIMG();
        }

    }
?>