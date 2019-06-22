<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Deal extends CI_Controller {

    
    public function __construct(){
        parent::__construct();
        $this->load->library('Convertdate');
        $this->load->library('pagination');
        $this->load->library('form_validation');
    }
//-----    start archive -----//
   public function archive(){
    if(!$this->session->has_userdata('see_deal') or $this->session->userdata('see_deal') != TRUE){
        show_404();
    }
    // filter
    if($this->input->get('per_page')){
        $offset = $this->input->get('per_page');
    }else{
        $offset = 0;
    }
    $between = NULL; $name = NULL; $type_deal = NULL; $money = NULL;
    $fullname = $this->input->get('fullname');
    $type = $this->input->get('type');
    $money_id = $this->input->get('money_id');
    $start_date = $this->input->get('start_date');
    $end_date = $this->input->get('end_date');
    if($fullname != ''){
        $fullname = trim($fullname , ' ');
        $name = array('customer.fullname'=>$fullname);
    }
    if($type != '' and $type != 0 and is_numeric($type)){
        $type_deal = array('deal.type'=>$type);
    }
    if($money_id != '' and $money_id != 0 and is_numeric($money_id)){
        $money = array('deal.money_id'=>$money_id);
    }
    if($start_date != '' and $end_date != ''){
        $start_date = str_replace('/', '-', $start_date);
        $end_date = str_replace('/', '-', $end_date);
        $between = "deal.date_deal BETWEEN '$start_date' AND '$end_date'";
    }
    // filter
    $config['base_url'] = base_url('deal/archive?fullname='.$fullname.'&start_date='.$start_date.'&end_date='.$end_date.'&type='.$type.'&money_id='.$money_id);
    $config['total_rows'] = $this->base_model->total_deal($between , $name , $type_deal , $money);
    $config['per_page'] = '10';
    $config["uri_segment"] = '3';
    $config['page_query_string'] = TRUE;
    $config['num_links'] = '5';
    $config['next_link'] = 'صفحه بعد';
    $config['last_link'] = 'صفحه آخر';
    $config['prev_link'] = 'صفحه قبل';
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
$data['deal'] = $this->base_model->get_deal($offset , $between , $name , $type_deal , $money);
$data['page'] = $this->pagination->create_links();
$data['count'] = $config['total_rows'];
$date = $this->convertdate->convert(time());
$data['date'] = $date['d'];
$data['unit'] = $this->base_model->get_data('unit' , 'id , name');
        $header['title'] = 'آرشیو معاملات';
        $header['active'] = 'deal';
        $header['active_sub'] = 'deal_archive';
        $this->load->view('header' , $header);
        $this->load->view('deal/archive',$data);
        $this->load->view('footer');
    }
    //----- archive -----//

    //----- search customer -----// 
        public function search(){
            if(isset($_POST['text_search'])){
                $title = trim($this->input->post('text_search') , ' ');
                $data = $this->base_model->search_data('deal' , 'customer' , 'deal.* , customer.fullname , unit.name' ,'deal.customer_id = customer.id' , 'left'  , array('customer.fullname'=>$title) , NULL , array('deal.id' , 'DESC') , NULL , array('unit','deal.money_id = unit.id'));
                echo json_encode($data);
            }else{
                show_404();
            }
        }
    //----- search customer -----//

    //pay little
    public function pay_little(){
        if(!$this->session->has_userdata('pay_little') or $this->session->userdata('pay_little') != TRUE){
            show_404();
        }
        $deal_id = $this->uri->segment(3);
        $red = $this->uri->segment(4);
        if(isset($deal_id) and is_numeric($deal_id)){
          $deal = $this->base_model->run_query("SELECT d.id , d.pay , d.volume , d.rest , d.customer_id , c.fullname FROM deal d LEFT JOIN customer c ON d.customer_id = c.id WHERE d.id = $deal_id" , 'row');
          if(empty($deal)){
              show_404();
          }
          if($red == 'group'){
            $red1 = 'deal';  
            $red2 = 'profile';
            $red3 = $deal->customer_id;  
          }else if($red == 'rest'){
              $red1 = 'settings';
              $red2 = 'rest_unit';
              $red3 = '';
          }else{
              $red1 = 'deal';
              $red2 = 'archive';
              $red3 = '';
          }
          $check = abs($deal->volume - $deal->pay);
          if($check <= 50000 && $check != 0 && $deal->rest != 0 && $deal->pay != 0){
                $date = $this->convertdate->convert(time());
                $updeal['rest'] = 0;
                $log['user_id'] = $this->session->userdata('id');
                $log['date_log'] = $date['dd'];
                $log['time_log'] = $date['t'];
                $log['activity_id'] = 28;
                $log['explain'] = " شناسه معامله :  ".$deal->id." | نام مشتری : ".$deal->fullname."</br>  حجم معامله  : ".number_format($deal->volume)." | حجم پرداخت شده ".number_format($deal->pay)." | حجم باقی مانده : ".number_format($deal->rest)."</br> مبلغ باقی مانده به اندازه ".number_format($check)." توسط کاربر صفر گردید ";
                $log['customer_id'] = $deal->customer_id;
                $this->base_model->insert_data('log' , $log);
                $this->base_model->update_data('deal' , $updeal , array('id'=>$deal->id));
                $message['msg'][0] = ' مبلغ '.number_format($check)." به صورت خرد پرداخت شد ";
                $message['msg'][1] = 'success';
                $this->session->set_flashdata($message);
                redirect($red1."/".$red2."/".$red3);
          }else{
              $message['msg'][0] = 'خطا در سیستم';
              $message['msg'][1] = 'danger';
              $this->session->set_flashdata($message);
              redirect($red1."/".$red2."/".$red3);
          }
        }else{
            show_404();
        }
    }


    //pay little
    //-----delete deal--------//
    public function delete_deal(){
       if(!$this->session->has_userdata('delete_deal') or $this->session->userdata('delete_deal') != TRUE){
           show_404();
       } 
        $id = $this->uri->segment(3);
        $red = $this->uri->segment(4);
        if(isset($id) and is_numeric($id)){
            //check
         $deal = $this->base_model->get_data_join('deal' , 'customer' ,'deal.* , customer.fullname , unit.name','deal.customer_id = customer.id', 'row' , array('deal.id' => $id) , NULL , NULL , NULL , array('unit' , 'deal.money_id = unit.id'));
         if(empty($deal)){
             show_404();
         }
         if($red == 'group'){
            $red1 = 'deal';
            $red2 = 'profile';
            $red3 = $deal->customer_id; 
         }else if($red == 'rest'){
            $red1 = 'settings';
            $red2 = 'rest_unit';
            $red3 = '';
         }else{
            $red1 = 'deal';
            $red2 = 'archive';
            $red3 = '';
         }
         if($deal->pay != 0){
             $message['msg'][0] = ' برای این معامله پرداخت صورت گرفته است و نمی توان آن را پاک کرد ';
             $message['msg'][1] = 'info';
             $this->session->set_flashdata($message);
             redirect($red1."/".$red2."/".$red3);
         }
         if($deal->state == 0){
           $message['msg'][0] = 'معامله مازاد نمی تواند حذف شود';
           $message['msg'][1] = 'info';
           $this->session->set_flashdata($message);
           redirect($red1."/".$red2."/".$red3);
         }

$type = 'خرید';
if($deal->type == 2){
    $type = 'فروش';
}         
$exp = ' شناسه معامله : '.$deal->id." | نام مشتری : ".$deal->fullname." | نوع معامله : ".$type." | ارز معامله :".$deal->name."</br> تعداد ارز : ".number_format($deal->count_money)." | کارمزد :  ".number_format($deal->wage)." | نرخ تبدیل :".number_format($deal->convert)." | حجم معامله :".number_format($deal->volume)."</br> حجم پرداخت شده : ".number_format($deal->pay)." | حجم باقی مانده : ".number_format($deal->rest)."</br> تاریخ ثبت : ".$deal->date_deal." ".$deal->time_deal."</br> توضحیات :".$deal->explain." </br> حذف شد ";
$date = $this->convertdate->convert(time());
$log['user_id'] = $this->session->userdata('id');
$log['date_log'] = $date['dd'];
$log['time_log'] = $date['t'];
$log['activity_id'] = 20;
$log['explain'] = $exp;
$log['customer_id'] = $deal->customer_id;
$res = $this->base_model->delete_data('deal' , array('id' => $id));
if($res == FALSE){
    $message['msg'][0] = 'مشکلی در حذف معامله رخ داده است . لطفا دوباره سعی کنید';
    $message['msg'][1] = 'danger';
    $this->session->set_flashdata($message);
    redirect($red1."/".$red2."/".$red3);
}
$this->base_model->insert_data('log' , $log);
    $message['msg'][0] = ' معامله با موفقیت حذف شد  ';
    $message['msg'][1] = 'success';
    $this->session->set_flashdata($message);
    redirect($red1."/".$red2."/".$red3);
        }else{
            show_404();
        }
    }
    //-----delete deal--------//
    //----- buy and sell -----//
    public function buy(){
        if(isset($_POST['sub'])){
            if($this->input->post('type') == 1){
                $page = 'buy';
                $act = 9;
                if(!$this->session->has_userdata('add_buy') or $this->session->userdata('add_buy') != TRUE){
                    show_404();
                }
            }else{
                $page = 'sell';
                $act = 10;
                if(!$this->session->has_userdata('add_sell') or $this->session->userdata('add_sell') != TRUE){
                    show_404();
                }
            }
            //validation
            $this->form_validation->set_rules('customer' , 'customer' , 'required');
            $this->form_validation->set_rules('count_money','count_money' , 'required|numeric');
            $this->form_validation->set_rules('wage','wage' , 'required|numeric');
            $this->form_validation->set_rules('convert','convert' , 'required|numeric');
			if($this->form_validation->run() == FALSE){
				$message['msg'][0] = "  لطفا اطلاعات مربوط به نام مشتری ، تعداد ارز ، کارمزد و نرخ تبدیل را وارد کنید  ";
				$message['msg'][1] = "danger";
				$this->session->set_flashdata($message);
				redirect("deal/$page");
            }
           //validation
           //deal
            $customer['fullname'] = trim($this->input->post('customer') , ' ');
            $check = $this->base_model->get_data('customer' , 'id' , 'row' , array('fullname'=>$customer['fullname']));
            if(empty($check)){
                $id = $this->base_model->insert_data('customer' , $customer);
            }else{
                $id = $check->id;
            }
            $string = str_replace('/', '-', $this->input->post('date_deal')); 
            $date_deal = substr($string , 0 , 10);
            $time_deal = substr($string , 10 , 20);
           $date = $this->convertdate->convert(time());
           $deal['count_money'] = $this->input->post('count_money');
           $deal['wage'] = $this->input->post('wage');
           $deal['convert'] = $this->input->post('convert');
           $deal['volume'] = ($deal['count_money'] + $deal['wage']) * $deal['convert'];
           $deal['pay'] = 0;
           $deal['rest'] = $deal['volume'];
           $deal['explain'] = $this->input->post('explain');
           $deal['date_deal'] = $date_deal;
           $deal['time_deal'] = $time_deal;
           $deal['type'] = $this->input->post('type');
           $deal['customer_id'] = $id;
           $deal['money_id'] = $this->input->post('money_id');
           $deal['state'] = 1;
           $deal_id = $this->base_model->insert_data('deal' , $deal);

           if($deal_id == FALSE){
            $message['msg'][0] = 'مشکلی در ثبت اطلاعات رخ داده است. لطفا دوباره سعی کنید';
            $message['msg'][1] = 'danger';
            $this->session->set_flashdata($message);
            redirect("deal/$page");
            }
        //deal
        //log
        $get = $this->base_model->get_data('unit' , 'name' , 'row' , array('id'=>$deal['money_id']));
        $money = $get->name;
        $log['user_id'] = $this->session->userdata('id');
        $log['date_log'] = $date['dd'];
        $log['time_log'] = $date['t'];
        $log['activity_id'] = $act;
        $log['explain'] = " نام مشتری :  ".$customer['fullname']." | شناسه معامله : ".$deal_id . " | ارز معامله : ". $money . " </br> تعداد ارز : ".number_format($deal['count_money']) ." | کارمزد : ".number_format($deal['wage']) . " | نرخ تبدیل : ".number_format($deal['convert'])." </br> حجم معامله  :  ".number_format($deal['volume'])."</br>  ثبت شد .";
        $log['customer_id'] = $id;
        $this->base_model->insert_data('log' , $log);
        //log
         $message['msg'][0] = ' اطلاعات معامله با موفقیت ثبت شد. برای وارد شدن به پروفایل  '.$customer['fullname'].' <a style="text-decoration: underline;" href="'. base_url('deal/profile/').$id.'"> اینجا </a> کلیک کنید .';
         $message['msg'][1] = 'success';
         $this->session->set_flashdata($message);
         redirect("deal/$page");
}else{
    if(!$this->session->has_userdata('add_buy') or $this->session->userdata('add_buy') != TRUE){
         show_404();
     } 
            $date = $this->convertdate->convert(time());
            $data['date'] = $date['d']." ".$date['t'];
            $header['title'] = 'افزودن خرید';
            $header['active'] = 'deal';
            $header['active_sub'] = 'deal_buy';
            $data['customer'] = $this->base_model->get_data('customer' ,'fullname');
            $data['unit'] = $this->base_model->get_data('unit' , 'id , name ', 'result' , array('id != ' => 5));
            $this->load->view('header' , $header);
            $this->load->view('deal/buy' , $data);
            $this->load->view('footer');
        }  
    }


    public function sell(){
    if(!$this->session->has_userdata('add_sell') or $this->session->userdata('add_sell') != TRUE){
        show_404();
    } 
            $date = $this->convertdate->convert(time());
            $data['date'] = $date['d']." ".$date['t'];
            $header['title'] = 'افزودن فروش';
            $header['active'] = 'deal';
            $header['active_sub'] = 'deal_sell';
            $data['customer'] = $this->base_model->get_data('customer' ,'fullname');
            $data['unit'] = $this->base_model->get_data('unit' , 'id , name ', 'result' , array('id !=' => 5));
            $this->load->view('header' , $header);
            $this->load->view('deal/sell', $data);
            $this->load->view('footer');
    }


    public function customer_history(){
        $fullname = trim($this->input->post('text_search') , ' ');  
        $data['other'] = $this->base_model->run_query("SELECT u.id , d.convert , d.rest , d.type , d.customer_id FROM unit u LEFT JOIN deal d ON d.money_id = u.id LEFT JOIN customer c ON d.customer_id = c.id where c.fullname = '$fullname' AND u.id <> 5 order by u.id");
        if(empty($data['other'])){
            echo false;
        }else{
            $cust_id = $data['other'][0]->customer_id;
            $data['rial'] = $this->base_model->run_query("SELECT rest , type FROM deal WHERE customer_id = $cust_id ");
            echo json_encode($data);
        }
    }
      //----- buy and sell -----//

     //----- edit -----//
    public function edit(){
        if(!$this->session->has_userdata('edit_deal') or $this->session->userdata('edit_deal') != TRUE){
            show_404();
        }
        $id = $this->uri->segment(3);
        if(isset($id) and is_numeric($id)){
            if(isset($_POST) and !empty($_POST)){ 
                //validation
                $this->form_validation->set_rules('customer' , 'customer' , 'required');
                $this->form_validation->set_rules('count_money','count_money' , 'required|numeric');
                $this->form_validation->set_rules('wage','wage' , 'required|numeric');
                $this->form_validation->set_rules('convert','convert' , 'required|numeric');
                if($this->form_validation->run() == FALSE){
                    $message['msg'][0] = "  لطفا اطلاعات مربوط به نام مشتری ، تعداد ارز ، کارمزد و نرخ تبدیل را وارد کنید  ";
                    $message['msg'][1] = "danger";
                    $this->session->set_flashdata($message);
                    redirect("deal/edit/$id");
                }
                //validation
                
                //check customer
              $customer['fullname'] = trim($this->input->post('customer') , ' ');
              $cust_id = $this->input->post('cust_id');
              $check = $this->base_model->get_data('customer' , 'id' , 'row' , array('fullname' =>  $customer['fullname']));
              if(!empty($check) and $check->id != $cust_id){
                $message['msg'][0] = 'این نام '.$customer['fullname'] . " قبلا استفاده شده است . لطفا جهت تمایز در نام مشتری ها از نام دیگری استفاده کنید ";
                $message['msg'][1] = 'danger';
                $this->session->set_flashdata($message);
                redirect("deal/edit/$id");
              }
              $this->base_model->update_data('customer' , $customer , array('id'=> $cust_id));
               //check cutomer

               // deal
             $base = $this->base_model->get_data('deal' , 'count_money , wage , convert , volume , pay , money_id ,type ,date_deal' , 'row' , array('id' => $id));
             $type = 'خرید';
             if($base->type == 2){
                 $type = 'فروش';
             }
             $exp = ' نام مشتری : '.$customer['fullname']." | شناسه معامله :  ".$id. " | نوع معامله :  ".$type ."</br>";
              $string = str_replace('/', '-', $this->input->post('date_deal')); 
              $date_deal = substr($string , 0 , 10);
              $time_deal = substr($string , 10 , 20);
             $date = $this->convertdate->convert(time());
             $deal['count_money'] = $this->input->post('count_money');
             $deal['wage'] = $this->input->post('wage');
             $deal['convert'] = $this->input->post('convert');
             $deal['volume'] = ($deal['count_money'] + $deal['wage']) * $deal['convert'];
             $deal['rest'] = $deal['volume'] - $base->pay;
             $check_rest = abs($deal['rest']);
             if($_POST['direct'] == 1 && $check_rest <= 50000 && $check_rest != 0 && $base->money_id == 5 && $base->money_id != $deal['money_id'] && $base->pay != 0){
                 $exp .= " مبلغ باقی مانده به اندازه  ".number_format($deal['rest'])." توسط کاربر صفر گردید </br>";
                 $deal['rest'] = 0;
             }
             $deal['explain'] = $this->input->post('explain');
             $deal['date_deal'] = $date_deal;
             $deal['time_deal'] = $time_deal;
             $deal['date_modified'] = $date['dd']."</br>".$date['t'];
             $deal['money_id'] = $this->input->post('money_id');
            //deal
            //currency 
             $status = $this->base_model->update_data('deal' , $deal , array('id'=> $id));
             if($status == FALSE){
                 $message['msg'][0] = 'مشکلی در ثبت اطلاعات رخ داده است. لطفا دوباره سعی کنید';
                 $message['msg'][1] = 'danger';
                 $this->session->set_flashdata($message);
                 redirect("deal/edit/$id");
             }
             if($base->money_id != $deal['money_id']){
                 $in1 = $base->money_id;
                 $in2 = $deal['money_id'];
                 $name = $this->base_model->run_query("SELECT name FROM unit WHERE id IN ($in1 , $in2 )");
                 $exp .= ' ارز معامله از  '.$name[0]->name . " به  ".$name[1]->name . " تغییر یافت </br> ";
             }
             if($base->date_deal != $deal['date_deal']){
                 $exp .=  ' تاریخ ثبت معامله از  '.$base->date_deal ." به ".$deal['date_deal']." تغییر یافت </br>" ;
             }
             $exp .= " تعداد ارز از ".number_format($base->count_money)." به  ".number_format($deal['count_money'])." تغییر یافت . </br> کارمزد از ".number_format($base->wage)." به ".number_format($deal['wage'])." تغییر یافت . </br> نرخ تبدیل از ".number_format($base->convert)." به ".number_format($deal['convert'])." تغییر یافت . </br> حجم معامله از  ".number_format($base->volume)." به ".number_format($deal['volume'])." تغییر یافت ";   
            $log['user_id'] = $this->session->userdata('id');
            $log['date_log'] = $date['dd'];
            $log['time_log'] = $date['t'];
            $log['activity_id'] = 11;
            $log['customer_id'] = $cust_id;
            $log['explain'] = $exp;
            $this->base_model->insert_data('log' , $log);
           $message['msg'][0] = 'اطلاعات معامله با موفقیت ویرایش شد';
           $message['msg'][1] = 'success';
           $this->session->set_flashdata($message);
           redirect("deal/edit/$id");
            }else{
                $data['deal'] = $this->base_model->get_data_join('deal' , 'customer' , 'deal.* , customer.fullname' , 'deal.customer_id = customer.id'  ,'row' , array('deal.id' => $id));
                if(empty($data['deal'])){
                    show_404();
                }else{
                    if($data['deal']->state == 0){
                        $message['msg'][0] = 'معامله مازاد نمی تواند ویرایش شود';
                        $message['msg'][1] = 'info';
                        $this->session->set_flashdata($message);
                        redirect('deal/archive');
                    }
                    $str = $data['deal']->date_deal;
                    $data['date_deal'] = str_replace('-', '/' , $str);
                    $data['unit'] = $this->base_model->get_data('unit' , 'id , name');
                    $header['title'] = ' ویرایش معامله ';
                    $header['active'] = 'deal';
                    $header['active_sub'] = 'deal_archive';
                    $this->load->view('header' , $header);
                    $this->load->view('deal/edit' , $data);
                    $this->load->view('footer');
                }
            }
        }else{
            show_404();
        }
    }
      //----- edit -----//

      //----- send photo -----//
      public function photo(){
          if(!$this->session->has_userdata('see_photo') or $this->session->userdata('see_photo') != TRUE){
              show_404();
          }
          $date = $this->convertdate->convert(time());
          $id = $this->uri->segment(3);
    if(isset($id) and is_numeric($id)){
        if(isset($_POST['sub'])){
    //photo
        $img = array();
        $exp = '';
        if($_FILES['deal_pic']['name'][0] != ''){
         $count = count($_FILES['deal_pic']['name']);
         $files['name'] = $_FILES['deal_pic']['name'];
         $files['type'] = $_FILES['deal_pic']['type'];
         $files['tmp_name'] = $_FILES['deal_pic']['tmp_name'];
         $files['error'] = $_FILES['deal_pic']['error'];
         $files['size'] = $_FILES['deal_pic']['size'];

         for($j = 0 ; $j < $count ; $j++){
             $counter = $j + 1;
             $exp .= " نام عکس شماره  ".$counter." : ".$files['name'][$j]."</br>";
             $_FILES['deal_pic']['name'] = $files['name'][$j];
             $_FILES['deal_pic']['type'] = $files['type'][$j];
             $_FILES['deal_pic']['tmp_name'] = $files['tmp_name'][$j];
             $_FILES['deal_pic']['error'] = $files['error'][$j];
             $_FILES['deal_pic']['size'] = $files['size'][$j];
 
             $config['upload_path']   = './uploads/deal';
             $config['allowed_types'] = 'gif|jpg|png|jpeg';
             $config['max_size']      = 1000000000;
 
             $this->load->library('upload', $config);  
             $this->upload->initialize($config);
 
             if($this->upload->do_upload('deal_pic')){
                 $img[] = array(
                     'deal_id'=> $id,
                     'pic_name' => $files['name'][$j],
                     'date_upload'=> $_POST['date'],
                     'explain'=>$_POST['explain']
                 );
             }else{
                 $message['msg'][0] = 'در ارسال عکس توجه داشته باشید که باید یکی از فرمت های PNG|JPEG|GIF باشد';
                 $message['msg'][1] = 'danger';
                 $this->session->set_flashdata($message);
                 redirect("deal/photo/".$id);
             }
            }
}
if(!empty($img)){
    $this->base_model->insert_batch('deal_pic' , $img);
    $cust = $this->base_model->get_data('deal' , 'customer_id' , 'row' , array('id'=>$id));
    $log['user_id'] = $this->session->userdata('id');
    $log['date_log'] = $date['dd'];
    $log['time_log'] = $date['t'];
    $log['activity_id'] = 24;
    $log['explain'] = ' ارسال قبض</br>'.$exp;
    $log['customer_id'] = $cust->customer_id;
    $this->base_model->insert_data('log' , $log);
    $message['msg'][0] = 'اطلاعات با موفقیت ثبت شد';
    $message['msg'][1] = 'success';
    $this->session->set_flashdata($message);
    redirect('deal/photo/'.$id);
}else{
    $message['msg'][0] = 'عکسی انتخاب نشده است';
    $message['msg'][1] = 'danger';
    $this->session->set_flashdata($message);
    redirect('deal/photo/'.$id);
}
    //photo
              }else{
                $total_rows = $this->base_model->get_count("deal_pic" , array('deal_id' => $id));
                $config['base_url'] = base_url("deal/photo/$id");
                $config['total_rows'] = $total_rows;
                $config['per_page'] = '8';
                $config["uri_segment"] = '4';
                $config['num_links'] = '5';
                $config['next_link'] = 'صفحه بعد';
                $config['last_link'] = 'صفحه آخر';
                $config['prev_link'] = 'صفحه قبل';
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
            $offset = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;      
            $data['photo'] = $this->base_model->run_query("SELECT * FROM deal_pic WHERE deal_id = $id ORDER BY id DESC LIMIT $offset , 8");
            $data['page'] = $this->pagination->create_links();
                $header['title'] = 'عکس ها';
                $header['active'] = 'deal';
                $header['active_sub'] = 'deal_archive';
                $data['date'] = $date['d']. " ".$date['t'];
                $this->load->view('header' , $header);
                $this->load->view('deal/photo' , $data);
                $this->load->view('footer');
              }
          }else{
              show_404();
          }
     }
     //----- send photo -----//

     //-----delete photo -----//
     public function delete_photo(){
         $red_id = $this->uri->segment(3);
         $id = $this->uri->segment(4);
         $name = $this->uri->segment(5);
         if(is_numeric($red_id) and is_numeric($id) and isset($red_id) and isset($id)){
         $cust = $this->base_model->get_data('deal' , 'customer_id' , 'row' , array('id'=> $red_id));    
        $date = $this->convertdate->convert(time());
        $this->base_model->delete_data('deal_pic' , array('id'=>$id));
        $log['user_id'] = $this->session->userdata('id');
        $log['date_log'] = $date['dd'];
        $log['time_log'] = $date['t'];
        $log['activity_id'] = 25;
        $log['explain'] = ' حذف قبض | نام قبض : '.$name;
        $log['customer_id'] = $cust->customer_id;
        $this->base_model->insert_data('log' , $log);
        $message['msg'][0] = 'قبض با موفقیت حذف شد';
        $message['msg'][1] = 'success';
        $this->session->set_flashdata($message);
        redirect('deal/photo/'.$red_id);
         }else{
             show_404();
         }
     }
     //-----delete photo -----//
     
     //-----edit photo -----//
     public function get_photo(){
         $id = $this->input->post('id');
         if(is_numeric($id)){
         $data = $this->base_model->get_data('deal_pic' , '*' , 'row' , array('id'=>$id));
         echo json_encode($data);
         }else{
             show_404();
         }
     }
     public function edit_photo(){
         if(isset($_POST['sub'])){
          $red_id = $this->uri->segment(3);
          $id = $this->uri->segment(4);
          $data['date_upload'] = $this->input->post('date');
          $data['explain'] = $this->input->post('explain');
          $this->base_model->update_data('deal_pic' , $data , array('id'=>$id));
          $message['msg'][0] = 'قبض با موفقیت ویرایش شد';
          $message['msg'][1] = 'success';
          $this->session->set_flashdata($message);
          redirect("deal/photo/".$red_id);
         }else{
             show_404();
         }
     }
     //-----edit photo -----//

    // ----profile--------//
   public function profile(){
    $id = $this->uri->segment(3);
        if(isset($_POST['sub'])){
            if(!$this->session->has_userdata('add_handle') or $this->session->userdata('add_handle') != TRUE){
                show_404();
            }
            $red = $this->uri->segment(4);
            if($red == 'profile'){
                $red2 = 'profile';
                $red3 = $id;
            }else{
                $red2 = 'worksheet';
                $red3 = '';
            }
            if($_POST['bank_id'] == 0){
                $message['msg'][0] = 'لطفا شماره حساب را از لیست مربوطه انتخاب کنید . در صورت موجود نبودن شماره حساب لطفا اقدام به افزودن شماره حساب کنید' ;
                $message['msg'][1] = 'danger';
                $this->session->set_flashdata($message);
                redirect("deal/$red2/$red3");
             }
$buy_name = trim($this->input->post('customer_buy') , ' ');
$sell_name = trim($this->input->post('customer_sell') , ' ');                 
$buy_info = $this->base_model->get_data_join('deal' , 'customer' , 'customer.id' , 'customer.id = deal.customer_id' , 'row' , array('customer.fullname'=>$buy_name , 'deal.type' => 1));
$sell_info = $this->base_model->get_data_join('deal' , 'customer' , 'customer.id' , 'customer.id = deal.customer_id' , 'row' , array('customer.fullname'=>$sell_name , 'deal.type'=> 2));
if(empty($buy_info)){
$message['msg'][0] = 'توجه داشته باشید که مشتری خرید باید در سامانه ثبت شده باشد و تعداد خرید های انجام شده با شخص حداقل یک باشد' ;
$message['msg'][1] = 'danger';
$this->session->set_flashdata($message);
redirect("deal/$red2/$red3");
}else if(empty($sell_info)){
$message['msg'][0] = 'توجه داشته باشید که مشتری فروش باید در سامانه ثبت شده باشد و تعداد فروش های انجام شده با شخص حداقل یک باشد' ;
$message['msg'][1] = 'danger';
$this->session->set_flashdata($message);
redirect("deal/$red2/$red3");
}
if($red == 'profile' and $id != $buy_info->id){
$message['msg'][0] = ' لطفا هماهنگی هر شخص را در پروفایل خود شخص وارد کنید ';
$message['msg'][1] = 'info';
$this->session->set_flashdata($message);
redirect("deal/$red2/$red3");
}
$date_handle = str_replace('/', '-' , $_POST['date_handle']);
$date = $this->convertdate->convert(time());
$data['volume_handle'] = $this->input->post('volume_handle');
$data['handle_pay'] = 0;
$data['handle_rest'] = $this->input->post('volume_handle');
$data['date_handle'] = $date_handle;
$data['time_handle'] = $date['t'];
$data['date_modified'] = 'ثبت نشده است';
$data['buy_id'] = $buy_info->id;
$data['sell_id'] = $sell_info->id;
$data['bank_id'] = $this->input->post('bank_id');
$this->base_model->set('rest_handle' , 'rest_handle-'.$data['volume_handle'] , array('id'=>$data['bank_id']) , 'bank');
$str = ' هماهنگی با مشخصات : </br> نام مشتری خرید :'.$buy_name."</br> نام مشتری فروش : ".$sell_name."</br> مبلغ هماهنگی : ".number_format($data['volume_handle'])."</br> تاریخ ثبت : ".$date_handle." </br> شناسه بانک : ".$data['bank_id']."</br> ثبت شد";
            $log['user_id'] = $this->session->userdata('id');
            $log['date_log'] = $date['dd'];
            $log['time_log'] = $date['t'];
            $log['activity_id'] = 12;
            $log['explain'] = $str;
            $log['customer_id'] = $buy_info->id;
$this->base_model->insert_data('log' , $log);
$this->base_model->insert_data('handle' , $data);
                $message['msg'][0] = 'اطلاعات هماهنگی با موفقیت ثبت شد';
                $message['msg'][1] = 'success';
                $message['status'] = 1;
                $this->session->set_flashdata($message);
                redirect("deal/$red2/$red3");
        }else{
            if(!$this->session->has_userdata('see_handle') or $this->session->userdata('see_handle') != TRUE or !isset($id) or !is_numeric($id)){
                show_404();
            }
            $header['active'] = 'deal';
            $header['active_sub'] = 'deal_archive';
            $data['dealCount'] = $this->base_model->get_count('deal' , array('customer_id'=> $id));
            $data['handleCount'] = $this->base_model->get_count('handle' , array('buy_id'=>$id));
            $data['handleCount2'] = $this->base_model->get_count('handle' , array('sell_id'=>$id));
            $data['deal'] = $this->base_model->run_query("SELECT d.* , c.fullname , c.plus ,  u.name FROM deal d LEFT JOIN customer c ON d.customer_id = c.id LEFT JOIN unit u ON d.money_id = u.id WHERE d.customer_id = $id ORDER BY d.id DESC LIMIT 0 , 7");
            $header['title'] = 'هماهنگی';
            if(!empty($data['deal'])){
                $header['title'] = $data['deal'][0]->fullname;
            }
            $data['sumDeal'] = $this->base_model->run_query("SELECT t.type_name , MAX(d.volume) as volume , MAX(d.pay) as pay , MAX(d.rest) as rest , MAX(dd.volume) as forbank FROM type_deal t LEFT JOIN (SELECT SUM(volume) as volume , SUM(pay) as pay , SUM(rest) as rest , type FROM deal WHERE customer_id = $id GROUP BY type) d ON d.type = t.id left join(SELECT SUM(volume) as volume , type FROM deal WHERE customer_id = $id AND rest > 0 group by type) dd ON dd.type = t.id GROUP BY t.id");
            $data['bank'] = $this->base_model->run_query("SELECT * FROM bank WHERE customer_id = $id AND active = 1 ORDER BY id DESC");
            $notBank = $this->base_model->run_query("SELECT SUM(amount) as amount FROM bank WHERE customer_id = $id AND rest > 0 GROUP BY customer_id" , 'row');
            if(empty($notBank)){
                $data['notBank'] = 0;
            }else{
                $data['notBank'] = $notBank->amount;
            }
            $data['handle'] = $this->base_model->get_data_join('handle' , 'bank' , 'handle.* , customer.fullname ,customer.id as cust_id, bank.explain' , 'handle.bank_id = bank.id' , 'result' , array('handle.buy_id'=>$id) , 7 , 0 , array('handle.id' , 'DESC'),array('customer' , 'handle.sell_id = customer.id'));
            $data['handle2'] = $this->base_model->get_data_join('handle' , 'bank' , 'handle.* , customer.fullname ,customer.id as cust_id, bank.explain' , 'handle.bank_id = bank.id' , 'result' , array('handle.sell_id'=>$id) , 7 , 0 , array('handle.id' , 'DESC'),array('customer' , 'handle.buy_id = customer.id'));
            
            $customer = $this->base_model->run_query("SELECT id , fullname FROM customer order by id ASC");
            $buy = $this->base_model->run_query("SELECT d.customer_id, SUM(d.volume) AS volume, max(h.volume_handle) AS handle FROM  deal d LEFT JOIN (SELECT buy_id, SUM(volume_handle) AS volume_handle FROM handle GROUP BY buy_id) h ON h.buy_id = d.customer_id  where d.type = 1 GROUP BY d.customer_id ORDER BY d.customer_id ASC");
            $sell = $this->base_model->run_query("SELECT d.customer_id, SUM(d.volume) AS volume, max(h.volume_handle) AS handle FROM  deal d LEFT JOIN (SELECT sell_id, SUM(volume_handle) AS volume_handle FROM handle GROUP BY sell_id) h ON h.sell_id = d.customer_id  where d.type = 2 GROUP BY d.customer_id ORDER BY d.customer_id ASC");
            $date = $this->convertdate->convert(time());
            $data['date'] = $date['d'];
            $search = array();
            foreach($customer as $rows){
                $search[$rows->id]['fullname'] = $rows->fullname;
                $search[$rows->id]['buy'] = 0;
                foreach($buy as $row){
                  if($rows->id == $row->customer_id){
                          $search[$rows->id]['buy'] = $row->volume - $row->handle;
                          break; 
                  }
                }
            }
            foreach($customer as $rows){
                $search[$rows->id]['sell'] = 0;
                foreach($sell as $row){
                  if($rows->id == $row->customer_id){
                          $search[$rows->id]['sell'] = $row->volume - $row->handle;
                          break; 
                  }
                }
            }
        $data['search'] = $search;
        $this->load->view('header' , $header);
        $this->load->view('deal/handle_profile' , $data);
        $this->load->view('footer');
    }
}
    //--------profile----------//  

    //-------pagindeal--------//
public function paginDeal(){
if(!$this->session->has_userdata('see_handle') or $this->session->userdata('see_handle') != TRUE){
    show_404();
}
$offset = $this->input->post('offset');
$id = $this->input->post('id');
if(isset($offset) and is_numeric($offset) and isset($id) and is_numeric($id)){
    $deal = $this->base_model->run_query("SELECT d.* , c.fullname , u.name FROM deal d LEFT JOIN customer c ON d.customer_id = c.id LEFT JOIN unit u ON d.money_id = u.id WHERE d.customer_id = $id ORDER BY d.id DESC LIMIT $offset , 7");
    echo json_encode($deal);
}else{
    show_404();
}
}
    //-------pagindeal--------//

    //------paginHandle-------//

public function paginHandle(){
if(!$this->session->has_userdata('see_handle') or $this->session->userdata('see_handle') != TRUE){
    show_404();
}
$offset = $this->input->post('offset');
$which = $this->input->post('which');
$id = $this->input->post('id');
if(is_numeric($id) and is_numeric($offset) and is_numeric($which)){
    if($which == 1){
        $data = $this->base_model->get_data_join('handle' , 'bank' , 'handle.* , customer.fullname ,customer.id as cust_id, bank.explain' , 'handle.bank_id = bank.id' , 'result' , array('handle.buy_id'=>$id) , 7 , $offset , array('handle.id' , 'DESC'),array('customer' , 'handle.sell_id = customer.id'));
    }else{
        $data = $this->base_model->get_data_join('handle' , 'bank' , 'handle.* , customer.fullname ,customer.id as cust_id, bank.explain' , 'handle.bank_id = bank.id' , 'result' , array('handle.sell_id'=>$id) , 7 , $offset , array('handle.id' , 'DESC'),array('customer' , 'handle.buy_id = customer.id'));
    }
 echo json_encode($data);
}else{
    show_404();
}
}

    //------paginHandle----//


    //----- add_bank -----//
    public function add_bank(){
        if(!$this->session->has_userdata('add_bank') or $this->session->userdata('add_bank') != TRUE){
            show_404();
        }
        $id = $this->uri->segment(3);
        if(isset($id) and is_numeric($id)){
            if(isset($_POST['sub'])){
                $data['explain'] = $this->input->post('bank_explain');
                $data['name'] = $this->input->post('name');
                $data['shaba'] = $this->input->post('shaba');
                $data['amount'] = $this->input->post('amount');
                $data['pay'] = 0;
                $data['rest'] = $this->input->post('amount');
                $data['rest_handle'] = $this->input->post('amount');
                $data['customer_id'] = $id;
                $data['active'] = 1;
                $res = $this->base_model->insert_data('bank' , $data);
                $date = $this->convertdate->convert(time());
                $customer = $this->base_model->get_data('customer' , 'fullname' , 'row' , array('id'=>$id));
                $log['user_id'] = $this->session->userdata('id');
                $log['date_log'] = $date['dd'];
                $log['time_log'] = $date['t'];
                $log['activity_id'] = 17;
                $log['explain'] = " حساب جدید با مشخصات :  </br> نام مشتری : ".$customer->fullname."</br>  شماره شبا : ".$data['shaba']." </br> نام بانک : ".$data['name']." </br> مقدار تعیین شده :  ".number_format($data['amount'])." </br> توضیحات :".$data['explain']."</br> افزوده شد ";
                $log['customer_id'] = $id;
                if($res == FALSE){
                    $message['msg'][0] = 'مشکلی در ثبت اطلاعات رخ داده است . لطفا دوباره سعی کنید';
                    $message['msg'][1] = 'danger';
                    $this->session->set_flashdata($message);
                    redirect("deal/profile/$id");
                }
                $this->base_model->insert_data('log' , $log);
                  $message['msg'][0] = 'اطلاعات حساب بانکی با موفقیت ثبت شد';
                  $message['msg'][1] = 'success';
                  $message['status'] = 2;
                  $this->session->set_flashdata($message);
                  redirect("deal/profile/$id");
            }else{
                show_404();
            }
        }else{
            show_404();
        }
      }
//----- add_bank -----//

//----- edit_bank -----//
public function show_bank(){
    if(!$this->session->has_userdata('edit_bank') or $this->session->userdata('edit_bank') != TRUE){
        show_404();
    }
    $id = $this->input->post('bank_id');
 if(isset($id) and is_numeric($id)){ 
$bank = $this->base_model->get_data('bank' , '*' , 'row' , array('id'=> $id));
echo json_encode($bank);
}else{
     show_404();
    }   
}
public function edit_bank(){
    if(!$this->session->has_userdata('edit_bank') or $this->session->userdata('edit_bank') != TRUE){
        show_404();
    }
    $id = $this->uri->segment(3);
    if(isset($id) and is_numeric($id)){
        if(isset($_POST['sub'])){
      $bank = $this->base_model->run_query("SELECT bank.* , customer.fullname FROM bank LEFT JOIN customer ON bank.customer_id = customer.id WHERE bank.id = $id" , 'row');
      $data['explain'] = $this->input->post('bank_explain');
      $data['name'] = $this->input->post('name');          
      $data['shaba'] = $this->input->post('shaba');
      $data['amount'] = $this->input->post('amount');
      $data['rest'] = $data['amount'] - $bank->pay;
      $data['rest_handle'] = $bank->rest_handle + ($this->input->post('amount') - $bank->amount);
      $exp = ' شناسه بانک : '.$bank->id." | نام مشتری : ".$bank->fullname."</br>";
      if($bank->shaba != $data['shaba']){
          $exp .= ' شماره شبا از '.$bank->shaba . " به ".$data['shaba']." تغییر یافت </br> ";
      }
      if($bank->name != $data['name']){
          $exp .= ' نام بانک از '.$bank->name." به ".$data['name']." تغییر یافت </br>";
      }
      if($bank->amount != $data['amount']){
          $exp .= ' مقدار تعیین شده از '.number_format($bank->amount)." به  ".number_format($data['amount'])." تغییر یافت </br>";
          $exp .= ' مقدار باقی مانده به اندازه '.number_format($data['rest'] - $bank->rest)." تغییر یافت ";
      }
      if($bank->explain != $data['explain']){
          $exp .= ' توضحیات بانک از  '.$bank->explain." به ".$data['explain']." تغییر یافت ";
      }

      $res = $this->base_model->update_data('bank' , $data , array('id'=>$id));
      $date = $this->convertdate->convert(time());
      $log['user_id'] = $this->session->userdata('id');
      $log['date_log'] = $date['dd'];
      $log['time_log'] = $date['t'];
      $log['activity_id'] = 18;
      $log['explain'] = $exp;
      $log['customer_id'] = $bank->customer_id;
      $this->base_model->insert_data('log' , $log);
      if($this->uri->segment(4) == 'disable'){
          $red2 = 'disable_bank';
      }else{
          $red2 = 'profile';
      }
      $message['msg'][0] = 'اطلاعات حساب بانکی با موفقیت ویرایش شد';
      $message['msg'][1] = 'success';
      $message['status'] = 2;
      $this->session->set_flashdata($message);
      redirect("deal/$red2/$bank->customer_id");
        }else{
            show_404();
        }

    }else{
        show_404();
    }
}
//----- edit_bank -----//

//----- active ------//
public function active(){
    if(!$this->session->has_userdata('active_bank') or $this->session->userdata('active_bank') != TRUE){
        show_404();
    }
    $red_id = $this->uri->segment(3);
    $id = $this->uri->segment(4);
    if(isset($red_id) and isset($id) and is_numeric($red_id) and is_numeric($id)){
        $data['active'] = $this->uri->segment(5);
        $this->base_model->update_data('bank' , $data , array('id' => $id));
        $customer = $this->base_model->get_data('customer' , 'fullname' , 'row' , array('id'=>$red_id));
        if($data['active'] == 1){$txt = " را فعال کرد ";}else{$txt = ' را غیر فعال کرد ';}
        $date = $this->convertdate->convert(time());
        $log['user_id'] = $this->session->userdata('id');
        $log['date_log'] = $date['dd'];
        $log['time_log'] = $date['t'];
        $log['activity_id'] = 19;
        $log['explain'] = " حساب بانکی با شناسه ".$id ." مربوط به مشتری  ".$customer->fullname.$txt;
        $log['customer_id'] = $red_id;
        $this->base_model->insert_data('log' , $log);
        if($this->uri->segment(6) == 'disable'){
            $red2 = 'disable_bank';
        }else{
            $red2 = 'profile';
        }
        $message['msg'][0] = 'وضعیت اطلاعات حساب تغییر کرد';
        $message['msg'][1] = 'success';
        $message['status'] = 2;
        $this->session->set_flashdata($message);
        redirect("deal/$red2/$red_id");
    }else{
        show_404();
    }
}
//-----active ------//

// disable archive ----
public function disable_bank(){
    if(!$this->session->has_userdata('active_bank') or $this->session->userdata('active_bank') != TRUE){
        show_404();
    }
    $id = $this->uri->segment(3);
    if(isset($id) and is_numeric($id)){
        $total_rows = $this->base_model->get_count("bank" , array('customer_id'=>$id , 'active'=> 0));
        $config['base_url'] = base_url('deal/disable_bank/'.$id);
        $config['total_rows'] = $total_rows;
        $config['per_page'] = '10';
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
    $data['bank'] = $this->base_model->get_data('bank', '*' , 'result'  , array('customer_id'=> $id , 'active'=>0) , $config['per_page'] , $page , array('id' , 'DESC'));
    $data['page'] = $this->pagination->create_links();
        $header['title'] = ' بانک های غیر فعال ';
        $header['active'] = 'deal';
        $header['active_sub'] = 'deal_archive';
        $this->load->view('header', $header);
        $this->load->view('deal/disable' , $data);
        $this->load->view('footer');
    }else{
        show_404();
    }
}
//disable archive -------

//-------pay------//
public function pay(){
    if($this->uri->segment(5) == 'all'){
        $act = 13;
        $text = 'کامل';
        if(!$this->session->has_userdata('pay_all') or $this->session->userdata('pay_all') != TRUE){
            show_404();
        }
    }else{
        $act = 14;
        $text = 'جزئی';
        if(!$this->session->has_userdata('pay_slice') or $this->session->userdata('pay_slice') != TRUE){
            show_404();
        }
    }
    $cust_id = $this->uri->segment(3);
    $id = $this->uri->segment(4);
    if(isset($cust_id) and is_numeric($cust_id) and isset($id) and is_numeric($id) and isset($_POST['sub']) ){
$date_pay = str_replace('/', '-', $this->input->post('date_pay'));        
$date = $this->convertdate->convert(time());
$handle_info = $this->base_model->get_data('handle' , '*' , 'row' , array('id'=>$id));
$buy = $this->base_model->run_query("SELECT deal.id , deal.pay , deal.rest  ,customer.fullname FROM deal LEFT JOIN customer ON deal.customer_id = customer.id WHERE deal.customer_id = $handle_info->buy_id AND deal.type = 1 AND deal.rest > 0 AND deal.volume > 0 ORDER BY deal.date_deal ASC , deal.id ASC");
        $push = $this->input->post('pay');
        $deal = array();
        $return = array();
        if(!empty($buy)){
            $str = ' پرداختی های مربوط به مشتری خرید  '. $buy[0]->fullname ."</br>";
            foreach($buy as $buys){
                if($push >= $buys->rest){
                    $pay = $buys->pay + $buys->rest;
                    $rest = 0;
                    $ampay = $buys->rest;
                }else{
                    $pay = $buys->pay + $push;
                    $rest = $buys->rest - $push;
                    $ampay = $push; 
                }
                $str .= ' شناسه معامله '. $buys->id ." | مقدار پرداختی : ".number_format($ampay)."</br>";
                $return[] = array(
                    'deal_id'=> $buys->id , 
                    'amount' => $ampay,
                    'state'  => 1
                  );
                $deal[] = array(
                'id'=> $buys->id,
                'pay'=> $pay,
                'rest'=> $rest
                );
                $push = $push - $ampay;
                if($push <= 0){
                    break;
                }
            }
        }
$this->db->trans_begin();            
if($push > 0){
    $state_sell = $this->base_model->get_data('deal' , 'id , count_money , volume , rest' , 'row' , array('customer_id'=>$handle_info->buy_id , 'type'=> 2 , 'state'=>0 , 'date_deal'=> $date_pay));
    if(!empty($state_sell)){
        $upsell['count_money'] = $state_sell->count_money + $push;
        $upsell['volume'] = $state_sell->volume + $push;
        $upsell['rest'] = $state_sell->rest + $push;
        $status = $this->base_model->update_data('deal' , $upsell , array('id' => $state_sell->id));
        $return[] = array(
            'deal_id'=>$state_sell->id,
            'amount'=>$push,
            'state'=>0
        );
        $str .= " مقدار ریالی معامله  ".$state_sell->id."  به دلیل مازاد بودن حجم پرداختی به اندازه ". number_format($push)." افزایش یافت "."</br>";
    }else{
        $inssell['count_money'] = $push;
        $inssell['wage'] = 0;
        $inssell['convert'] = 1;
        $inssell['volume'] = $push;
        $inssell['pay'] = 0;
        $inssell['rest'] = $push;
        $inssell['explain'] = 'این معامله به صورت مازاد است';
        $inssell['date_deal'] = $date_pay;
        $inssell['time_deal'] = $date['t'];
        $inssell['type'] = 2;
        $inssell['customer_id'] = $handle_info->buy_id;
        $inssell['money_id'] = 5;
        $inssell['state'] = 0;
        $status = $this->base_model->insert_data('deal' , $inssell);
        $return[] = array(
            'deal_id'=>$status,
            'amount'=>$push,
            'state'=>0
        );
        $str .= " به دلیل مازاد بودن پرداختی صورت گرفته معامله ای از نوع فروش با شناسه ".$status." و مقدار ".number_format($push)." ایجاد شد ";
    }     
}

    $sell = $this->base_model->run_query("SELECT deal.id , deal.pay , deal.rest  , customer.fullname FROM deal LEFT JOIN customer ON deal.customer_id = customer.id WHERE deal.customer_id = $handle_info->sell_id AND deal.type = 2 AND deal.rest > 0 AND deal.volume > 0 ORDER BY deal.date_deal ASC , deal.id ASC");
    $push = $this->input->post('pay');
    if(!empty($sell)){
        $str .= ' پرداختی های مربوط به مشتری فروش  '.$sell[0]->fullname ."</br>";
        foreach($sell as $sells){
                if($push >= $sells->rest){
                    $pay = $sells->pay + $sells->rest;
                    $rest = 0;
                    $ampay = $sells->rest;
                }else{
                    $pay = $sells->pay + $push;
                    $rest = $sells->rest - $push;
                    $ampay = $push;
                }
                $str .= ' شناسه معامله '. $sells->id ." | مقدار پرداختی : ".number_format($ampay)."</br>";
                $return[] = array(
                    'deal_id'=> $sells->id , 
                    'amount' => $ampay,
                    'state'  => 1
                  );
                $deal[] = array(
                    'id'=>$sells->id,
                    'pay'=>$pay,
                    'rest'=>$rest
                );
                $push = $push - $ampay;
                if($push <= 0){
                    break;
                }
        }
    }
        if($push > 0){
            $state_buy = $this->base_model->get_data('deal' , 'id , count_money , volume , rest' , 'row' , array('customer_id'=>$handle_info->sell_id , 'type'=> 1 , 'state'=>0 , 'date_deal'=> $date_pay));
            if(!empty($state_buy)){
                $upbuy['count_money'] = $state_buy->count_money + $push;
                $upbuy['volume'] = $state_buy->volume + $push;
                $upbuy['rest'] = $state_buy->rest + $push;
                $status = $this->base_model->update_data('deal' , $upbuy , array('id' => $state_buy->id));
                $return[] = array(
                    'deal_id'=> $state_buy->id , 
                    'amount' => $push,
                    'state'=>0
                  );
                $str .= " مقدار ریالی معامله  ".$state_buy->id."  به دلیل مازاد بودن حجم پرداختی به اندازه ". number_format($push)." افزایش یافت "."</br>";
            }else{
                $insbuy['count_money'] = $push;
                $insbuy['wage'] = 0;
                $insbuy['convert'] = 1;
                $insbuy['volume'] = $push;
                $insbuy['pay'] = 0;
                $insbuy['rest'] = $push;
                $insbuy['explain'] = 'این معامله به صورت مازاد  است';
                $insbuy['date_deal'] = $date_pay;
                $insbuy['time_deal'] = $date['t'];
                $insbuy['type'] = 1;
                $insbuy['customer_id'] = $handle_info->sell_id;
                $insbuy['money_id'] = 5;
                $insbuy['state'] = 0;
                $status = $this->base_model->insert_data('deal' , $insbuy);
                $return[] = array(
                    'deal_id'=> $status , 
                    'amount' => $push,
                    'state'=>0
                  );
                  $str .= " به دلیل مازاد بودن پرداختی صورت گرفته معامله ای از نوع خرید با شناسه ".$status." و مقدار ".number_format($push)." ایجاد شد ";
            }    
            }
        $bank_info = $this->base_model->get_data_join('bank' , 'customer' , 'bank.pay , bank.rest , customer.fullname as owner', 'bank.customer_id = customer.id' , 'row' , array('bank.id'=>$handle_info->bank_id));        
        $handle['handle_pay'] = $handle_info->handle_pay + $this->input->post('pay');
        $handle['handle_rest'] = $handle_info->handle_rest - $this->input->post('pay');
        $handle['date_modified'] = $date['dd']."</br> ".$date['t'];
        $bank['pay'] = $bank_info->pay + $this->input->post('pay');
        $bank['rest'] = $bank_info->rest - $this->input->post('pay');
        $history['date_pay'] = $date_pay." ".$date['t'];
        $history['volume'] = $this->input->post('pay');
        $history['active'] = 1;
        $history['handle_id'] = $handle_info->id;
        $turn['owner'] = $bank_info->owner;
        $turn['cust_id'] = $handle_info->sell_id;
        $turn['bank_id'] = $handle_info->bank_id;
        $turn['date'] = $date_pay;
        $turn['time'] = $date['t'];
        $turn['amount'] = $this->input->post('pay');
        $turn['rest'] = $bank['rest'];
        $status = $this->base_model->update_data('bank' , $bank , array('id'=>$handle_info->bank_id));
        $status =  $this->base_model->update_data('handle' , $handle , array('id'=> $id));
        $status = $this->base_model->insert_data('turnover' , $turn);
        $res_his = $this->base_model->insert_data('history' , $history);
        $count_ret = sizeof($return);
        for($i = 0 ; $i < $count_ret ; $i++){
            $return[$i]['his_id'] = $res_his;
        }
        $status = $this->base_model->insert_batch('ret_his' , $return);
        if(!empty($deal)){
             $this->base_model->update_batch('deal' , $deal , 'id');
        }
        if($handle_info->buy_id == $handle_info->sell_id){
            $status = $this->base_model->set('plus' , 'plus+'.$this->input->post('pay') , array('id'=>$handle_info->buy_id) , 'customer');
        }
        $log['user_id'] = $this->session->userdata('id');
        $log['date_log'] = $date['dd'];
        $log['time_log'] = $date['t'];
        $log['activity_id'] = $act;
        $log['explain'] = $str;
        $log['customer_id'] = $cust_id;
        if($this->db->trans_status() === FALSE or $status == FALSE or $res_his == FALSE){
            $this->db->trans_rollback();
            $message['msg'][0] = 'متاسفانه مشکلی در ثبت اطلاعات رخ داده است . لطفا دوباره سعی کنید';
            $message['msg'][1] = 'danger';
            $this->session->set_flashdata($message);
            redirect("deal/profile/$cust_id");
        }else{
            $this->db->trans_commit();
        }
        $this->base_model->insert_data('log' , $log);
          $message['msg'][0] =  ' پرداخت به صورت  '.$text." پرداخت شد ";
          $message['msg'][1] = 'success';
          $message['status'] = 3;
          $this->session->set_flashdata($message);
          redirect("deal/profile/$cust_id");
    }else{
        show_404();
    }  
}
//--------pay-----//

// ----history------//
public function get_history(){
    if(!$this->session->has_userdata('restore') or $this->session->userdata('restore') != TRUE){
        show_404();
    }
    if(isset($_POST['handle_id'])){
       $handle_id = $this->input->post('handle_id');
       $history = $this->base_model->get_data('history' , '*' , 'result' , array('handle_id'=> $handle_id , 'active'=> 1) , NULL , NULL , array('id' , 'DESC'));
       echo json_encode($history);
    }else{
        show_404();
    }
}

public function restore(){
    if(!$this->session->has_userdata('restore') or $this->session->userdata('restore') != TRUE){
        show_404();
    }
    $cust_id = $this->uri->segment(3);
    $id = $this->uri->segment(4);
    if(isset($cust_id) and isset($id) and is_numeric($cust_id) and is_numeric($id)){
     $history = $this->base_model->get_data('history' , '*' , 'row' , array('id'=> $id , 'active'=> 1));
     if(empty($history)){
        $message['msg'][0] = 'خطا در سیستم';
        $message['msg'][1] = 'danger';
        $this->session->set_flashdata($message);
        redirect("deal/profile/$cust_id"); 
     }
    $handle_info = $this->base_model->get_data('handle' , '*' , 'row' , array('id'=>$history->handle_id));
    $return = $this->base_model->run_query("SELECT * FROM ret_his WHERE his_id = $history->id ORDER BY deal_id ASC");
    $in = '' ;
    foreach($return as $str){
        $in .= $str->deal_id.",";
    }
    $in = trim($in , ',');
    $base = $this->base_model->run_query("SELECT id , pay , rest , count_money , volume FROM deal WHERE id IN ($in) ORDER BY id ASC");
    if(empty($base)){
      $message['msg'][0] = 'خطا در سیستم';
      $message['msg'][1] = 'danger';
      $this->session->set_flashdata($message);
      redirect("deal/profile/$cust_id"); 
    }
    $change = array();
    foreach($base as $rows){
        $change[$rows->id] = array(
           'pay'=> $rows->pay,
          'rest'=> $rows->rest,
         'count'=> $rows->count_money,
        'volume'=> $rows->volume
        );
    }
$deal = array();
$exp = '';
foreach($return as $key => $ret){
    if($ret->state == 1){
       $exp .= ' مبلغ بازگشتی معامله  '.$ret->deal_id . " به اندازه : ". number_format($ret->amount)."</br>";
       $deal[] = array(
            'id'=>$ret->deal_id,
           'pay'=>$change[$ret->deal_id]['pay']  - $ret->amount,
          'rest'=>$change[$ret->deal_id]['rest'] + $ret->amount,
   'count_money'=>$change[$ret->deal_id]['count'],
        'volume'=>$change[$ret->deal_id]['volume']
       );
    }else{
        if($change[$ret->deal_id]['pay'] != 0){
            $message['msg'][0] = ' جهت جلوگیری از ناسازگاری در سیستم ابتدا معامله  '.$ret->deal_id ." را بازگشت بزنید ";
            $message['msg'][1] = 'danger';
            $this->session->set_flashdata($message);
            redirect("deal/profile/$cust_id"); 
        }else{
            $exp .= ' مازاد بازگشتی معامله  '.$ret->deal_id . " به اندازه : ". number_format($ret->amount)."</br>";
            $deal[] = array(
                'id'=>$ret->deal_id,
               'pay'=>$change[$ret->deal_id]['pay'],
              'rest'=>$change[$ret->deal_id]['rest'] - $ret->amount
           );
           if($deal[$key]['pay'] == 0 && $deal[$key]['rest'] == 0){
               $deal[$key]['count_money'] = 0;
               $deal[$key]['volume'] = 0;
           }else{
            $deal[$key]['count_money'] = $change[$ret->deal_id]['count'] - $ret->amount;
            $deal[$key]['volume'] = $change[$ret->deal_id]['volume'] - $ret->amount;
           }
        }
    }
}
if(empty($deal)){
    $message['msg'][0] = 'خطا در سیستم';
    $message['msg'][1] = 'danger';
    $this->session->set_flashdata($message);
    redirect("deal/profile/$cust_id"); 
}
    $date = $this->convertdate->convert(time());
    $this->db->trans_begin();
    $bank_info = $this->base_model->get_data_join('bank' , 'customer' , 'bank.pay , bank.rest , customer.fullname as owner' ,'bank.customer_id = customer.id','row' , array('bank.id'=>$handle_info->bank_id));
    $handle['handle_pay'] = $handle_info->handle_pay - $history->volume;
    $handle['handle_rest'] = $handle_info->handle_rest + $history->volume;
    $handle['date_modified'] = $date['dd']."</br>".$date['t'];
    $bank['pay'] = $bank_info->pay - $history->volume;
    $bank['rest'] = $bank_info->rest + $history->volume;
    $turn['owner'] = $bank_info->owner;
    $turn['cust_id'] = $handle_info->sell_id;
    $turn['bank_id'] = $handle_info->bank_id;
    $turn['date'] = $date['dd'];
    $turn['time'] = $date['t'];
    $turn['amount'] = 0 - $history->volume;
    $turn['rest'] = $bank['rest'];
    $log['user_id'] = $this->session->userdata('id');
    $log['date_log'] = $date['dd'];
    $log['time_log'] = $date['t'];
    $log['activity_id'] = 15;
    $log['explain'] = $exp;
    $log['customer_id'] = $handle_info->buy_id;
    $his['active'] = 0;
    $status = $this->base_model->insert_data('turnover' , $turn);
    $status = $this->base_model->update_data('bank' , $bank , array('id'=>$handle_info->bank_id));
    $status = $this->base_model->update_data('handle' , $handle , array('id'=> $history->handle_id));
    $this->base_model->update_batch('deal' , $deal , 'id');
    $status = $this->base_model->update_data('history' , $his , array('id'=> $id));
    if($handle_info->buy_id == $handle_info->sell_id){
        $status = $this->base_model->set('plus' , 'plus-'.$history->volume , array('id'=>$handle_info->buy_id) , 'customer');
    } 
    if($this->db->trans_status() === FALSE or $status == FALSE){
        $this->db->trans_rollback();
        $message['msg'][0] = 'متاسفانه مشکلی در ثبت اطلاعات رخ داده است . لطفا دوباره سعی کنید';
        $message['msg'][1] = 'danger';
        $this->session->set_flashdata($message);
        redirect("deal/profile/$cust_id");
    }else{
        $this->db->trans_commit();
    }
    $this->base_model->insert_data('log' , $log);
      $message['msg'][0] = 'بازگشت پرداخت به صورت کامل انجام شد ';
      $message['msg'][1] = 'success';
      $message['status'] = 3;
      $this->session->set_flashdata($message);
      redirect("deal/profile/$cust_id"); 
    }else{
        show_404();
    }
}
// -----history------//

//------edit handle-----//
public function edit_handle(){
    if(!$this->session->has_userdata('edit_handle') or $this->session->userdata('edit_handle') != TRUE){
        show_404();
    }
    $red_id = $this->uri->segment(3);
    $id = $this->uri->segment(4);
    if(isset($_POST['sub']) and isset($id) and isset($red_id) and is_numeric($id) and is_numeric($red_id)){
      
      $handle_info = $this->base_model->get_data_join('handle' , 'bank' ,'handle.volume_handle , handle.handle_rest, handle.bank_id ,bank.rest_handle , customer.fullname' , 'bank.id = handle.bank_id' ,'row' , array('handle.id'=>$id) , NULL , NULL , NULL , array('customer' , 'handle.buy_id = customer.id'));
      $change = $this->input->post('volume_handle') - $handle_info->volume_handle;
      $handle['volume_handle'] = $this->input->post('volume_handle');
      $handle['handle_rest'] = $handle_info->handle_rest + $change;
      $handle['date_handle'] = $this->input->post('date_handle');
      $bank['rest_handle'] = $handle_info->rest_handle - $change;
      $str = ' حجم هماهنگی مشتری خرید  '.$handle_info->fullname . " از مقدار ".number_format($handle_info->volume_handle) . " به مقدار ". number_format($handle['volume_handle']) . " تغییر یافت ";
      $date = $this->convertdate->convert(time());
      $log['user_id'] = $this->session->userdata('id');
      $log['date_log'] = $date['dd'];
      $log['time_log'] = $date['t'];
      $log['activity_id'] = 21;
      $log['explain'] = $str;
      $log['customer_id'] = $red_id;
      $this->base_model->update_data('handle' , $handle , array('id'=> $id));
      $this->base_model->update_data('bank' , $bank , array('id'=> $handle_info->bank_id));
      $this->base_model->insert_data('log' , $log);
      $message['msg'][0] = 'هماهنگی با موفقیت ویرایش شد';
      $message['msg'][1] = 'success';
      $message['status'] = 3;
      $this->session->set_flashdata($message);
      redirect("deal/profile/$red_id");       
    }else{
        show_404();
    }
}
//---------edit handle--------//

// ----delete handle---------//
    public function delete_handle(){
        $red_id = $this->uri->segment(3);
        $id = $this->uri->segment(4);
        if(isset($id) and is_numeric($id) and isset($red_id) and is_numeric($red_id)){
           $handle = $this->base_model->get_data_join('handle' , 'customer' ,'handle.* , customer.fullname ' , 'handle.buy_id = customer.id' ,'row' , array('handle.id' => $id));
           if(empty($handle)){
               show_404();
           }else{
               if($handle->handle_pay != 0 ){
                   $message['msg'][0] = 'خطا در سیستم';
                   $message['msg'][1] = 'info';
                   $this->session->set_flashdata($message);
                   redirect("deal/profile/$red_id");
               }
            $exp = " هماهنگی مربوط به مشتری  ".$handle->fullname." با مشخصات : </br> "." حجم هماهنگی : ".number_format($handle->volume_handle)." </br> حجم پرداخت شده : ".number_format($handle->handle_pay)." </br> حجم باقی مانده : ".number_format($handle->handle_rest)."</br> شناسه بانک : ".$handle->bank_id."</br> حذف شد";
            $date = $this->convertdate->convert(time());
            $log['date_log'] = $date['dd'];
            $log['time_log'] = $date['t'];
            $log['user_id'] = $this->session->userdata('id');
            $log['activity_id'] = 16;
            $log['explain'] = $exp;
            $log['customer_id'] = $red_id;
            $res = $this->base_model->set('rest_handle' , 'rest_handle+'.$handle->volume_handle , array('id'=>$handle->bank_id) , 'bank');
            $res = $this->base_model->delete_data('handle' , array('id'=>$id));
            $this->base_model->insert_data('log' , $log);
            if($res == FALSE){
                $message['msg'][0] = 'متاسفانه مشکلی در روند عملیات رخ داده است . لطفا دوباره سعی کنید';
                $message['msg'][1] = 'danger';
                $this->session->set_flashdata($message);
                redirect("deal/profile/$red_id");
            }else{
                $message['msg'][0] = 'هماهنگی با موفقیت حذف شد';
                $message['msg'][1] = 'success';
                $message['status'] = 3;
                $this->session->set_flashdata($message);
                redirect("deal/profile/$red_id");
            }
           }
        }else{
            show_404();
        }
    }
    // ----delete handle---//

    public function worksheet(){
        if(!$this->session->has_userdata('see_deal') or $this->session->userdata('see_deal') != TRUE){
            show_404();
        }
        if(isset($_POST['sub'])){

            if($_POST['bank_id'] == 0){
                $message['msg'][0] = 'لطفا شماره حساب را از لیست مربوطه انتخاب کنید . در صورت موجود نبودن شماره حساب لطفا اقدام به افزودن شماره حساب کنید' ;
                $message['msg'][1] = 'danger';
                $this->session->set_flashdata($message);
                redirect("deal/worksheet");
             }
$buy_name = trim($this->input->post('customer_buy') , ' ');
$sell_name = trim($this->input->post('customer_sell') , ' ');                 
$buy_info = $this->base_model->get_data_join('deal' , 'customer' , 'deal.id as deal_id , customer.id' , 'customer.id = deal.customer_id' , 'row' , array('customer.fullname'=>$buy_name , 'deal.type' => 1));
$sell_info = $this->base_model->get_data_join('deal' , 'customer' , 'deal.id as deal_id , customer.id' , 'customer.id = deal.customer_id' , 'row' , array('customer.fullname'=>$sell_name , 'deal.type'=> 2));
if(empty($buy_info)){
$message['msg'][0] = 'توجه داشته باشید که مشتری خرید باید در سامانه ثبت شده باشد و تعداد خرید های انجام شده با شخص حداقل یک باشد' ;
$message['msg'][1] = 'danger';
$this->session->set_flashdata($message);
redirect("deal/worksheet");
}else if(empty($sell_info)){
$message['msg'][0] = 'توجه داشته باشید که مشتری فروش باید در سامانه ثبت شده باشد و تعداد فروش های انجام شده با شخص حداقل یک باشد' ;
$message['msg'][1] = 'danger';
$this->session->set_flashdata($message);
redirect("deal/worksheet");
}
$start_date = $_POST['date_handle'];
$persian_num = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
$latin_num = range(0, 9);
$slash = '/';
$dash = '-';
$start = str_replace($persian_num, $latin_num, $start_date);
$start = str_replace($slash, $dash, $start);
$date_handle = substr($start , 0 , 10);
$time_handle = substr($start , 10 , 20);
$date = $this->convertdate->convert(time());
$data['volume_handle'] = $this->input->post('volume_handle');
$data['handle_pay'] = 0;
$data['handle_rest'] = $this->input->post('volume_handle');
$data['date_handle'] = $date_handle;
$data['time_handle'] = $time_handle;
$data['date_modified'] = ' ثبت نشده است ';
$data['buy_id'] = $buy_info->id;
$data['sell_id'] = $sell_info->id;
$data['bank_id'] = $this->input->post('bank_id');
$this->base_model->set('rest_handle' , 'rest_handle-'.$data['volume_handle'] , array('id'=>$data['bank_id']) , 'bank');
$str = ' هماهنگی با مشخصات : </br> نام مشتری خرید :'.$buy_name."</br> نام مشتری فروش : ".$sell_name."</br> مبلغ هماهنگی : ".number_format($data['volume_handle'])."</br> ثبت شد";
            $log['user_id'] = $this->session->userdata('id');
            $log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
            $log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
            $log['activity_id'] = 12;
            $log['explain'] = $str;
$this->base_model->insert_data('log' , $log);
$this->base_model->insert_data('handle' , $data);

                $message['msg'][0] = 'اطلاعات هماهنگی با موفقیت ثبت شد';
                $message['msg'][1] = 'success';
                $this->session->set_flashdata($message);
                redirect("deal/worksheet");

        }else{
            $rows_buy = $this->base_model->get_data('deal' , 'count(id) as cust_id' , 'result' , array('type'=>1) , NULL , NULL , NULL , 'customer_id');
            $rows_sell = $this->base_model->get_data('deal' , 'count(id) as cust_id' , 'result' , array('type'=>2) , NULL , NULL , NULL , 'customer_id');
            $data['buy'] = $this->base_model->run_query("SELECT d.customer_id, SUM(d.rest) AS rest, SUM(d.volume) AS volume, max(h.volume_handle) AS handle , c.fullname  FROM  deal d LEFT JOIN (SELECT buy_id, SUM(volume_handle) AS volume_handle FROM handle GROUP BY buy_id) h ON h.buy_id = d.customer_id inner join customer c on c.id = d.customer_id where d.type = 1 GROUP BY d.customer_id ORDER BY d.id DESC LIMIT 0 , 10");
            $data['sell'] = $this->base_model->run_query("SELECT d.customer_id, SUM(d.rest) AS rest, SUM(d.volume) AS volume, max(h.volume_handle) AS handle , c.fullname  FROM  deal d LEFT JOIN (SELECT sell_id, SUM(volume_handle) AS volume_handle FROM handle GROUP BY sell_id) h ON h.sell_id = d.customer_id inner join customer c on c.id = d.customer_id where d.type = 2 GROUP BY d.customer_id ORDER BY d.id DESC LIMIT 0 , 10");
            $data['rows_buy'] = sizeof($rows_buy);
            $data['rows_sell'] = sizeof($rows_sell);
            $customer = $this->base_model->run_query("SELECT id , fullname FROM customer order by id ASC");
            $buy = $this->base_model->run_query("SELECT d.customer_id, SUM(d.volume) AS volume, max(h.volume_handle) AS handle FROM  deal d LEFT JOIN (SELECT buy_id, SUM(volume_handle) AS volume_handle FROM handle GROUP BY buy_id) h ON h.buy_id = d.customer_id  where d.type = 1 GROUP BY d.customer_id ORDER BY d.customer_id ASC");
            $sell = $this->base_model->run_query("SELECT d.customer_id, SUM(d.volume) AS volume, max(h.volume_handle) AS handle FROM  deal d LEFT JOIN (SELECT sell_id, SUM(volume_handle) AS volume_handle FROM handle GROUP BY sell_id) h ON h.sell_id = d.customer_id  where d.type = 2 GROUP BY d.customer_id ORDER BY d.customer_id ASC");
            $date = $this->convertdate->convert(time());
            $data['date'] = $date['year']."/".$date['month_num']."/".$date['day']." ".$date['hour'].":".$date['minute'].":".$date['second'];
            $search = array();
            foreach($customer as $rows){
                $search[$rows->id]['fullname'] = $rows->fullname;
                $search[$rows->id]['buy'] = 0;
                foreach($buy as $row){
                  if($rows->id == $row->customer_id){
                          if($row->volume < $row->handle){
                              $search[$rows->id]['buy'] = 0;
                          }else{
                              $search[$rows->id]['buy'] = $row->volume - $row->handle;
                          }
                      break; 
                  }
                }
            }
            foreach($customer as $rows){
                $search[$rows->id]['sell'] = 0;
                foreach($sell as $row){
                  if($rows->id == $row->customer_id){
                        if($row->volume < $row->handle){
                              $search[$rows->id]['sell'] = 0;
                          }else{
                              $search[$rows->id]['sell'] = $row->volume - $row->handle;
                          }
                      break; 
                  }
                }
            }
            $data['search'] = $search;
                        $header['title'] = 'کاربرگ معاملات';
                        $header['active'] = 'deal';
                        $header['active_sub'] = 'deal_sheet';
                        $this->load->view('header' , $header);
                        $this->load->view('deal/sheet' , $data);
                        $this->load->view('footer');
        }
    }
    public function page_buy(){
        if(isset($_POST['offset'])){
            $offset = $this->input->post('offset');
            $buy = $this->base_model->run_query("SELECT d.customer_id, SUM(d.rest) AS rest, SUM(d.volume) AS volume, max(h.volume_handle) AS handle , c.fullname  FROM  deal d LEFT JOIN (SELECT buy_id, SUM(volume_handle) AS volume_handle FROM handle GROUP BY buy_id) h ON h.buy_id = d.customer_id inner join customer c on c.id = d.customer_id where d.type = 1 GROUP BY d.customer_id ORDER BY d.id DESC LIMIT $offset , 10");
            echo json_encode($buy);
        }else{
            show_404();
        }
    }
    public function page_sell(){
        if(isset($_POST['offset'])){
            $offset = $this->input->post('offset');
            $sell =  $this->base_model->run_query("SELECT d.customer_id, SUM(d.rest) AS rest, SUM(d.volume) AS volume, max(h.volume_handle) AS handle , c.fullname  FROM  deal d LEFT JOIN (SELECT sell_id, SUM(volume_handle) AS volume_handle FROM handle GROUP BY sell_id) h ON h.sell_id = d.customer_id inner join customer c on c.id = d.customer_id where d.type = 2 GROUP BY d.customer_id ORDER BY d.id DESC LIMIT $offset , 10");
            echo json_encode($sell);
        }else{
            show_404();
        }
    }
    public function get_customer(){
        $name = $this->input->post('name');
        $name = trim($name , ' ');
        $type = $this->input->post('type');
        if($type == 'buy'){
           $data['cust'] = $this->base_model->run_query("SELECT d.customer_id, SUM(d.rest) AS rest, SUM(d.volume) AS volume, max(h.volume_handle) AS handle , c.fullname  FROM  deal d LEFT JOIN (SELECT buy_id, SUM(volume_handle) AS volume_handle FROM handle GROUP BY buy_id) h ON h.buy_id = d.customer_id inner join customer c on c.id = d.customer_id where d.type = 1 AND c.fullname = '$name' GROUP BY d.customer_id");
           if(!empty($data['cust'])){
            $cust_id = $data['cust'][0]->customer_id;
            $data['bank'] = $this->base_model->get_data('bank' , 'id , rest_handle , rest , explain' , 'result' , array('customer_id'=>$cust_id , 'active'=>1)); 
           }else{
               $data['bank'] = array();
           }
        }else{
           $data['cust'] = $this->base_model->run_query("SELECT d.customer_id, SUM(d.rest) AS rest, SUM(d.volume) AS volume, max(h.volume_handle) AS handle , c.fullname  FROM  deal d LEFT JOIN (SELECT sell_id, SUM(volume_handle) AS volume_handle FROM handle GROUP BY sell_id) h ON h.sell_id = d.customer_id inner join customer c on c.id = d.customer_id where d.type = 2 AND c.fullname = '$name' GROUP BY d.customer_id");
        }
        // echo "<pre>";
        // var_dump($data);
        // echo "</pre>";
        echo json_encode($data);
    }
}

/* End of file Controllername.php */

?>