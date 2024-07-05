<?php
	class Banner extends Controller {
	    function __construct(){
	    		parent::__construct();
		}
	
		function ShowBanner(){
			$this->model->ShowBanner();
		}

		function UploadIMG(){
			$this->model->UploadIMG();
		}

		function DelBanner(){
			$this->model->DelBanner();
		}

		function ShowDetailEdit(){
			$this->model->ShowDetailEdit();
		}

		function SaveEdit(){
			$this->model->SaveEdit();
		}

        


       ////////////////////////////////////////////////////////////////////////////////////////////////////////
	}
?>