<?php
class Home extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->load->library('Convertdate');
    }
    public function index(){
        $header['title'] = 'داشبورد';
        $header['active'] = 'dashbord';
        $header['active_sub'] = '';
        $date = $this->convertdate->convert(time());
        $start_date =  $date['year']."-".$date['month_num']."-".$date['day'];
        $data['remain'] = $this->base_model->get_data('currency_unit' , '*');
        $data['buy_dollar'] = $this->base_model->get_data('deal' , 'sum(count_money) as cd , sum(wage) as wd' , 'row' , array('type_deal'=>1 , 'money_id'=>1 , 'date_deal'=> $start_date));
        $data['sell_dollar'] = $this->base_model->get_data('deal' , 'sum(count_money) as cd , sum(wage) as wd' , 'row' , array('type_deal'=>2 , 'money_id'=>1 , 'date_deal'=> $start_date));
        $data['buy_euro'] = $this->base_model->get_data('deal' , 'sum(count_money) as cd , sum(wage) as wd' , 'row' , array('type_deal'=>1 , 'money_id'=>2 , 'date_deal'=> $start_date));
        $data['sell_euro'] = $this->base_model->get_data('deal' , 'sum(count_money) as cd , sum(wage) as wd' , 'row' , array('type_deal'=>2 , 'money_id'=>2 , 'date_deal'=> $start_date));
        $data['buy_yuan'] = $this->base_model->get_data('deal' , 'sum(count_money) as cd , sum(wage) as wd' , 'row' , array('type_deal'=>1 , 'money_id'=>3 , 'date_deal'=> $start_date));
        $data['sell_yuan'] = $this->base_model->get_data('deal' , 'sum(count_money) as cd , sum(wage) as wd' , 'row' , array('type_deal'=>2 , 'money_id'=>3 , 'date_deal'=> $start_date));
        $data['buy_derham'] = $this->base_model->get_data('deal' , 'sum(count_money) as cd , sum(wage) as wd' , 'row' , array('type_deal'=>1 , 'money_id'=>4 , 'date_deal'=> $start_date));
        $data['sell_derham'] = $this->base_model->get_data('deal' , 'sum(count_money) as cd , sum(wage) as wd' , 'row' , array('type_deal'=>2 , 'money_id'=>4 , 'date_deal'=> $start_date));

        $this->load->view('header' , $header);
        $this->load->view('home/home' , $data);
        $this->load->view('footer');
    }
    public function logout(){
$date = $this->convertdate->convert(time());
$log['user_id'] = $this->session->userdata('id');
$log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
$log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
$log['activity_id'] = 2;
$log['explain'] = 'خروج از سامانه';
$this->base_model->insert_data('log' , $log);
        $this->session->unset_userdata('login');
        $this->session->unset_userdata('username');
        
        redirect('login');
        
        
    }
}
?>