<?php
	class User extends Controller {
	    function __construct(){
	    		parent::__construct();
			}
         
        function ShowUser(){
            $this->model->ShowUser();
        }
        
        function Addemail_phone_birthday(){
            $this->model->Addemail_phone_birthday();
        }

        function Update_name_sex(){
            $this->model->Update_name_sex();
        }

        function UploadIMG(){
            $this->model->UploadIMG();
        }

    }
?>