<?php
class Device extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function Test()
    {
        $this->model->Test();
    }

    public function CountInfo()
    {
        $this->model->CountInfo();
    }

}
