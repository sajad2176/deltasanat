<?php
class Home extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->load->library('Convertdate');
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
$date = $this->convertdate->convert(time());
$log['user_id'] = $this->session->userdata('id');
$log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
$log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
$log['activity_id'] = 2;
$log['explain'] = 'خروچ از سامانه';
$this->base_model->insert_data('log' , $log);
        $this->session->unset_userdata('login');
        $this->session->unset_userdata('username');
        
        redirect('login');
        
        
    }
}
?>