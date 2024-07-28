<?php
	class Buy extends Controller {
	    function __construct(){
	    		parent::__construct();
		}

		function Buyproduct(){
			$this->model->Buyproduct();
		}

		function CancelOrder(){
			$this->model->CancelOrder();
		}




		


       ////////////////////////////////////////////////////////////////////////////////////////////////////////
	}
?>