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
            $customer['fullname'] = $this->input->post('fullname');
            $customer['address'] = $this->input->post('address');
            $customer['email'] = $this->input->post('email');
            $id = $this->base_model->insert_data('customer' , $data);
            if($id == FALSE){
                $message['msg'][0] = 'مشکلی در ثبت اطلاعات رخ داده است . لطفا دوباره سعی کنید';
                $message['msg'][1] = 'danger';
                $this->session->set_flashdata($message);
                redirect('customer/add');
            }
            for($i = 0 ; $i < sizeof($_POST['tel_title']) ; $i++){
                $tel[] = array(
                    'customer_id' => $id,
                    'tel_title'=> $_POST['tel_title'][$i],
                    'tel'=> $_POST['tel'][$i]
                  );
            }
            for($j = 0 ; $j < sizeof($_POST['name_acount']) ; $j++){
                $customer_info[] = array(
                    "customer_id"=> $id,
                    "name_acount"=> $_POST['name_acount'][$j],
                    "name_bank"=>$_POST['name_bank'][$j],
                    "number_acount"=>$_POST['number_acount'][$j],
                    "number_card"=>$_POST['number_card'][$j],
                    "number_shaba"=>$_POST['number_shaba'][$j]
                );
            }
            $res_tel = $this->base_model->insert_batch('customer_tel' , $tel);
            $res_info = $this->base_model->insert_batch('customer_info' , $customer_info);
            if($res_tel == TRUE and $res_info == TRUE){
                $message['msg'][0] = 'اطلاعات مشتری '. $customer['fullname'] . 'با موفقیت ثبت شد';
                $message['msg'][1] = 'success';
                $this->session->set_flashdata($message);
                redirect('customer/add/');
            }else{
             $message['msg'][0] = 'مشکلی در ثبت اطلاعات رخ داده است . لطفا دوباره سعی کنید';
             $message['msg'][1] = 'danger';
             $this->session->set_flashdata($message);
             redirect('customer/add/');     
            }
            
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