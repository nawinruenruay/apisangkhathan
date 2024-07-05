<?php
	class Activity extends Controller {
	    function __construct(){
	    		parent::__construct();
		}
	
		function ShowActivity(){
			$this->model->ShowActivity();
		}

		function postShowactivity(){
			$this->model->postShowactivity();
		}

        function AllImg(){
			$this->model->AllImg();
		}

		function ActivityDetail(){
			$this->model->ActivityDetail();
		}

        function DelImage(){
			$this->model->DelImage();
		}

		function AddActivity(){
			$this->model->AddActivity();
		}

		function UploadIMG(){
			$this->model->UploadIMG();
		}

		function UploadGallery(){
			$this->model->UploadGallery();
		}

		function ShowDetailEdit(){
			$this->model->ShowDetailEdit();
		}

		function ShowIMG(){
			$this->model->ShowIMG();
		}

		function SaveEdit(){
			$this->model->SaveEdit();
		}



       ////////////////////////////////////////////////////////////////////////////////////////////////////////
	}
?>