<?php
class Home extends CI_Controller{
    function __construct(){
        parent::__construct();
    }
    public function index(){
        $header['title'] = 'داشبورد';
        $header['active'] = 'dashbord';
        $header['active_sub'] = '';
        $this->load->view('header' , $header);
        $this->load->view('home/home');
        $this->load->view('footer');
    }
    public function logout(){
        
        $this->session->unset_userdata('login');
        $this->session->unset_userdata('username');
        
        redirect('login');
        
        
    }
}
?>