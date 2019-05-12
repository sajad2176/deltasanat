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
    $where = array();
    $total_where = array();
    $s_date = $this->input->get('start_date');
    $e_date = $this->input->get('end_date');
    if($e_date != '' && $s_date != ''){
        $persian_num = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
        $latin_num = range(0, 9);
        $slash = '/';
        $dash = '-';
        $start = str_replace($persian_num, $latin_num, $s_date);
        $start = str_replace($slash, $dash, $start);
        $end = str_replace($persian_num, $latin_num, $e_date);
        $end = str_replace($slash, $dash, $end); 
        $date_start = substr($start , 0 , 10);
        $date_end = substr($end , 0 , 10);
        $between = "deal.date_deal BETWEEN '$date_start' AND '$date_end'";
    }else{
        $between = NULL;
    }
    $fullname = trim($this->input->get('fullname') , ' ');
    if($fullname != ''){
        $get = $this->base_model->get_data('customer' , 'id' , 'row' , array('fullname'=> $fullname));
        if(!empty($get)){
            $where['deal.customer_id'] = $get->id;
            $total_where['customer_id'] = $get->id;
        }
    }
    $money_id = $this->input->get('money_id');
    $type = $this->input->get('type');
    if($money_id != 0){
        $where['deal.money_id'] = $money_id;
        $total_where['money_id'] = $money_id;
    }
    if($type != 0){
        $where['deal.type'] = $type;
        $total_where['type'] = $type;
    }
    $data['m'] = $money_id; $data['t'] = $type;
    $total_rows = $this->base_model->get_count_between("deal" , $total_where , $between);
    if($this->input->get('per_page')){
        $offset = $this->input->get('per_page');
    }else{
        $offset = 0;
    }
    $limit = 10;
    $config['base_url'] = base_url('deal/archive?fullname='.$fullname.'&money_id='.$money_id.'&type='.$type);
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
$data['deal'] = $this->base_model->get_data_join('deal' ,'customer', 'deal.* , customer.fullname , customer.id as cust_id , unit.name' , 'deal.customer_id = customer.id' ,'result'  , $where , $limit , $offset , array('deal.id' , 'DESC') , array('unit','deal.money_id = unit.id') , NULL , $between);
$data['page'] = $this->pagination->create_links();
$data['count'] = $config['total_rows'];
$date = $this->convertdate->convert(time());
$data['date'] = $date['year']."/".$date['month_num']."/".$date['day'] . " ".$date['hour'].":".$date['minute'].":".$date['second'];
$data['unit'] = $this->base_model->get_data('unit' , 'id , name' , 'result');
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
         $deal = $this->base_model->get_data_join('deal' , 'customer' ,'deal.* , customer.fullname , unit.name','deal.customer_id = customer.id', 'row' , array('deal.id' => $id) , NULL , NULL , NULL , array('unit' , 'deal.money_id = unit.id'));
         if($deal->volume_pay != 0 or empty($deal)){
             show_404();
         }else{
            $money = $deal->name;
            $a = $id +100;
            $explain = ' شناسه معامله :  '. $a . " | نام مشتری : ".$deal->fullname . " | نام ارز : ".$money ." | تعداد ارز : ".number_format($deal->count_money)." | کارمزد : " . number_format($deal->wage)." | نرخ تبدیل : ".number_format($deal->convert)." | حجم معامله : ".number_format($deal->volume)."حذف شد </br>";
//check

//currency
$this->db->trans_begin();
$am = $deal->count_money + $deal->wage;
if($deal->money_id == 10){
//rial
$change_other = TRUE;
if($deal->type == 1){
    $change_rial = $this->base_model->set('amount' , 'amount-'.$deal->volume , array('id'=>10) , 'unit');
    $text = 'کاهش یافت';
}else{
    $change_rial = $this->base_model->set('amount' , 'amount+'.$deal->volume , array('id'=>10) , 'unit');
    $text = 'افزایش یافت';
}
$explain .= "</br>"."مقدار ریال به اندازه : ".number_format($deal->volume)." ".$text;
//rial
}else{
    if($deal->type == 1){
//other
        $change_rial = $this->base_model->set('amount' , 'amount+'.$deal->volume , array('id'=>10) , 'unit');
        $change_other = $this->base_model->set('amount' , 'amount-'.$am , array('id'=>$deal->money_id) , 'unit');
        $text = " کاهش یافت ";
        $text2 = 'افزایش یافت';
        }else{
        $change_rial = $this->base_model->set('amount' , 'amount-'.$deal->volume , array('id'=>10) , 'unit');
        $change_other = $this->base_model->set('amount' , 'amount+'.$am , array('id'=>$deal->money_id) , 'unit');   
        $text = ' افزایش یافت ';
        $text2 = 'کاهش یافت ';
        }
$explain .= "</br>"." مقدار ارز ".$money." به اندازه ".number_format($am) . $text ." | مقدار ریال به اندازه ".number_format($deal->volume). " ".$text2;
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
if($this->uri->segment(5) == 'group'){
    $red = 'handle_profile';
    $red_id = $this->uri->segment(4);
}else{
    $red = 'archive';
    $red_id = '';
}
if($res == FALSE or $change_rial == FALSE or $change_other == FALSE){
    $this->db->trans_rollback();
    $message['msg'][0] = 'مشکلی در حذف معامله رخ داده است . لطفا دوباره سعی کنید';
    $message['msg'][1] = 'danger';
    $this->session->set_flashdata($message);
    redirect("deal/$red/$red_id");
}else{
    $this->db->trans_commit();
}
$this->base_model->insert_data('log' , $log);
$this->base_model->insert_data('backup' , $back);
    $message['msg'][0] = ' معامله با موفقیت حذف شد  ';
    $message['msg'][1] = 'success';
    $this->session->set_flashdata($message);
    redirect("deal/$red/$red_id");
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
           $change_other = $deal['count_money'] + $deal['wage'];
           $this->db->trans_begin();
           if($deal['type'] == 1){
              $rial = $this->base_model->set('amount' , 'amount-'.$deal['volume'] , array('id'=>10) , 'unit');
              $other = $this->base_model->set('amount' , 'amount+'.$change_other , array('id'=>$deal['money_id']) , 'unit');
              $act = 9;
              $text2 = ' کاهش یافت ';
              $text = " افزایش یافت ";
           }else{
            $rial = $this->base_model->set('amount' , 'amount+'.$deal['volume'] , array('id'=>10) , 'unit');
            $other = $this->base_model->set('amount' , 'amount-'.$change_other , array('id'=>$deal['money_id']) , 'unit'); 
               $act = 10;
               $text2 = 'افزایش یافت ';
               $text = " کاهش یافت ";
           }
           //currency
           $deal_id = $this->base_model->insert_data('deal' , $deal);
           if($deal_id == FALSE or $rial == FALSE or $other == FALSE){
            $this->db->trans_rollback();
            $message['msg'][0] = 'مشکلی در ثبت اطلاعات رخ داده است. لطفا دوباره سعی کنید';
            $message['msg'][1] = 'danger';
            $this->session->set_flashdata($message);
            redirect("deal/$page");
        }else{
            $this->db->trans_commit();
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
        if(isset($bank) and !empty($bank)){
            $res_bank = $this->base_model->insert_batch('bank' , $bank);
            if($res_bank == FALSE){
                $message['msg'][0] = 'معامله با موفقیت ثبت شد ولی متاسفانه در ثبت اطلاعات بانکی و ارسال عکس ها مشکلی پیش آمده است .';
                $message['msg'][1] = 'danger';
                $this->session->set_flashdata($message);
                redirect("deal/$page");
            }
        }

        //log
        $m = $deal['money_id'];
        if($m == 1){$money = 'دلار';}elseif($m == 2){$money = 'یورو';}elseif($m == 3){$money = 'یوان';}else{$money = 'درهم';}
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
        $fullname = trim($this->input->post('text_search') , ' ');
        $cust_id = $this->base_model->get_data('customer' , 'id' , 'row' , array('fullname' => $fullname));
        if(empty($cust_id)){
            echo json_encode(false);
        }else{
            $date = $this->convertdate->convert(time());
            $today = $date['year']."-".$date['month_num']."-".$date['day'];
            $id = $cust_id->id;
            $buy = $this->base_model->get_data('deal' , 'money_id , rest , convert' , 'result' , array('type'=> 1 , 'customer_id'=> $id , 'date_deal'=>$today));
            $sell = $this->base_model->get_data('deal' , 'money_id , rest , convert' , 'result' , array('type'=> 2 , 'customer_id'=> $id, 'date_deal'=> $today));
            $give = $this->base_model->get_data('deal' , 'sum(rest) as give' , 'row' , array('type'=> 1 , 'customer_id'=> $id));
            $want = $this->base_model->get_data('deal' , 'sum(rest) as want' , 'row' , array('type'=> 2 , 'customer_id'=> $id));
            if(empty($buy)){
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
            if(empty($sell)){
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
             
             $base_count = $base->count_money + $base->wage;
             $send_count = $deal['count_money'] + $deal['wage'];

             $change_rial = $deal['volume'] - $base->volume ;

             $this->db->trans_begin();
             $c_rial = TRUE ;
             $c_base = TRUE;
             $c_send = TRUE;
             if($base->money_id == 10){
                 //rial
                 if($base->type == 1){
                    $c_rial = $this->base_model->set('amount' , 'amount+'.$change_rial , array('id'=>10) , 'unit');
                    $text3 = ' افزایش یافت';
                 }else{
                    $c_rial = $this->base_model->set('amount' , 'amount-'.$change_rial , array('id'=>10) , 'unit');
                    $text3 = 'کاهش یافت';
                 }
                 $change_unit = ' مقدار ریال به اندازه  '.number_format($change_rial).$text3."</br>";
                 //rial
             }else{
                 //other
                if($base->money_id != $deal['money_id']){
                     if($base->type == 1){
                        $c_rial = $this->base_model->set('amount' , 'amount-'.$change_rial , array('id'=>10) , 'unit');
                        $c_base = $this->base_model->set('amount' , 'amount-'.$base_count , array('id'=>$base->money_id) , 'unit');
                        $c_send = $this->base_model->set('amount' , 'amount+'.$send_count , array('id'=>$deal['money_id']) , 'unit');
                        $text3 = 'کاهش یافت';
                     }else{
                        $c_rial = $this->base_model->set('amount' , 'amount+'.$change_rial , array('id'=>10) , 'unit');
                        $c_base = $this->base_model->set('amount' , 'amount+'.$base_count , array('id'=>$base->money_id) , 'unit');
                        $c_send = $this->base_model->set('amount' , 'amount-'.$send_count , array('id'=>$deal['money_id']) , 'unit');
                        $text3 = 'افزایش یافت';
                     }
                     $change_unit = "  ارز معامله تغییر یافت "."</br>"." مقدار ریال به اندازه   ".number_format($change_rial). $text3."</br>";
                 }else{
                     $c_am = $send_count - $base_count;
                     if($base->type == 1){
                       $c_base = $this->base_model->set('amount' , 'amount+'.$c_am , array('id'=>$base->money_id) , 'unit');
                       $c_rial = $this->base_model->set('amount' , 'amount-'.$change_rial , array('id'=>10) , 'unit');
                       $text3 = 'کاهش یافت';
                      }else{
                        $c_base = $this->base_model->set('amount' , 'amount-'.$c_am , array('id'=>$base->money_id) , 'unit');
                        $c_rial = $this->base_model->set('amount' , 'amount+'.$change_rial , array('id'=>10) , 'unit');
                        $text3 = 'افزایش یافت';
                     }
                     $change_unit = ' ارز معامله به اندازه  '.number_format($c_am).' تغییر یافت '."</br>"." مقدار ریال به اندازه ".number_format($change_rial).$text3."</br>";
                 }

                //other

             }
             $res = $this->base_model->update_data('deal' , $deal , array('id'=> $id));
             if($this->db->trans_status() === FALSE or $res == FALSE or $c_rial == FALSE or $c_base == FALSE or $c_send == FALSE){
                $this->db->trans_rollback();
                 $message['msg'][0] = 'مشکلی در ثبت اطلاعات رخ داده است. لطفا دوباره سعی کنید';
                 $message['msg'][1] = 'danger';
                 $this->session->set_flashdata($message);
                 redirect("deal/edit/$id");
             } else{
                $this->db->trans_commit();
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
               $res_bank = $this->base_model->insert_batch('bank' , $new_bank);
               if($res_bank == FALSE){
                $message['msg'][0] = 'معامله با موفقیت ویرایش شد ولی در ثبت بانک ها متاسفانه مشکلی رخ داده است';
                $message['msg'][1] = 'danger';
                $this->session->set_flashdata($message);
                redirect("deal/edit/$id");
               }
              }
              if(isset($bank) and !empty($bank)){
                $res_bank = $this->base_model->update_batch('bank' , $bank , 'id');
                if($res_bank == FALSE){
                    $message['msg'][0] = 'معامله با موفقیت ویرایش شد ولی در ویرایش بانک ها متاسفانه مشکلی رخ داده است';
                    $message['msg'][1] = 'danger';
                    $this->session->set_flashdata($message);
                    redirect("deal/edit/$id");
                   }
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
                if($res == FALSE){
                    $message['msg'][0] = 'مشکلی در ثبت اطلاعات رخ داده است . لطفا دوباره سعی کنید';
                    $message['msg'][1] = 'danger';
                    $this->session->set_flashdata($message);
                    redirect("deal/handle_profile/$id");
                }
                $this->base_model->insert_data('log' , $log);
                  $message['msg'][0] = 'اطلاعات حساب بانکی با موفقیت ثبت شد';
                  $message['msg'][1] = 'success';
                  $message['status'] = 2;
                  $this->session->set_flashdata($message);
                  redirect("deal/handle_profile/$id");
             
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
      $message['status'] = 2;
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
$date = $this->convertdate->convert(time());        
$handle_info = $this->base_model->get_data('handle' , '*' , 'row' , array('id'=>$id));
$check_buy = $this->base_model->get_count('deal' , array('customer_id'=> $handle_info->buy_id , 'type'=> 1 , 'rest !=' => 0));
$check_sell = $this->base_model->get_count('deal' , array('customer_id'=> $handle_info->sell_id , 'type'=> 2 , 'rest !=' => 0));
if($check_buy == 0){
$message['msg'][0] = ' تمام معاملات خرید مشتری خرید پرداخت شده است ';
$message['msg'][1] = 'danger';
$this->session->set_flashdata($message);
redirect("deal/handle_profile/$cust_id");
}else if($check_sell == 0){
    $message['msg'][0] = ' تمام معاملات فروش مشتری فروش پرداخت شده است ';
    $message['msg'][1] = 'danger';
    $this->session->set_flashdata($message);
    redirect("deal/handle_profile/$cust_id");
}
$buy = $this->base_model->get_data('deal' , 'id  , pay , rest' , 'result' ,array('customer_id'=> $handle_info->buy_id , 'type'=> 1 , 'rest !=' => 0) , NULL , NULL , array('id' , 'ASC'));
$push = $handle_info->handle_rest;
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
$this->db->trans_begin();
if($push > 0){
    $unit_rial = $this->base_model->get_data('unit' , 'amount' , 'row' , array('id'=> 10));
    $state_sell = $this->base_model->get_data('deal' , 'id , count_money , volume , rest' , 'row' , array('customer_id'=>$handle_info->buy_id , 'type'=> 2 , 'state'=>0));
    if(!empty($state_sell)){
        $upsell['count_money'] = $state_sell->count_money + $push;
        $upsell['volume'] = $state_sell->volume + $push;
        $upsell['rest'] = $state_sell->rest + $push;
        $status = $this->base_model->update_data('deal' , $upsell , array('id' => $state_sell->id));
        $rial['amount'] = $unit_rial->amount - $push;
        $a = $state_sell->id + 100;
        $str .= " مقدار ریالی معامله  ".$a."  به دلیل مازاد بودن حجم پرداختی به اندازه ". number_format($push)." افزایش یافت "."</br>";
        $str .= " مقدار ریالی به اندازه ".number_format($push)." کاهش یافت "."</br>";
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
        $status = $this->base_model->insert_data('deal' , $inssell);
        $rial['amount'] = $unit_rial->amount - $push;
        $str .= " معامله ای از نوع فروش به دلیل مازاد بودن حجم هماهنگی به اندازه  ".number_format($push)." افزوده شد "."</br>";
        $str .= " مقدار ریالی به اندازه ".number_format($push)." کاهش یافت "."</br>";
    }
   $status =  $this->base_model->update_data('unit' , $rial , array('id'=>10));    
    }


    $sell = $this->base_model->get_data('deal' , 'id  , pay , rest' , 'result' ,array('customer_id'=> $handle_info->sell_id , 'type'=> 2 ,'rest !='=> 0) , NULL , NULL , array('id' , 'ASC'));
    $str .= ' پرداختی های مربوط به مشتری فروش : ' ."</br>";
    $push = $handle_info->handle_rest;
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
        $unit_rial = $this->base_model->get_data('unit' , 'amount' , 'row' , array('id'=> 10));
        $state_buy = $this->base_model->get_data('deal' , 'id , count_money , volume , rest' , 'row' , array('customer_id'=>$handle_info->sell_id , 'type'=> 1 , 'state'=>0));
        if(!empty($state_buy)){
            $upbuy['count_money'] = $state_buy->count_money + $push;
            $upbuy['volume'] = $state_buy->volume + $push;
            $upbuy['rest'] = $state_buy->rest + $push;
            $status = $this->base_model->update_data('deal' , $upbuy , array('id' => $state_buy->id));
            $rial['amount'] = $unit_rial->amount + $push;
            $a = $state_buy->id + 100;
            $str .= " مقدار ریالی معامله  ".$a."  به دلیل مازاد بودن حجم پرداختی به اندازه ". number_format($push)." افزایش یافت "."</br>";
            $str .= " مقدار ریالی به اندازه ".number_format($push)." افزایش یافت "."</br>";
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
            $status =  $this->base_model->insert_data('deal' , $insbuy);
            $rial['amount'] = $unit_rial->amount + $push;
            $str .= " معامله ای از نوع خرید به دلیل مازاد بودن حجم هماهنگی به اندازه  ".number_format($push)." افزوده شد "."</br>";
            $str .= " مقدار ریالی به اندازه ".number_format($push)." افزایش یافت "."</br>";
        }
        $status = $this->base_model->update_data('unit' , $rial , array('id'=>10));        
        }
$bank_info = $this->base_model->get_data_join('bank' , 'customer' ,'bank.pay , bank.rest , customer.fullname as owner' , 'bank.customer_id = customer.id', 'row' , array('bank.id'=>$handle_info->bank_id));        
$handle['handle_pay'] = $handle_info->handle_pay + $handle_info->handle_rest;
$handle['handle_rest'] = 0;
$handle['date_modified'] = $date['year']."-".$date['month_num']."-".$date['day']."</br>".$date['hour'].":".$date['minute'].":".$date['second'];
$bank['pay'] = $bank_info->pay + $handle_info->handle_rest;
$bank['rest'] = $bank_info->rest - $handle_info->handle_rest;
$history['date_pay'] = $date['year']."-".$date['month_num']."-".$date['day']." ".$date['hour'].":".$date['minute'].":".$date['second'];
$history['volume'] = $handle_info->handle_rest;
$history['active'] = 1;
$history['handle_id'] = $handle_info->id;
$turn['owner'] = $bank_info->owner;
$turn['cust_id'] = $handle_info->sell_id;
$turn['bank_id'] = $handle_info->bank_id;
$turn['date'] = $date['year']."-".$date['month_num']."-".$date['day'];
$turn['time'] = $date['hour'].":".$date['minute'].":".$date['second'];
$turn['amount'] = $handle_info->handle_rest;
$turn['rest'] = $bank['rest'];
$status = $this->base_model->insert_data('turnover' , $turn);
$status = $this->base_model->insert_data('history' , $history);
$status = $this->base_model->update_data('bank' , $bank , array('id'=>$handle_info->bank_id));
$status = $this->base_model->update_data('handle' , $handle , array('id'=> $id));
if(!empty($deal)){
    $status = $this->base_model->update_batch('deal' , $deal , 'id');
}
        $log['user_id'] = $this->session->userdata('id');
        $log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
        $log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
        $log['activity_id'] = 13;
        $log['explain'] = $str;
        if($this->db->trans_status() === FALSE or $status == FALSE){
            $this->db->trans_rollback();
            $message['msg'][0] = 'متاسفانه مشکلی در ثبت اطلاعات رخ داده است . لطفا دوباره سعی کنید';
            $message['msg'][1] = 'danger';
            $this->session->set_flashdata($message);
            redirect("deal/handle_profile/$cust_id");
        }else{
            $this->db->trans_commit();
        }
        $this->base_model->insert_data('log' , $log);
          $message['msg'][0] = 'پرداخت به صورت کامل انجام شد';
          $message['msg'][1] = 'success';
          $message['status'] = 3;
          $this->session->set_flashdata($message);
          redirect("deal/handle_profile/$cust_id");
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
$date = $this->convertdate->convert(time());
$handle_info = $this->base_model->get_data('handle' , '*' , 'row' , array('id'=>$id));
$check_buy = $this->base_model->get_count('deal' , array('customer_id'=> $handle_info->buy_id , 'type'=> 1 , 'rest !=' => 0));
$check_sell = $this->base_model->get_count('deal' , array('customer_id'=> $handle_info->sell_id , 'type'=> 2 , 'rest !=' => 0));
if($check_buy == 0){
$message['msg'][0] = ' تمام معاملات خرید مشتری خرید پرداخت شده است ';
$message['msg'][1] = 'danger';
$this->session->set_flashdata($message);
redirect("deal/handle_profile/$cust_id");
}else if($check_sell == 0){
    $message['msg'][0] = ' تمام معاملات فروش مشتری فروش پرداخت شده است ';
    $message['msg'][1] = 'danger';
    $this->session->set_flashdata($message);
    redirect("deal/handle_profile/$cust_id");
}
        $buy = $this->base_model->get_data('deal' , 'id  , pay , rest' , 'result' ,array('customer_id'=> $handle_info->buy_id , 'type'=> 1 , 'rest !=' => 0) , NULL , NULL , array('id' , 'ASC'));
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
$this->db->trans_begin();            
if($push > 0){
    $unit_rial = $this->base_model->get_data('unit' , 'amount' , 'row' , array('id'=>10));
    $state_sell = $this->base_model->get_data('deal' , 'id , count_money , volume , rest' , 'row' , array('customer_id'=>$handle_info->buy_id , 'type'=> 2 , 'state'=>0));
    if(!empty($state_sell)){
        $upsell['count_money'] = $state_sell->count_money + $push;
        $upsell['volume'] = $state_sell->volume + $push;
        $upsell['rest'] = $state_sell->rest + $push;
        $status = $this->base_model->update_data('deal' , $upsell , array('id' => $state_sell->id));
        $rial['amount'] = $unit_rial->amount - $push;
        $a = $state_sell->id + 100;
        $str .= " مقدار ریالی معامله  ".$a."  به دلیل مازاد بودن حجم پرداختی به اندازه ". number_format($push)." افزایش یافت "."</br>";
        $str .= " مقدار ریالی به اندازه ".number_format($push)." کاهش یافت "."</br>";
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
        $status = $this->base_model->insert_data('deal' , $inssell);
        $rial['amount'] = $unit_rial->amount - $push;
        $str .= " معامله ای از نوع فروش به دلیل مازاد بودن حجم هماهنگی به اندازه  ".number_format($push)." افزوده شد "."</br>";
        $str .= " مقدار ریالی به اندازه ".number_format($push)." کاهش یافت "."</br>";
    }
    $status = $this->base_model->update_data('unit' , $rial , array('id'=>10));     
    }

    $sell = $this->base_model->get_data('deal' , 'id  , pay , rest' , 'result' ,array('customer_id'=> $handle_info->sell_id , 'type'=> 2 ,'rest !='=> 0) , NULL , NULL , array('id' , 'ASC'));
    $push = $this->input->post('slice');
        $str .= ' پرداختی های مربوط به مشتری فروش : ' ."</br>";
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
            $unit_rial = $this->base_model->get_data('unit' , 'amount' , 'row' , array('id'=>10));
            $state_buy = $this->base_model->get_data('deal' , 'id , count_money , volume , rest' , 'row' , array('customer_id'=>$handle_info->sell_id , 'type'=> 1 , 'state'=>0));
            if(!empty($state_buy)){
                $upbuy['count_money'] = $state_buy->count_money + $push;
                $upbuy['volume'] = $state_buy->volume + $push;
                $upbuy['rest'] = $state_buy->rest + $push;
                $status = $this->base_model->update_data('deal' , $upbuy , array('id' => $state_buy->id));
                $rial['amount'] = $unit_rial->amount + $push;
                $a = $state_buy->id + 100;
                $str .= " مقدار ریالی معامله  ".$a."  به دلیل مازاد بودن حجم پرداختی به اندازه ". number_format($push)." افزایش یافت "."</br>";
                $str .= " مقدار ریالی به اندازه ".number_format($push)." افزایش یافت "."</br>";
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
                $status = $this->base_model->insert_data('deal' , $insbuy);
                $rial['amount'] = $unit_rial->amount + $push;
                $str .= " معامله ای از نوع خرید به دلیل مازاد بودن حجم هماهنگی به اندازه  ".number_format($push)." افزوده شد "."</br>";
                $str .= " مقدار ریالی به اندازه ".number_format($push)." افزایش یافت "."</br>";
            } 
            $status = $this->base_model->update_data('unit' , $rial , array('id'=>10));    
            }
        $bank_info = $this->base_model->get_data_join('bank' , 'customer' , 'bank.pay , bank.rest , customer.fullname as owner', 'bank.customer_id = customer.id' , 'row' , array('bank.id'=>$handle_info->bank_id));        
        $handle['handle_pay'] = $handle_info->handle_pay + $this->input->post('slice');
        $handle['handle_rest'] = $handle_info->handle_rest - $this->input->post('slice');
        $handle['date_modified'] = $date['year']."-".$date['month_num']."-".$date['day']."</br> ".$date['hour'].":".$date['minute'].":".$date['second'];
        $bank['pay'] = $bank_info->pay + $this->input->post('slice');
        $bank['rest'] = $bank_info->rest - $this->input->post('slice');
        $history['date_pay'] = $date['year']."-".$date['month_num']."-".$date['day']." ".$date['hour'].":".$date['minute'].":".$date['second'];
        $history['volume'] = $this->input->post('slice');
        $history['active'] = 1;
        $history['handle_id'] = $handle_info->id;
        $turn['owner'] = $bank_info->owner;
        $turn['cust_id'] = $handle_info->sell_id;
        $turn['bank_id'] = $handle_info->bank_id;
        $turn['date'] = $date['year']."-".$date['month_num']."-".$date['day'];
        $turn['time'] = $date['hour'].":".$date['minute'].":".$date['second'];
        $turn['amount'] = $this->input->post('slice');
        $turn['rest'] = $bank['rest'];
        $status = $this->base_model->insert_data('turnover' , $turn);
        $status = $this->base_model->insert_data('history' , $history);
        $status = $this->base_model->update_data('bank' , $bank , array('id'=>$handle_info->bank_id));
        $status =  $this->base_model->update_data('handle' , $handle , array('id'=> $id));
        if(!empty($deal)){
            $status = $this->base_model->update_batch('deal' , $deal , 'id');
        }
        $log['user_id'] = $this->session->userdata('id');
        $log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
        $log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
        $log['activity_id'] = 14;
        $log['explain'] = $str;
        if($this->db->trans_status() === FALSE or $status == FALSE){
            $this->db->trans_rollback();
            $message['msg'][0] = 'متاسفانه مشکلی در ثبت اطلاعات رخ داده است . لطفا دوباره سعی کنید';
            $message['msg'][1] = 'danger';
            $this->session->set_flashdata($message);
            redirect("deal/handle_profile/$cust_id");
        }else{
            $this->db->trans_commit();
        }
        $this->base_model->insert_data('log' , $log);
          $message['msg'][0] = 'پرداخت به صورت جزیی انجام شد';
          $message['msg'][1] = 'success';
          $message['status'] = 3;
          $this->session->set_flashdata($message);
          redirect("deal/handle_profile/$cust_id");
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
    $sum_buy = $this->base_model->get_data('deal' , 'sum(volume) as buy' , 'row' , array('customer_id'=>$handle_info->buy_id , 'type'=> 1 , 'pay !=' => 0) , NULL , NULL , NULL , 'customer_id');
    $sum_sell = $this->base_model->get_data('deal' , 'sum(volume) as sell' , 'row' , array('customer_id'=>$handle_info->sell_id , 'type'=> 2 , 'pay !=' => 0) , NULL , NULL , NULL , 'customer_id');
    $check = $history->volume;
    if($sum_buy->buy < $check){
        $s_buy = $this->base_model->get_data('deal' , 'pay' , 'row' , array('customer_id'=>$handle_info->buy_id , 'type'=> 2 , 'state'=>0));
        if(!empty($s_buy)){
            if($s_buy->pay != 0 ){
                $message['msg'][0] = ' جهت جلوگیری از ناسازگاری در سیستم ابتدا معاملات دیگر مشتری خرید را بازگردانید ';
                $message['msg'][1] = 'danger';
                $this->session->set_flashdata($message);
                redirect('deal/handle_profile/'.$cust_id);
             }
        }
    }else if($sum_sell->sell < $check){
        $s_sell = $this->base_model->get_data('deal' , 'pay' , 'row' , array('customer_id'=>$handle_info->sell_id , 'type'=> 1 , 'state'=>0));
        if(!empty($s_sell)){
            if($s_sell->pay != 0){
                $message['msg'][0] = ' جهت جلوگیری از ناسازگاری در سیستم ابتدا معاملات دیگر مشتری فروش را بازگردانید ';
                $message['msg'][1] = 'danger';
                $this->session->set_flashdata($message);
                redirect('deal/handle_profile/'.$cust_id);
            }
        }
    }
    $date = $this->convertdate->convert(time());
    $buy = $this->base_model->get_data('deal' , 'id  , pay , rest' , 'result' ,array('customer_id'=> $handle_info->buy_id , 'type'=> 1 , 'pay !=' => 0) , NULL , NULL , array('id' , 'DESC'));
    $push = $history->volume;
    $deal = array();
    if(!empty($buy)){
        $str = ' بازگشتی های مربوط به مشتری خرید : ' ."</br>";
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
    $this->db->trans_begin();
    if($push > 0){
        $unit_rial = $this->base_model->get_data('unit' , 'amount' , 'row' , array('id'=> 10));
        $state_sell = $this->base_model->get_data('deal' , 'id , count_money , volume , rest' , 'row' , array('customer_id'=>$handle_info->buy_id , 'type'=> 2 , 'state'=>0));
        if(!empty($state_sell)){
            $upsell['count_money'] = $state_sell->count_money - $push;
            $upsell['volume'] = $state_sell->volume - $push;
            $upsell['rest'] = $state_sell->rest - $push;
            $status = $this->base_model->update_data('deal' , $upsell , array('id' => $state_sell->id));
            $a = $state_sell->id + 100;
            $str .= " مقدار ریالی معامله  ".$a."  به دلیل مازاد بودن حجم بازگشتی به اندازه ". number_format($push)." کاهش یافت "."</br>";
            $rial['amount'] = $unit_rial->amount + $push;
            $str .= ' مقدار ریال به اندازه  '.number_format($push)." افزایش یافت "."</br>";
            $status = $this->base_model->update_data('unit' , $rial , array('id'=>10));
        }
    }

    $sell = $this->base_model->get_data('deal' , 'id  , pay , rest' , 'result' ,array('customer_id'=> $handle_info->sell_id , 'type'=> 2 ,'pay !='=> 0) , NULL , NULL , array('id' , 'DESC'));
    $push = $history->volume;
    if(!empty($sell)){
        $str .= ' بازگشتی های مربوط به مشتری فروش : ' ."</br>";
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
        $unit_rial = $this->base_model->get_data('unit' , 'amount' , 'row' , array('id'=> 10));
        $state_buy = $this->base_model->get_data('deal' , 'id , count_money , volume , rest' , 'row' , array('customer_id'=>$handle_info->sell_id , 'type'=> 1 , 'state'=>0));
        if(!empty($state_buy)){
            $upbuy['count_money'] = $state_buy->count_money - $push;
            $upbuy['volume'] = $state_buy->volume - $push;
            $upbuy['rest'] = $state_buy->rest - $push;
            $status = $this->base_model->update_data('deal' , $upbuy , array('id' => $state_buy->id));
            $a = $state_buy->id + 100;
            $str .= " مقدار ریالی معامله  ".$a."  به دلیل مازاد بودن حجم بازگشتی به اندازه ". number_format($push)." کاهش یافت "."</br>";
            $rial['amount'] = $unit_rial->amount - $push;
            $str .= ' مقدار ریال به اندازه  '.number_format($push)." کاهش یافت "."</br>";
            $status = $this->base_model->update_data('unit' , $rial , array('id'=>10));
        } 
    }
    $bank_info = $this->base_model->get_data_join('bank' , 'customer' , 'bank.pay , bank.rest , customer.fullname as owner' ,'bank.customer_id = customer.id','row' , array('bank.id'=>$handle_info->bank_id));
    $handle['handle_pay'] = $handle_info->handle_pay - $history->volume;
    $handle['handle_rest'] = $handle_info->handle_rest + $history->volume;
    $handle['date_modified'] = $date['year']."-".$date['month_num']."-".$date['day']."</br>".$date['hour'].":".$date['minute'].":".$date['second'];
    $bank['pay'] = $bank_info->pay - $history->volume;
    $bank['rest'] = $bank_info->rest + $history->volume;
    $turn['owner'] = $bank_info->owner;
    $turn['cust_id'] = $handle_info->sell_id;
    $turn['bank_id'] = $handle_info->bank_id;
    $turn['date'] = $date['year']."-".$date['month_num']."-".$date['day'];
    $turn['time'] = $date['hour'].":".$date['minute'].":".$date['second'];
    $turn['amount'] = 0 - $history->volume;
    $turn['rest'] = $bank['rest'];
    $log['user_id'] = $this->session->userdata('id');
    $log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
    $log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
    $log['activity_id'] = 15;
    $log['explain'] = $str;
    $his['active'] = 0;
    $status = $this->base_model->insert_data('turnover' , $turn);
    $status = $this->base_model->update_data('bank' , $bank , array('id'=>$handle_info->bank_id));
    $status = $this->base_model->update_data('handle' , $handle , array('id'=> $history->handle_id));
    if(!empty($deal)){
        $status = $this->base_model->update_batch('deal' , $deal , 'id');
    }
    $status = $this->base_model->update_data('history' , $his , array('id'=> $id)); 
    if($this->db->trans_status() === FALSE or $status == FALSE){
        $this->db->trans_rollback();
        $message['msg'][0] = 'متاسفانه مشکلی در ثبت اطلاعات رخ داده است . لطفا دوباره سعی کنید';
        $message['msg'][1] = 'danger';
        $this->session->set_flashdata($message);
        redirect("deal/handle_profile/$cust_id");
    }else{
        $this->db->trans_commit();
    }
    $this->base_model->insert_data('log' , $log);
      $message['msg'][0] = 'بازگشت پرداخت به صورت کامل انجام شد ';
      $message['msg'][1] = 'success';
      $message['status'] = 3;
      $this->session->set_flashdata($message);
      redirect("deal/handle_profile/$cust_id"); 
    }else{
        show_404();
    }
}
// -----history------//
// edit handle
public function edit_handle(){
    if(!$this->session->has_userdata('edit_handle') or $this->session->userdata('edit_handle') != TRUE){
        show_404();
    }
    $red_id = $this->uri->segment(3);
    $id = $this->uri->segment(4);
    if(isset($_POST['sub']) and isset($id) and isset($red_id) and is_numeric($id) and is_numeric($red_id)){
      
      $handle_info = $this->base_model->get_data_join('handle' , 'bank' ,'handle.volume_handle , handle.handle_rest, handle.bank_id ,bank.rest_handle , customer.fullname' , 'bank.id = handle.bank_id' ,'row' , array('handle.id'=>$id) , NULL , NULL , NULL , array('customer' , 'handle.buy_id = customer.id'));
      $handle['volume_handle'] = $this->input->post('edit');
      $change = $this->input->post('edit') - $handle_info->volume_handle;
      $handle['handle_rest'] = $handle_info->handle_rest + $change;
      $bank['rest_handle'] = $handle_info->rest_handle - $change;
      $str = ' حجم هماهنگی مشتری خرید  '.$handle_info->fullname . " از مقدار ".number_format($handle_info->volume_handle) . " به مقدار ". number_format($handle['volume_handle']) . " تغییر یافت ";
      $date = $this->convertdate->convert(time());
      $log['user_id'] = $this->session->userdata('id');
      $log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
      $log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
      $log['activity_id'] = 21;
      $log['explain'] = $str;
      $this->base_model->update_data('handle' , $handle , array('id'=> $id));
      $this->base_model->update_data('bank' , $bank , array('id'=> $handle_info->bank_id));
      $this->base_model->insert_data('log' , $log);
      $message['msg'][0] = 'هماهنگی با موفقیت ویرایش شد';
      $message['msg'][1] = 'success';
      $message['status'] = 3;
      $this->session->set_flashdata($message);
      redirect("deal/handle_profile/$red_id");       
    }else{
        show_404();
    }
}
//edit handle
    // ----delete handle---//
    public function delete_handle(){
        $red_id = $this->uri->segment(3);
        $id = $this->uri->segment(4);
        if(isset($id) and is_numeric($id) and isset($red_id) and is_numeric($red_id)){
           $handle = $this->base_model->get_data_join('handle' , 'customer' ,'handle.* , customer.fullname ' , 'handle.buy_id = customer.id' ,'row' , array('handle.id' => $id));
           if($handle->handle_pay != 0 or empty($handle)){
               show_404();
           }else{
            $bank_info = $this->base_model->get_data('bank' , 'rest_handle' , 'row' , array('id' => $handle->bank_id));
            $explain = 'هماهنگی با حجم : '.number_format($handle->volume_handle)." مربوط به مشتری خرید  ".$handle->fullname . " حذف شد ";
            $date = $this->convertdate->convert(time());
            $log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
            $log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
            $log['user_id'] = $this->session->userdata('id');
            $log['activity_id'] = 16;
            $log['explain'] = $explain;
            $back['explain'] =  $explain;
            $back['time_backup'] = $log['time_log'];
            $back['date_backup'] = $log['date_log'];
            $bank['rest_handle'] = $bank_info->rest_handle + $handle->volume_handle;
            $res = $this->base_model->delete_data('handle' , array('id'=>$id));
            $this->base_model->update_data('bank' , $bank , array('id'=> $handle->bank_id));
            $this->base_model->insert_data('log' , $log);
            $this->base_model->insert_data('backup' , $back);
            if($res == FALSE){
                $message['msg'][0] = 'متاسفانه مشکلی در روند عملیات رخ داده است . لطفا دوباره سعی کنید';
                $message['msg'][1] = 'danger';
                $this->session->set_flashdata($message);
                redirect("deal/handle_profile/$red_id");
            }else{
                $message['msg'][0] = 'هماهنگی با موفقیت حذف شد';
                $message['msg'][1] = 'success';
                $message['status'] = 3;
                $this->session->set_flashdata($message);
                redirect("deal/handle_profile/$red_id");
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
                    $message['status'] = 1;
                    $this->session->set_flashdata($message);
                    redirect("deal/handle_profile/$id");
            }else{
                if(!$this->session->has_userdata('see_handle') or $this->session->userdata('see_handle') != TRUE){
                    show_404();
                }
                $header['title'] = 'هماهنگی';
                $header['active'] = 'deal';
                $header['active_sub'] = 'deal_archive';      
                $data['deal'] = $this->base_model->get_data_join('deal' ,'customer', 'deal.* , customer.fullname ,unit.name' , 'deal.customer_id = customer.id' ,'result'  , array('deal.customer_id'=> $id), NULL , NULL , array('deal.id' , 'DESC') , array('unit','deal.money_id = unit.id'));
                $data['page'] = $this->pagination->create_links();
                $data['bank'] = $this->base_model->get_data('bank' ,'*' ,'result' ,array('customer_id' => $id));
                $data['select'] = $this->base_model->get_data('bank' , 'id , explain , rest , rest_handle' , 'result' , array('customer_id' => $id , 'active'=> 1));
                $data['handle'] = $this->base_model->get_data_join('handle' , 'bank' , 'handle.* , customer.fullname ,customer.id as cust_id, bank.explain' , 'handle.bank_id = bank.id' , 'result' , array('handle.buy_id'=>$id) , NULL , NULL , array('handle.id' , 'DESC'),array('customer' , 'handle.sell_id = customer.id'));
                $data['handle2'] = $this->base_model->get_data_join('handle' , 'bank' , 'handle.* , customer.fullname ,customer.id as cust_id, bank.explain' , 'handle.bank_id = bank.id' , 'result' , array('handle.sell_id'=>$id) , NULL , NULL , array('handle.id' , 'DESC'),array('customer' , 'handle.buy_id = customer.id'));    
                $data['customer'] = $this->base_model->get_data('customer' , 'fullname , id' , 'result');
                $data['buy'] = $this->base_model->run_query("SELECT d.customer_id, SUM(d.volume) AS volume, max(h.volume_handle) AS handle , c.fullname  FROM  deal d LEFT JOIN (SELECT buy_id, SUM(volume_handle) AS volume_handle FROM handle GROUP BY buy_id) h ON h.buy_id = d.customer_id inner join customer c on c.id = d.customer_id where d.type = 1 GROUP BY d.customer_id ORDER BY d.id DESC");
                $data['sell'] = $this->base_model->run_query("SELECT d.customer_id, SUM(d.volume) AS volume, max(h.volume_handle) AS handle , c.fullname  FROM  deal d LEFT JOIN (SELECT sell_id, SUM(volume_handle) AS volume_handle FROM handle GROUP BY sell_id) h ON h.sell_id = d.customer_id inner join customer c on c.id = d.customer_id where d.type = 2 GROUP BY d.customer_id ORDER BY d.id DESC");
                $deal_buy = 0;
            foreach($data['buy'] as $row){
                if($row->customer_id == $id){
                    $deal_buy = $row->volume - $row->handle;
                    break;
                }else{
                    $deal_buy = -1;
                }
            }
            $deal_sell = 0;
            foreach($data['sell'] as $rows){
                if($rows->customer_id == $id){
                    $deal_sell = $rows->volume - $rows->handle;
                    break;
                }else{
                    $deal_sell = -1;
                }
            }
            $data['deal_buy'] = $deal_buy;
            $data['deal_sell'] = $deal_sell;
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
        $name = trim($name);
        $type = $this->input->post('type');
        if($type == 'buy'){
           $data['cust'] = $this->base_model->run_query("SELECT d.customer_id, SUM(d.rest) AS rest, SUM(d.volume) AS volume, max(h.volume_handle) AS handle , c.fullname  FROM  deal d LEFT JOIN (SELECT buy_id, SUM(volume_handle) AS volume_handle FROM handle GROUP BY buy_id) h ON h.buy_id = d.customer_id inner join customer c on c.id = d.customer_id where d.type = 1 AND c.fullname = '$name' GROUP BY d.customer_id");
           $cust_id = $data['cust'][0]->customer_id;
           $data['bank'] = $this->base_model->get_data('bank' , 'id , rest_handle , rest , explain' , 'result' , array('customer_id'=>$cust_id)); 
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