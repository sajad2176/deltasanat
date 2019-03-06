<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Deal extends CI_Controller {

    
    public function __construct(){
        parent::__construct();
    }
   public function archive(){
        $header['title'] = 'آرشیو معاملات';
        $header['active'] = 'deal';
        $header['active_sub'] = 'deal_archive';
        $this->load->view('header' , $header);
        $this->load->view('deal/archive');
        $this->load->view('footer');
        
    } 
    public function add(){
        if(isset($_POST['sub'])){

        }else{
            $header['title'] = 'افزودن معامله';
            $header['active'] = 'deal';
            $header['active_sub'] = 'deal_add';
            $this->load->view('header' , $header);
            $this->load->view('deal/add');
            $this->load->view('footer');
        }

        
    }

}

/* End of file Controllername.php */

?>