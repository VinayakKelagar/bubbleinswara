<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller{

     function __construct(){

        parent::__construct();
        $this->load->database();
        $this->load->model('admin/Login_model','Alogin');
    }

    public function index()
    {
    	$this->load->view('admin/include/header');
    	$this->load->view('admin/dashboard/dashboard');
    	$this->load->view('admin/include/footer');
    }
}