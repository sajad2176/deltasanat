<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {

    
    public function __construct(){
        parent::__construct();
        $this->load->library('pagination');
        $this->load->library('Convertdate');
    }
    //set unit
    public function set_unit(){
        if(!$this->session->has_userdata('set_unit') or $this->session->userdata('set_unit') != TRUE){
            show_404();
        }
        if(isset($_POST['sub'])){
            $date = $this->convertdate->convert(time());
            $data['date_rate'] = $date['year']."-".$date['month_num']."-".$date['day'];
            $data['time_rate'] = $date['hour'].":".$date['minute'].":".$date['second'];
            $data['rate_euro'] = $this->input->post('euro');
            $data['rate_yuan'] = $this->input->post('yuan');
            $data['rate_derham'] = $this->input->post('derham');
            $log['user_id'] = $this->session->userdata('id');
            $log['date_log'] = $data['date_rate'];
            $log['time_log'] =  $data['time_rate'];
            $log['activity_id'] = 22;
            $log['explain'] = ' نرخ تبدیل دلار به یورو '.$data['rate_euro']." </br> نرخ تبدیل دلار به یوان : ".$data['rate_yuan']." </br> نرخ تبدیل دلار به درهم : ".$data['rate_derham'];
            $this->base_model->insert_data('log' , $log);
            $this->base_model->insert_data('rate' , $data);
            $message['msg'][0] = 'اطلاعات با موفقیت ثبت شد';
            $message['msg'][1] = 'success';
            $this->session->set_flashdata($message);
            
            redirect('settings/set_unit');
            
            
        }else{
            $total_rows = $this->base_model->get_count("rate" , 'ALL');
            $config['base_url'] = base_url('settings/set_unit');
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
        $data['rate'] = $this->base_model->get_data('rate', '*' , 'result'  , NULL , $config['per_page'] , $page , array('id' , 'DESC'));
        $data['page'] = $this->pagination->create_links();
        $data['count'] = $config['total_rows'];
            $header['title'] = 'تنظیم ارز ها';
            $header['active'] = 'settings';
            $header['active_sub'] = 'set_unit';
            $this->load->view('header', $header);
            $this->load->view('currency/unit' , $data);
            $this->load->view('footer');
        }
    }
    //set unit
    //primitive_unit
	    public function primitive_unit(){
        if(!$this->session->has_userdata('set_primitive') or $this->session->userdata('set_primitive') != TRUE){
                show_404();
        }
        if(isset($_POST['sub'])){
            $unit = $this->base_model->get_data('unit' , '*' , 'result' , array('id < ' => 10) , NULL , NULL ,array('id' , 'ASC'));
            $update = array(
                array('id'=>1 , 'amount'=> $_POST['dollar']) ,
                array('id'=>2, 'amount'=>$_POST['euro']) , 
                array('id'=>3, 'amount'=>$_POST['yuan']),
                array('id'=>4,'amount'=> $_POST['derham']) 
            );
           $exp = '';
           foreach($unit as $key => $row){
               $exp .= " موجودی ارز  ".$row->name . " از مقدار ".number_format($row->amount) . " به مقدار  ".number_format($update[$key]['amount'])." تغییر یافت "."</br>";
           }
           $date = $this->convertdate->convert(time());
           $log['user_id'] = $this->session->userdata('id');
           $log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
           $log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
           $log['activity_id'] = 23;
           $log['explain'] = $exp;
           $this->base_model->update_batch('unit' , $update , 'id');
           $this->base_model->insert_data('log' , $log);
           $message['msg'][0] = 'اطلاعات با موفقیت ثبت شد';
           $message['msg'][1] = 'success';
           $this->session->set_flashdata($message);
           redirect("settings/primitive_unit");
        }else{
            $total_rows = $this->base_model->get_count("rate" , 'ALL');
            $config['base_url'] = base_url('settings/primitive_unit');
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
        $data['change'] = $this->base_model->get_data_join('log' , 'member', 'log.* , member.username as name' ,'log.user_id = member.id' ,  'result'  , array('log.activity_id' => 23) , $config['per_page'] , $page , array('log.id' , 'DESC'));
        $data['unit'] = $this->base_model->get_data('unit' , '*' , 'result' , array('id < ' => 10) , NULL , NULL ,array('id' , 'ASC'));
        $data['page'] = $this->pagination->create_links();
        $data['count'] = $config['total_rows'];
            $header['title'] = 'ارز اولیه';
            $header['active'] = 'settings';
            $header['active_sub'] = 'primitive_unit';
            $this->load->view('header', $header);
            $this->load->view('currency/primitive' , $data);
            $this->load->view('footer');
        }
    }
    //primitive_unit
    //rest unit
    public function rest_unit(){
        if(!$this->session->has_userdata('rest_unit') or $this->session->userdata('rest_unit') != TRUE){
            show_404();
    }
    if(isset($_POST['sub'])){
        $customer['fullname'] = trim($this->input->post('fullname'), ' ');
        $check = $this->base_model->get_data('customer' , 'id' , 'row' , array('fullname'=>$customer['fullname']));
        if(empty($check)){
            $customer['address'] = '';
            $customer['email'] = '';
            $customer['customer_tel'] = '';
            $id = $this->base_model->insert_data('customer' , $customer);
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
       $deal['convert'] = 1;
       $deal['volume'] = $this->input->post('count');
       $deal['pay'] = 0;
       $deal['rest'] = $this->input->post('count');
       $deal['explain'] = '';
       $deal['date_deal'] = $date_deal;
       $deal['time_deal'] = $time_deal;
       $deal['date_modified'] = 'ثبت نشده است';
       $deal['type'] = $this->input->post('type');
       $deal['customer_id'] = $id;
       $deal['money_id'] = 10;
       $deal['state'] = 1;
       $rial = $this->base_model->get_data('unit' , 'amount' , 'row' , array('id'=> 10));
       if($deal['type'] == 1){
           $unit_rial['amount'] = $rial->amount + $deal['count_money'];
           $act = 9;
           $text = " افزیش یافت ";
       }else{
           $unit_rial['amount'] = $rial->amount - $deal['count_money']; 
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
    $this->base_model->update_data('unit' , $unit_rial , array('id' => 10)); 

    $aa = $deal_id + 100;
    $log['user_id'] = $this->session->userdata('id');
    $log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
    $log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
    $log['activity_id'] = $act;
    $log['explain'] = " نام مشتری :  ".$customer['fullname']." | شناسه معامله : ".$aa . " | ارز معامله : ریال | تعداد ارز : ".number_format($deal['count_money']) ." | کارمزد : 0 | نرخ تبدیل : 1"." حجم معامله :  ".number_format($deal['volume'])." ریال "." | مقدار ارز ریال به اندازه  ".number_format($deal['count_money'])." ".$text;
    $this->base_model->insert_data('log' , $log);
    $message['msg'][0] = 'اطلاعات با موفقیت ثبت شد';
    $message['msg'][1] = 'success';
    $this->session->set_flashdata($message);
    redirect("settings/rest_unit");

    }else{
        $total_rows = $this->base_model->get_count("deal" , array('money_id'=> 10));
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
    $data['deal'] = $this->base_model->get_data_join('deal' ,'customer', 'deal.* , customer.fullname , customer.id as cust_id ,unit.name' , 'deal.customer_id = customer.id' ,'result'  , array('deal.money_id'=> 10, 'deal.state' => 1) , $config['per_page'] , $page , array('deal.id' , 'DESC') , array('unit','deal.money_id = unit.id'));
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
    //rest unit
    //turnover
    public function turnover(){
        if(!$this->session->has_userdata('turnover') or $this->session->userdata('turnover') != TRUE){
        show_404();
        }
        $date = $this->convertdate->convert(time());
        if(isset($_POST['sub'])){
$owner = $this->input->post('owner');

$persian_num = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
$latin_num = range(0, 9);
$slash = '/';
$dash = '-';
$start = str_replace($persian_num, $latin_num, $_POST['start_date']);
$start = str_replace($slash, $dash, $start);
$end = str_replace($persian_num, $latin_num, $_POST['end_date']);
$end = str_replace($slash, $dash, $end); 
$date_start = substr($start , 0 , 10);
$date_end = substr($end , 0 , 10);
$between = "turnover.date BETWEEN '$date_start' AND '$date_end'";
$data['turnover'] = $this->base_model->search_data('turnover' , 'bank' , 'turnover.* , bank.shaba , bank.name , customer.fullname' , 'turnover.bank_id = bank.id' ,'inner' , array('turnover.owner' => $owner) , NULL , NULL , NULL , array('customer' , 'customer.id = turnover.cust_id') , $between);
$data['date'] = $date['year']."/".$date['month_num']."/".$date['day']." ".$date['hour'].":".$date['minute'].":".$date['second'];
$header['title'] = ' گردش حساب '.$owner;
$header['active'] = 'settings';
$header['active_sub'] = 'turnover';
$this->load->view('header' , $header);
$this->load->view('currency/turnover' , $data);
$this->load->view('footer');
        }else{
            $total_rows = $this->base_model->get_count("turnover" , 'ALL');
            $config['base_url'] = base_url('settings/turnover');
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
        $data['turnover'] = $this->base_model->get_data_join('turnover' ,'bank', 'turnover.* , customer.fullname , bank.shaba , bank.name' , 'turnover.bank_id = bank.id' ,'result'  , NULL , $config['per_page'] , $page , array('turnover.id' , 'DESC') , array('customer','turnover.cust_id = customer.id'));
        $data['page'] = $this->pagination->create_links();
        $data['date'] = $date['year']."/".$date['month_num']."/".$date['day']." ".$date['hour'].":".$date['minute'].":".$date['second'];
        $header['title'] = ' گردش حساب ';
        $header['active'] = 'settings';
        $header['active_sub'] = 'turnover';
        $this->load->view('header' , $header);
        $this->load->view('currency/turnover' , $data);
        $this->load->view('footer');
        }
    }
}
?>