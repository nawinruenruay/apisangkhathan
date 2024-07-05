<?php
	class User extends Controller {
	    function __construct(){
	    		parent::__construct();
			}
        function ShowUser(){
            $this->model->ShowUser();
        }
         function ShowUserEdit($id){
            $this->model->ShowUserEdit($id);
         }    
         function ShowUstatus(){
            $this->model->ShowUstatus();
         }    
         function SaveEdit(){
            $this->model->SaveEdit();
         }    
         function SaveUser(){
            $this->model->SaveUser();
         }    
         function SaveRole(){
            $this->model->SaveRole();
         }    
         function SaveEDTail(){
            $this->model->SaveEDTail();
         }    

    }
?>