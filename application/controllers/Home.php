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
        $date = $this->convertdate->convert(time());
        $start_date =  $date['year']."-".$date['month_num']."-".$date['day'];
        $data['today'] = $date['day']." ".$date['month_name']." ".$date['year'];
        $data['remain'] = $this->base_model->get_data('currency_unit' , '*');
        $data['sum_handle'] = $this->base_model->get_data('deal_handle' , 'sum(volume_handle) as vh , sum(handle_rest) as hr' , 'row');
        $data['sum_pay'] = $this->base_model->get_data('deal' , 'sum(volume_pay) as vp' , 'row');
        $buy = $this->base_model->get_data('deal' , 'count_money , wage , money_id' , 'result' , array('type_deal'=> 1 , 'date_deal'=> $start_date));
        $sell = $this->base_model->get_data('deal' , 'count_money , wage , money_id' , 'result' , array('type_deal'=> 2 , 'date_deal'=> $start_date));
        if(sizeof($buy) == 0){
            $data['buy_dollar'] = 0;
            $data['buy_euro'] = 0;
            $data['buy_yuan'] = 0;
            $data['buy_derham'] = 0;
        }else{
            $buy_dollar = 0; $buy_euro = 0 ; $buy_yuan = 0 ; $buy_derham = 0;
            foreach($buy as $buys){
                if($buys->money_id == 1){
                    $buy_dollar += $buys->count_money + $buys->wage;
                }else if($buys->money_id == 2){
                    $buy_euro += $buys->count_money + $buys->wage;
                }else if($buys->money_id == 3){
                    $buy_yuan += $buys->count_money + $buys->wage;
                }else if($buys->money_id == 4){
                    $buy_derham += $buys->count_money + $buys->wage;
                }
        }
        $data['buy_dollar'] = $buy_dollar;
        $data['buy_euro'] = $buy_euro;
        $data['buy_yuan'] = $buy_yuan;
        $data['buy_derham'] = $buy_derham;
    }
        if(sizeof($sell) == 0){
            $data['sell_dollar'] = 0;
            $data['sell_euro'] = 0;
            $data['sell_yuan'] = 0;
            $data['sell_derham'] = 0;
        }else{
            $sell_dollar = 0; $sell_euro = 0 ; $sell_yuan = 0 ; $sell_derham = 0;
            foreach($sell as $sells){
                if($sells->money_id == 1){
                    $sell_dollar += $sells->count_money + $sells->wage;
                }else if($sells->money_id == 2){
                    $sell_euro += $sells->count_money + $sells->wage;
                }else if($sells->money_id == 3){
                    $sell_yuan += $sells->count_money + $sells->wage;
                }else if($sells->money_id == 4){
                    $sell_derham += $sells->count_money + $sells->wage;
                }
            }
            $data['sell_dollar'] = $sell_dollar;
            $data['sell_euro'] = $sell_euro;
            $data['sell_yuan'] = $sell_yuan;
            $data['sell_derham'] = $sell_derham;
        }
        $this->load->view('header' , $header);
        $this->load->view('home/home' , $data);
        $this->load->view('footer');
    }
    }
    public function update_dashbord(){
      if(isset($_POST['request']) and $_POST['request'] == true and $this->session->has_userdata('see_dashbord')){
        $date = $this->convertdate->convert(time());
        $start_date =  $date['year']."-".$date['month_num']."-".$date['day'];
        $data['remain'] = $this->base_model->get_data('currency_unit' , '*');
        $data['sum_handle'] = $this->base_model->get_data('deal_handle' , 'sum(volume_handle) as vh ,sum(handle_rest) as hr' , 'row');
        $data['sum_pay'] = $this->base_model->get_data('deal' , 'sum(volume_pay) as vp' , 'row');
        $data['today'] = $date['day']." ".$date['month_name']." ".$date['year'];
        $buy = $this->base_model->get_data('deal' , 'count_money , wage , money_id' , 'result' , array('type_deal'=> 1 , 'date_deal'=> $start_date));
        $sell = $this->base_model->get_data('deal' , 'count_money , wage , money_id' , 'result' , array('type_deal'=> 2 , 'date_deal'=> $start_date));
        if(sizeof($buy) == 0){
            $data['buy_dollar'] = 0;
            $data['buy_euro'] = 0;
            $data['buy_yuan'] = 0;
            $data['buy_derham'] = 0;
        }else{
            $buy_dollar = 0; $buy_euro = 0 ; $buy_yuan = 0 ; $buy_derham = 0;
            foreach($buy as $buys){
                if($buys->money_id == 1){
                    $buy_dollar += $buys->count_money + $buys->wage;
                }else if($buys->money_id == 2){
                    $buy_euro += $buys->count_money + $buys->wage;
                }else if($buys->money_id == 3){
                    $buy_yuan += $buys->count_money + $buys->wage;
                }else if($buys->money_id == 4){
                    $buy_derham += $buys->count_money + $buys->wage;
                }
        }
        $data['buy_dollar'] = $buy_dollar;
        $data['buy_euro'] = $buy_euro;
        $data['buy_yuan'] = $buy_yuan;
        $data['buy_derham'] = $buy_derham;
    }
        if(sizeof($sell) == 0){
            $data['sell_dollar'] = 0;
            $data['sell_euro'] = 0;
            $data['sell_yuan'] = 0;
            $data['sell_derham'] = 0;
        }else{
            $sell_dollar = 0; $sell_euro = 0 ; $sell_yuan = 0 ; $sell_derham = 0;
            foreach($sell as $sells){
                if($sells->money_id == 1){
                    $sell_dollar += $sells->count_money + $sells->wage;
                }else if($sells->money_id == 2){
                    $sell_euro += $sells->count_money + $sells->wage;
                }else if($sells->money_id == 3){
                    $sell_yuan += $sells->count_money + $sells->wage;
                }else if($sells->money_id == 4){
                    $sell_derham += $sells->count_money + $sells->wage;
                }
            }
            $data['sell_dollar'] = $sell_dollar;
            $data['sell_euro'] = $sell_euro;
            $data['sell_yuan'] = $sell_yuan;
            $data['sell_derham'] = $sell_derham;
        }
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