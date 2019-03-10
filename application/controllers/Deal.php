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
$data['deal'] = $this->base_model->get_data_join('deal' ,'customer', 'deal.* , customer.fullname , currency_unit.name' , 'deal.customer_id = customer.id' ,'result'  , array('deal.pub'=> 1), $config['per_page'] , $page , array('deal.id' , 'DESC') , array('currency_unit','deal.money_id = currency_unit.id'));
$data['page'] = $this->pagination->create_links();
$data['count'] = $config['total_rows'];

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
    public function handle($id){
        if(isset($_POST['sub'])){

        }else{
            $header['title'] = 'هماهنگی ها';
            $header['active'] = 'deal';
            $header['active_sub'] = 'deal_archive';
            $data['deal'] = $this->base_model->get_data_join('deal' ,'customer', 'deal.* , customer.fullname , currency_unit.name' , 'deal.customer_id = customer.id' ,'row'  , array('deal.pub'=> 1 , 'deal.id'=>$id) , NULL , NULL , NULL , array('currency_unit','deal.money_id = currency_unit.id'));
            $data['bank'] = $this->base_model->get_data('deal_bank' , '*' , 'result' , array('deal_id' => $id));
            $data['select'] = $this->base_model->get_data('deal_bank' , '*' , 'result' , array('deal_id' => $id , 'active' => 1));
            $data['handle'] = $this->base_model->get_data('deal_handle' , '*' , 'result' , array('deal_id' => $id));
            $this->load->view('header' , $header);
            $this->load->view('deal/handle' , $data);
            $this->load->view('footer');
        }
    }

}

/* End of file Controllername.php */

?>