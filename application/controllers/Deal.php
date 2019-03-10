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
            $customer['fullname'] = $this->input->post('customer[0]');
            $check = $this->base_model->get_data('customer' , 'id' , 'row' , array('fullname'=>$customer['fullname']));
            if(sizeof($check) == 0){
                $customer['address'] = '';
                $customer['email'] = '';
                $customer['customer_tel'] = '';
                $customer['pub'] = 1;
                $id = $this->base_model->insert_data('customer' , $customer);
            }else{
                $id = $check->id;
            }
            $date = $this->convertdate->convert(time());
           $deal['count_money'] = $this->input->post('count_money');
           $deal['wage'] = $this->input->post('wage');
           $deal['convert_money'] = $this->input->post('convert_money');
           $deal['volume_deal'] = ($deal['count_money'] + $deal['wage']) * $deal['convert_money'];
           $deal['volume_pay'] = 0;
           $deal['volume_rest'] = $deal['volume_deal'];
           $deal['explain'] = $this->input->post('explain');
           $deal['date_deal'] = $date['year']."-".$date['month_num']."-".$date['day'];
           $deal['time_deal'] = $date['hour'].":".$date['minute'].":".$date['second'];
           $deal['date_modified'] = '';
           $deal['type_deal'] = $this->input->post('deal_type');
           $deal['customer_id'] = $id;
           $deal['money_id'] = $this->input->post('money_id');
           $deal['pub'] = 1;
           if($deal['type_deal'] == 1){$page = 'buy';}else {$page = "sell"};
           $deal_id = $this->base_model->insert_data('deal' , $deal);
           if($deal_id == FALSE){
               $message['msg'][0] = 'مشکلی در ثبت اطلاعات رخ داده است. لطفا دوباره سعی کنید';
               $message['msg'][1] = 'danger';
               $this->session->set_flashdata($message);
               redirect("deal/$page");
           } 

           $img = array();

           if($_FILES['deal_pic']['name'][0] != ''){
            $count = count($_FILES['deal_pic']['name']);
            $files['name'] = $_FILES['deal_pic']['name'];
            $files['type'] = $_FILES['deal_pic']['type'];
            $files['tmp_name'] = $_FILES['deal_pic']['tmp_name'];
            $files['error'] = $_FILES['deal_pic']['error'];
            $files['size'] = $_FILES['deal_pic']['size'];

            for($j = 0 ; $j < $count ; $j++){
            
                $_FILES['deal_pic']['name'] = $files['name'][$j];
                $_FILES['deal_pic']['type'] = $files['type'][$j];
                $_FILES['deal_pic']['tmp_name'] = $files['tmp_name'][$j];
                $_FILES['deal_pic']['error'] = $files['error'][$j];
                $_FILES['deal_pic']['size'] = $files['size'][$j];
    
                $config['upload_path'] = './uploads/deal';
                $config['allowed_types']        = 'gif|jpg|png|jpeg';
                $config['max_size']             = 1000000000;
    
                $this->load->library('upload', $config);  
                $this->upload->initialize($config);
    
                if($this->upload->do_upload('deal_pic')){
                    $img[] = array(
                        'deal_id'=> $deal_id,
                        'pic_name' => $files['name'][$j]
                    );
                }else{
                    $message['msg'][0] = 'مشکلی در ارسال عکس ها پیش آمده لطفا دوباره سعی کنید';
                    $message['msg'][1] = 'danger';
                    $this->session->set_flashdata($message);
                    redirect("deal/$page");
                }
               }
           $this->base_model->insert_batch('deal_pic' , $img);
           }

           for($i = 0 ; $i < sizeof($_POST['number_shaba']) ; $i++){
            $bank[] = array(
                'explain'=> htmlspecialchars($_POST['bank_explain'][$i]),
                'number_shaba'=>htmlspecialchars($_POST['number_shaba'][$i]),
                'name_bank'=> htmlspecialchars($_POST['name_bank'][$i]),
                'amount'=> htmlspecialchars($_POST['amount_bank'][$i]),
                'deal_id'=> $deal_id
            );
        }
        $res_bank = $this->base_model->insert_batch('deal_bank' , $bank);
        if($res_bank == FALSE){
         $message['msg'][0] = 'مشکلی در ثبت اطلاعات رخ داده است. لطفا دوباره سعی کنید';
         $message['msg'][1] = 'danger';
         $this->session->set_flashdata($message);
        redirect("deal/$page");
     }else{
         $message['msg'][0] = 'اطلاعات معامله با موفقیت ثبت شد';
         $message['msg'][1] = 'success';
         $this->session->set_flashdata($message);
         redirect("deal/$page");
     } 
        }else{
            $header['title'] = 'افزودن معامله';
            $header['active'] = 'deal';
            $header['active_sub'] = 'deal_buy';
            $data['customer'] = $this->base_model->get_data('customer' ,'fullname' , 'result' , array('pub' => 1));
            $this->load->view('header' , $header);
            $this->load->view('deal/buy' , $data);
            $this->load->view('footer');
        }

        
    }
    public function sell(){
            $header['title'] = 'افزودن معامله';
            $header['active'] = 'deal';
            $header['active_sub'] = 'deal_sell';
            $data['customer'] = $this->base_model->get_data('customer' ,'fullname' , 'result' , array('pub' => 1));
            $this->load->view('header' , $header);
            $this->load->view('deal/sell', $data);
            $this->load->view('footer');
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