<?php
	class Auth extends Controller {
	    function __construct(){
	    	parent::__construct();
		}
			
	   function LoginAdmin()
	   {
        $this->model->LoginAdmin();
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