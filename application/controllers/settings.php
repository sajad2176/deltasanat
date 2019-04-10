<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {

    
    public function __construct(){
        parent::__construct();
        $this->load->library('pagination');
        $this->load->library('Convertdate');
    }
    public function set_unit(){
        if(!$this->session->has_userdata('set_unit') or $this->session->userdata('set_unit') != TRUE){
            show_404();
        }
        $header['title'] = 'تنظیم ارز ها';
        $header['active'] = 'settings';
        $header['active_sub'] = 'set_unit';
        $this->load->view('header', $header);
        $this->load->view('currency/unit');
        $this->load->view('footer');
    }
	    public function primitive_unit(){
        if(!$this->session->has_userdata('set_primitive') or $this->session->userdata('set_primitive') != TRUE){
                show_404();
        }
        $header['title'] = 'ارز اولیه';
        $header['active'] = 'settings';
        $header['active_sub'] = 'primitive_unit';
        $this->load->view('header', $header);
        $this->load->view('currency/primitive');
        $this->load->view('footer');
    }
    public function rest_unit(){
        if(!$this->session->has_userdata('rest_unit') or $this->session->userdata('rest_unit') != TRUE){
            show_404();
    }
    if(isset($_POST['sub'])){
        $customer['fullname'] = $this->input->post('fullname');
        $check = $this->base_model->get_data('customer' , 'id' , 'row' , array('fullname'=>$customer['fullname']));
        if(sizeof($check) == 0){
            $cust['fullname'] = $customer['fullname'];
            $cust['address'] = '';
            $cust['email'] = '';
            $cust['customer_tel'] = '';
            $id = $this->base_model->insert_data('customer' , $cust);
        }else{
            $id = $check->id;
        }
        $persian_num = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
        $latin_num = range(0, 9);
        $slash = '/';
        $dash = '-';
        $string = str_replace($persian_num, $latin_num, $_POST['date_deal']);
        $string = str_replace($slash, $dash, $string); 
        $date_deal = substr($string , 0 , 10);
        $time_deal = substr($string , 10 , 20);
       $date = $this->convertdate->convert(time());
       $deal['count_money'] = $this->input->post('count');
       $deal['wage'] = 0;
       $deal['convert_money'] = 1;
       $deal['volume_deal'] = $this->input->post('count');
       $deal['volume_pay'] = 0;
       $deal['volume_rest'] = $this->input->post('count');
       $deal['explain'] = '';
       $deal['date_deal'] = $date_deal;
       $deal['time_deal'] = $time_deal;
       $deal['date_modified'] = 'ثبت نشده است';
       $deal['type_deal'] = $this->input->post('type_deal');
       $deal['customer_id'] = $id;
       $deal['money_id'] = 5;

       $amount_unit = $this->base_model->get_data('currency_unit' , 'amount_unit' , 'row' , array('id'=> 5));
       if($deal['type_deal'] == 1){
           $unit['amount_unit'] = $amount_unit->amount_unit + $deal['count_money'];
           $act = 9;
           $text = " افزایش یافت ";
       }else{
           $unit['amount_unit'] = $amount_unit->amount_unit - $deal['count_money']; 
           $act = 10;
           $text = " کاهش یافت ";
       }
       $deal_id = $this->base_model->insert_data('deal' , $deal);
       if($deal_id == FALSE){
        $message['msg'][0] = 'مشکلی در ثبت اطلاعات رخ داده است. لطفا دوباره سعی کنید';
        $message['msg'][1] = 'danger';
        $this->session->set_flashdata($message);
        redirect("settings/rest_unit");
    }
    $this->base_model->update_data('currency_unit' , $unit , array('id' => $deal['money_id'])); 

    $aa = $deal_id + 100;
    $log['user_id'] = $this->session->userdata('id');
    $log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
    $log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
    $log['activity_id'] = $act;

    $log['explain'] = " نام مشتری :  ".$customer['fullname']." | شناسه معامله : ".$aa . " | ارز معامله : ریال | تعداد ارز : ".number_format($deal['count_money']) ." | کارمزد : 0 | نرخ تبدیل : 1"." حجم معامله :  ".number_format($deal['volume_deal'])." ریال "." | مقدار ارز ریال به اندازه  ".number_format($deal['count_money'])." ".$text;
    $this->base_model->insert_data('log' , $log);
    $message['msg'][0] = 'اطلاعات با موفقیت ثبت شد';
    $message['msg'][1] = 'success';
    $this->session->set_flashdata($message);
    redirect("settings/rest_unit");
    }else{
        $total_rows = $this->base_model->get_count("deal" , array('money_id'=> 5));
        $config['base_url'] = base_url('settings/rest');
        $config['total_rows'] = $total_rows;
        $config['per_page'] = '10';
        $config["uri_segment"] = '3';
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
    $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;      
    $data['deal'] = $this->base_model->get_data_join('deal' ,'customer', 'deal.* , customer.fullname , customer.id as cust_id ,currency_unit.name' , 'deal.customer_id = customer.id' ,'result'  , array('money_id'=> 5) , $config['per_page'] , $page , array('deal.id' , 'DESC') , array('currency_unit','deal.money_id = currency_unit.id'));
    $data['page'] = $this->pagination->create_links();
    $data['count'] = $config['total_rows'];
    $date = $this->convertdate->convert(time());
    $data['date'] = $date['year']."/".$date['month_num']."/".$date['day'] . " ".$date['hour'].":".$date['minute'].":".$date['second'];
        $header['title'] = 'مانده حساب ریالی';
        $header['active'] = 'settings';
        $header['active_sub'] = 'rest_unit';
        $data['customer'] = $this->base_model->get_data('customer' , 'fullname','result');
        $this->load->view('header', $header);
        $this->load->view('currency/rest' , $data);
        $this->load->view('footer');
    }
    }
	 }
?>