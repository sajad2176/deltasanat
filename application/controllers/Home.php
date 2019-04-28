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
        $header['title'] = 'داشبورد';
        $header['active'] = 'dashbord';
        $header['active_sub'] = '';
        $rate = $this->base_model->get_data('rate', '*' , 'row'  , NULL , 1 , 0 , array('id' , 'DESC')); 
        $date = $this->convertdate->convert(time());
        $start_date =  $date['year']."-".$date['month_num']."-".$date['day'];
        $data['today'] = $date['day']." ".$date['month_name']." ".$date['year'];
        $data['remain'] = $this->base_model->get_data('unit' , '*' , 'result' , array('id < ' => 10));
        $buy_not = $this->base_model->run_query("SELECT SUM(d.volume) AS volume, max(h.volume_handle) AS handle  FROM  deal d LEFT JOIN (SELECT buy_id, SUM(volume_handle) AS volume_handle FROM handle GROUP BY buy_id) h ON h.buy_id = d.customer_id where d.type = 1  and d.state = 1 GROUP BY d.type" , 'row');
        if(empty($buy_not)){
            $data['buy_not'] = 0;
        }else{
            $data['buy_not'] = $buy_not->volume - $buy_not->handle;
        }
        $sell_not = $this->base_model->run_query("SELECT SUM(d.volume) AS volume, max(h.volume_handle) AS handle  FROM  deal d LEFT JOIN (SELECT sell_id, SUM(volume_handle) AS volume_handle FROM handle GROUP BY sell_id) h ON h.sell_id = d.customer_id where d.type = 2 and d.state = 1 GROUP BY d.type" , 'row');
        if(empty($sell_not)){
            $data['sell_not'] = 0;
        }else{
            $data['sell_not'] = $sell_not->volume - $sell_not->handle;
        }
        $buy = $this->base_model->get_data('deal' , 'count_money , wage , money_id , volume' , 'result' , array('type'=> 1 , 'date_deal'=> $start_date) , NULL , NULL , array('money_id' , 'ASC'));
        $sell = $this->base_model->get_data('deal' , 'count_money , wage , money_id , volume' , 'result' , array('type'=> 2 , 'date_deal'=> $start_date) , NULL , NULL , array('money_id' , 'ASC'));
        if(empty($buy)){
            $data['buy_dollar'] = 0;
            $data['buy_euro'] = 0;
            $data['buy_yuan'] = 0;
            $data['buy_derham'] = 0;
            $data['ave_buy'] = 0;
            $buy_rial = 0;
        }else{
            $buy_dollar = 0; $buy_euro = 0 ; $buy_yuan = 0 ; $buy_derham = 0; $volume_dollar = 0;$volume_euro = 0; $volume_yuan = 0; $volume_derham = 0; $buy_rial = 0;
            foreach($buy as $buys){
                if($buys->money_id == 1){
                    $buy_dollar += $buys->count_money + $buys->wage;
                    $volume_dollar += $buys->volume;
                }else if($buys->money_id == 2){
                    $buy_euro += $buys->count_money + $buys->wage;
                    $volume_euro += $buys->volume;
                }else if($buys->money_id == 3){
                    $buy_yuan += $buys->count_money + $buys->wage;
                    $volume_yuan += $buys->volume;
                }else if($buys->money_id == 4){
                    $buy_derham += $buys->count_money + $buys->wage;
                    $volume_derham += $buys->volume;
                }
                $buy_rial += $buys->volume;
        }
        $data['buy_dollar'] = $buy_dollar;
        $data['buy_euro'] = $buy_euro;
        $data['buy_yuan'] = $buy_yuan;
        $data['buy_derham'] = $buy_derham;
        if(!empty($rate)){
            $dollar_r = $buy_dollar;
            $dollar_e = $buy_euro / $rate->rate_euro;
            $dollar_y = $buy_yuan / $rate->rate_yuan;
            $dollar_d = $buy_derham / $rate->rate_derham;
            if($dollar_e == 0 and $dollar_y == 0 and $dollar_d == 0 and $dollar_r == 0){
                $data['ave_buy'] = 0;
            }else{
                $data['ave_buy'] = ($volume_dollar + $volume_euro + $volume_yuan + $volume_derham) / ( $dollar_r + $dollar_e + $dollar_y + $dollar_d);
            }
        }else{
            $data['ave_buy'] = 0;
        }
    }
        if(empty($sell)){
            $data['sell_dollar'] = 0;
            $data['sell_euro'] = 0;
            $data['sell_yuan'] = 0;
            $data['sell_derham'] = 0;
            $data['ave_sell'] = 0;
            $sell_rial = 0;
        }else{
            $sell_dollar = 0; $sell_euro = 0 ; $sell_yuan = 0 ; $sell_derham = 0;  $volume_dollar = 0; $volume_euro = 0; $volume_yuan = 0; $volume_derham = 0; $sell_rial = 0;
            foreach($sell as $sells){
                if($sells->money_id == 1){
                    $sell_dollar += $sells->count_money + $sells->wage;
                    $volume_dollar += $sells->volume;
                }else if($sells->money_id == 2){
                    $sell_euro += $sells->count_money + $sells->wage;
                    $volume_euro += $sells->volume;
                }else if($sells->money_id == 3){
                    $sell_yuan += $sells->count_money + $sells->wage;
                    $volume_yuan += $sells->volume;
                }else if($sells->money_id == 4){
                    $sell_derham += $sells->count_money + $sells->wage;
                    $volume_derham += $sells->volume;
                }
                $sell_rial += $sells->volume;
            }
            $data['sell_dollar'] = $sell_dollar;
            $data['sell_euro'] = $sell_euro;
            $data['sell_yuan'] = $sell_yuan;
            $data['sell_derham'] = $sell_derham;
            if(!empty($rate)){
                $dollar_r = $sell_dollar;
                $dollar_e = $sell_euro / $rate->rate_euro;
                $dollar_y = $sell_yuan / $rate->rate_yuan;
                $dollar_d = $sell_derham / $rate->rate_derham;
                if($dollar_e == 0 and $dollar_y == 0 and $dollar_d == 0 and $dollar_r == 0){
                    $data['ave_sell'] = 0;
                }else{
                    $data['ave_sell'] = ($volume_dollar + $volume_euro + $volume_yuan + $volume_derham) / ($dollar_r + $dollar_e + $dollar_y + $dollar_d);
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
        $date = $this->convertdate->convert(time());
        $start_date =  $date['year']."-".$date['month_num']."-".$date['day'];
        $data['today'] = $date['day']." ".$date['month_name']." ".$date['year'];
        $data['remain'] = $this->base_model->get_data('unit' , '*' , 'result' , array('id < ' => 10));
        $buy_not = $this->base_model->run_query("SELECT SUM(d.volume) AS volume, max(h.volume_handle) AS handle  FROM  deal d LEFT JOIN (SELECT buy_id, SUM(volume_handle) AS volume_handle FROM handle GROUP BY buy_id) h ON h.buy_id = d.customer_id where d.type = 1  and d.state = 1 GROUP BY d.type" , 'row');
        if(empty($buy_not)){
            $data['buy_not'] = 0;
        }else{
            $data['buy_not'] = $buy_not->volume - $buy_not->handle;
        }
        $sell_not = $this->base_model->run_query("SELECT SUM(d.volume) AS volume, max(h.volume_handle) AS handle  FROM  deal d LEFT JOIN (SELECT sell_id, SUM(volume_handle) AS volume_handle FROM handle GROUP BY sell_id) h ON h.sell_id = d.customer_id where d.type = 2 and d.state = 1 GROUP BY d.type" , 'row');
        if(empty($sell_not)){
            $data['sell_not'] = 0;
        }else{
            $data['sell_not'] = $sell_not->volume - $sell_not->handle;
        }
        $buy = $this->base_model->get_data('deal' , 'count_money , wage , money_id , volume' , 'result' , array('type'=> 1 , 'date_deal'=> $start_date) , NULL , NULL , array('money_id' , 'ASC'));
        $sell = $this->base_model->get_data('deal' , 'count_money , wage , money_id , volume' , 'result' , array('type'=> 2 , 'date_deal'=> $start_date) , NULL , NULL , array('money_id' , 'ASC'));
        if(empty($buy)){
            $data['buy_dollar'] = 0;
            $data['buy_euro'] = 0;
            $data['buy_yuan'] = 0;
            $data['buy_derham'] = 0;
            $data['ave_buy'] = 0;
            $buy_rial = 0;
        }else{
            $buy_dollar = 0; $buy_euro = 0 ; $buy_yuan = 0 ; $buy_derham = 0; $volume_dollar = 0;$volume_euro = 0; $volume_yuan = 0; $volume_derham = 0; $buy_rial = 0;
            foreach($buy as $buys){
                if($buys->money_id == 1){
                    $buy_dollar += $buys->count_money + $buys->wage;
                    $volume_dollar += $buys->volume;
                }else if($buys->money_id == 2){
                    $buy_euro += $buys->count_money + $buys->wage;
                    $volume_euro += $buys->volume;
                }else if($buys->money_id == 3){
                    $buy_yuan += $buys->count_money + $buys->wage;
                    $volume_yuan += $buys->volume;
                }else if($buys->money_id == 4){
                    $buy_derham += $buys->count_money + $buys->wage;
                    $volume_derham += $buys->volume;
                }
                $buy_rial += $buys->volume;
        }
        $data['buy_dollar'] = $buy_dollar;
        $data['buy_euro'] = $buy_euro;
        $data['buy_yuan'] = $buy_yuan;
        $data['buy_derham'] = $buy_derham;
        if(!empty($rate)){
            $dollar_r = $buy_dollar;
            $dollar_e = $buy_euro / $rate->rate_euro;
            $dollar_y = $buy_yuan / $rate->rate_yuan;
            $dollar_d = $buy_derham / $rate->rate_derham;
            if($dollar_e == 0 and $dollar_y == 0 and $dollar_d == 0 and $dollar_r == 0){
                $data['ave_buy'] = 0;
            }else{
                $data['ave_buy'] = ($volume_dollar + $volume_euro + $volume_yuan + $volume_derham) / ( $dollar_r + $dollar_e + $dollar_y + $dollar_d);
            }
        }else{
            $data['ave_buy'] = 0;
        }
    }
        if(empty($sell)){
            $data['sell_dollar'] = 0;
            $data['sell_euro'] = 0;
            $data['sell_yuan'] = 0;
            $data['sell_derham'] = 0;
            $data['ave_sell'] = 0;
            $sell_rial = 0;
        }else{
            $sell_dollar = 0; $sell_euro = 0 ; $sell_yuan = 0 ; $sell_derham = 0;  $volume_dollar = 0; $volume_euro = 0; $volume_yuan = 0; $volume_derham = 0; $sell_rial = 0;
            foreach($sell as $sells){
                if($sells->money_id == 1){
                    $sell_dollar += $sells->count_money + $sells->wage;
                    $volume_dollar += $sells->volume;
                }else if($sells->money_id == 2){
                    $sell_euro += $sells->count_money + $sells->wage;
                    $volume_euro += $sells->volume;
                }else if($sells->money_id == 3){
                    $sell_yuan += $sells->count_money + $sells->wage;
                    $volume_yuan += $sells->volume;
                }else if($sells->money_id == 4){
                    $sell_derham += $sells->count_money + $sells->wage;
                    $volume_derham += $sells->volume;
                }
                $sell_rial += $sells->volume;
            }
            $data['sell_dollar'] = $sell_dollar;
            $data['sell_euro'] = $sell_euro;
            $data['sell_yuan'] = $sell_yuan;
            $data['sell_derham'] = $sell_derham;
            if(!empty($rate)){
                $dollar_r = $sell_dollar;
                $dollar_e = $sell_euro / $rate->rate_euro;
                $dollar_y = $sell_yuan / $rate->rate_yuan;
                $dollar_d = $sell_derham / $rate->rate_derham;
                if($dollar_e == 0 and $dollar_y == 0 and $dollar_d == 0 and $dollar_r == 0){
                    $data['ave_sell'] = 0;
                }else{
                    $data['ave_sell'] = ($volume_dollar + $volume_euro + $volume_yuan + $volume_derham) / ($dollar_r + $dollar_e + $dollar_y + $dollar_d);
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