<?php
	class Order extends Controller {
	    function __construct(){
	    		parent::__construct();
		}
	
		function ShowOrder(){
			$this->model->ShowOrder();
		}

		function OrderDetail(){
			$this->model->OrderDetail();
		}

		function Confirmpay(){
			$this->model->Confirmpay();
		}

		


	

        


       ////////////////////////////////////////////////////////////////////////////////////////////////////////
	}
?>