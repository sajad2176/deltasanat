<?php
class Customer extends CI_Controller{
    function __construct(){
        parent::__construct();
    }
    public function archive(){
        $header['title'] = 'آرشیو مشتریان';
        $header['active'] = 'customer';
        $header['active_sub'] = 'customer_archive';
        $this->load->view('header' , $header);
        $this->load->view('customer/archive');
        $this->load->view('footer');
    }
    public function add(){
        if(isset($_POST['sub'])){

        }else{
            $header['title'] = 'افزودن مشتری جدید';
            $header['active'] = 'customer';
            $header['active_sub'] = 'customer_add';
            $this->load->view('header' , $header);
            $this->load->view('customer/add');
            $this->load->view('footer');
        }
    }
}
?>