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
    if(isset($_POST['sub'])){
        $data['m'] = $_POST['money_id']; $data['t'] = $_POST['type'];
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
        $between = "deal.date_deal BETWEEN '$date_start' AND '$date_end'";
        if($_POST['type'] == 0 and $_POST['money_id'] == 0){
            $where = NULL;
            $total_rows = $this->base_model->get_count_between("deal" , NULL , $between);
        }else if($_POST['type'] == 0){
           $where = array('deal.money_id' => $_POST['money_id']);
           $total_rows = $this->base_model->get_count_between("deal" , array('money_id'=> $_POST['money_id']) , $between);
        }else if($_POST['money_id'] == 0){
            $where = array('deal.type' => $_POST['type']);
            $total_rows = $this->base_model->get_count_between("deal" , array('type'=> $_POST['type']) , $between);
        }else{
            $where = array('deal.type' => $_POST['type'] , 'deal.money_id' => $_POST['money_id']);
            $total_rows = $this->base_model->get_count_between("deal" , array('type' => $_POST['type'] , 'money_id' => $_POST['money_id']) , $between);
        }
        $limit = NULL;
        $offset = NULL;
    }else{
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0; 
        $limit = 10;
        $offset = $page;
        $data['m'] = 0; $data['t'] = 0;
        $between = NULL;
        $where = NULL;
        $total_rows = $this->base_model->get_count("deal" , 'ALL');
    }
    $config['base_url'] = base_url('deal/archive');
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
$data['deal'] = $this->base_model->get_data_join('deal' ,'customer', 'deal.* , customer.fullname , customer.id as cust_id , unit.name' , 'deal.customer_id = customer.id' ,'result'  , $where , $limit , $offset , array('deal.id' , 'DESC') , array('unit','deal.money_id = unit.id') , NULL , $between);
if(!isset($_POST['sub'])){
    $data['page'] = $this->pagination->create_links();
}
$data['count'] = $config['total_rows'];
$date = $this->convertdate->convert(time());
$data['date'] = $date['year']."/".$date['month_num']."/".$date['day'] . " ".$date['hour'].":".$date['minute'].":".$date['second'];
$data['unit'] = $this->base_model->get_data('unit' , 'id , name' , 'result' , array('id < ' => 10));
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
            $data = $this->base_model->search_data('deal' , 'customer' , 'deal.* , customer.fullname , customer.id as cust_id ,unit.name' ,'deal.customer_id = customer.id' , 'inner'  , array('customer.fullname'=>$title) , NULL , array('deal.id' , 'DESC') , NULL , array('unit','deal.money_id = unit.id'));
            echo json_encode($data);
        }else{
            show_404();
        }
    }
    //----- search customer -----//
    //-----delete deal--------//
    public function delete_deal(){
       if(!$this->session->has_userdata('delete_deal') or $this->session->userdata('delete_deal') != TRUE){
           show_404();
       } 
        $id = $this->uri->segment(3);
        if(isset($id) and is_numeric($id)){
            //check
         $deal = $this->base_model->get_data_join('deal' , 'customer' ,'deal.* , customer.fullname','deal.customer_id = customer.id', 'row' , array('deal.id' => $id));
         if($deal->volume_pay != 0 or empty($deal)){
             show_404();
         }else{
            $other = $this->base_model->get_data('unit' , 'amount  , name' , 'row' , array('id'=> $deal->money_id));
            $rial = $this->base_model->get_data('unit' , 'amount' , 'row' , array('id'=> 10));
             
            $a = $id +100;
            $explain = ' شناسه معامله :  '. $a . " | نام مشتری : ".$deal->fullname . " | نام ارز : ".$other->name ." | تعداد ارز : ".number_format($deal->count_money)." | کارمزد : " . number_format($deal->wage)." | نرخ تبدیل : ".number_format($deal->convert)." | حجم معامله : ".number_format($deal->volume)."حذف شد </br>";
//check

//currency
$am = $deal->count_money + $deal->wage;
if($deal->money_id == 10){
//rial
if($deal->type == 1){
    $unit_rial['amount'] = $rial->amount - $deal->volume;
    $text = 'کاهش یافت';
}else{
    $unit_rial['amount'] = $rial->amount + $deal->volume;
    $text = 'افزایش یافت';
}
$explain .= "</br>"."مقدار ریال به اندازه : ".number_format($deal->volume)." ".$text;
$this->base_model->update_data('unit' , $unit_rial , array('id' => 10));
//rial
}else{
    if($deal->type == 1){
//other
        $unit_other['amount'] = $other->amount - $am;
        $unit_rial['amount'] = $rial->amount + $deal->volume; 
        $text = " کاهش یافت ";
        $text2 = 'افزایش یافت';
        }else{
        $unit_other['amount'] = $other->amount + $am;
        $unit_rial['amount'] = $rial->amount - $deal->volume;
        $text = ' افزایش یافت ';
        $text2 = 'کاهش یافت ';
        }
$explain .= "</br>"." مقدار ارز ".$other->name." به اندازه ".number_format($am) . $text ." | مقدار ریال به اندازه ".number_format($deal->volume). " ".$text2;
$this->base_model->update_data('unit' , $unit_other , array('id' => $deal->money_id));
$this->base_model->update_data('unit' , $unit_rial , array('id' => 10));
//other
}
//currency

$date = $this->convertdate->convert(time());
$log['user_id'] = $this->session->userdata('id');
$log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
$log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
$log['activity_id'] = 20;
$log['explain'] = $explain;
$back['explain'] =  $explain;
$back['date_backup'] = $log['date_log'];
$back['time_backup'] = $log['time_log'];
$res = $this->base_model->delete_data('deal' , array('id' => $id));
$this->base_model->insert_data('log' , $log);
$this->base_model->insert_data('backup' , $back);
if($this->uri->segment(5) == 'group'){
    $red = 'handle_profile';
    $red_id = $this->uri->segment(4);
}else{
    $red = 'archive';
    $red_id = '';
}
if($res == TRUE){
    $message['msg'][0] = ' معامله با موفقیت حذف شد  ';
    $message['msg'][1] = 'success';
    $this->session->set_flashdata($message);
    redirect("deal/$red/$red_id");
}else{
    $message['msg'][0] = 'مشکلی در حذف معامله رخ داده است . لطفا دوباره سعی کنید';
    $message['msg'][1] = 'danger';
    $this->session->set_flashdata($message);
    redirect("deal/$red/$red_id");
}
         }
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
                if(!$this->session->has_userdata('add_buy') or $this->session->userdata('add_buy') != TRUE){
                    show_404();
                }
            }else{
                $page = 'sell';
                if(!$this->session->has_userdata('add_sell') or $this->session->userdata('add_sell') != TRUE){
                    show_404();
                }
            }
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

            //deal
            $customer['fullname'] = trim($this->input->post('customer') , ' ');
            $check = $this->base_model->get_data('customer' , 'id' , 'row' , array('fullname'=>$customer['fullname']));
            if(sizeof($check) == 0){
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
           $deal['count_money'] = $this->input->post('count_money');
           $deal['wage'] = $this->input->post('wage');
           $deal['convert'] = $this->input->post('convert');
           $deal['volume'] = ($deal['count_money'] + $deal['wage']) * $deal['convert'];
           $deal['pay'] = 0;
           $deal['rest'] = $deal['volume'];
           $deal['explain'] = $this->input->post('explain');
           $deal['date_deal'] = $date_deal;
           $deal['time_deal'] = $time_deal;
           $deal['date_modified'] = 'ثبت نشده است';
           $deal['type'] = $this->input->post('type');
           $deal['customer_id'] = $id;
           $deal['money_id'] = $this->input->post('money_id');
           $deal['state'] = 1;
           // deal

           //currency
           $rial = $this->base_model->get_data('unit' , 'amount' , 'row' , array('id'=> 10));
           $other = $this->base_model->get_data('unit' , 'amount , name' , 'row' , array('id'=> $deal['money_id']));
           if($deal['type'] == 1){
               $unit_rial['amount'] = $rial->amount - ($deal['volume']);
               $unit_other['amount'] = $other->amount + ($deal['count_money'] + $deal['wage']);
               $act = 9;
               $text2 = ' کاهش یافت ';
               $text = " افزایش یافت ";
           }else{
               $unit_rial['amount'] = $rial->amount + ($deal['volume']);
               $unit_other['amount'] = $other->amount - ($deal['count_money'] + $deal['wage']); 
               $act = 10;
               $text2 = 'افزایش یافت ';
               $text = " کاهش یافت ";
           }
           //currency

           $deal_id = $this->base_model->insert_data('deal' , $deal);
           if($deal_id == FALSE){
            $message['msg'][0] = 'مشکلی در ثبت اطلاعات رخ داده است. لطفا دوباره سعی کنید';
            $message['msg'][1] = 'danger';
            $this->session->set_flashdata($message);
            redirect("deal/$page");
        }
           //bank
            $count = sizeof($_POST['shaba']);
            for($i = 0 ; $i < $count ; $i++){
                if($_POST['bank_explain'][$i] != '' or $_POST['shaba'][$i] != '' or $_POST['name'][$i] != '' or $_POST['amount'][$i] != ''){
                    $bank[] = array(
                        'explain'=> htmlspecialchars($_POST['bank_explain'][$i]),
                        'name'=> htmlspecialchars($_POST['name'][$i]),
                        'shaba'=>htmlspecialchars($_POST['shaba'][$i]),
                        'amount'=> htmlspecialchars($_POST['amount'][$i]),
                        'pay'=>0,
                        'rest'=> htmlspecialchars($_POST['amount'][$i]),
                        'rest_handle'=> htmlspecialchars($_POST['amount'][$i]),
                        'customer_id'=> $id,
                        'active'=> 1
                    );
                }
            }
            //bank
        $this->base_model->update_data('unit' , $unit_rial , array('id' => 10)); 
        $this->base_model->update_data('unit' , $unit_other , array('id' => $deal['money_id']));  

        if(isset($bank) and !empty($bank)){
            $this->base_model->insert_batch('bank' , $bank);
        }  

        //log
        $money = $other->name;
        $aa = $deal_id + 100;
        $log['user_id'] = $this->session->userdata('id');
        $log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
        $log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
        $log['activity_id'] = $act;
        $am = $deal['count_money'] + $deal['wage'];
        $log['explain'] = " نام مشتری :  ".$customer['fullname']." | شناسه معامله : ".$aa . " | ارز معامله : ". $money . " | تعداد ارز : ".number_format($deal['count_money']).$money ." | کارمزد : ".number_format($deal['wage']).$money . " | نرخ تبدیل : ".number_format($deal['convert'])." ریال "." | حجم معامله  :  ".number_format($deal['volume'])." ریال "." | مقدار ارز  ".$money. " به اندازه ".number_format($am)." ".$text."| مقدار ریال به اندازه ".number_format($deal['volume'])." ".$text2;
        $this->base_model->insert_data('log' , $log);
        //log

        //photo
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
                     'pic_name' => $files['name'][$j],
                     'date_upload'=> $deal['date_deal']."</br>".$deal['time_deal']
                 );
             }else{
                 $message['msg'][0] = 'معامله با موفقیت ثبت شد . در ارسال عکس توجه داشته باشید که فرمت فایل باید معتبر باشد و نام عکس حاوی کلمه index نباشد . در بخش ویرایش معامله می توانید دوباره عکس های خود را اضافه کنید';
                 $message['msg'][1] = 'danger';
                 $this->session->set_flashdata($message);
                 redirect("deal/$page");
             }
            }
        $this->base_model->insert_batch('deal_pic' , $img);
        }
         //photo

         $message['msg'][0] = 'اطلاعات معامله با موفقیت ثبت شد';
         $message['msg'][1] = 'success';
         $this->session->set_flashdata($message);
         redirect("deal/$page");

}else{
    if(!$this->session->has_userdata('add_buy') or $this->session->userdata('add_buy') != TRUE){
         show_404();
     } 
            $date = $this->convertdate->convert(time());
            $data['date'] = $date['year']."/".$date['month_num']."/".$date['day']." ".$date['hour'].":".$date['minute'].":".$date['second'];
            $header['title'] = 'افزودن خرید';
            $header['active'] = 'deal';
            $header['active_sub'] = 'deal_buy';
            $data['customer'] = $this->base_model->get_data('customer' ,'fullname' , 'result');
            $data['unit'] = $this->base_model->get_data('unit' , 'id , name ', 'result' , array('id < ' => 10));
            $this->load->view('header' , $header);
            $this->load->view('deal/buy' , $data);
            $this->load->view('footer');
        }  
    }


    public function sell(){
    if(!$this->session->has_userdata('add_sell') or $this->session->userdata('add_sell') != TRUE){
        show_404();
    } 
            $header['title'] = 'افزودن فروش';
            $header['active'] = 'deal';
            $header['active_sub'] = 'deal_sell';
            $data['customer'] = $this->base_model->get_data('customer' ,'fullname' , 'result');
            $date = $this->convertdate->convert(time());
            $data['date'] = $date['year']."/".$date['month_num']."/".$date['day']." ".$date['hour'].":".$date['minute'].":".$date['second'];
            $data['unit'] = $this->base_model->get_data('unit' , 'id , name ', 'result' , array('id < ' => 10));
            $this->load->view('header' , $header);
            $this->load->view('deal/sell', $data);
            $this->load->view('footer');
    }


    public function customer_history(){
        $fullname = $this->input->post('text_search');
        $cust_id = $this->base_model->get_data('customer' , 'id' , 'row' , array('fullname' => $fullname));
        if(sizeof($cust_id) == 0){
            echo json_encode(false);
        }else{
            $date = $this->convertdate->convert(time());
            $today = $date['year']."-".$date['month_num']."-".$date['day'];
            $id = $cust_id->id;
            $buy = $this->base_model->get_data('deal' , 'money_id , rest , convert' , 'result' , array('type'=> 1 , 'customer_id'=> $id , 'date_deal'=>$today));
            $sell = $this->base_model->get_data('deal' , 'money_id , rest , convert' , 'result' , array('type'=> 2 , 'customer_id'=> $id, 'date_deal'=> $today));
            $give = $this->base_model->get_data('deal' , 'sum(rest) as give' , 'row' , array('type'=> 1 , 'customer_id'=> $id));
            $want = $this->base_model->get_data('deal' , 'sum(rest) as want' , 'row' , array('type'=> 2 , 'customer_id'=> $id));
            if(sizeof($buy) == 0){
                $buy_dollar = 0;
                $buy_euro = 0;
                $buy_yuan = 0;
                $buy_derham = 0;
            }else{
                $b_dollar = 0; $b_euro = 0 ; $b_yuan = 0 ; $b_derham = 0;
                foreach($buy as $buys){
                    if($buys->money_id == 1){
                        $b_dollar += ($buys->rest)/($buys->convert);
                    }else if($buys->money_id == 2){
                        $b_euro += ($buys->rest)/($buys->convert);
                    }else if($buys->money_id == 3){
                        $b_yuan += ($buys->rest)/($buys->convert);
                    }else if($buys->money_id == 4){
                        $b_derham += ($buys->rest)/($buys->convert);
                    }
            }
            $buy_dollar = $b_dollar;
            $buy_euro = $b_euro;
            $buy_yuan = $b_yuan;
            $buy_derham = $b_derham;
        }
            if(sizeof($sell) == 0){
                $sell_dollar = 0;
                $sell_euro = 0;
                $sell_yuan = 0;
                $sell_derham = 0;
            }else{
                $s_dollar = 0; $s_euro = 0 ; $s_yuan = 0 ; $s_derham = 0;
                foreach($sell as $sells){
                    if($sells->money_id == 1){
                        $s_dollar += ($sells->rest)/($sells->convert);
                    }else if($sells->money_id == 2){
                        $s_euro += ($sells->rest)/($sells->convert);
                    }else if($sells->money_id == 3){
                        $s_yuan += ($sells->rest)/($sells->convert);
                    }else if($sells->money_id == 4){
                        $s_derham += ($sells->rest)/($sells->convert);
                    }
                }
                $sell_dollar = $s_dollar;
                $sell_euro = $s_euro;
                $sell_yuan = $s_yuan;
                $sell_derham = $s_derham;
            }
            $data['dollar'] = $buy_dollar - $sell_dollar;
            $data['euro'] = $buy_euro - $sell_euro;
            $data['yuan'] = $buy_yuan - $sell_yuan;
            $data['derham'] = $buy_derham - $sell_derham;
            $data['rial'] = $want->want - $give->give;
            // echo "<pre>";var_dump($data); echo $give->give." ".$want->want;echo "</pre>";
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
            if(isset($_POST['sub'])){  
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
              $persian_num = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
              $latin_num = range(0, 9);
              $slash = '/';
              $dash = '-';
              $string = str_replace($persian_num, $latin_num, $_POST['date_deal']);
              $string = str_replace($slash, $dash, $string); 
              $date_deal = substr($string , 0 , 10);
              $time_deal = substr($string , 10 , 20);
             $date = $this->convertdate->convert(time());
             $deal['count_money'] = $this->input->post('count_money');
             $deal['wage'] = $this->input->post('wage');
             $deal['convert'] = $this->input->post('convert');
             $deal['volume'] = ($deal['count_money'] + $deal['wage']) * $deal['convert'];
             $deal['rest'] = $deal['volume'] - $this->input->post('pay');
             $deal['explain'] = $this->input->post('explain');
             $deal['date_deal'] = $date_deal;
             $deal['time_deal'] = $time_deal;
             $deal['date_modified'] = $date['year']."-".$date['month_num']."-".$date['day']."</br>".$date['hour'].":".$date['minute'].":".$date['second'];
             $deal['money_id'] = $this->input->post('money_id');
            //deal

            //currency 

             $base = $this->base_model->get_data('deal' , 'count_money , wage , money_id , type , volume , convert' , 'row' , array('id' => $id));
             
             $res = $this->base_model->update_data('deal' , $deal , array('id'=> $id));
             if($res == FALSE){
                 $message['msg'][0] = 'مشکلی در ثبت اطلاعات رخ داده است. لطفا دوباره سعی کنید';
                 $message['msg'][1] = 'danger';
                 $this->session->set_flashdata($message);
                 redirect("deal/edit/$id");
             } 

             $base_count = $base->count_money + $base->wage;
             $send_count = $deal['count_money'] + $deal['wage'];

             $change_rial = $deal['volume'] - $base->volume ;
             $rial = $this->base_model->get_data('unit' , 'amount' , 'row' , array('id'=> 10));

             if($base->money_id == 10){
                 //rial
                 if($base->type == 1){
                    $unit_rial['amount'] = $rial->amount + ($change_rial);
                    $text3 = ' افزایش یافت';
                 }else{
                    $unit_rial['amount'] = $rial->amount - ($change_rial);
                    $text3 = 'کاهش یافت';
                 }
                 $this->base_model->update_data('unit' , $unit_rial , array('id' => 10));
                 $change_unit = ' مقدار ریال به اندازه  '.number_format($change_rial).$text3."</br>";
                 //rial
             }else{
                 //other
                $base_unit = $this->base_model->get_data('unit' , 'amount , name' , 'row' , array('id'=> $base->money_id));

                if($base->money_id != $deal['money_id']){
                    $new_unit = $this->base_model->get_data('unit' , 'amount , name' , 'row' , array('id'=> $deal['money_id']));
                   
                     if($base->type == 1){
                        $update_base['amount'] = $base_unit->amount - ($base_count);
                        $update_send['amount'] = $new_unit->amount + ($send_count);
                        $unit_rial['amount'] = $rial->amount - ($change_rial);
                        $text3 = 'کاهش یافت';
                     }else{
                        $update_base['amount'] = $base_unit->amount + ($base_count);
                        $update_send['amount'] = $new_unit->amount - ($send_count);
                        $unit_rial['amount'] = $rial->amount + ($change_rial);
                        $text3 = 'افزایش یافت';
                     }
                     $change_unit = "  ارز معامله از : ".$base_unit->name." به ".$new_unit->name." تغییر یافت "."</br>"." مقدار ریال به اندازه   ".number_format($change_rial). $text3."</br>";
                     $this->base_model->update_data('unit' , $update_base , array('id' => $base->money_id));
                     $this->base_model->update_data('unit' , $update_send , array('id' => $deal['money_id']));
                     $this->base_model->update_data('unit' , $unit_rial , array('id' => 10));
                 }else{
                     if($base->type == 1){
                       $update_base['amount'] = $base_unit->amount + ($send_count - $base_count);
                       $unit_rial['amount'] = $rial->amount - ($change_rial);
                       $text3 = 'کاهش یافت';
                      }else{
                        $update_base['amount'] = $base_unit->amount - ($send_count - $base_count);
                        $unit_rial['amount'] = $rial->amount + ($change_rial);
                        $text3 = 'افزایش یافت';
                     }
                     $change_unit = '  ارز معامله :'.$base_unit->name."</br>"." مقدار ریال به اندازه ".number_format($change_rial).$text3."</br>";
                     $this->base_model->update_data('unit' , $update_base , array('id' => $base->money_id));
                     $this->base_model->update_data('unit' , $unit_rial , array('id' => 10));
                 }

                //other

             }

             $count_deal = " تعداد ارز معامله از : ".number_format($base->count_money)." به ".number_format($deal['count_money'])." تغییر یافت "."</br>";
             $wage_deal = "  کارمزد معامله از : ".number_format($base->wage)." به ".number_format($deal['wage'])." تغییر یافت "."</br>";
             $convert_deal = " نرخ تبدیل معامله از  ".number_format($base->convert)." به ".number_format($deal['convert'])." تغییر یافت "."</br>";
             $volume_deal = " حجم  معامله از  ".number_format($base->volume)." به ".number_format($deal['volume'])." تغییر یافت "."</br>";

            if(isset($_POST['shaba'][0])){
              for($i = 0 ; $i < sizeof($_POST['shaba']) ; $i++){
                  $rest = $_POST['amount'][$i] - $_POST['pay_bank'][$i];
                  $rest_handle = $_POST['rest_handle'][$i] + ($_POST['amount'][$i] - $_POST['real_amount'][$i]);
                  $bank[] = array(
                      'id'=>htmlspecialchars($_POST['bank_id'][$i]),
                      'explain'=> htmlspecialchars($_POST['bank_explain'][$i]),
                      'name'=> htmlspecialchars($_POST['name'][$i]),
                      'shaba'=>htmlspecialchars($_POST['shaba'][$i]),
                      'amount'=> htmlspecialchars($_POST['amount'][$i]),
                      'rest'=> $rest,
                      'rest_handle'=> $rest_handle
                  );
              }
            }

            if(isset($_POST['send_shaba'][0])){
                for($i = 0 ; $i < sizeof($_POST['send_shaba']) ; $i++){
                    if($_POST['send_shaba'][$i] != '' or $_POST['send_bank'][$i] != '' or $_POST['send_amount'][$i] != '' or $_POST['send_explain'][$i] != ''){
                        $new_bank[] = array(
                            'explain'=> htmlspecialchars($_POST['send_explain'][$i]),
                            'name'=> htmlspecialchars($_POST['send_bank'][$i]),
                            'shaba'=>htmlspecialchars($_POST['send_shaba'][$i]),
                            'amount'=> htmlspecialchars($_POST['send_amount'][$i]),
                            'pay'=> 0,
                            'rest' => htmlspecialchars($_POST['send_amount'][$i]),
                            'rest_handle'=>htmlspecialchars($_POST['send_amount'][$i]),
                            'customer_id' => $cust_id ,
                            'active' => 1
                        );
                    }
                }
              }

              if(isset($new_bank) and !empty($new_bank)){
                $this->base_model->insert_batch('bank' , $new_bank);
              }
              if(isset($bank) and !empty($bank)){
                $this->base_model->update_batch('bank' , $bank , 'id');
              }
              
            $log['user_id'] = $this->session->userdata('id');
            $log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
            $log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
            $log['activity_id'] = 11;
            $aa = $id + 100;
            $log['explain'] = " شناسه معامله : ".$aa." | نام مشتری :  ".$customer['fullname'] ."</br>". $change_unit . $count_deal . $wage_deal  . $convert_deal . $volume_deal;
            $this->base_model->insert_data('log' , $log);

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
                         'deal_id'=> $id,
                         'pic_name' => $files['name'][$j],
                         'date_upload'=>$deal['date_modified']
                     );
                 }else{
                     $message['msg'][0] = 'معامله با موفقیت ویرایش شد . در ارسال عکس توجه داشته باشید که عکس باید یکی از فرمت های gif|jpg|png|jpeg باشد و حاوی کلمه index نباشد ';
                     $message['msg'][1] = 'danger';
                     $this->session->set_flashdata($message);
                     redirect("deal/edit/$id");
                 }
                }
            $this->base_model->insert_batch('deal_pic' , $img);
            }


           $message['msg'][0] = 'اطلاعات معامله با موفقیت ثبت شد';
           $message['msg'][1] = 'success';
           $this->session->set_flashdata($message);
           redirect("deal/edit/$id");
            }else{
                $data['deal'] = $this->base_model->get_data_join('deal' , 'customer' , 'deal.* , customer.fullname , customer.id as cust_id' , 'deal.customer_id = customer.id'  ,'row' , array('deal.id' => $id));
                
                if(empty($data['deal'])){
                    show_404();
                }else{
                    $slash = '/';
                    $dash = '-';
                    $str = $data['deal']->date_deal;
                    $data['date_deal'] = str_replace($dash, $slash , $str);
                    $cust_id = $data['deal']->cust_id;
                    $data['bank'] = $this->base_model->get_data('bank' , '*' , 'result' , array('customer_id'=> $cust_id));
                    $data['unit'] = $this->base_model->get_data('unit' , 'id , name' , 'result' , array('id < ' => 10));
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

      //----- photo -----//
      public function photo(){
          if(!$this->session->has_userdata('see_photo') or $this->session->userdata('see_photo') != TRUE){
              show_404();
          }
          $id = $this->uri->segment(3);
          if(isset($id) and is_numeric($id)){
            $header['title'] = 'عکس ها';
            $header['active'] = 'deal';
            $header['active_sub'] = 'deal_archive';
            $data['photo'] = $this->base_model->get_data('deal_pic' , '*' , 'result' , array('deal_id' => $id));
            $this->load->view('header' , $header);
            $this->load->view('deal/photo' , $data);
            $this->load->view('footer');
          }else{
              show_404();
          }

     }
     //----- photo -----//


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
                $log['user_id'] = $this->session->userdata('id');
                $log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
                $log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
                $log['activity_id'] = 17;
                $log['explain'] = " حساب جدید با مشخصات :  </br> شماره شبا : ".$data['shaba']." </br> نام بانک : ".$data['name']." </br> مقدار تعیین شده :  ".number_format($data['amount'])." </br> توضیحات :".$data['explain']."</br> افزوده شد ";
                $this->base_model->insert_data('log' , $log);
                if($res == FALSE){
                    $message['msg'][0] = 'مشکلی در ثبت اطلاعات رخ داده است . لطفا دوباره سعی کنید';
                    $message['msg'][1] = 'danger';
                    $this->session->set_flashdata($message);
                    redirect("deal/handle_profile/$id");
                }else{
                  $message['msg'][0] = 'اطلاعات حساب بانکی با موفقیت ثبت شد';
                  $message['msg'][1] = 'success';
                  $this->session->set_flashdata($message);
                  redirect("deal/handle_profile/$id");
                }
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
 if(isset($_POST['bank_id'])){ 
$id = $this->input->post('bank_id');
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
    $red_id = $this->uri->segment(3);
    $id = $this->uri->segment(4);
    if(isset($red_id) and isset($id) and is_numeric($red_id) and is_numeric($id)){
        if(isset($_POST['sub'])){
      $bank = $this->base_model->get_data('bank' , '*' , 'row' , array('id'=> $id));
      $data['explain'] = $this->input->post('bank_explain');
      $data['name'] = $this->input->post('name');          
      $data['shaba'] = $this->input->post('shaba');
      $data['amount'] = $this->input->post('amount');
      $data['rest'] = $data['amount'] - $bank->pay;
      $data['rest_handle'] = $bank->rest_handle + ($this->input->post('amount') - $bank->amount);

      $bb = $bank->id + 1000;
      $num_sh = " شماره شبا از ".$bank->shaba." به ".$data['shaba']." تغییر یافت "."</br>";
      $nam_ba = "  نام بانک از ".$bank->name." به ".$data['name']." تغییر یافت "."</br>";
      $amo = " مقدار تعیین شده از ".number_format($bank->amount)." به ".number_format($data['amount'])." تغییر یافت "."</br>";
      $res = $this->base_model->update_data('bank' , $data , array('id'=>$id));
      $date = $this->convertdate->convert(time());
      $log['user_id'] = $this->session->userdata('id');
      $log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
      $log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
      $log['activity_id'] = 18;
      $log['explain'] = "اطلاعات بانکی با مشخصات : </br> شناسه بانک :  ".$bb."</br>".$num_sh.$nam_ba.$amo.' توضیحات :'.$data['explain']."</br>"." ویرایش شد ";
      $this->base_model->insert_data('log' , $log);
      $message['msg'][0] = 'اطلاعات حساب بانکی با موفقیت ویرایش شد';
      $message['msg'][1] = 'success';
      $this->session->set_flashdata($message);
      redirect("deal/handle_profile/$red_id");
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
        if($data['active'] == 1){$txt = " را فعال کرد ";}else{$txt = ' را غیر فعال کرد ';}
        $date = $this->convertdate->convert(time());
        $log['user_id'] = $this->session->userdata('id');
        $log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
        $log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
        $log['activity_id'] = 19;
        $a = $id + 1000;
        $log['explain'] = " حساب بانکی با شناسه ".$a ." ".$txt;
        $this->base_model->insert_data('log' , $log);
        redirect("deal/handle_profile/$red_id");
    }else{
        show_404();
    }
}
//-----active ------//

// -----pay all-----//
public function pay_all(){
    if(!$this->session->has_userdata('pay_all') or $this->session->userdata('pay_all') != TRUE){
        show_404();
    }
    $cust_id = $this->uri->segment(3);
    $id = $this->uri->segment(4);
    if(isset($cust_id) and is_numeric($cust_id) and isset($id) and is_numeric($id)){
$handle_info = $this->base_model->get_data('handle' , '*' , 'row' , array('id'=>$id));
$buy = $this->base_model->get_data('deal' , 'id  , pay , rest' , 'result' ,array('customer_id'=> $handle_info->buy_id , 'type'=> 1 , 'rest !=' => 0) , NULL , NULL , array('id' , 'ASC'));
$sell = $this->base_model->get_data('deal' , 'id  , pay , rest' , 'result' ,array('customer_id'=> $handle_info->sell_id , 'type'=> 2 ,'rest !='=> 0) , NULL , NULL , array('id' , 'ASC'));
$bank_info = $this->base_model->get_data('bank' , 'pay , rest' , 'row' , array('id'=>$handle_info->bank_id));
if(empty($buy)){
    $message['msg'][0] = ' تمام معاملات مربوط به مشتری خرید پرداخت شده است  ';
    $message['msg'][1] = 'danger';
    $this->session->set_flashdata($message);
    redirect("deal/handle_profile/$cust_id");
}else if(empty($sell)){
    $message['msg'][0] = ' تمام معاملات مربوط به مشتری فروش پرداخت شده است  ';
    $message['msg'][1] = 'danger';
    $this->session->set_flashdata($message);
    redirect("deal/handle_profile/$cust_id");
}
$date = $this->convertdate->convert(time());
$push = $handle_info->handle_rest;
$deal = array();
// echo "پوش قبل بای ".$push."</br>";
$str = ' پرداختی های مربوط به مشتری خرید : ' ."</br>";
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
    $a = $buys->id + 100;
    $str .= ' شناسه معامله '. $a ." | مقدار پرداختی : ".number_format($ampay)."</br>";
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
// echo " پوش بعد بای ".$push."</br>";
if($push > 0){
    $state_sell = $this->base_model->get_data('deal' , 'id , count_money , volume , rest' , 'row' , array('customer_id'=>$handle_info->buy_id , 'type'=> 2 , 'state'=>0));
    if(!empty($state_sell)){
        $upsell['count_money'] = $state_sell->count_money + $push;
        $upsell['volume'] = $state_sell->volume + $push;
        $upsell['rest'] = $state_sell->rest + $push;
        $this->base_model->update_data('deal' , $upsell , array('id' => $state_sell->id));
        $a = $state_sell->id + 100;
        $str .= " مقدار ریالی معامله  ".$a."  به دلیل مازاد بودن حجم پرداختی به اندازه ". number_format($push)." افزایش یافت ";
    }else{
        $inssell['count_money'] = $push;
        $inssell['wage'] = 0;
        $inssell['convert'] = 1;
        $inssell['volume'] = $push;
        $inssell['pay'] = 0;
        $inssell['rest'] = $push;
        $inssell['explain'] = ' این فروش به دلیل بیشتر بودن مقدار پرداخت شده از مقدار باقی مانده معاملات  خرید مشتری خرید ثبت شده است ';
        $inssell['date_deal'] = $date['year']."-".$date['month_num']."-".$date['day'];
        $inssell['time_deal'] = $date['hour'].":".$date['minute'].":".$date['second'];
        $inssell['date_modified'] = ' ثبت نشده است ';
        $inssell['type'] = 2;
        $inssell['customer_id'] = $handle_info->buy_id;
        $inssell['money_id'] = 10;
        $inssell['state'] = 0;
        $this->base_model->insert_data('deal' , $inssell);
        $str .= " معامله ای از نوع فروش به دلیل مازاد بودن حجم هماهنگی به اندازه  ".number_format($push)." افزوده شد ";
    }    
    }


$str .= ' پرداختی های مربوط به مشتری فروش : ' ."</br>";
$push = $handle_info->handle_rest;
// echo " پوش قبل سل ".$push."</br>";
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
        $a = $sells->id + 100;
        $str .= ' شناسه معامله '. $a ." | مقدار پرداختی : ".number_format($ampay)."</br>";
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
// echo "پوش بعد سل ".$push."</br>";
    if($push > 0){
        $state_buy = $this->base_model->get_data('deal' , 'id , count_money , volume , rest' , 'row' , array('customer_id'=>$handle_info->sell_id , 'type'=> 1 , 'state'=>0));
        if(!empty($state_buy)){
            $upbuy['count_money'] = $state_buy->count_money + $push;
            $upbuy['volume'] = $state_buy->volume + $push;
            $upbuy['rest'] = $state_buy->rest + $push;
            $this->base_model->update_data('deal' , $upbuy , array('id' => $state_buy->id));
            $a = $state_buy->id + 100;
            $str .= " مقدار ریالی معامله  ".$a."  به دلیل مازاد بودن حجم پرداختی به اندازه ". number_format($push)." افزایش یافت ";
        }else{
            $insbuy['count_money'] = $push;
            $insbuy['wage'] = 0;
            $insbuy['convert'] = 1;
            $insbuy['volume'] = $push;
            $insbuy['pay'] = 0;
            $insbuy['rest'] = $push;
            $insbuy['explain'] = ' این خرید به دلیل بیشتر بودن مقدار پرداخت شده از مقدار باقی مانده معاملات فروش مشتری فروش ثبت شده است ';
            $insbuy['date_deal'] = $date['year']."-".$date['month_num']."-".$date['day'];
            $insbuy['time_deal'] = $date['hour'].":".$date['minute'].":".$date['second'];
            $insbuy['date_modified'] = ' ثبت نشده است ';
            $insbuy['type'] = 1;
            $insbuy['customer_id'] = $handle_info->sell_id;
            $insbuy['money_id'] = 10;
            $insbuy['state'] = 0;
            $this->base_model->insert_data('deal' , $insbuy);
            $str .= " معامله ای از نوع خرید به دلیل مازاد بودن حجم هماهنگی به اندازه  ".number_format($push)." افزوده شد ";
        }    
        }
$handle['handle_pay'] = $handle_info->handle_pay + $handle_info->handle_rest;
$handle['handle_rest'] = 0;
$handle['date_modified'] = $date['year']."-".$date['month_num']."-".$date['day']."</br>".$date['hour'].":".$date['minute'].":".$date['second'];
$bank['pay'] = $bank_info->pay + $handle_info->handle_rest;
$bank['rest'] = $bank_info->rest - $handle_info->handle_rest;
$history['date_pay'] = $date['year']."-".$date['month_num']."-".$date['day']." ".$date['hour'].":".$date['minute'].":".$date['second'];
$history['volume'] = $handle_info->handle_rest;
$history['active'] = 1;
$history['handle_id'] = $handle_info->id;
$this->base_model->insert_data('history' , $history);
$this->base_model->update_data('bank' , $bank , array('id'=>$handle_info->bank_id));
$this->base_model->update_data('handle' , $handle , array('id'=> $id));
$this->base_model->update_batch('deal' , $deal , 'id');
        $log['user_id'] = $this->session->userdata('id');
        $log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
        $log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
        $log['activity_id'] = 13;
        $log['explain'] = $str;
        $this->base_model->insert_data('log' , $log);
          $message['msg'][0] = 'پرداخت به صورت کامل انجام شد';
          $message['msg'][1] = 'success';
          $this->session->set_flashdata($message);
          redirect("deal/handle_profile/$cust_id");
// echo "<pre>";
// var_dump($handle_info);
// echo "<hr>";
// var_dump($handle);
// echo "<hr>";
// var_dump($buy);
// echo "<hr>";
// var_dump($sell);
// echo "<hr>";
// var_dump($deal);
// echo "<hr>";
// var_dump($bank_info);
// echo "<hr>";
// var_dump($bank);
// echo "</pre>";
    }else{
        show_404();
    }
}
//----pay all -----//
//----pay slice ----//
public function pay_slice(){
    if(!$this->session->has_userdata('pay_slice') or $this->session->userdata('pay_slice') != TRUE){
        show_404();
    }
    $cust_id = $this->uri->segment(3);
    $id = $this->uri->segment(4);
    if(isset($cust_id) and is_numeric($cust_id) and isset($id) and is_numeric($id) and isset($_POST['sub']) ){
        $handle_info = $this->base_model->get_data('handle' , '*' , 'row' , array('id'=>$id));
        $buy = $this->base_model->get_data('deal' , 'id  , pay , rest' , 'result' ,array('customer_id'=> $handle_info->buy_id , 'type'=> 1 , 'rest !=' => 0) , NULL , NULL , array('id' , 'ASC'));
        $sell = $this->base_model->get_data('deal' , 'id  , pay , rest' , 'result' ,array('customer_id'=> $handle_info->sell_id , 'type'=> 2 ,'rest !='=> 0) , NULL , NULL , array('id' , 'ASC'));
        $bank_info = $this->base_model->get_data('bank' , 'pay , rest' , 'row' , array('id'=>$handle_info->bank_id));
        if(empty($buy)){
            $message['msg'][0] = ' تمام معاملات مربوط به مشتری خرید پرداخت شده است  ';
            $message['msg'][1] = 'danger';
            $this->session->set_flashdata($message);
            redirect("deal/handle_profile/$cust_id");
        }else if(empty($sell)){
            $message['msg'][0] = ' تمام معاملات مربوط به مشتری فروش پرداخت شده است  ';
            $message['msg'][1] = 'danger';
            $this->session->set_flashdata($message);
            redirect("deal/handle_profile/$cust_id");
        } 
        $date = $this->convertdate->convert(time());
        $push = $this->input->post('slice');
        $deal = array();
        $str = ' پرداختی های مربوط به مشتری خرید : ' ."</br>";
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
            $a = $buys->id + 100;
            $str .= ' شناسه معامله '. $a ." | مقدار پرداختی : ".number_format($ampay)."</br>";
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
if($push > 0){
    $state_sell = $this->base_model->get_data('deal' , 'id , count_money , volume , rest' , 'row' , array('customer_id'=>$handle_info->buy_id , 'type'=> 2 , 'state'=>0));
    if(!empty($state_sell)){
        $upsell['count_money'] = $state_sell->count_money + $push;
        $upsell['volume'] = $state_sell->volume + $push;
        $upsell['rest'] = $state_sell->rest + $push;
        $this->base_model->update_data('deal' , $upsell , array('id' => $state_sell->id));
        $a = $state_sell->id + 100;
        $str .= " مقدار ریالی معامله  ".$a."  به دلیل مازاد بودن حجم پرداختی به اندازه ". number_format($push)." افزایش یافت ";
    }else{
        $inssell['count_money'] = $push;
        $inssell['wage'] = 0;
        $inssell['convert'] = 1;
        $inssell['volume'] = $push;
        $inssell['pay'] = 0;
        $inssell['rest'] = $push;
        $inssell['explain'] = ' این فروش به دلیل بیشتر بودن مقدار پرداخت شده از مقدار باقی مانده معاملات  خرید مشتری خرید ثبت شده است ';
        $inssell['date_deal'] = $date['year']."-".$date['month_num']."-".$date['day'];
        $inssell['time_deal'] = $date['hour'].":".$date['minute'].":".$date['second'];
        $inssell['date_modified'] = ' ثبت نشده است ';
        $inssell['type'] = 2;
        $inssell['customer_id'] = $handle_info->buy_id;
        $inssell['money_id'] = 10;
        $inssell['state'] = 0;
        $this->base_model->insert_data('deal' , $inssell);
        $str .= " معامله ای از نوع فروش به دلیل مازاد بودن حجم هماهنگی به اندازه  ".number_format($push)." افزوده شد ";
    }    
    }

        $str .= ' پرداختی های مربوط به مشتری فروش : ' ."</br>";
        $push = $this->input->post('slice');
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
                $a = $sells->id + 100;
                $str .= ' شناسه معامله '. $a ." | مقدار پرداختی : ".number_format($ampay)."</br>";
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
        if($push > 0){
            $state_buy = $this->base_model->get_data('deal' , 'id , count_money , volume , rest' , 'row' , array('customer_id'=>$handle_info->sell_id , 'type'=> 1 , 'state'=>0));
            if(!empty($state_buy)){
                $upbuy['count_money'] = $state_buy->count_money + $push;
                $upbuy['volume'] = $state_buy->volume + $push;
                $upbuy['rest'] = $state_buy->rest + $push;
                $this->base_model->update_data('deal' , $upbuy , array('id' => $state_buy->id));
                $a = $state_buy->id + 100;
                $str .= " مقدار ریالی معامله  ".$a."  به دلیل مازاد بودن حجم پرداختی به اندازه ". number_format($push)." افزایش یافت ";
            }else{
                $insbuy['count_money'] = $push;
                $insbuy['wage'] = 0;
                $insbuy['convert'] = 1;
                $insbuy['volume'] = $push;
                $insbuy['pay'] = 0;
                $insbuy['rest'] = $push;
                $insbuy['explain'] = ' این خرید به دلیل بیشتر بودن مقدار پرداخت شده از مقدار باقی مانده معاملات فروش مشتری فروش ثبت شده است ';
                $insbuy['date_deal'] = $date['year']."-".$date['month_num']."-".$date['day'];
                $insbuy['time_deal'] = $date['hour'].":".$date['minute'].":".$date['second'];
                $insbuy['date_modified'] = ' ثبت نشده است ';
                $insbuy['type'] = 1;
                $insbuy['customer_id'] = $handle_info->sell_id;
                $insbuy['money_id'] = 10;
                $insbuy['state'] = 0;
                $this->base_model->insert_data('deal' , $insbuy);
                $str .= " معامله ای از نوع خرید به دلیل مازاد بودن حجم هماهنگی به اندازه  ".number_format($push)." افزوده شد ";
            }    
            }
        
        $handle['handle_pay'] = $handle_info->handle_pay + $this->input->post('slice');
        $handle['handle_rest'] = $handle_info->handle_rest - $this->input->post('slice');
        $handle['date_modified'] = $date['year']."-".$date['month_num']."-".$date['day']."</br> ".$date['hour'].":".$date['minute'].":".$date['second'];
        $bank['pay'] = $bank_info->pay + $this->input->post('slice');
        $bank['rest'] = $bank_info->rest - $this->input->post('slice');
        $history['date_pay'] = $date['year']."-".$date['month_num']."-".$date['day']." ".$date['hour'].":".$date['minute'].":".$date['second'];
        $history['volume'] = $this->input->post('slice');
        $history['active'] = 1;
        $history['handle_id'] = $handle_info->id;
        $this->base_model->insert_data('history' , $history);
        $this->base_model->update_data('bank' , $bank , array('id'=>$handle_info->bank_id));
        $this->base_model->update_data('handle' , $handle , array('id'=> $id));
        $this->base_model->update_batch('deal' , $deal , 'id');
        $log['user_id'] = $this->session->userdata('id');
        $log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
        $log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
        $log['activity_id'] = 14;
        $log['explain'] = $str;
        $this->base_model->insert_data('log' , $log);
          $message['msg'][0] = 'پرداخت به صورت جزیی انجام شد';
          $message['msg'][1] = 'success';
          $this->session->set_flashdata($message);
          redirect("deal/handle_profile/$cust_id");

// echo "<pre>";
// var_dump($handle_info);
// echo "<hr>";
// var_dump($handle);
// echo "<hr>";
// var_dump($buy);
// echo "<hr>";
// var_dump($sell);
// echo "<hr>";
// var_dump($deal);
// echo "<hr>";
// var_dump($bank_info);
// echo "<hr>";
// var_dump($bank);
// echo "</pre>";
    }else{
        show_404();
    }  
}
//-----pay slice-----//

// ----history------//
public function get_history(){
    if(!$this->session->has_userdata('restore') or $this->session->userdata('restore') != TRUE){
        show_404();
    }
    if(isset($_POST['handle_id'])){
       $handle_id = $this->input->post('handle_id');
       $history = $this->base_model->get_data('history' , '*' , 'result' , array('handle_id'=> $handle_id , 'active'=> 1));
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
     $history = $this->base_model->get_data('history' , 'volume , handle_id' , 'row' , array('id'=> $id , 'active'=> 1));
     if(empty($history)){
         show_404();
     }
     $handle_info = $this->base_model->get_data('handle' , '*' , 'row' , array('id'=>$history->handle_id));
     $buy = $this->base_model->get_data('deal' , 'id  , pay , rest' , 'result' ,array('customer_id'=> $handle_info->buy_id , 'type'=> 1 , 'pay !=' => 0) , NULL , NULL , array('id' , 'DESC'));
     $sell = $this->base_model->get_data('deal' , 'id  , pay , rest' , 'result' ,array('customer_id'=> $handle_info->sell_id , 'type'=> 2 ,'pay !='=> 0) , NULL , NULL , array('id' , 'DESC'));
     $bank_info = $this->base_model->get_data('bank' , 'pay , rest' , 'row' , array('id'=>$handle_info->bank_id));
    $date = $this->convertdate->convert(time());
    $push = $history->volume;
    $deal = array();
    $str = ' بازگشتی های مربوط به مشتری خرید : ' ."</br>";
    if(!empty($buy)){
        foreach($buy as $buys){
            if($push >= $buys->pay){
                $pay = 0;
                $rest = $buys->rest + $buys->pay;
                $ampay = $buys->pay;
            }else{
                $pay = $buys->pay - $push;
                $rest = $buys->rest + $push;
                $ampay = $push; 
            }
            $a = $buys->id + 100;
            $str .= ' شناسه معامله '. $a ." | مقدار بازگشتی : ".number_format($ampay)."</br>";
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
    if($push > 0){
        $state_sell = $this->base_model->get_data('deal' , 'id , count_money , volume , rest' , 'row' , array('customer_id'=>$handle_info->buy_id , 'type'=> 2 , 'state'=>0));
        if(!empty($state_sell)){
            $upsell['count_money'] = $state_sell->count_money - $push;
            $upsell['volume'] = $state_sell->volume - $push;
            $upsell['rest'] = $state_sell->rest - $push;
            $this->base_model->update_data('deal' , $upsell , array('id' => $state_sell->id));
            $a = $state_sell->id + 100;
            $str .= " مقدار ریالی معامله  ".$a."  به دلیل مازاد بودن حجم بازگشتی به اندازه ". number_format($push)." کاهش یافت ";
        } }
    $str .= ' بازگشتی های مربوط به مشتری فروش : ' ."</br>";
    $push = $history->volume;
    if(!empty($sell)){
        foreach($sell as $sells){
            if($push >= $sells->pay){
                $pay = 0;
                $rest = $sells->rest + $sells->pay;
                $ampay = $sells->pay;
            }else{
                $pay = $sells->pay - $push;
                $rest = $sells->rest + $push;
                $ampay = $push; 
            }
            $a = $sells->id + 100;
            $str .= ' شناسه معامله '. $a ." | مقدار بازگشتی : ".number_format($ampay)."</br>";
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
        $state_buy = $this->base_model->get_data('deal' , 'id , count_money , volume , rest' , 'row' , array('customer_id'=>$handle_info->sell_id , 'type'=> 1 , 'state'=>0));
        if(!empty($state_buy)){
            $upbuy['count_money'] = $state_buy->count_money - $push;
            $upbuy['volume'] = $state_buy->volume - $push;
            $upbuy['rest'] = $state_buy->rest - $push;
            $this->base_model->update_data('deal' , $upbuy , array('id' => $state_buy->id));
            $a = $state_buy->id + 100;
            $str .= " مقدار ریالی معامله  ".$a."  به دلیل مازاد بودن حجم بازگشتی به اندازه ". number_format($push)." کاهش یافت ";
        }  }
   
    $handle['handle_pay'] = $handle_info->handle_pay - $history->volume;
    $handle['handle_rest'] = $handle_info->handle_rest + $history->volume;
    $handle['date_modified'] = $date['year']."-".$date['month_num']."-".$date['day']."</br>".$date['hour'].":".$date['minute'].":".$date['second'];
    $bank['pay'] = $bank_info->pay - $history->volume;
    $bank['rest'] = $bank_info->rest + $history->volume;
    $log['user_id'] = $this->session->userdata('id');
    $log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
    $log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
    $log['activity_id'] = 15;
    $log['explain'] = $str;
    $his['active'] = 0;
    
    $this->base_model->update_data('bank' , $bank , array('id'=>$handle_info->bank_id));
    $this->base_model->update_data('handle' , $handle , array('id'=> $history->handle_id));
    if(!empty($deal)){
        $this->base_model->update_batch('deal' , $deal , 'id');
    }
    $this->base_model->update_data('history' , $his , array('id'=> $id)); 
    $this->base_model->insert_data('log' , $log);
      $message['msg'][0] = 'بازگشت پرداخت به صورت کامل انجام شد ';
      $message['msg'][1] = 'success';
      $this->session->set_flashdata($message);
      redirect("deal/handle_profile/$cust_id"); 
// echo "<pre>";
// var_dump($history);
// echo '<hr>';
// var_dump($handle_info);
// echo "<hr>";
// var_dump($handle);
// echo "<hr>";
// var_dump($buy);
// echo "<hr>";
// var_dump($sell);
// echo "<hr>";
// var_dump($deal);
// echo "<hr>";
// var_dump($bank_info);
// echo "<hr>";
// var_dump($bank);
// echo "</pre>";
    }else{
        show_404();
    }
}
// -----history------//
    // ----delete handle---//
    public function delete_handle(){
        $id = $this->uri->segment(3);
        if(isset($id) and is_numeric($id)){
           $handle = $this->base_model->get_data('deal_handle' , 'volume_handle , deal_id , handle_pay' , 'row' , array('id' => $id));
           if($handle->handle_pay != 0 or sizeof($handle) == 0){
               show_404();
           }else{
            $a = $handle->deal_id + 100 ;
            $explain = 'هماهنگی با حجم : '.number_format($handle->volume_handle)." مربوطه به معامله با شناسه ".$a . "حذف شد";
            $date = $this->convertdate->convert(time());
            $log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
            $log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
            $log['user_id'] = $this->session->userdata('id');
            $log['activity_id'] = 16;
            $log['explain'] = $explain;
            $back['explain'] =  $explain;
            $back['time_backup'] = $log['time_log'];
            $back['date_backup'] = $log['date_log'];
            $res = $this->base_model->delete_data('deal_handle' , array('id'=>$id));
            $this->base_model->insert_data('log' , $log);
            $this->base_model->insert_data('backup' , $back);
            $red_id = $this->uri->segment(4);
            if($this->uri->segment(5) == 'group'){
                $red = 'handle_profile';
            }else{
                $red = "handle";
            }
            if($res == FALSE){
                $message['msg'][0] = 'متاسفانه مشکلی در روند عملیات رخ داده است . لطفا دوباره سعی کنید';
                $message['msg'][1] = 'danger';
                $this->session->set_flashdata($message);
                redirect("deal/$red/$red_id");
            }else{
                $message['msg'][0] = 'هماهنگی با موفقیت حذف شد';
                $message['msg'][1] = 'success';
                $this->session->set_flashdata($message);
                redirect("deal/$red/$red_id");
            }
           }
        }else{
            show_404();
        }
    }
    // ----delete handle---//
 
    public function handle_profile(){
        $id = $this->uri->segment(3);
        if(isset($id) and is_numeric($id)){
            if(isset($_POST['sub'])){
                if(!$this->session->has_userdata('add_handle') or $this->session->userdata('add_handle') != TRUE){
                    show_404();
                }
                if($_POST['bank_id'] == 0){
                    $message['msg'][0] = 'لطفا شماره حساب را از لیست مربوطه انتخاب کنید . در صورت موجود نبودن شماره حساب لطفا اقدام به افزودن شماره حساب کنید' ;
                    $message['msg'][1] = 'danger';
                    $this->session->set_flashdata($message);
                    redirect("deal/handle_profile/$id");
                 }
$buy_name = trim($this->input->post('customer_buy') , ' ');
$sell_name = trim($this->input->post('customer_sell') , ' ');                 
$buy_info = $this->base_model->get_data_join('deal' , 'customer' , 'deal.id as deal_id , customer.id' , 'customer.id = deal.customer_id' , 'row' , array('customer.fullname'=>$buy_name ,'customer.id'=>$id , 'deal.type' => 1));
$sell_info = $this->base_model->get_data_join('deal' , 'customer' , 'deal.id as deal_id , customer.id' , 'customer.id = deal.customer_id' , 'row' , array('customer.fullname'=>$sell_name , 'deal.type'=> 2));
if(empty($buy_info)){
    $message['msg'][0] = 'توجه داشته باشید که مشتری خرید باید در سامانه ثبت شده باشد و تعداد خرید های انجام شده با شخص حداقل یک باشد' ;
    $message['msg'][1] = 'danger';
    $this->session->set_flashdata($message);
    redirect("deal/handle_profile/$id");
}else if(empty($sell_info)){
    $message['msg'][0] = 'توجه داشته باشید که مشتری فروش باید در سامانه ثبت شده باشد و تعداد فروش های انجام شده با شخص حداقل یک باشد' ;
    $message['msg'][1] = 'danger';
    $this->session->set_flashdata($message);
    redirect("deal/handle_profile/$id");
}
$date = $this->convertdate->convert(time());
$data['volume_handle'] = $this->input->post('volume_handle');
$data['handle_pay'] = 0;
$data['handle_rest'] = $this->input->post('volume_handle');
$data['date_handle'] = $date['year']."-".$date['month_num']."-".$date['day'];
$data['time_handle'] = $date['hour'].":".$date['minute'].":".$date['second'];
$data['date_modified'] = ' ثبت نشده است ';
$data['buy_id'] = $buy_info->id;
$data['sell_id'] = $sell_info->id;
$data['bank_id'] = $this->input->post('bank_id');
$rest = $this->base_model->get_data('bank' , 'rest_handle' , 'row' , array('id'=> $data['bank_id']));
$bank['rest_handle'] = $rest->rest_handle - $data['volume_handle'];
$str = ' هماهنگی با مشخصات : </br> نام مشتری خرید :'.$buy_name."</br> نام مشتری فروش : ".$sell_name."</br> مبلغ هماهنگی : ".number_format($data['volume_handle'])."</br> ثبت شد";
                $log['user_id'] = $this->session->userdata('id');
                $log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
                $log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
                $log['activity_id'] = 12;
                $log['explain'] = $str;
$this->base_model->insert_data('log' , $log);
$this->base_model->insert_data('handle' , $data);
$this->base_model->update_data('bank' , $bank , array('id'=> $data['bank_id']));

                    $message['msg'][0] = 'اطلاعات هماهنگی با موفقیت ثبت شد';
                    $message['msg'][1] = 'success';
                    $this->session->set_flashdata($message);
                    redirect("deal/handle_profile/$id");
            }else{
                if(!$this->session->has_userdata('see_handle') or $this->session->userdata('see_handle') != TRUE){
                    show_404();
                }
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
            $data['deal'] = $this->base_model->get_data_join('deal' ,'customer', 'deal.* , customer.fullname ,unit.name' , 'deal.customer_id = customer.id' ,'result'  , array('deal.customer_id'=> $id), $config['per_page'] , $page , array('deal.id' , 'DESC') , array('unit','deal.money_id = unit.id'));
            $data['page'] = $this->pagination->create_links();
            $data['bank'] = $this->base_model->get_data('bank' ,'*' ,'result' ,array('customer_id' => $id));
            $data['select'] = $this->base_model->get_data('bank' , 'id , explain , rest , rest_handle' , 'result' , array('customer_id' => $id , 'active'=> 1));
            $data['handle'] = $this->base_model->get_data_join('handle' , 'bank' , 'handle.* , customer.fullname , bank.explain' , 'handle.bank_id = bank.id' , 'result' , array('handle.buy_id'=>$id) , NULL , NULL , array('handle.id' , 'DESC'),array('customer' , 'handle.sell_id = customer.id'));  
            $data['customer'] = $this->base_model->get_data('customer' , 'fullname , id' , 'result');
            $want = $this->base_model->get_data('deal','customer_id , sum(deal.rest) as want' , 'result' , array('type'=> 1), NULL , NULL , NULL , 'customer_id');
            $give = $this->base_model->get_data('deal','customer_id , sum(deal.rest) as give' , 'result' , array('type'=> 2), NULL , NULL , NULL , 'customer_id');
            $want_rial = array();
            $give_rial = array();
            foreach($data['customer'] as $key => $customers){
                foreach($want as $wants ){
                    if($customers->id  == $wants->customer_id){
                        $want_rial[$key] = $wants->want;
                        break;
                    }else{
                        $want_rial[$key] = 0;
                    }
                }
            }
            foreach($data['customer'] as $key => $customers){
                foreach($give as $sells ){
                    if($customers->id  == $sells->customer_id){
                        $give_rial[$key] = $sells->give;
                        break;
                    }else{
                        $give_rial[$key] = 0;
                    }
                }
            }
            $data['want_rial'] = $want_rial;
            $data['give_rial'] = $give_rial;
            $this->load->view('header' , $header);
            $this->load->view('deal/handle_profile' , $data);
            $this->load->view('footer');
         
        }
        }else{
            show_404();
        }

    }
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
$date = $this->convertdate->convert(time());
$data['volume_handle'] = $this->input->post('volume_handle');
$data['handle_pay'] = 0;
$data['handle_rest'] = $this->input->post('volume_handle');
$data['date_handle'] = $date['year']."-".$date['month_num']."-".$date['day'];
$data['time_handle'] = $date['hour'].":".$date['minute'].":".$date['second'];
$data['date_modified'] = ' ثبت نشده است ';
$data['buy_id'] = $buy_info->id;
$data['sell_id'] = $sell_info->id;
$data['bank_id'] = $this->input->post('bank_id');
$rest = $this->base_model->get_data('bank' , 'rest_handle' , 'row' , array('id'=> $data['bank_id']));
$bank['rest_handle'] = $rest->rest_handle - $data['volume_handle'];
$str = ' هماهنگی با مشخصات : </br> نام مشتری خرید :'.$buy_name."</br> نام مشتری فروش : ".$sell_name."</br> مبلغ هماهنگی : ".number_format($data['volume_handle'])."</br> ثبت شد";
            $log['user_id'] = $this->session->userdata('id');
            $log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
            $log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
            $log['activity_id'] = 12;
            $log['explain'] = $str;
$this->base_model->insert_data('log' , $log);
$this->base_model->insert_data('handle' , $data);
$this->base_model->update_data('bank' , $bank , array('id'=> $data['bank_id']));

                $message['msg'][0] = 'اطلاعات هماهنگی با موفقیت ثبت شد';
                $message['msg'][1] = 'success';
                $this->session->set_flashdata($message);
                redirect("deal/worksheet");

        }else{
            $rows_buy = $this->base_model->get_data('deal' , 'count(id) as cust_id' , 'result' , array('type'=>1) , NULL , NULL , NULL , 'customer_id');
            $rows_sell = $this->base_model->get_data('deal' , 'count(id) as cust_id' , 'result' , array('type'=>2) , NULL , NULL , NULL , 'customer_id');
            $data['buy'] = $this->base_model->run_query("SELECT d.customer_id, SUM(d.rest) AS rest, SUM(d.volume) AS volume, max(h.volume_handle) AS handle , c.fullname  FROM  deltasanat.deal d LEFT JOIN (SELECT buy_id, SUM(volume_handle) AS volume_handle FROM deltasanat.handle GROUP BY buy_id) h ON h.buy_id = d.customer_id inner join deltasanat.customer c on c.id = d.customer_id where d.type = 1 GROUP BY d.customer_id ORDER BY d.id DESC LIMIT 0 , 10");
            $data['sell'] = $this->base_model->run_query("SELECT d.customer_id, SUM(d.rest) AS rest, SUM(d.volume) AS volume, max(h.volume_handle) AS handle , c.fullname  FROM  deltasanat.deal d LEFT JOIN (SELECT sell_id, SUM(volume_handle) AS volume_handle FROM deltasanat.handle GROUP BY sell_id) h ON h.sell_id = d.customer_id inner join deltasanat.customer c on c.id = d.customer_id where d.type = 2 GROUP BY d.customer_id ORDER BY d.id DESC LIMIT 0 , 10");
                        $data['rows_buy'] = sizeof($rows_buy);
                        $data['rows_sell'] = sizeof($rows_sell);
                        $data['cust_buy'] = $this->base_model->get_data_join('customer' , 'deal' , 'fullname' , 'deal.customer_id = customer.id', 'result' , array('deal.type'=>1) , NULL , NULL , NULL , NULL, NULL , NULL , 'customer.id');
                        $data['cust_sell'] = $this->base_model->get_data_join('customer' , 'deal' , 'fullname' , 'deal.customer_id = customer.id', 'result' , array('deal.type'=>2), NULL , NULL , NULL , NULL, NULL , NULL , 'customer.id');
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
            $buy = $this->base_model->run_query("SELECT d.customer_id, SUM(d.rest) AS rest, SUM(d.volume) AS volume, max(h.volume_handle) AS handle , c.fullname  FROM  deltasanat.deal d LEFT JOIN (SELECT buy_id, SUM(volume_handle) AS volume_handle FROM deltasanat.handle GROUP BY buy_id) h ON h.buy_id = d.customer_id inner join deltasanat.customer c on c.id = d.customer_id where d.type = 1 GROUP BY d.customer_id ORDER BY d.id DESC LIMIT $offset , 10");
            echo json_encode($buy);
        }else{
            show_404();
        }
    }
    public function page_sell(){
        if(isset($_POST['offset'])){
            $offset = $this->input->post('offset');
            $sell =  $this->base_model->run_query("SELECT d.customer_id, SUM(d.rest) AS rest, SUM(d.volume) AS volume, max(h.volume_handle) AS handle , c.fullname  FROM  deltasanat.deal d LEFT JOIN (SELECT sell_id, SUM(volume_handle) AS volume_handle FROM deltasanat.handle GROUP BY sell_id) h ON h.sell_id = d.customer_id inner join deltasanat.customer c on c.id = d.customer_id where d.type = 2 GROUP BY d.customer_id ORDER BY d.id DESC LIMIT $offset , 10");
            echo json_encode($sell);
        }else{
            show_404();
        }
    }
    public function get_customer(){
        $name = $this->input->post('name');
        $name = trim($name);
        $type = $this->input->post('type');
        if($type == 'buy'){
           $data['cust'] = $this->base_model->run_query("SELECT d.customer_id, SUM(d.rest) AS rest, SUM(d.volume) AS volume, max(h.volume_handle) AS handle , c.fullname  FROM  deltasanat.deal d LEFT JOIN (SELECT buy_id, SUM(volume_handle) AS volume_handle FROM deltasanat.handle GROUP BY buy_id) h ON h.buy_id = d.customer_id inner join deltasanat.customer c on c.id = d.customer_id where d.type = 1 AND c.fullname = '$name' GROUP BY d.customer_id");
           $cust_id = $data['cust'][0]->customer_id;
           $data['bank'] = $this->base_model->get_data('bank' , 'id , rest_handle , rest , explain' , 'result' , array('customer_id'=>$cust_id)); 
        }else{
           $data['cust'] = $this->base_model->run_query("SELECT d.customer_id, SUM(d.rest) AS rest, SUM(d.volume) AS volume, max(h.volume_handle) AS handle , c.fullname  FROM  deltasanat.deal d LEFT JOIN (SELECT sell_id, SUM(volume_handle) AS volume_handle FROM deltasanat.handle GROUP BY sell_id) h ON h.sell_id = d.customer_id inner join deltasanat.customer c on c.id = d.customer_id where d.type = 2 AND c.fullname = '$name' GROUP BY d.customer_id");
        }
        // echo "<pre>";
        // var_dump($data);
        // echo "</pre>";
        echo json_encode($data);
    }
}

/* End of file Controllername.php */

?>