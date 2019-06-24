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
            $count = sizeof($_POST['rate']);
            $rate = array();
            $exp = '';
            for($i = 0 ; $i < $count ; $i++){
              $rate[] = array(
                  'unit_id'=>$_POST['unit_id'][$i],
                  'rate'=>$_POST['rate'][$i],
                  'pub'=>1
              );
              $exp .= 'نرخ تبدیل دلار به '.$_POST['name'][$i]. " برابر است با :  ".$_POST['rate'][$i]."</br>";
            }
            $this->base_model->update_data('rate' , array('pub'=> 0) , NULL);
            $this->base_model->insert_batch('rate' , $rate);
            $log['user_id'] = $this->session->userdata('id');
            $log['date_log'] = $date['dd'];
            $log['time_log'] =  $date['t'];
            $log['activity_id'] = 22;
            $log['explain'] = $exp;
            $this->base_model->insert_data('log' , $log);
            $message['msg'][0] = 'اطلاعات با موفقیت ثبت شد';
            $message['msg'][1] = 'success';
            $this->session->set_flashdata($message);
            redirect('settings/set_unit');
        }else{
            $total_rows = $this->base_model->get_count("log" , array('activity_id'=>22));
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
        $data['rate'] = $this->base_model->get_data('log', '*' , 'result'  , array('activity_id'=> 22) , $config['per_page'] , $page , array('id' , 'DESC'));
        $data['page'] = $this->pagination->create_links();
        $data['count'] = $config['total_rows'];
            $data['unit'] = $this->base_model->get_data('unit' , 'id , name' , 'result' , array('id != ' => 5));
            $header['title'] = 'تنظیم ارز ها';
            $header['active'] = 'settings';
            $header['active_sub'] = 'set_unit';
            $this->load->view('header', $header);
            $this->load->view('currency/set_unit' , $data);
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
            $unit = array();
            $exp = '';
            $count = sizeof($_POST['id']);
            for($i = 0 ; $i < $count ; $i++){
                $unit[] = array(
                  'id'=> htmlspecialchars($_POST['id'][$i]),
                  'amount'=>htmlspecialchars($_POST['amount'][$i])
                );
                $exp .= ' موجودی ارز '.$_POST['name'][$i]. ' از مقدار '.number_format($_POST['base'][$i])."  به مقدار ".number_format($_POST['amount'][$i])." تغییر یافت "."</br>";
            }
           $date = $this->convertdate->convert(time());
           $log['user_id'] = $this->session->userdata('id');
           $log['date_log'] = $date['dd'];
           $log['time_log'] = $date['t'];
           $log['activity_id'] = 23;
           $log['explain'] = $exp;
           $this->base_model->update_batch('unit' , $unit , 'id');
           $this->base_model->insert_data('log' , $log);
           $message['msg'][0] = 'اطلاعات با موفقیت ثبت شد';
           $message['msg'][1] = 'success';
           $this->session->set_flashdata($message);
           redirect("settings/primitive_unit");
        }else{
            $total_rows = $this->base_model->get_count("log" , array('activity_id' => 23));
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
        $data['unit'] = $this->base_model->get_data('unit' , '*' , 'result' , array('id != ' => 5) , NULL , NULL ,array('id' , 'ASC'));
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
            $id = $this->base_model->insert_data('customer' , $customer);
        }else{
            $id = $check->id;
        }
        $date_deal = str_replace('/', '-', $_POST['date_deal']); 
       $date = $this->convertdate->convert(time());
       $deal['count_money'] = $this->input->post('count');
       $deal['wage'] = 0;
       $deal['convert'] = 1;
       $deal['volume'] = $this->input->post('count');
       $deal['pay'] = 0;
       $deal['rest'] = $this->input->post('count');
       $deal['explain'] = '';
       $deal['date_deal'] = $date_deal;
       $deal['time_deal'] = $date['t'];
       $deal['type'] = $this->input->post('type');
       $deal['customer_id'] = $id;
       $deal['money_id'] = 5;
       $deal['state'] = 1;
       if($deal['type'] == 1){
           $act = 9;
       }else{
           $act = 10;
       }
       $deal_id = $this->base_model->insert_data('deal' , $deal);
       if($deal_id == FALSE){
        $message['msg'][0] = 'مشکلی در ثبت اطلاعات رخ داده است. لطفا دوباره سعی کنید';
        $message['msg'][1] = 'danger';
        $this->session->set_flashdata($message);
        redirect("settings/rest_unit");
    }
    $log['user_id'] = $this->session->userdata('id');
    $log['date_log'] = $date['dd'];
    $log['time_log'] = $date['t'];
    $log['activity_id'] = $act;
    $log['explain'] = " نام مشتری :  ".$customer['fullname']." | شناسه معامله : ".$deal_id . " | ارز معامله : ریال  </br> تعداد ارز : ".number_format($deal['count_money']) ." | کارمزد : 0 | نرخ تبدیل : 1"."</br> حجم معامله :  ".number_format($deal['volume'])."</br> افزوده شد";
    $log['customer_id'] = $id;
    $this->base_model->insert_data('log' , $log);
    $message['msg'][0] = 'اطلاعات با موفقیت ثبت شد';
    $message['msg'][1] = 'success';
    $this->session->set_flashdata($message);
    redirect("settings/rest_unit");

    }else{
        $total_rows = $this->base_model->get_count("deal" , array('money_id'=> 5 , 'state'=>1));
        $config['base_url'] = base_url('settings/rest_unit');
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
    $data['deal'] = $this->base_model->get_data_join('deal' ,'customer', 'deal.* , customer.fullname , customer.id as cust_id ,unit.name' , 'deal.customer_id = customer.id' ,'result'  , array('deal.money_id'=> 5, 'deal.state' => 1) , $config['per_page'] , $page , array('deal.id' , 'DESC') , array('unit','deal.money_id = unit.id'));
    $data['page'] = $this->pagination->create_links();
    $data['count'] = $config['total_rows'];
    $date = $this->convertdate->convert(time());
    $data['date'] = $date['d'];
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
            $owner = $this->input->get('owner');
            $provider = $this->input->get('provider');
            $start_date = $this->input->get('start_date');
            $end_date = $this->input->get('end_date');
            if($this->input->get('check')){$check = $this->input->get('check');}else{$check = 0;}
            $o_where = NULL; $p_where = NULL; $between = NULL;
            if($owner != ''){
                $owner = trim($owner , ' ');
                $o_where = array('turnover.owner'=>$owner);
            }
            if($provider != ''){
                $provider = trim($provider  , ' ');
                $p_where = array('customer.fullname'=>$provider);
            }
            if($start_date != '' and $end_date != ''){
                $persian_num = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
                $latin_num = range(0, 9);
                $slash = '/';
                $dash = '-';
                $start = str_replace($persian_num, $latin_num, $start_date);
                $start = str_replace($slash, $dash, $start);
                $end = str_replace($persian_num, $latin_num, $end_date);
                $end = str_replace($slash, $dash, $end); 
                $date_start = substr($start , 0 , 10);
                $date_end = substr($end , 0 , 10);
                $between = "turnover.date BETWEEN '$date_start' AND '$date_end'";
            }
            if($this->input->get('per_page')){
              $offset = $this->input->get('per_page');
            }else{
                $offset = 0;
            }

            $total_rows = $this->base_model->total_turnover($o_where , $p_where , $between);
            $config['base_url'] = base_url('settings/turnover?owner='.$owner.'&provider='.$provider.'&start_date='.$start_date.'&end_date='.$end_date."&check=".$check);
            $config['total_rows'] = $total_rows;
            $config['per_page'] = '10';
            $config["uri_segment"] = '3';
            $config['page_query_string'] = TRUE;
            $config['num_links'] = '5';
            $config['next_link'] = '<i class="icon-arrow-left5"></i>';
            $config['last_link'] = 'صفحه آخر';
            $config['prev_link'] = '<i class="icon-arrow-right5"></i>';
            $config['first_link'] = 'صفحه اول';
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
        $data['turnover'] = $this->base_model->get_turnover($offset , $check , $o_where , $p_where , $between);
        if($check == 0){
            $data['page'] = $this->pagination->create_links();
        }
        $header['title'] = ' گردش حساب ';
        $header['active'] = 'settings';
        $header['active_sub'] = 'turnover';
        $date = $this->convertdate->convert(time());
        $data['date'] = $date['year']."/".$date['month_num']."/".$date['day']." ".$date['hour'].":".$date['minute'].":".$date['second'];
        $data['total'] = $config['total_rows'];
        $data['status'] = 0;
        $this->load->view('header' , $header);
        $this->load->view('currency/turnover' , $data);
        $this->load->view('footer');
    }


    public function bank(){
        $id = $this->uri->segment(3);
        if(isset($id) and is_numeric($id)){
            $date = $this->convertdate->convert(time());
            $data['turnover'] = $this->base_model->get_data_join('turnover' ,'bank', 'turnover.* , customer.fullname , bank.shaba , bank.name , bank.explain' , 'turnover.bank_id = bank.id' ,'result'  , array('turnover.bank_id'=>$id) , NULL , NULL , array('turnover.id' , 'DESC') , array('customer','turnover.cust_id = customer.id'));
            $header['active'] = 'settings';
            $header['active_sub'] = 'turnover';
            if(!empty($data['turnover'])){
                $data['owner'] = $data['turnover'][0]->owner;
                $header['title'] = ' گردش حساب '.$data['turnover'][0]->owner;
            }else{
                $data['owner'] = '';
                $header['title'] = 'گردش حساب';
            }
            $data['date'] = $date['year']."/".$date['month_num']."/".$date['day']." ".$date['hour'].":".$date['minute'].":".$date['second'];
            $data['status'] = 1;
            $this->load->view('header' , $header);
            $this->load->view('currency/turnover' , $data);
            $this->load->view('footer');
        }else{
            show_404();
        }
    }
    public function change(){
$persian_num = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
$latin_num = range(0, 9);
$slash = '/';
$dash = '-';
$date = str_replace($persian_num, $latin_num, $_POST['date']);
$date = str_replace($slash, $dash, $date);
$update['date'] = substr($date , 0 , 10);
$update['time'] = substr($date , 10 , 20);
$res = $this->base_model->update_data('turnover' , $update ,array('id'=>$this->input->post('id')));
if($res){
    echo $update['date'].' '.$update['time'];
}else{
    echo 0; 
}

    }

 public function add_unit(){
     if(!$this->session->has_userdata('add_unit') or $this->session->userdata('add_unit') != TRUE){
         show_404();
     }
     if(isset($_POST['sub'])){
         $name = trim( $this->input->post('unit') , ' ');
         $check = $this->base_model->get_data('unit' , 'id' , 'row' , array('name' => $name));
         if(!empty($check)){
             $message['msg'][0] = ' ارز '.$name.' قبلا استفاده شده است .  از نام دیگری استفاده کنید ';
             $message['msg'][1] = 'danger';
             $this->session->set_flashdata($message);
             redirect('settings/add_unit');
         }else{
             $date = $this->convertdate->convert(time());
             $unit['name'] = $name;
             $unit['amount'] = 0;
             $log['user_id'] = $this->session->userdata('id');
             $log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
             $log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
             $log['activity_id'] = 26;
             $log['explain'] = ' ارز '.$name." به لیست ارز ها افزوده شد ";
             $this->base_model->insert_data('unit' , $unit);
             $this->base_model->insert_data('log' , $log);
             $message['msg'][0] = ' ارز جدید با موفقیت ثبت شد';
             $message['msg'][1] = 'success';
             $this->session->set_flashdata($message);
             redirect('settings/add_unit');
         }

     }else{
         $header['title'] = 'افزودن ارز';
         $header['active'] = 'settings';
         $header['active_sub'] = 'add_unit';
         $data['unit'] = $this->base_model->get_data('unit' , 'id , name');
         $this->load->view('header' , $header);
         $this->load->view('currency/add_unit' , $data);
         $this->load->view('footer');
     }
 }
 public function get_unit(){
    $id = $this->input->post('id');
     if(isset($id) and is_numeric($id)){
        $data = $this->base_model->get_data('unit' , 'id , name' , 'row' , array('id'=>$id));
        echo json_encode($data);
     }else{
         show_404();
     }
 }   
 public function edit_unit(){
     $id = $this->uri->segment(3);
     if(isset($id) and is_numeric($id)){
       $name = trim($this->input->post('unit') , ' ');
       $check = $this->base_model->get_data('unit' , 'id' , 'row' , array('name'=> $name));
       if(!empty($check) and $check->id != $id){
           $message['msg'][0] = 'از نام '.$name." قبلا استفاده شده است . لطفا نام دیگری را انتخاب کنید ";
           $message['msg'][1] = 'danger';
           $this->session->set_flashdata($message);
           redirect('settings/add_unit');
       }else{
        $date = $this->convertdate->convert(time());
        $log['user_id'] = $this->session->userdata('id');
        $log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
        $log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
        $log['activity_id'] = 27;
        $log['explain'] = ' ارز '.$name." ویرایش شد";
        $unit['name'] = $name;
        $this->base_model->update_data('unit' , $unit , array('id'=>$id));
        $this->base_model->insert_data('log' , $log);
        $message['msg'][0] = 'ارز مورد نظر با موفقیت ویرایش شد';
        $message['msg'][1] = 'success';
        $this->session->set_flashdata($message);
        redirect('settings/add_unit');
       }
     }else{
         show_404();
     }
 }
 function alert(){
    $header['title'] = 'اعلان مشتری';
    $header['active'] = 'settings';
    $header['active_sub'] = 'alert';
    $this->load->view('header' , $header);
    $this->load->view('currency/alert');
    $this->load->view('footer');
 }
}


?>