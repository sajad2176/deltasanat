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
        $data['m'] = $_POST['money_id']; $data['t'] = $_POST['deal_type'];
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
        if($_POST['deal_type'] == 0 and $_POST['money_id'] == 0){
            $where = NULL;
            $total_rows = $this->base_model->get_count_between("deal" , NULL , $between);
        }else if($_POST['deal_type'] == 0){
           $where = array('deal.money_id' => $_POST['money_id']);
           $total_rows = $this->base_model->get_count_between("deal" , array('money_id'=> $_POST['money_id']) , $between);
        }else if($_POST['money_id'] == 0){
            $where = array('deal.type_deal' => $_POST['deal_type']);
            $total_rows = $this->base_model->get_count_between("deal" , array('type_deal'=> $_POST['deal_type']) , $between);
        }else{
            $where = array('deal.type_deal' => $_POST['deal_type'] , 'deal.money_id' => $_POST['money_id']);
            $total_rows = $this->base_model->get_count_between("deal" , array('type_deal' => $_POST['deal_type'] , 'money_id' => $_POST['money_id']) , $between);
        }
    }else{
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
$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;      
$data['deal'] = $this->base_model->get_data_join('deal' ,'customer', 'deal.* , customer.fullname , customer.id as cust_id ,currency_unit.name' , 'deal.customer_id = customer.id' ,'result'  , $where , $config['per_page'] , $page , array('deal.id' , 'DESC') , array('currency_unit','deal.money_id = currency_unit.id') , NULL , $between);
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
    //----- archive -----//

    //----- search customer -----// 
    public function search(){
        if(isset($_POST['text_search'])){
            $title = $this->input->post('text_search');
            $data = $this->base_model->search_data('deal' , 'customer' , 'deal.* , customer.fullname , customer.id as cust_id ,currency_unit.name' ,'deal.customer_id = customer.id' , 'inner'  , array('customer.fullname'=>$title) , NULL , array('deal.id' , 'DESC') , NULL , array('currency_unit','deal.money_id = currency_unit.id'));
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
         $deal = $this->base_model->get_data_join('deal' , 'customer' ,'deal.* , customer.fullname','deal.customer_id = customer.id', 'row' , array('deal.id' => $id));
         if($deal->volume_pay != 0 or sizeof($deal) == 0){
             show_404();
         }else{
            $deal_handle = $this->base_model->get_data('deal_handle' , 'volume_handle' ,  'result' , array('deal_handle.deal_id' => $id));
            $deal_bank = $this->base_model->get_data('deal_bank' , '*' , 'result' , array('deal_id' => $id));
            $deal_pic = $this->base_model->get_data('deal_pic' , '*' , 'result' , array('deal_id'=> $id));
            $amount_unit = $this->base_model->get_data('currency_unit' , 'amount_unit  , name' , 'row' , array('id'=> $deal->money_id));
            $a = $id +100;
            $explain = ' شناسه معامله :  '. $a . " | نام مشتری : ".$deal->fullname . " | نام ارز : ".$amount_unit->name ." | تعداد ارز : ".number_format($deal->count_money).$deal->name." | کارمزد : " . number_format($deal->wage).$deal->name." | نرخ تبدیل : ".number_format($deal->convert_money)." | حجم معامله : ".number_format($deal->volume_deal)."</br>";
           if(sizeof($deal_handle) != 0){
            for($i = 0 ; $i < sizeof($deal_handle) ; $i++){
                $b = $i + 1;
                $explain .= " هماهنگی شماره ".$b." - حجم هماهنگی : ".number_format($deal_handle[$i]->volume_handle)."</br>";
            }
            $this->base_model->delete_data('deal_handle' , array('deal_id'=>$id));
           }
         if(sizeof($deal_bank) != 0){
            for($j = 0 ; $j < sizeof($deal_bank) ; $j++){
                $c = $j + 1;
                $explain .= " اطلاعات حساب شماره  ".$c." - نام بانک : ".$deal_bank[$j]->name_bank." | شماره شبا : ".$deal_bank[$j]->number_shaba." | مبلغ تعیین شده : ".number_format($deal_bank[$j]->amount)." | توضحیات : ".$deal_bank[$j]->explain."</br>";
            }
            $this->base_model->delete_data('deal_bank' , array('deal_id' => $id));
         }
         if(sizeof($deal_pic)  != 0){
            for($z = 0 ; $z < sizeof($deal_pic) ; $z++){
                $d = $z + 1;
                $explain .= ' نام عکس قبض شماره  '.$d." : ".$deal_pic[$z]->pic_name."</br>";
            }
            $this->base_model->delete_data('deal_pic' , array('deal_id'=> $id));
         }

$explain .= ' حذف شد ';
$am = $deal->count_money + $deal->wage;
if($deal->type_deal == 1){
$unit['amount_unit'] = $amount_unit->amount_unit - $am;
$text = " کاهش یافت ";
}else{
$unit['amount_unit'] = $amount_unit->amount_unit + $am;
$text = ' افزایش یافت ';
}
$explain .= "</br>"." مقدار ارز ".$amount_unit->name." به اندازه ".number_format($am) . $text;
$date = $this->convertdate->convert(time());
$log['user_id'] = $this->session->userdata('id');
$log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
$log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
$log['activity_id'] = 20;
$log['explain'] = $explain;
$back['explain'] =  $explain;
$back['date_backup'] = $log['date_log'];
$back['time_backup'] = $log['time_log'];
$this->base_model->update_data('currency_unit' , $unit , array('id' => $deal->money_id));
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
            if($this->input->post('deal_type') == 1){
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
            $this->form_validation->set_rules('convert_money','convert_money' , 'required|numeric');
			if($this->form_validation->run() == FALSE){
				$message['msg'][0] = "  لطفا اطلاعات مربوط به نام مشتری ، تعداد ارز ، کارمزد و نرخ تبدیل را وارد کنید  ";
				$message['msg'][1] = "danger";
				$this->session->set_flashdata($message);
				redirect("deal/$page");
            }

            //deal
            $customer['fullname'] = $this->input->post('customer');
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
           $deal['convert_money'] = $this->input->post('convert_money');
           $deal['volume_deal'] = ($deal['count_money'] + $deal['wage']) * $deal['convert_money'];
           $deal['volume_pay'] = 0;
           $deal['volume_rest'] = $deal['volume_deal'];
           $deal['explain'] = $this->input->post('explain');
           $deal['date_deal'] = $date_deal;
           $deal['time_deal'] = $time_deal;
           $deal['date_modified'] = '';
           $deal['type_deal'] = $this->input->post('deal_type');
           $deal['customer_id'] = $id;
           $deal['money_id'] = $this->input->post('money_id');
           // deal

           //currency
           $amount_unit = $this->base_model->get_data('currency_unit' , 'amount_unit' , 'row' , array('id'=> $deal['money_id']));
           if($deal['type_deal'] == 1){
               $unit['amount_unit'] = $amount_unit->amount_unit + ($deal['count_money'] + $deal['wage']);
               $act = 9;
               $text = " افزایش یافت ";
           }else{
               $unit['amount_unit'] = $amount_unit->amount_unit - ($deal['count_money'] + $deal['wage']); 
               $act = 10;
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
           if($data['type_deal'] == 1){
            $count = sizeof($_POST['number_shaba']);
            for($i = 0 ; $i < $count ; $i++){
                if($_POST['bank_explain'][$i] != '' or $_POST['number_shaba'][$i] != '' or $_POST['name_bank'][$i] != '' or $_POST['amount_bank'][$i] != ''){
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
            }
           }
            //bank

        $this->base_model->update_data('currency_unit' , $unit , array('id' => $deal['money_id']));  

        if(isset($bank) and sizeof($bank) != 0){
            $this->base_model->insert_batch('deal_bank' , $bank);
        }  

        //log
        if($deal['money_id'] == 1){$money = 'دلار';}else if($deal['money_id'] == 2){$money = 'یورو';}else if($deal['money_id'] == 3){$money = 'یوان';}else{$money = 'درهم';}
        $aa = $deal_id + 100;
        $log['user_id'] = $this->session->userdata('id');
        $log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
        $log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
        $log['activity_id'] = $act;
        $am = $deal['count_money'] + $deal['wage'];
        $log['explain'] = " نام مشتری :  ".$customer['fullname']." | شناسه معامله : ".$aa . " | ارز معامله : ". $money . " | تعداد ارز : ".number_format($deal['count_money']).$money ." | کارمزد : ".number_format($deal['wage']).$money . " | نرخ تبدیل : ".number_format($deal['convert_money'])." ریال "." حجم معامله :  ".number_format($deal['volume_deal'])." ریال "." | مقدار ارز  ".$money. " به اندازه ".number_format($am)." ".$text;
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
            $header['title'] = 'افزودن معامله';
            $header['active'] = 'deal';
            $header['active_sub'] = 'deal_buy';
            $data['customer'] = $this->base_model->get_data('customer' ,'fullname' , 'result');
            $this->load->view('header' , $header);
            $this->load->view('deal/buy' , $data);
            $this->load->view('footer');
        }  
    }


    public function sell(){
    if(!$this->session->has_userdata('add_sell') or $this->session->userdata('add_sell') != TRUE){
        show_404();
    } 
            $header['title'] = 'افزودن معامله';
            $header['active'] = 'deal';
            $header['active_sub'] = 'deal_sell';
            $data['customer'] = $this->base_model->get_data('customer' ,'fullname' , 'result');
            $date = $this->convertdate->convert(time());
            $data['date'] = $date['year']."/".$date['month_num']."/".$date['day']." ".$date['hour'].":".$date['minute'].":".$date['second'];
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
            $id = $cust_id->id;
            $buy = $this->base_model->get_data('deal' , 'money_id , volume_rest , convert_money' , 'result' , array('type_deal'=> 1 , 'customer_id'=> $id));
            $sell = $this->base_model->get_data('deal' , 'money_id , volume_rest , convert_money' , 'result' , array('type_deal'=> 2 , 'customer_id'=> $id));
            if(sizeof($buy) == 0){
                $buy_dollar = 0;
                $buy_euro = 0;
                $buy_yuan = 0;
                $buy_derham = 0;
                $give_rial = 0;
            }else{
                $b_dollar = 0; $b_euro = 0 ; $b_yuan = 0 ; $b_derham = 0; $g_rial = 0;
                foreach($buy as $buys){
                    if($buys->money_id == 1){
                        $b_dollar += ($buys->volume_rest)/($buys->convert_money);
                    }else if($buys->money_id == 2){
                        $b_euro += ($buys->volume_rest)/($buys->convert_money);
                    }else if($buys->money_id == 3){
                        $b_yuan += ($buys->volume_rest)/($buys->convert_money);
                    }else if($buys->money_id == 4){
                        $b_derham += ($buys->volume_rest)/($buys->convert_money);
                    }
                    $g_rial += $buys->volume_rest;
            }
            $buy_dollar = $b_dollar;
            $buy_euro = $b_euro;
            $buy_yuan = $b_yuan;
            $buy_derham = $b_derham;
            $give_rial = $g_rial;;
        }
            if(sizeof($sell) == 0){
                $sell_dollar = 0;
                $sell_euro = 0;
                $sell_yuan = 0;
                $sell_derham = 0;
                $want_rial = 0;
            }else{
                $s_dollar = 0; $s_euro = 0 ; $s_yuan = 0 ; $s_derham = 0; $w_rial = 0;
                foreach($sell as $sells){
                    if($sells->money_id == 1){
                        $s_dollar += ($sells->volume_rest)/($sells->convert_money);
                    }else if($sells->money_id == 2){
                        $s_euro += ($sells->volume_rest)/($sells->convert_money);
                    }else if($sells->money_id == 3){
                        $s_yuan += ($sells->volume_rest)/($sells->convert_money);
                    }else if($sells->money_id == 4){
                        $s_derham += ($sells->volume_rest)/($sells->convert_money);
                    }
                    $w_rial += $sells->volume_rest;
                }
                $sell_dollar = $s_dollar;
                $sell_euro = $s_euro;
                $sell_yuan = $s_yuan;
                $sell_derham = $s_derham;
                $want_rial = $w_rial;
            }
            $data['dollar'] = $buy_dollar - $sell_dollar;
            $data['euro'] = $buy_euro - $sell_euro;
            $data['yuan'] = $buy_yuan - $sell_yuan;
            $data['derham'] = $buy_derham - $sell_derham;
            $data['rial'] = $give_rial - $want_rial;
            // echo "<pre>";var_dump($data);echo "</pre>";
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
              $customer['fullname'] = $this->input->post('customer');
              $cust_id = $this->input->post('cust_id');
              $check = $this->base_model->get_data('customer' , 'id' , 'row' , array('fullname' =>  $customer['fullname']));
              if(sizeof($check) != 0 and $check->id != $cust_id){
                $message['msg'][0] = 'این نام '.$customer['fullname'] . " قبلا استفاده شده است . لطفا جهت تمایز در نام مشتری ها از نام دیگری استفاده کنید ";
                $message['msg'][1] = 'danger';
                $this->session->set_flashdata($message);
                redirect("deal/edit/$id");
              }
              $this->base_model->update_data('customer' , $customer , array('id'=> $cust_id));
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
             $deal['convert_money'] = $this->input->post('convert_money');
             $deal['volume_deal'] = ($deal['count_money'] + $deal['wage']) * $deal['convert_money'];
             $deal['explain'] = $this->input->post('explain');
             $deal['date_modified'] = $date['year']."-".$date['month_num']."-".$date['day']."</br>".$date['hour'].":".$date['minute'].":".$date['second'];
             $deal['date_deal'] = $date_deal;
             $deal['time_deal'] = $time_deal;
             $deal['volume_rest'] = $deal['volume_deal'] - $this->input->post('volume_pay');
             $deal['money_id'] = $this->input->post('money_id');
             $base = $this->base_model->get_data('deal' , 'count_money , wage , money_id , type_deal , volume_deal , convert_money' , 'row' , array('id' => $id));
             $base_unit = $this->base_model->get_data('currency_unit' , 'amount_unit , name' , 'row' , array('id'=> $base->money_id));
              
             $base_count = $base->count_money + $base->wage;
             $send_count = $deal['count_money'] + $deal['wage'];

             $count_deal = " تعداد ارز معامله از : ".number_format($base->count_money)." به ".number_format($deal['count_money'])." تغییر یافت "."</br>";
             $wage_deal = "  کارمزد معامله از : ".number_format($base->wage)." به ".number_format($deal['wage'])." تغییر یافت "."</br>";
             $convert_deal = " نرخ تبدیل معامله از  ".number_format($base->convert_money)." به ".number_format($deal['convert_money'])." تغییر یافت "."</br>";
             $volume_deal = " حجم  معامله از  ".number_format($base->volume_deal)." به ".number_format($deal['volume_deal'])." تغییر یافت "."</br>";

             if($base->money_id != $deal['money_id']){
                $new_unit = $this->base_model->get_data('currency_unit' , 'amount_unit , name' , 'row' , array('id'=> $deal['money_id']));

                 if($base->type_deal == 1){
                    $update_base['amount_unit'] = $base_unit->amount_unit - ($base_count);
                    $update_send['amount_unit'] = $new_unit->amount_unit + ($send_count);
                 }else{
                    $update_base['amount_unit'] = $base_unit->amount_unit + ($base_count);
                    $update_send['amount_unit'] = $new_unit->amount_unit - ($send_count);
                 }
                 $change_unit = "  ارز معامله از : ".$base_unit->name." به ".$new_unit->name." تغییر یافت "."</br>";
                 $this->base_model->update_data('currency_unit' , $update_base , array('id' => $base->money_id));
                 $this->base_model->update_data('currency_unit' , $update_send , array('id' => $deal['money_id']));
             }else{
                 if($base->type_deal == 1){
                   $update_base['amount_unit'] = $base_unit->amount_unit + ($send_count - $base_count);
                  }else{
                    $update_base['amount_unit'] = $base_unit->amount_unit - ($send_count - $base_count);
                 }
                 $change_unit = '  ارز معامله :'.$base_unit->name."</br>";
                 $this->base_model->update_data('currency_unit' , $update_base , array('id' => $base->money_id));
             }


             $res = $this->base_model->update_data('deal' , $deal , array('id'=> $id));
             if($res == FALSE){
                 $message['msg'][0] = 'مشکلی در ثبت اطلاعات رخ داده است. لطفا دوباره سعی کنید';
                 $message['msg'][1] = 'danger';
                 $this->session->set_flashdata($message);
                 redirect("deal/edit/$id");
             } 
            if(isset($_POST['number_shaba'][0])){
              for($i = 0 ; $i < sizeof($_POST['number_shaba']) ; $i++){
                  $bank[] = array(
                      'id'=>htmlspecialchars($_POST['bank_id'][$i]),
                      'explain'=> htmlspecialchars($_POST['bank_explain'][$i]),
                      'number_shaba'=>htmlspecialchars($_POST['number_shaba'][$i]),
                      'name_bank'=> htmlspecialchars($_POST['name_bank'][$i]),
                      'amount'=> htmlspecialchars($_POST['amount_bank'][$i])
                  );
              }
            }
            if(isset($_POST['send_shaba'][0])){
                for($i = 0 ; $i < sizeof($_POST['send_shaba']) ; $i++){
                    if($_POST['send_shaba'][$i] != '' or $_POST['send_bank'][$i] != '' or $_POST['send_amount'][$i] != '' or $_POST['send_explain'][$i] != ''){
                        $new_bank[] = array(
                            'explain'=> htmlspecialchars($_POST['send_explain'][$i]),
                            'number_shaba'=>htmlspecialchars($_POST['send_shaba'][$i]),
                            'name_bank'=> htmlspecialchars($_POST['send_bank'][$i]),
                            'amount'=> htmlspecialchars($_POST['send_amount'][$i]),
                            'pay'=> 0,
                            'deal_id' => $id ,
                            'active' => 1
                        );
                    }
                }
              }
              if(isset($new_bank) and sizeof($new_bank) != 0){
                $this->base_model->insert_batch('deal_bank' , $new_bank);
              }
              if(isset($bank) and sizeof($bank) != 0){
                $this->base_model->update_batch('deal_bank' , $bank , 'id');
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
                
                if(sizeof($data['deal']) == 0){
                    show_404();
                }else{
                    $slash = '/';
                    $dash = '-';
                    $str = $data['deal']->date_deal;
                    $data['date_deal'] = str_replace($dash, $slash , $str);
                    $date = $this->convertdate->convert(time());
                    $data['bank'] = $this->base_model->get_data('deal_bank' , '*' , 'result' , array('deal_id'=> $id));
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

     //----- handle -----//
    public function handle(){
        $id = $this->uri->segment(3);
        if(isset($id) and is_numeric($id)){
            if(isset($_POST['sub'])){
                if(!$this->session->has_userdata('add_handle') or $this->session->userdata('add_handle') != TRUE){
                    show_404();
                }
                $date = $this->convertdate->convert(time());
                $d = $date['year']."-".$date['month_num']."-".$date['day'];
                $t = $date['hour'].":".$date['minute'].":".$date['second'];
                $exp = '';
                for($i = 0 ; $i < sizeof($_POST['customer']) ; $i++){
                    if($_POST['bank_id'][$i] == 0){
                        $message['msg'][0] = 'لطفا شماره حساب را از لیست مربوطه انتخاب کنید . در صورت موجود نبودن شماره حساب لطفا اقدام به افزودن شماره حساب کنید' ;
                        $message['msg'][1] = 'danger';
                        $this->session->set_flashdata($message);
                        redirect("deal/handle/$id");
                     }else{
                    $check = $this->base_model->get_data('customer' , 'id' , 'row' , array('fullname'=> $_POST['customer'][$i]));
                    if(sizeof($check) == 0){
                        $customer = array(
                            'fullname'=>$_POST['customer'][$i],
                            'address'=>'',
                            'email'=>'',
                            'customer_tel'=> '',
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
                $k = $i + 1;
                $s = $id +100;
                $b = $_POST['bank_id'][$i] + 1000;
                $str .= $k." - نام هماهنگ کننده : ".$_POST['customer'][$i]." | مبلغ هماهنگی : ".number_format($_POST['volume_handle'][$i])." | شناسه معامله : ".$s."| شناسه بانک : ". $b ."</br>"; 
                }
                $res = $this->base_model->insert_batch('deal_handle' , $handle);
                $log['user_id'] = $this->session->userdata('id');
                $log['date_log'] = $d;
                $log['time_log'] = $t;
                $log['activity_id'] = 12;
                $log['explain'] = $str;
                $this->base_model->insert_data('log' , $log);
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
                if(!$this->session->has_userdata('see_handle') or $this->session->userdata('see_handle') != TRUE){
                    show_404();
                }
                $header['title'] = 'هماهنگی ها';
                $header['active'] = 'deal';
                $header['active_sub'] = 'deal_archive';
                $data['deal'] = $this->base_model->get_data_join('deal' ,'customer', 'deal.* , customer.fullname ,customer.id as cust_id, currency_unit.name , sum(deal_handle.volume_handle) as vh , sum(deal_handle.handle_rest) as vr' , 'deal.customer_id = customer.id' ,'row'  , array('deal.id'=>$id) , NULL , NULL , NULL , array('currency_unit','deal.money_id = currency_unit.id') , array('deal_handle','deal_handle.deal_id = deal.id'));
                if(sizeof($data['deal']) == 0 or $data['deal']->type_deal != 1){
                show_404();
                }else{
                    $data['customer'] = $this->base_model->get_data('customer' , 'fullname , id' , 'result');
                    $want = $this->base_model->get_data('deal','customer_id , sum(deal.volume_rest) as want' , 'result' , array('deal.type_deal'=> 1), NULL , NULL , NULL , 'customer_id');
                    $give = $this->base_model->get_data('deal','customer_id , sum(deal.volume_rest) as give' , 'result' , array('deal.type_deal'=> 2), NULL , NULL , NULL , 'customer_id');
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
                    $data['bank'] = $this->base_model->get_data('deal_bank' , '*' , 'result' , array('deal_id' => $id) , NULL , NULL , array('id' , 'DESC'));
                    $data['select'] = $this->base_model->get_data('deal_bank' , 'id' , 'result' , array('deal_id' => $id , 'active' => 1) , NULL , NULL , array('id' , 'DESC'));
                    $data['handle'] = $this->base_model->get_data_join('deal_handle','customer' , 'deal_handle.* , customer.fullname','deal_handle.customer_id = customer.id', 'result' , array('deal_handle.deal_id' => $id) , NULL , NULL , array('deal_handle.id' , 'DESC'));
                    $this->load->view('header' , $header);
                    $this->load->view('deal/handle' , $data);
                    $this->load->view('footer');
                }
     
            }
           
        }else{
            show_404();
        }
    }
    //----- handle -----//
    //----- add_bank -----//
    public function add_bank(){
        if(!$this->session->has_userdata('add_bank') or $this->session->userdata('add_bank') != TRUE){
            show_404();
        }
        $id = $this->uri->segment(3);
        if(isset($id) and is_numeric($id)){
            if(isset($_POST['sub'])){
                $data['explain'] = $this->input->post('bank_explain');
                $data['name_bank'] = $this->input->post('name_bank');
                $data['number_shaba'] = $this->input->post('number_shaba');
                $data['amount'] = $this->input->post('amount_bank');
                $data['pay'] = 0;
                $data['deal_id'] = $id;
                $data['active'] = 1;
                $res = $this->base_model->insert_data('deal_bank' , $data);
                $a = $id + 100;
                $date = $this->convertdate->convert(time());
                $log['user_id'] = $this->session->userdata('id');
                $log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
                $log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
                $log['activity_id'] = 17;
                $log['explain'] = ' حساب جدید مربوط به شناسه معامله '.$a." با مشخصات :  "."</br> شماره شبا : ".$data['number_shaba']." </br> نام بانک : ".$data['name_bank']." </br> مقدار تعیین شده :  ".number_format($data['amount'])." </br> توضحیات :".$data['explain']."</br> افزوده شد ";
                $this->base_model->insert_data('log' , $log);
                if($this->uri->segment(5) == 'group'){
                    $red = 'handle_profile';
                    $red_id = $this->uri->segment(4);
                }else{
                    $red = 'handle';
                    $red_id = $id;
                }
                if($res == FALSE){
                    $message['msg'][0] = 'مشکلی در ثبت اطلاعات رخ داده است . لطفا دوباره سعی کنید';
                    $message['msg'][1] = 'danger';
                    $this->session->set_flashdata($message);
                    redirect("deal/$red/$red_id");
                }else{
                  $message['msg'][0] = 'اطلاعات حساب بانکی با موفقیت ثبت شد';
                  $message['msg'][1] = 'success';
                  $this->session->set_flashdata($message);
                  redirect("deal/$red/$red_id");
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
$bank = $this->base_model->get_data('deal_bank' , '*' , 'row' , array('id'=> $id));
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
      $bank = $this->base_model->get_data('deal_bank' , '*' , 'row' , array('id'=> $id));          
      $data['number_shaba'] = $this->input->post('number_shaba');
      $data['name_bank'] = $this->input->post('name_bank');
      $data['amount'] = $this->input->post('amount_bank');
      $data['explain'] = $this->input->post('bank_explain');
      $a = $bank->deal_id + 100;
      $num_sh = " شماره شبا از ".$bank->number_shaba." به ".$data['number_shaba']." تغییر یافت "."</br>";
      $nam_ba = "  نام بانک از ".$bank->name_bank." به ".$data['name_bank']." تغییر یافت "."</br>";
      $amo = " مقدار تعیین شده از ".number_format($bank->amount)." به ".number_format($data['amount'])." تغییر یافت "."</br>";
      $res = $this->base_model->update_data('deal_bank' , $data , array('id'=>$id));
      $date = $this->convertdate->convert(time());
      $log['user_id'] = $this->session->userdata('id');
      $log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
      $log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
      $log['activity_id'] = 18;
      $log['explain'] = "اطلاعات حساب مربوط به شناسه معامله ".$a."</br>".$num_sh.$nam_ba.$amo.' توضحیات :'.$data['explain']."</br>"." ویرایش شد ";
      $this->base_model->insert_data('log' , $log);
      $message['msg'][0] = 'اطلاعات حساب بانکی با موفقیت ویرایش شد';
      $message['msg'][1] = 'success';
      $this->session->set_flashdata($message);
      if($this->uri->segment(5) == 'group'){
          $red = 'handle_profile';
      }else{
          $red = 'handle';
      }
      redirect("deal/$red/$red_id");
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
        show_bank();
    }
    $red_id = $this->uri->segment(3);
    $id = $this->uri->segment(4);
    if(isset($red_id) and isset($id) and is_numeric($red_id) and is_numeric($id)){
        $data['active'] = $this->uri->segment(5);
        $this->base_model->update_data('deal_bank' , $data , array('id' => $id));
        $bank = $this->base_model->get_data('deal_bank' , 'name_bank , number_shaba , deal_id , amount' , 'row' , array('id'=> $id));
        $a = $bank->deal_id + 100;
        if($data['active'] == 1){$txt = " را فعال کرد ";}else{$txt = ' را غیر فعال کرد ';}
        $date = $this->convertdate->convert(time());
        $log['user_id'] = $this->session->userdata('id');
        $log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
        $log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
        $log['activity_id'] = 19;
        $log['explain'] = " حساب بانکی مربوط به شناسه معامله  ".$a." با مشخصات : "." </br> شماره شبا : ".$bank->number_shaba." </br> نام بانک :  ".$bank->name_bank."</br> مقدار تعیین شده : ".number_format($bank->amount)."</br>". $txt;
        $this->base_model->insert_data('log' , $log);
        $seg = $this->uri->segment(6);
        if($seg == 'group'){
            $red = 'handle_profile';
        }else{
            $red = 'handle';
        }
        redirect("deal/$red/$red_id");
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
    $deal_id = $this->uri->segment(3);
    $id = $this->uri->segment(4);
    if(isset($deal_id) and isset($id) and is_numeric($deal_id) and is_numeric($id)){

        $handle = $this->base_model->get_data_join('deal_handle' , 'deal' , 'deal_handle.handle_pay , deal_handle.handle_rest , deal_handle.bank_id , deal.volume_pay , deal.volume_rest , deal_bank.pay , deal_bank.number_shaba , deal_bank.name_bank , deal_bank.amount , deal_bank.explain' , 'deal_handle.deal_id = deal.id' , 'row' , array('deal_handle.id'=> $id) , NULL , NULL , NULL , array('deal_bank','deal_bank.id = deal_handle.bank_id'));
        if($handle->handle_rest < 0){
            show_404();
        }
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
        $log['user_id'] = $this->session->userdata('id');
        $log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
        $log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
        $log['activity_id'] = 13;
        $aa = $deal_id +100;
        $log['explain'] = " مقدار ".number_format($handle->handle_rest)." به حساب بانکی با مشخصات : </br> شماره شبا : ".$handle->number_shaba." </br> نام بانک : ".$handle->name_bank." </br> مقدار تعیین شده :  ".number_format($handle->amount)."</br> توضحیات : ".$handle->explain."</br> مربوط به شناسه معامله  ".$aa. " به صورت کامل پرداخت شد ";
        $deal_handle['date_modified'] =  $date['year']."-".$date['month_num']."-".$date['day']."</br>".$date['hour'].":".$date['minute'].":".$date['second'];
        $this->base_model->update_data('deal' , $deal , array('id'=>$deal_id));
        $this->base_model->update_data('deal_handle' , $deal_handle , array('id' => $id));
        $this->base_model->update_data('deal_bank' , $deal_bank , array('id' => $handle->bank_id));
        $this->base_model->insert_data('log' , $log);
        $res = $this->base_model->insert_data('handle_history' , $history);
        if($this->uri->segment(5) == 'group'){
        $red = 'handle_profile';
        $red_id = $this->uri->segment(6);
        }else{
          $red = 'handle';
          $red_id = $deal_id;
        }
        if($res == FALSE){
            $message['msg'][0] = 'مشکلی در ثبت اطلاعات رخ داده است . لطفا دوباره سعی کنید';
            $message['msg'][1] = 'danger';
            $this->session->set_flashdata($message);
            redirect("deal/$red/$red_id");
        }else{
          $message['msg'][0] = 'پرداخت به صورت کامل انجام شد';
          $message['msg'][1] = 'success';
          $this->session->set_flashdata($message);
          redirect("deal/$red/$red_id");
        }

    }else{
     show_404();
    }
}
//----pay all -----//
//----pay slice ----//
public function pay_slice(){
    $deal_id = $this->uri->segment(3);
    $id = $this->uri->segment(4);
    if(isset($deal_id) and isset($id) and is_numeric($deal_id) and is_numeric($id)){
        if(isset($_POST['sub'])){
            $handle = $this->base_model->get_data_join('deal_handle','deal','deal_handle.handle_pay,deal_handle.handle_rest,deal_handle.bank_id,deal.volume_pay , deal.volume_rest ,  deal_bank.pay , deal_bank.number_shaba , deal_bank.name_bank , deal_bank.amount , deal_bank.explain' , 'deal_handle.deal_id = deal.id' , 'row' , array('deal_handle.id'=> $id) , NULL , NULL , NULL , array('deal_bank','deal_bank.id = deal_handle.bank_id'));
            if($handle->handle_rest < 0){
                show_404();
            }
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
            $log['user_id'] = $this->session->userdata('id');
            $log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
            $log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
            $log['activity_id'] = 14;
            $aa = $deal_id +100;
            $log['explain'] = " مقدار ".number_format($slice)." به حساب بانکی با مشخصات : </br> شماره شبا : ".$handle->number_shaba." </br> نام بانک : ".$handle->name_bank." </br> مقدار تعیین شده :  ".number_format($handle->amount)."</br> توضحیات : ".$handle->explain."</br> مربوط به شناسه معامله  ".$aa. " به صورت جزئی پرداخت شد ";
            $deal_handle['date_modified'] = $log['date_log']."</br>".$log['time_log'];
            $this->base_model->update_data('deal' , $deal , array('id' => $deal_id));
            $this->base_model->update_data('deal_handle' , $deal_handle , array('id' => $id));
            $this->base_model->update_data('deal_bank' , $deal_bank , array('id' => $handle->bank_id));
            $this->base_model->insert_data('log' , $log);
            $res = $this->base_model->insert_data('handle_history' , $history);
            if($this->uri->segment(5) == 'group'){
                $red = 'handle_profile';
                $red_id = $this->uri->segment(6);
            }else{
                $red = 'handle' ;
                $red_id = $deal_id;
            }
            if($res == TRUE){
                $message['msg'][0] = 'پرداخت با موفقیت انجام شد';
                $message['msg'][1] = 'success';
                $this->session->set_flashdata($message);
                redirect("deal/$red/$red_id");
            }else{
                $message['msg'][0] = 'مشکلی در روند عملیات رخ داده است . لطفا دوباره سعی کنید';
                $message['msg'][1] = 'danger';
                $this->session->set_flashdata($message);
                redirect("deal/$red/$red_id");
            }
    
            }else{
                show_404();
            }
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
       $history = $this->base_model->get_data('handle_history' , 'handle_history.*' , 'result' , array('handle_id'=> $handle_id , 'active'=> 1));
       echo json_encode($history);
    }else{
        show_404();
    }
}
public function restore(){
    if(!$this->session->has_userdata('restore') or $this->session->userdata('restore') != TRUE){
        show_404();
    }
    $id = $this->uri->segment(3);
    $deal_id = $this->uri->segment(4);
    if(isset($id) and isset($deal_id) and is_numeric($id) and is_numeric($deal_id)){
        $res = $this->base_model->get_data('handle_history' , 'handle_id ,volume' , 'row' , array('id' => $id , 'active'=> 1));
        if(sizeof($res) == 0){
            show_404();
        }else{
            $handle_id = $res->handle_id;
            $restore = $res->volume;
            $store = $this->base_model->get_data_join('deal_handle' , 'deal' , 'deal_handle.handle_pay , deal_handle.handle_rest , deal.volume_pay , deal.volume_rest  ,deal_bank.pay , deal_handle.bank_id' , 'deal_handle.deal_id = deal.id' , 'row' , array('deal_handle.id' => $handle_id) , NULL , NULL , NULL , array('deal_bank' , 'deal_bank.id = deal_handle.bank_id'));
           $bank_id = $store->bank_id;
           $handle['handle_pay'] = $store->handle_pay - $restore;
           $handle['handle_rest'] = $store->handle_rest + $restore;
           $deal['volume_pay'] = $store->volume_pay - $restore;
           $deal['volume_rest'] = $store->volume_rest + $restore;
           $bank['pay'] = $store->pay - $restore;
           $history['active'] = 0;
           $date = $this->convertdate->convert(time());
           $log['user_id'] = $this->session->userdata('id');
           $log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
           $log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
           $log['activity_id'] = 15;
           $aa = $deal_id + 100;
           $log['explain'] = "شناسه معامله : ".$aa." | مبلغ پرداخت : ".number_format($restore)." را باز گردانید ";
           $handle['date_modified'] = $log['date_log']."</br>".$log['time_log'];
           $this->base_model->update_data('handle_history' , $history , array('id' => $id));
           $this->base_model->update_data('deal_handle', $handle , array('id' => $handle_id));
           $this->base_model->update_data('deal' , $deal , array('id' => $deal_id));
           $this->base_model->insert_data('log' , $log);
           $res = $this->base_model->update_data('deal_bank' , $bank , array('id'=> $bank_id));
           if($this->uri->segment(5) == 'group'){
            $red = 'handle_profile';
            $red_id = $this->uri->segment(6);
        }else{
            $red = 'handle' ;
            $red_id = $deal_id;
        }
        if($res == TRUE){
            $message['msg'][0] = 'مبلغ مورد نظر با موفقیت بازگرداننده شد';
            $message['msg'][1] = 'success';
            $this->session->set_flashdata($message);
            redirect("deal/$red/$red_id");
        }else{
            $message['msg'][0] = 'مشکلی در روند عملیات رخ داده است . لطفا دوباره سعی کنید';
            $message['msg'][1] = 'danger';
            $this->session->set_flashdata($message);
            redirect("deal/$red/$red_id");
        }

        }
       
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
    public function search_deal(){
        if(isset($_POST['deal_id']) and isset($_POST['customer_id']) and is_numeric($_POST['deal_id']) and is_numeric($_POST['customer_id'])){
            $deal_id = $this->input->post('deal_id');
            $customer_id = $this->input->post('customer_id');
            $data = $this->base_model->get_data_join('deal' ,'customer', 'deal.* , customer.fullname ,currency_unit.name' , 'deal.customer_id = customer.id' ,'result'  , array('deal.customer_id'=> $customer_id , 'deal.id'=>$deal_id , 'deal.type_deal'=> 1), NULL , NULL , NULL , array('currency_unit','deal.money_id = currency_unit.id'));
            echo json_encode($data);
        }else{
            show_404();
        }
        } 
    public function handle_profile(){
        $id = $this->uri->segment(3);
        if(isset($id) and is_numeric($id)){
            if(isset($_POST['sub'])){
                if(!$this->session->has_userdata('add_handle') or $this->session->userdata('add_handle') != TRUE){
                    show_404();
                }
                $date = $this->convertdate->convert(time());
                $d = $date['year']."-".$date['month_num']."-".$date['day'];
                $t = $date['hour'].":".$date['minute'].":".$date['second'];
                $str = '';
                for($i = 0 ; $i < sizeof($_POST['customer']) ; $i++){
                    if($_POST['bank_id'][$i] == 0){
                        $message['msg'][0] = 'لطفا شماره حساب را از لیست مربوطه انتخاب کنید . در صورت موجود نبودن شماره حساب لطفا اقدام به افزودن شماره حساب کنید' ;
                        $message['msg'][1] = 'danger';
                        $this->session->set_flashdata($message);
                        redirect("deal/handle_profile/$id");
                     }else{
                        $a = $_POST['deal_id'][$i] - 100;
                        $check_deal = $this->base_model->get_data('deal' , 'id' , 'row' , array('id' => $a , 'customer_id'=>$id , 'type_deal'=> 1));
                        if(sizeof($check_deal) == 0){
                            $message['msg'][0] = 'لطفا در انتخاب معامله دقت فرمایید که شناسه معامله باید مربوط به معاملات آن شخص و از نوع خرید باشد';
                            $message['msg'][1] = 'danger';
                            $this->session->set_flashdata($message);
                            redirect("deal/handle_profile/$id");
                        }
                    $check = $this->base_model->get_data('customer' , 'id' , 'row' , array('fullname'=> $_POST['customer'][$i]));
                    if($check == FALSE){
                        $customer = array(
                            'fullname'=>$_POST['customer'][$i],
                            'address'=>'',
                            'email'=>'',
                            'customer_tel'=> '',
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
                      'deal_id'=> $a,
                      'bank_id' => htmlspecialchars($_POST['bank_id'][$i])
                    );
                }
                $k = $i + 1;
                $s = $_POST['deal_id'][$i];
                $str .= $k." - نام هماهنگ کننده : ".$_POST['customer'][$i]." | مبلغ هماهنگی : ".$_POST['volume_handle'][$i]." | شناسه معامله : ".$s."</br>"; 
                }
                $res = $this->base_model->insert_batch('deal_handle' , $handle);
                $log['user_id'] = $this->session->userdata('id');
                $log['date_log'] = $d;
                $log['time_log'] = $t;
                $log['activity_id'] = 12;
                $log['explain'] = $str;
                $this->base_model->insert_data('log' , $log);
                if($res == FALSE){
                    $message['msg'][0] = 'مشکلی در ثبت اطلاعات رخ داده است . لطفا دوباره سعی کنید';
                    $message['msg'][1] = 'danger';
                    $this->session->set_flashdata($message);
                    redirect("deal/handle_profile/$id");
                }else{
                    $message['msg'][0] = 'اطلاعات هماهنگی با موفقیت ثبت شد';
                    $message['msg'][1] = 'success';
                    $this->session->set_flashdata($message);
                    redirect("deal/handle_profile/$id");
                }
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
            $data['deal'] = $this->base_model->get_data_join('deal' ,'customer', 'deal.* , customer.fullname ,currency_unit.name' , 'deal.customer_id = customer.id' ,'result'  , array('deal.customer_id'=> $id), $config['per_page'] , $page , array('deal.id' , 'DESC') , array('currency_unit','deal.money_id = currency_unit.id'));
            $data['page'] = $this->pagination->create_links();
            if(sizeof($data['deal']) == 0){
                show_404();
            }else{
            $data['bank'] = $this->base_model->get_data_join('deal' , 'deal_bank'  ,'deal.id as deal_id , deal_bank.*','deal.id = deal_bank.deal_id' ,'result' ,array('deal.customer_id' => $id) , NULL , NULL , array('deal_bank.id', 'DESC'));
            $data['select2'] = $this->base_model->get_data_join('deal' , 'deal_bank' ,'deal_bank.id , deal.id as deal_id','deal.id = deal_bank.deal_id' , 'result' , array('deal.customer_id' => $id , 'active' => 1) , NULL , NULL , array('deal_bank.id' , 'DESC'));          
            $data['handle'] = $this->base_model->get_data_join('deal_handle' , 'deal' , 'deal_handle.* , customer.fullname , deal_bank.name_bank , deal_bank.number_shaba' , 'deal_handle.deal_id = deal.id' , 'result' , array('deal.customer_id'=>$id) , NULL , NULL , array('deal_handle.id' , 'DESC'),array('customer' , 'deal_handle.customer_id = customer.id') , array('deal_bank' , 'deal_handle.bank_id = deal_bank.id'));  
            $data['customer'] = $this->base_model->get_data('customer' , 'fullname , id' , 'result');
            $want = $this->base_model->get_data('deal','customer_id , sum(deal.volume_rest) as want' , 'result' , array('deal.type_deal'=> 1), NULL , NULL , NULL , 'customer_id');
            $give = $this->base_model->get_data('deal','customer_id , sum(deal.volume_rest) as give' , 'result' , array('deal.type_deal'=> 2), NULL , NULL , NULL , 'customer_id');
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
        }
        }else{
            show_404();
        }

    }
}

/* End of file Controllername.php */

?>