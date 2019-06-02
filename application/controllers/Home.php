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
        $data['remain'] = $this->base_model->get_data('unit' , '*' , 'result' , array('id != ' => 5));
        $data['deal'] = $this->base_model->run_query("SELECT u.id , u.name , MAX(d.count_money) as buy , MAX(d.volume) AS buy_v , MAX(dd.count_m) as sell , MAX(dd.volume) as sell_v FROM unit u LEFT JOIN (SELECT SUM(count_money) as count_money , sum(volume) as volume , money_id from deal where type = 1 AND date_deal = '$start_date'  group by money_id) d ON u.id = d.money_id left join (select sum(count_money) as count_m , sum(volume) as volume , money_id from deal where type = 2 AND date_deal = '$start_date' group by money_id) dd ON u.id = dd.money_id where u.id <> 5  group by u.id order by u.id ASC");
        $buy_not = $this->base_model->run_query("SELECT SUM(d.volume) AS volume, max(h.volume_handle) AS handle  FROM  deal d LEFT JOIN (SELECT buy_id, SUM(volume_handle) AS volume_handle FROM handle) h ON h.buy_id = d.customer_id where d.type = 1  and d.state = 1 and d.date_deal = '$start_date' GROUP BY d.type" , 'row');
        if(empty($buy_not)){
            $data['buy_not'] = 0;
        }else{
            $data['buy_not'] = $buy_not->volume - $buy_not->handle;
        }
        $sell_not = $this->base_model->run_query("SELECT SUM(d.volume) AS volume, max(h.volume_handle) AS handle  FROM  deal d LEFT JOIN (SELECT sell_id, SUM(volume_handle) AS volume_handle FROM handle) h ON h.sell_id = d.customer_id where d.type = 2 and d.state = 1 and d.date_deal = '$start_date' GROUP BY d.type" , 'row');
        if(empty($sell_not)){
            $data['sell_not'] = 0;
        }else{
            $data['sell_not'] = $sell_not->volume - $sell_not->handle;
        }

       $rate = $this->base_model->run_query("SELECT unit_id , rate FROM rate WHERE rate.pub = 1");
       $volume_buy = 0; $buy = 0; $volume_sell = 0; $sell = 0; $data['ave_buy'] = 0; $data['ave_sell'] = 0;
       if(!empty($rate)){
        foreach($rate as $r){
                 if($r->rate == 0){
                     continue;
                 }
                 foreach($data['deal'] as $d){
                     if($d->id == $r->unit_id){
                         if($d->buy != 0){
                           $volume_buy += $d->buy_v;
                           $buy += $d->buy/$r->rate;
                         }
                         if($d->sell != 0){
                             $volume_sell += $d->sell_v;
                             $sell += $d->sell/$r->rate;
                         }
                       break;
                     }
                 }
        }
        if($buy != 0){
            $data['ave_buy'] = $volume_buy / $buy;
        }if($sell != 0){
          $data['ave_sell'] = $volume_sell / $sell;
        }
       }

        $this->load->view('header' , $header);
        $this->load->view('home/home' , $data);
        $this->load->view('footer');
    }
    }
    // update
    
    public function update_dashbord(){
      if(isset($_POST['request']) and $_POST['request'] == true and $this->session->has_userdata('see_dashbord')){
        $date = $this->convertdate->convert(time());
        $start_date =  $date['year']."-".$date['month_num']."-".$date['day'];
        $data['today'] = $date['day']." ".$date['month_name']." ".$date['year'];
        $data['remain'] = $this->base_model->get_data('unit' , '*' , 'result' , array('id != ' => 5));
        $data['deal'] = $this->base_model->run_query("SELECT u.id , u.name , MAX(d.count_money) as buy , MAX(d.volume) AS buy_v , MAX(dd.count_m) as sell , MAX(dd.volume) as sell_v FROM unit u LEFT JOIN (SELECT SUM(count_money) as count_money , sum(volume) as volume , money_id from deal where type = 1 AND date_deal = '$start_date'  group by money_id) d ON u.id = d.money_id left join (select sum(count_money) as count_m , sum(volume) as volume , money_id from deal where type = 2 AND date_deal = '$start_date' group by money_id) dd ON u.id = dd.money_id where u.id <> 5  group by u.id order by u.id ASC");
        $buy_not = $this->base_model->run_query("SELECT SUM(d.volume) AS volume, max(h.volume_handle) AS handle  FROM  deal d LEFT JOIN (SELECT buy_id, SUM(volume_handle) AS volume_handle FROM handle) h ON h.buy_id = d.customer_id where d.type = 1  and d.state = 1 and d.date_deal = '$start_date' GROUP BY d.type" , 'row');
        if(empty($buy_not)){
            $data['buy_not'] = 0;
        }else{
            $data['buy_not'] = $buy_not->volume - $buy_not->handle;
        }
        $sell_not = $this->base_model->run_query("SELECT SUM(d.volume) AS volume, max(h.volume_handle) AS handle  FROM  deal d LEFT JOIN (SELECT sell_id, SUM(volume_handle) AS volume_handle FROM handle) h ON h.sell_id = d.customer_id where d.type = 2 and d.state = 1 and d.date_deal = '$start_date' GROUP BY d.type" , 'row');
        if(empty($sell_not)){
            $data['sell_not'] = 0;
        }else{
            $data['sell_not'] = $sell_not->volume - $sell_not->handle;
        }

       $rate = $this->base_model->run_query("SELECT unit_id , rate FROM rate WHERE rate.pub = 1");
       $volume_buy = 0; $buy = 0; $volume_sell = 0; $sell = 0; $data['ave_buy'] = 0; $data['ave_sell'] = 0;
       if(!empty($rate)){
        foreach($rate as $r){
                 if($r->rate == 0){
                     continue;
                 }
                 foreach($data['deal'] as $d){
                     if($d->id == $r->unit_id){
                         if($d->buy != 0){
                           $volume_buy += $d->buy_v;
                           $buy += $d->buy/$r->rate;
                         }
                         if($d->sell != 0){
                             $volume_sell += $d->sell_v;
                             $sell += $d->sell/$r->rate;
                         }
                       break;
                     }
                 }
        }
        if($buy != 0){
            $data['ave_buy'] = $volume_buy / $buy;
        }if($sell != 0){
          $data['ave_sell'] = $volume_sell / $sell;
        }
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