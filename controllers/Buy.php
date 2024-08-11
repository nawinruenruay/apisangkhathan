<?php
	class Buy extends Controller {
	    function __construct(){
	    		parent::__construct();
		}

		function Buyproduct(){
			$this->model->Buyproduct();
		}

		function Checkout(){
			$this->model->Checkout();
		}

		function UploadIMG(){
			$this->model->UploadIMG();
		}

		function CancelOrder(){
			$this->model->CancelOrder();
		}




		


       ////////////////////////////////////////////////////////////////////////////////////////////////////////
	}
?>