<?php
	class Auth extends Controller {
	    function __construct(){
	    	parent::__construct();
		}
			
	   function Login()
	   {
        $this->model->Login();
       }

	   function LoginUser()
	   {
        $this->model->LoginUser();
       }

	   function Register()
	   {
        $this->model->Register();
       }

	   function verifyToken()
	   {
        $this->model->verifyToken();
	   }

	   
       ////////////////////////////////////////////////////////////////////////////////////////////////////////
	}
?>