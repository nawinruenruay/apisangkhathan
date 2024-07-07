<?php
	class User extends Controller {
	    function __construct(){
	    		parent::__construct();
			}
         
        function ShowUser(){
            $this->model->ShowUser();
        }



        function Addemail(){
            $this->model->Addemail();
        }

        function Addphone(){
            $this->model->Addphone();
        }

        function Addbirthday(){
            $this->model->Addbirthday();
        }

    }
?>