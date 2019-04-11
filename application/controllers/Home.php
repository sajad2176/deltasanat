<?php
class Home extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->load->library('Convertdate');
    }
    public function index(){
        if(!$this->session->has_userdata('see_dashbord') or $this->session->userdata('see_dashbord') != TRUE){
           $header['title'] = 'خانه';
           $header['active'] = '';
           $header['active_sub'] = '';
            $this->load->view('header' , $header);
            $this->load->view('footer');
        }else{
       $rate = $this->base_model->get_data('rate', '*' , 'row'  , NULL , 1 , 0 , array('id' , 'DESC'));    
        $header['title'] = 'داشبورد';
        $header['active'] = 'dashbord';
        $header['active_sub'] = '';
        $date = $this->convertdate->convert(time());
        $start_date =  $date['year']."-".$date['month_num']."-".$date['day'];
        $data['today'] = $date['day']." ".$date['month_name']." ".$date['year'];
        $data['remain'] = $this->base_model->get_data('currency_unit' , '*');
        $data['sum_handle'] = $this->base_model->get_data('deal_handle' , 'sum(volume_handle) as vh , sum(handle_rest) as hr' , 'row');
        $data['sum_pay'] = $this->base_model->get_data('deal' , 'sum(volume_pay) as vp' , 'row');

        $buy = $this->base_model->get_data('deal' , 'count_money , wage , money_id , volume_deal' , 'result' , array('type_deal'=> 1 , 'date_deal'=> $start_date));
        $sell = $this->base_model->get_data('deal' , 'count_money , wage , money_id , volume_deal' , 'result' , array('type_deal'=> 2 , 'date_deal'=> $start_date));
        if(sizeof($buy) == 0){
            $data['buy_dollar'] = 0;
            $data['buy_euro'] = 0;
            $data['buy_yuan'] = 0;
            $data['buy_derham'] = 0;
            $data['ave_buy'] = 0;
            $buy_rial = 0;
        }else{
            $buy_dollar = 0; $buy_euro = 0 ; $buy_yuan = 0 ; $buy_derham = 0; $volume_euro = 0; $volume_yuan = 0; $volume_derham = 0; $buy_rial = 0;
            foreach($buy as $buys){
                if($buys->money_id == 1){
                    $buy_dollar += $buys->count_money + $buys->wage;
                }else if($buys->money_id == 2){
                    $buy_euro += $buys->count_money + $buys->wage;
                    $volume_euro += $buys->volume_deal;
                }else if($buys->money_id == 3){
                    $buy_yuan += $buys->count_money + $buys->wage;
                    $volume_yuan += $buys->volume_deal;
                }else if($buys->money_id == 4){
                    $buy_derham += $buys->count_money + $buys->wage;
                    $volume_derham += $buys->volume_deal;
                }
                $buy_rial += $buys->volume_deal;
        }
        $data['buy_dollar'] = $buy_dollar;
        $data['buy_euro'] = $buy_euro;
        $data['buy_yuan'] = $buy_yuan;
        $data['buy_derham'] = $buy_derham;
        if(sizeof($rate) != 0 ){
            $dollar_e = $buy_euro / $rate->rate_euro;
            $dollar_y = $buy_yuan / $rate->rate_yuan;
            $dollar_d = $buy_derham / $rate->rate_derham;
            if($dollar_e == 0 and $dollar_y == 0 and $dollar_d == 0){
                $data['ave_buy'] = 0;
            }else{
                $data['ave_buy'] = ($volume_euro + $volume_yuan + $volume_derham) / ($dollar_e + $dollar_y + $dollar_d);
            }
            
        }else{
            $data['ave_buy'] = 0;
        }
    }
        if(sizeof($sell) == 0){
            $data['sell_dollar'] = 0;
            $data['sell_euro'] = 0;
            $data['sell_yuan'] = 0;
            $data['sell_derham'] = 0;
            $data['ave_sell'] = 0;
            $sell_rial = 0;
        }else{
            $sell_dollar = 0; $sell_euro = 0 ; $sell_yuan = 0 ; $sell_derham = 0; $volume_euro = 0; $volume_yuan = 0; $volume_derham = 0;$sell_rial = 0;
            foreach($sell as $sells){
                if($sells->money_id == 1){
                    $sell_dollar += $sells->count_money + $sells->wage;
                }else if($sells->money_id == 2){
                    $sell_euro += $sells->count_money + $sells->wage;
                    $volume_euro += $sells->volume_deal;
                }else if($sells->money_id == 3){
                    $sell_yuan += $sells->count_money + $sells->wage;
                    $volume_yuan += $sells->volume_deal;
                }else if($sells->money_id == 4){
                    $sell_derham += $sells->count_money + $sells->wage;
                    $volume_derham += $sells->volume_deal;
                }
                $sell_rial += $sells->volume_deal;
            }
            $data['sell_dollar'] = $sell_dollar;
            $data['sell_euro'] = $sell_euro;
            $data['sell_yuan'] = $sell_yuan;
            $data['sell_derham'] = $sell_derham;
            if(sizeof($rate) != 0 ){
                $dollar_e = $sell_euro / $rate->rate_euro;
                $dollar_y = $sell_yuan / $rate->rate_yuan;
                $dollar_d = $sell_derham / $rate->rate_derham;
                if($dollar_e == 0 and $dollar_y == 0 and $dollar_d == 0){
                    $data['ave_sell'] = 0;
                }else{
                    $data['ave_sell'] = ($volume_euro + $volume_yuan + $volume_derham) / ($dollar_e + $dollar_y + $dollar_d);
                }
                
            }else{
                $data['ave_sell'] = 0;
            }
        }
        $data['rest_rial'] = $buy_rial - $sell_rial; 
        $this->load->view('header' , $header);
        $this->load->view('home/home' , $data);
        $this->load->view('footer');
    }
    }
    public function update_dashbord(){
      if(isset($_POST['request']) and $_POST['request'] == true and $this->session->has_userdata('see_dashbord')){
        $rate = $this->base_model->get_data('rate', '*' , 'row'  , NULL , 1 , 0 , array('id' , 'DESC'));    
        $header['title'] = 'داشبورد';
        $header['active'] = 'dashbord';
        $header['active_sub'] = '';
        $date = $this->convertdate->convert(time());
        $start_date =  $date['year']."-".$date['month_num']."-".$date['day'];
        $data['today'] = $date['day']." ".$date['month_name']." ".$date['year'];
        $data['remain'] = $this->base_model->get_data('currency_unit' , '*');
        $data['sum_handle'] = $this->base_model->get_data('deal_handle' , 'sum(volume_handle) as vh , sum(handle_rest) as hr' , 'row');
        $data['sum_pay'] = $this->base_model->get_data('deal' , 'sum(volume_pay) as vp' , 'row');

        $buy = $this->base_model->get_data('deal' , 'count_money , wage , money_id , volume_deal' , 'result' , array('type_deal'=> 1 , 'date_deal'=> $start_date));
        $sell = $this->base_model->get_data('deal' , 'count_money , wage , money_id , volume_deal' , 'result' , array('type_deal'=> 2 , 'date_deal'=> $start_date));
        if(sizeof($buy) == 0){
            $data['buy_dollar'] = 0;
            $data['buy_euro'] = 0;
            $data['buy_yuan'] = 0;
            $data['buy_derham'] = 0;
            $data['ave_buy'] = 0;
            $buy_rial = 0;
        }else{
            $buy_dollar = 0; $buy_euro = 0 ; $buy_yuan = 0 ; $buy_derham = 0; $volume_euro = 0; $volume_yuan = 0; $volume_derham = 0; $buy_rial = 0;
            foreach($buy as $buys){
                if($buys->money_id == 1){
                    $buy_dollar += $buys->count_money + $buys->wage;
                }else if($buys->money_id == 2){
                    $buy_euro += $buys->count_money + $buys->wage;
                    $volume_euro += $buys->volume_deal;
                }else if($buys->money_id == 3){
                    $buy_yuan += $buys->count_money + $buys->wage;
                    $volume_yuan += $buys->volume_deal;
                }else if($buys->money_id == 4){
                    $buy_derham += $buys->count_money + $buys->wage;
                    $volume_derham += $buys->volume_deal;
                }
                $buy_rial += $buys->volume_deal;
        }
        $data['buy_dollar'] = $buy_dollar;
        $data['buy_euro'] = $buy_euro;
        $data['buy_yuan'] = $buy_yuan;
        $data['buy_derham'] = $buy_derham;
        if(sizeof($rate) != 0 ){
            $dollar_e = $buy_euro / $rate->rate_euro;
            $dollar_y = $buy_yuan / $rate->rate_yuan;
            $dollar_d = $buy_derham / $rate->rate_derham;
            if($dollar_e == 0 and $dollar_y == 0 and $dollar_d == 0){
                $data['ave_buy'] = 0;
            }else{
                $data['ave_buy'] = ($volume_euro + $volume_yuan + $volume_derham) / ($dollar_e + $dollar_y + $dollar_d);
            }
            
        }else{
            $data['ave_buy'] = 0;
        }
    }
        if(sizeof($sell) == 0){
            $data['sell_dollar'] = 0;
            $data['sell_euro'] = 0;
            $data['sell_yuan'] = 0;
            $data['sell_derham'] = 0;
            $data['ave_sell'] = 0;
            $sell_rial = 0;
        }else{
            $sell_dollar = 0; $sell_euro = 0 ; $sell_yuan = 0 ; $sell_derham = 0; $volume_euro = 0; $volume_yuan = 0; $volume_derham = 0;$sell_rial = 0;
            foreach($sell as $sells){
                if($sells->money_id == 1){
                    $sell_dollar += $sells->count_money + $sells->wage;
                }else if($sells->money_id == 2){
                    $sell_euro += $sells->count_money + $sells->wage;
                    $volume_euro += $sells->volume_deal;
                }else if($sells->money_id == 3){
                    $sell_yuan += $sells->count_money + $sells->wage;
                    $volume_yuan += $sells->volume_deal;
                }else if($sells->money_id == 4){
                    $sell_derham += $sells->count_money + $sells->wage;
                    $volume_derham += $sells->volume_deal;
                }
                $sell_rial += $sells->volume_deal;
            }
            $data['sell_dollar'] = $sell_dollar;
            $data['sell_euro'] = $sell_euro;
            $data['sell_yuan'] = $sell_yuan;
            $data['sell_derham'] = $sell_derham;
            if(sizeof($rate) != 0 ){
                $dollar_e = $sell_euro / $rate->rate_euro;
                $dollar_y = $sell_yuan / $rate->rate_yuan;
                $dollar_d = $sell_derham / $rate->rate_derham;
                if($dollar_e == 0 and $dollar_y == 0 and $dollar_d == 0){
                    $data['ave_sell'] = 0;
                }else{
                    $data['ave_sell'] = ($volume_euro + $volume_yuan + $volume_derham) / ($dollar_e + $dollar_y + $dollar_d);
                }
                
            }else{
                $data['ave_sell'] = 0;
            }
        }
        $data['rest_rial'] = $buy_rial - $sell_rial; 
        echo json_encode($data);
      }else{
          show_404();
      }
    }
    public function logout(){
$date = $this->convertdate->convert(time());
$log['user_id'] = $this->session->userdata('id');
$log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
$log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
$log['activity_id'] = 2;
$log['explain'] = 'خروج از سامانه';
$this->base_model->insert_data('log' , $log);
$this->session->sess_destroy();

        
        redirect('login');       
    }
}
?>