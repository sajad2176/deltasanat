<?php
class Home extends CI_Controller{
    function __construct(){
        parent::__construct();
    }
    public function index(){
        $header['title'] = 'داشبورد';
        $header['active'] = 'dashbord';
        $this->load->view('header' , $header);
        $this->load->view('home/home');
        $this->load->view('footer');
    }
}
?>