<?php
	class Product extends Controller {
	    function __construct(){
	    		parent::__construct();
		}
	
		function ShowProduct(){
			$this->model->ShowProduct();
		}

		function postShowproduct(){
			$this->model->postShowproduct();
		}


		// function ProductCheck(){
		// 	$this->model->ProductCheck();
		// }

		function ShowDetailEdit(){
			$this->model->ShowDetailEdit();
		}

		function ShowIMG(){
			$this->model->ShowIMG();
		}

		function SaveEdit(){
			$this->model->SaveEdit();
		}

		function DelProduct(){
			$this->model->DelProduct();
		}

		function ShowTypeProduct(){
			$this->model->ShowTypeProduct();
		}

		function AddProduct(){
			$this->model->AddProduct();
		}

		function UploadIMG(){
			$this->model->UploadIMG();
		}

		
		

       ////////////////////////////////////////////////////////////////////////////////////////////////////////
	}
?>