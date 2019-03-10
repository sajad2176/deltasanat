<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Deal extends CI_Controller {

    
    public function __construct(){
        parent::__construct();
        $this->load->library('Convertdate');
    }
   public function archive(){
        $header['title'] = 'آرشیو معاملات';
        $header['active'] = 'deal';
        $header['active_sub'] = 'deal_archive';
        $this->load->view('header' , $header);
        $this->load->view('deal/archive');
        $this->load->view('footer');
        
    } 
    public function buy(){
        if(isset($_POST['sub'])){
            echo "<pre>";
            var_dump($_POST);
            var_dump($_FILES);
            echo "</pre>";
        //     $data['fullname'] = $this->input->post('fullname[0]');
        //     $chack = $this->base_model->get_data('customer' , 'id' , 'row' , array('fullname'));
        //     if(sizeof($chack) == 0){
        //         $data['address'] = '';
        //         $data['email'] = '';
        //         $data['customer_tel'] = '';
        //         $data['pub'] = 1;
        //         $id = $this->base_model->insert_data('customer' , $data);
        //     }else{
        //         $id = $check;
        //     }
        //     $date = $this->convertdate->convert(time());
        //    $data['count_money'] = $this->input->post('count_money');
        //    $data['wage'] = $this->input->post('wage');
        //    $data['convert_money'] = $this->input->post('convert_money');
        //    $data['volume_deal'] = ($data['count_money'] + $data['wage']) * $data['convert_money'];
        //    $data['volume_pay'] = 0;
        //    $data['volume_rest'] = $data['volume_deal'];
        //    $data['explain'] = $this->input->post('explain');
        //    $data['date_deal'] = $date['year']."-".$date['num_month']."-".$date['day'];
        //    $data['time_deal'] = $date['hour'].":".$date['minute'].":".$date['second'];
        //    $data['date_modified'] = '';
        //    $data['type_deal'] = $this->input->post('type_deal');
        //    $data['customer_id'] = $id;
        //    $data['money_id'] = $this->input->post('money_id');
        //    $data['pub'] = 1; 

        }else{
            $header['title'] = 'افزودن معامله';
            $header['active'] = 'deal';
            $header['active_sub'] = 'deal_buy';
            $this->load->view('header' , $header);
            $this->load->view('deal/buy');
            $this->load->view('footer');
        }

        
    }
    public function sell(){
        if(isset($_POST['sub'])){

        }else{
            $header['title'] = 'افزودن معامله';
            $header['active'] = 'deal';
            $header['active_sub'] = 'deal_sell';
            $this->load->view('header' , $header);
            $this->load->view('deal/sell');
            $this->load->view('footer');
        }

        
    }
    public function handle(){
        $header['title'] = 'آرشیو معاملات';
        $header['active'] = 'deal';
        $header['active_sub'] = 'deal_archive';
        $this->load->view('header' , $header);
        $this->load->view('deal/handle');
        $this->load->view('footer');
    }

}

/* End of file Controllername.php */

?>