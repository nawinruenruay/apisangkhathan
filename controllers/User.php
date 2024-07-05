<?php
	class User extends Controller {
	    function __construct(){
	    		parent::__construct();
			}
         
        function ShowUser(){
            $this->model->ShowUser();
        }

    }
?>