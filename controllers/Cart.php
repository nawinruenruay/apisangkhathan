<?php
	class Cart extends Controller {
	    function __construct(){
	    		parent::__construct();
		}

		
		function Showcart(){
			$this->model->Showcart();
		}

		function Addcart(){
			$this->model->Addcart();
		}
        
		function Delcart(){
			$this->model->Delcart();
		}

		function Plus(){
			$this->model->Plus();
		}

		function Minus(){
			$this->model->Minus();
		}
	
	
		function Cartsum(){
			$this->model->Cartsum();
		}


		

	
		
		

       ////////////////////////////////////////////////////////////////////////////////////////////////////////
	}
?>