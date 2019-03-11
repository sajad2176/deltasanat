<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    
    public function __construct(){
        parent::__construct();
    }
    public function archive(){
        $header['title'] = 'آرشیو کاربران';
        $header['active'] = 'admin';
        $header['active_sub'] = 'admin_archive';
        $this->load->view('header' , $header);
        $this->load->view('admin/archive');
        $this->load->view('footer');
    }

    public function add(){
    if(isset($_POST['sub'])){

    }else{
        $header['title'] = 'افزودن کاربر جدید';
        $header['active'] = 'admin';
        $header['active_sub'] = 'admin_add';
        $this->load->view('header' , $header);
        $this->load->view('admin/add');
        $this->load->view('footer');
    }
    }
  public function log(){
        $header['title'] = 'فعالیت کاربران';
        $header['active'] = 'admin';
        $header['active_sub'] = 'admin_archive';
        $this->load->view('header' , $header);
        $this->load->view('admin/log');
        $this->load->view('footer');
    }
    

}

/* End of file Controllername.php */

?>