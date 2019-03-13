<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Deal extends CI_Controller {

    
    public function __construct(){
        parent::__construct();
        $this->load->library('Convertdate');
        $this->load->library('pagination');
    }
   public function archive(){
    $total_rows = $this->base_model->get_count("deal" , 'ALL');
    if($this->uri->segment(3) == 'show'){
        $base_url = "deal/archive/show/".$this->uri->segment(4);
        $uri_segment = 5;
        if($this->uri->segment(4) == 'all'){
            $per_page = $total_rows;
        }else{
            $per_page = $this->uri->segment(4);
        }
    }else{
        $uri_segment = 3;
        $base_url = "deal/archive/";
        $per_page = 10;
    }
    $config['base_url'] = base_url($base_url);
    $config['total_rows'] = $total_rows;
    $config['per_page'] = $per_page;
    $config["uri_segment"] = $uri_segment;
    $config['num_links'] = '5';
    $config['next_link'] = '<i class="icon-arrow-left5"></i>';
    $config['last_link'] = '<i class="icon-backward2"></i>';
    $config['prev_link'] = '<i class="icon-arrow-right5"></i>';
    $config['first_link'] = '<i class="icon-forward3"></i>';
    $config['full_tag_open'] = '<nav><ul class="pagination pagination-sm">';
    $config['full_tag_close'] = '</ul></nav>';
    $config['cur_tag_open'] = '<li class="active"><a href="javascript:void(0)">';
    $config['cur_tag_close'] = '</a></li>';
    $config['num_tag_open'] = '<li>';
    $config['num_tag_close'] = '</li>';
    $config['next_tag_open'] = '<li>';
    $config['next_tag_close'] = '</li>';
    $config['last_tag_open'] = '<li>';
    $config['last_tag_close'] = '</li>';
    $config['first_tag_open'] = '<li>';
    $config['first_tag_close'] = '</li>';
    $config['prev_tag_open'] = '<li>';
    $config['prev_tag_close'] = '</li>';
    $config['suffix'] = "";
$this->pagination->initialize($config);
$page = ($this->uri->segment($uri_segment)) ? $this->uri->segment($uri_segment) : 0;      
$data['deal'] = $this->base_model->get_data_join('deal' ,'customer', 'deal.* , customer.fullname , customer.id as cust_id ,currency_unit.name' , 'deal.customer_id = customer.id' ,'result'  , array('deal.pub'=> 1), $config['per_page'] , $page , array('deal.id' , 'DESC') , array('currency_unit','deal.money_id = currency_unit.id'));
$data['page'] = $this->pagination->create_links();
$data['count'] = $config['total_rows'];
$date = $this->convertdate->convert(time());
$data['date'] = $date['year']."/".$date['month_num']."/".$date['day'] . " ".$date['hour'].":".$date['minute'].":".$date['second'];
        $header['title'] = 'آرشیو معاملات';
        $header['active'] = 'deal';
        $header['active_sub'] = 'deal_archive';
        $this->load->view('header' , $header);
        $this->load->view('deal/archive',$data);
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
           if($deal['type_deal'] == 1){$page = 'buy';}else {$page = "sell";}
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
          if($_POST['number_shaba'][0] != ''){
            for($i = 0 ; $i < sizeof($_POST['number_shaba']) ; $i++){
                $bank[] = array(
                    'explain'=> htmlspecialchars($_POST['bank_explain'][$i]),
                    'number_shaba'=>htmlspecialchars($_POST['number_shaba'][$i]),
                    'name_bank'=> htmlspecialchars($_POST['name_bank'][$i]),
                    'amount'=> htmlspecialchars($_POST['amount_bank'][$i]),
                    'pay'=>0,
                    'active'=> 1,
                    'deal_id'=> $deal_id
                );
            }
            $res_bank = $this->base_model->insert_batch('deal_bank' , $bank);
            if($res_bank == FALSE){
        $message['msg'][0] = 'مشکلی در ثبت اطلاعات رخ داده است. لطفا دوباره سعی کنید';
        $message['msg'][1] = 'danger';
        $this->session->set_flashdata($message);
        redirect("deal/$page");
            }
          }
         $message['msg'][0] = 'اطلاعات معامله با موفقیت ثبت شد';
         $message['msg'][1] = 'success';
         $this->session->set_flashdata($message);
         redirect("deal/$page");

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
        $id = $this->uri->segment(3);
        if(isset($id) and is_numeric($id)){
            if(isset($_POST['sub'])){
                $date = $this->convertdate->convert(time());
                $d = $date['year']."-".$date['month_num']."-".$date['day'];
                $t = $date['hour'].":".$date['minute'].":".$date['second'];
                for($i = 0 ; $i < sizeof($_POST['customer']) ; $i++){
                    if($_POST['bank_id'][$i] == 0){
                        $message['msg'][0] = 'لطفا شماره حساب را از لیست مربوطه انتخاب کنید . در صورت موجود نبودن شماره حساب لطفا اقدام به افزودن شماره حساب کنید' ;
                        $message['msg'][1] = 'danger';
                        $this->session->set_flashdata($message);
                        redirect("deal/handle/$id");
                     }else{
                    $check = $this->base_model->get_data('customer' , 'id' , 'row' , array('pub' => 1 , 'fullname'=> $_POST['customer'][$i]));
                    if($check == FALSE){
                        $customer = array(
                            'fullname'=>$_POST['customer'][$i],
                            'address'=>'',
                            'email'=>'',
                            'customer_tel'=> '',
                            'pub'=> 1
                        );
                        $customer_id = $this->base_model->insert_data('customer' , $customer);
                    }else{
                       $customer_id = $check->id;
                    }
                    $handle[] = array(
                     'volume_handle'=> htmlspecialchars($_POST['volume_handle'][$i]),
                      'handle_pay' => 0 ,
                      'handle_rest'=> htmlspecialchars($_POST['volume_handle'][$i]),
                      'date_handle' => $d , 
                      'time_handle' => $t , 
                      'date_modified' => '',
                      'customer_id' => $customer_id,
                      'deal_id'=> $id,
                      'bank_id' => htmlspecialchars($_POST['bank_id'][$i])
                    );
                }
                }
                $res = $this->base_model->insert_batch('deal_handle' , $handle);
                if($res == FALSE){
                    $message['msg'][0] = 'مشکلی در ثبت اطلاعات رخ داده است . لطفا دوباره سعی کنید';
                    $message['msg'][1] = 'danger';
                    $this->session->set_flashdata($message);
                    redirect("deal/handle/$id");
                }else{
                    $message['msg'][0] = 'اطلاعات هماهنگی با موفقیت ثبت شد';
                    $message['msg'][1] = 'success';
                    $this->session->set_flashdata($message);
                    redirect("deal/handle/$id");
                }
            }else{
                $header['title'] = 'هماهنگی ها';
                $header['active'] = 'deal';
                $header['active_sub'] = 'deal_archive';
                $data['customer'] = $this->base_model->get_data('customer' ,'fullname' , 'result' , array('pub' => 1));
                $data['deal'] = $this->base_model->get_data_join('deal' ,'customer', 'deal.* , customer.fullname , currency_unit.name , sum(deal_handle.volume_handle) as vh , sum(deal_handle.handle_rest) as vr' , 'deal.customer_id = customer.id' ,'row'  , array('deal.pub'=> 1 , 'deal.id'=>$id) , NULL , NULL , NULL , array('currency_unit','deal.money_id = currency_unit.id') , array('deal_handle','deal_handle.deal_id = deal.id'));
                if(sizeof($data['deal']) == 0){
                show_404();
                }else{
                    $data['bank'] = $this->base_model->get_data('deal_bank' , '*' , 'result' , array('deal_id' => $id) , NULL , NULL , array('id' , 'DESC'));
                    $data['select'] = $this->base_model->get_data('deal_bank' , 'id , number_shaba , name_bank' , 'result' , array('deal_id' => $id , 'active' => 1) , NULL , NULL , array('id' , 'DESC'));
                    $data['handle'] = $this->base_model->get_data_join('deal_handle','customer' , 'deal_handle.* , customer.fullname , deal_bank.number_shaba , deal_bank.name_bank','deal_handle.customer_id = customer.id', 'result' , array('deal_handle.deal_id' => $id) , NULL , NULL , array('deal_handle.id' , 'DESC') , array('deal_bank' , 'deal_handle.bank_id = deal_bank.id'));
                    $this->load->view('header' , $header);
                    $this->load->view('deal/handle' , $data);
                    $this->load->view('footer');
                }
     
            }
           
        }else{
            show_404();
        }
    }
    public function handle_profile(){
        $id = $this->uri->segment(3);
        if(isset($id) and is_numeric($id)){
            if(isset($_POST['sub'])){
             
             
            }else{
                $header['title'] = 'هماهنگی';
                $header['active'] = 'deal';
                $header['active_sub'] = 'deal_archive';
                $total_rows = $this->base_model->get_count("deal" , array('customer_id' => $id));
                $config['base_url'] = base_url("deal/handle_profile/$id");
                $config['total_rows'] = $total_rows;
                $config['per_page'] = '5';
                $config["uri_segment"] = '4';
                $config['num_links'] = '5';
                $config['next_link'] = '<i class="icon-arrow-left5"></i>';
                $config['last_link'] = '<i class="icon-backward2"></i>';
                $config['prev_link'] = '<i class="icon-arrow-right5"></i>';
                $config['first_link'] = '<i class="icon-forward3"></i>';
                $config['full_tag_open'] = '<nav><ul class="pagination pagination-sm">';
                $config['full_tag_close'] = '</ul></nav>';
                $config['cur_tag_open'] = '<li class="active"><a href="javascript:void(0)">';
                $config['cur_tag_close'] = '</a></li>';
                $config['num_tag_open'] = '<li>';
                $config['num_tag_close'] = '</li>';
                $config['next_tag_open'] = '<li>';
                $config['next_tag_close'] = '</li>';
                $config['last_tag_open'] = '<li>';
                $config['last_tag_close'] = '</li>';
                $config['first_tag_open'] = '<li>';
                $config['first_tag_close'] = '</li>';
                $config['prev_tag_open'] = '<li>';
                $config['prev_tag_close'] = '</li>';
                $config['suffix'] = "";
            $this->pagination->initialize($config);
            $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;      
            $data['deal'] = $this->base_model->get_data_join('deal' ,'customer', 'deal.* , customer.fullname ,currency_unit.name' , 'deal.customer_id = customer.id' ,'result'  , array('deal.pub'=> 1 , 'deal.customer_id'=> $id), $config['per_page'] , $page , array('deal.id' , 'DESC') , array('currency_unit','deal.money_id = currency_unit.id'));
            $data['page'] = $this->pagination->create_links();
            if(sizeof($data['deal']) == 0){
                show_404();
            }else{
            $data['bank'] = $this->base_model->get_data_join('deal' , 'deal_bank'  ,'deal.id as deal_id , deal_bank.*','deal.id = deal_bank.deal_id' ,'result' ,array('deal.pub'=> 1 , 'deal.customer_id' => $id) , NULL , NULL , array('deal_bank.id', 'DESC'));
            $data['select'] = $this->base_model->get_data_join('deal' , 'deal_bank' ,'deal_bank.id , deal_bank.number_shaba , deal_bank.name_bank , deal.id as deal_id','deal.id = deal_bank.deal_id' , 'result' , array('deal.customer_id' => $id , 'active' => 1) , NULL , NULL , array('id' , 'DESC'));  
                $this->load->view('header' , $header);
                $this->load->view('deal/handle_profile' , $data);
                $this->load->view('footer');
            }
        }
        }else{
            show_404();
        }

    }
    public function active(){
        $deal_id = $this->uri->segment(3);
        $id = $this->uri->segment(4);
        $data['active'] = $this->uri->segment(5);
        $this->base_model->update_data('deal_bank' , $data , array('id' => $id));
        $seg = $this->uri->segment(6);
        if($seg == 'group'){
            $red = 'handle_profile';
        }else{
            $red = 'handle';
        }
        redirect("deal/$red/$deal_id");
    }
    public function add_bank($id){
      if(isset($_POST['sub'])){
          $data['explain'] = $this->input->post('bank_explain');
          $data['name_bank'] = $this->input->post('name_bank');
          $data['number_shaba'] = $this->input->post('number_shaba');
          $data['amount'] = $this->input->post('amount_bank');
          $data['pay'] = 0;
          $data['deal_id'] = $id;
          $data['active'] = 1;
          $res = $this->base_model->insert_data('deal_bank' , $data);
          if($res == FALSE){
              $message['msg'][0] = 'مشکلی در ثبت اطلاعات رخ داده است . لطفا دوباره سعی کنید';
              $message['msg'][1] = 'danger';
              $this->session->set_flashdata($message);
              redirect("deal/handle/$id");
          }else{
            $message['msg'][0] = 'اطلاعات حساب بانکی با موفقیت ثبت شد';
            $message['msg'][1] = 'success';
            $this->session->set_flashdata($message);
            redirect("deal/handle/$id");
          }
      }else{
          show_404();
      }
    }
    public function pay_all($deal_all , $id){
        $deal_all = $this->uri->segment(3);
        $id = $this->uri->segment(4);
        if(isset($deal_all) and isset($id)){

            $handle = $this->base_model->get_data_join('deal_handle' , 'deal' , 'deal_handle.handle_pay , deal_handle.handle_rest , deal_handle.bank_id , deal.volume_pay , deal.volume_rest , deal_bank.pay' , 'deal_handle.deal_id = deal.id' , 'row' , array('deal_handle.id'=> $id) , NULL , NULL , NULL , array('deal_bank','deal_bank.id = deal_handle.bank_id'));
            $date = $this->convertdate->convert(time());
            $history['date_pay'] = $date['year']."-".$date['month_num']."-".$date['day']." ".$date['hour'].":".$date['minute'].":".$date['second'];
            $history['active'] = 1;
            $history['volume'] = $handle->handle_rest;
            $history['handle_id'] = $id;
            $deal['volume_pay'] = $handle->volume_pay + $handle->handle_rest;
            $deal['volume_rest'] = $handle->volume_rest - $handle->handle_rest;
            $deal_handle['handle_rest'] = 0;
            $deal_handle['handle_pay'] = $handle->handle_pay + $handle->handle_rest;
            $deal_bank['pay'] = $handle->pay + $handle->handle_rest;
            $this->base_model->update_data('deal' , $deal , array('id'=>$deal_all));
            $this->base_model->update_data('deal_handle' , $deal_handle , array('id' => $id));
            $this->base_model->update_data('deal_bank' , $deal_bank , array('id' => $handle->bank_id));
            $res = $this->base_model->insert_data('handle_history' , $history);
    
            if($res == FALSE){
                $message['msg'][0] = 'مشکلی در ثبت اطلاعات رخ داده است . لطفا دوباره سعی کنید';
                $message['msg'][1] = 'danger';
                $this->session->set_flashdata($message);
                redirect("deal/handle/$deal_all");
            }else{
              $message['msg'][0] = 'پرداخت به صورت کامل انجام شد';
              $message['msg'][1] = 'success';
              $this->session->set_flashdata($message);
              redirect("deal/handle/$deal_all");
            }

        }else{
         show_404();
        }
    }
    public function pay_slice(){
        $deal_id = $this->uri->segment(3);
        $id = $this->uri->segment(4);
        if(isset($deal_id) and isset($id)){
            if(isset($_POST['sub'])){
                $handle = $this->base_model->get_data_join('deal_handle','deal','deal_handle.handle_pay,deal_handle.handle_rest,deal_handle.bank_id,deal.volume_pay , deal.volume_rest ,  deal_bank.pay' , 'deal_handle.deal_id = deal.id' , 'row' , array('deal_handle.id'=> $id) , NULL , NULL , NULL , array('deal_bank','deal_bank.id = deal_handle.bank_id'));
                $slice = $this->input->post('slice');
                $date = $this->convertdate->convert(time());
                $history['date_pay'] = $date['year']."-".$date['month_num']."-".$date['day']." ".$date['hour'].":".$date['minute'].":".$date['second'];
                $history['active'] = 1;
                $history['volume'] = $slice;
                $history['handle_id'] = $id;
                $deal_handle['handle_rest'] = $handle->handle_rest - $slice;
                $deal_handle['handle_pay'] = $handle->handle_pay + $slice;
                $deal['volume_pay'] = $handle->volume_pay + $slice;
                $deal['volume_rest'] = $handle->volume_rest - $slice;
                $deal_bank['pay'] = $handle->pay + $slice;
                $this->base_model->update_data('deal' , $deal , array('id' => $deal_id));
                $this->base_model->update_data('deal_handle' , $deal_handle , array('id' => $id));
                $this->base_model->update_data('deal_bank' , $deal_bank , array('id' => $handle->bank_id));
                $res = $this->base_model->insert_data('handle_history' , $history);
                if($res == TRUE){
                    $message['msg'][0] = 'پرداخت با موفقیت انجام شد';
                    $message['msg'][1] = 'success';
                    $this->session->set_flashdata($message);
                    redirect("deal/handle/$deal_id");
                }else{
                    $message['msg'][0] = 'مشکلی در روند عملیات رخ داده است . لطفا دوباره سعی کنید';
                    $message['msg'][1] = 'danger';
                    $this->session->set_flashdata($message);
                    redirect("deal/handle/$deal_id");
                }
        
                }else{
                    show_404();
                }
        }else{
            show_404();
        }
 
    }

public function photo(){
   $header['title'] = 'عکس ها';
   $header['active'] = 'deal';
   $header['active_sub'] = 'deal_archive';
   $this->load->view('header' , $header);
   $this->load->view('deal/photo');
   $this->load->view('footer');
}
public function get_history(){
    if(isset($_POST['handle_id'])){
       $handle_id = $this->input->post('handle_id');
       $history = $this->base_model->get_data('handle_history' , 'handle_history.*' , 'result' , array('handle_id'=> $handle_id , 'active'=> 1));
       echo json_encode($history);
    }else{
        show_404();
    }
}
public function restore(){
    $id = $this->uri->segment(3);
    $deal_id = $this->uri->segment(4);
    if(isset($id) and isset($deal_id)){
        $res = $this->base_model->get_data('handle_history' , 'handle_id ,volume' , 'row' , array('id' => $id));
        $handle_id = $res->handle_id;
        $restore = $res->volume;
        $store = $this->base_model->get_data_join('deal_handle' , 'deal' , 'deal_handle.handle_pay , deal_handle.handle_rest , deal.volume_pay , deal.volume_rest , deal_bank.pay , deal_handle.bank_id' , 'deal_handle.deal_id = deal.id' , 'row' , array('deal_handle.id' => $handle_id) , NULL , NULL , NULL , array('deal_bank' , 'deal_bank.id = deal_handle.bank_id'));
       $bank_id = $store->bank_id;
       $handle['handle_pay'] = $store->handle_pay - $restore;
       $handle['handle_rest'] = $store->handle_rest + $restore;
       $deal['volume_pay'] = $store->volume_pay - $restore;
       $deal['volume_rest'] = $store->volume_rest + $restore;
       $bank['pay'] = $store->pay - $restore;
       $history['active'] = 0;
       $this->base_model->update_data('handle_history' , $history , array('id' => $id));
       $this->base_model->update_data('deal_handle', $handle , array('id' => $handle_id));
       $this->base_model->update_data('deal' , $deal , array('id' => $deal_id));
       $res = $this->base_model->update_data('deal_bank' , $bank , array('id'=> $bank_id));
       if($res == TRUE){
        $message['msg'][0] = 'مبلغ مورد نظر با موفقیت بازگرداننده شد';
        $message['msg'][1] = 'success';
        $this->session->set_flashdata($message);
        redirect("deal/handle/$deal_id");
    }else{
        $message['msg'][0] = 'مشکلی در روند عملیات رخ داده است . لطفا دوباره سعی کنید';
        $message['msg'][1] = 'danger';
        $this->session->set_flashdata($message);
        redirect("deal/handle/$deal_id");
    }
       
    }else{
        show_404();
    }
}
}

/* End of file Controllername.php */

?>