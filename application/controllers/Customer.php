<?php
class Customer extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->load->library('pagination');
    }
    public function archive(){
        $config['base_url'] = base_url("customer/archive/");
        $config['total_rows'] = $this->base_model->get_count("customer" , 'ALL');
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
 $data['customer'] = $this->base_model->get_data('customer' , 'id , fullname' , 'result' , NULL, $config['per_page'] , $page , array('id' , 'DESC'));
        $data['page'] = $this->pagination->create_links();
        $header['title'] = 'آرشیو مشتریان';
        $header['active'] = 'customer';
        $header['active_sub'] = 'customer_archive';
        $this->load->view('header' , $header);
        $this->load->view('customer/archive' , $data);
        $this->load->view('footer');
    }
    public function add(){
        if(isset($_POST['sub'])){
            $customer['fullname'] = $this->input->post('fullname');
            $customer['address'] = $this->input->post('address');
            $customer['email'] = $this->input->post('email');
            $customer['pub'] = 1;
            $id = $this->base_model->insert_data('customer' , $customer);
            if($id == FALSE){
                $message['msg'][0] = 'مشکلی در ثبت اطلاعات رخ داده است . لطفا دوباره سعی کنید';
                $message['msg'][1] = 'danger';
                $this->session->set_flashdata($message);
                redirect('customer/add');
            }
            for($i = 0 ; $i < sizeof($_POST['tel_title']) ; $i++){
                $tel[] = array(
                    'customer_id' => $id,
                    'tel_title'=> $_POST['tel_title'][$i],
                    'tel'=> $_POST['tel'][$i]
                  );
            }
            for($j = 0 ; $j < sizeof($_POST['name_acount']) ; $j++){
                $customer_info[] = array(
                    "customer_id"=> $id,
                    "name_acount"=> $_POST['name_acount'][$j],
                    "name_bank"=>$_POST['name_bank'][$j],
                    "number_acount"=>$_POST['number_acount'][$j],
                    "number_card"=>$_POST['number_card'][$j],
                    "number_shaba"=>$_POST['number_shaba'][$j]
                );
            }
            $res_tel = $this->base_model->insert_batch('customer_tel' , $tel);
            $res_info = $this->base_model->insert_batch('customer_info' , $customer_info);
            if($res_tel == TRUE and $res_info == TRUE){
                $message['msg'][0] = 'اطلاعات مشتری ' . $customer['fullname'] . ' با موفقیت ثبت شد ';
                $message['msg'][1] = 'success';
                $this->session->set_flashdata($message);
                redirect('customer/add/');
            }else{
             $message['msg'][0] = 'مشکلی در ثبت اطلاعات رخ داده است . لطفا دوباره سعی کنید';
             $message['msg'][1] = 'danger';
             $this->session->set_flashdata($message);
             redirect('customer/add/');     
            }
        }else{
            $header['title'] = 'افزودن مشتری جدید';
            $header['active'] = 'customer';
            $header['active_sub'] = 'customer_add';
            $this->load->view('header' , $header);
            $this->load->view('customer/add');
            $this->load->view('footer');
        }
    }
    public function edit($id){
        if(isset($_POST['sub'])){
        $customer['fullname'] = $this->input->post('fullname');
        $customer['address'] = $this->input->post('address');
        $customer['email'] = $this->input->post('email');
        for($i = 0 ; $i < sizeof($_POST['tel_title']) ; $i++){
            $tel[] = array(
             'customer'=> $id,
             'tel_title'=> htmlspecialchars($_POST['tel_title'][$i]),
             'tel' => htmlspecialchars($_POST['tel'][$i])
            );
        }
        for($j = 0 ; $j < sizeof($_POST['name_acount']) ; $j++){
            $info[] = array(
                'custtomer_id'=>$id,
                'name_acount'=>htmlspecialchars($_POST['name_acount'][$j]),
                'name_bank'=>htmlspecialchars($_POST['name_bank'][$j]),
                'number_acount'=>htmlspecialchars($_POST['number_acount'][$j]),
                'number_card'=>htmlspecialchars($_POST['number_card'][$j]),
                'number_shaba'=>htmlspecialchars($_POST['number_shaba'][$j])
            ); 
        }
        
        
        }else{
            $data['customer'] = $this->base_model->get_data('customer' , '*' , 'row' , array('id' => $id , 'pub'=> '1'));
            $data['tel'] = $this->base_model->get_data('customer_tel' , 'tel_title , tel' , 'result' , array('customer_id' => $id));
            $data['info'] = $this->base_model->get_data('customer_info' , '*' , 'result' ,array('customer_id' => $id));
            if(sizeof($data['customer']) == 0){
                show_404();
            }else{
                $header['title'] = ' ویرایش مشتری ' . $data['customer']->fullname;
                $header['active'] = 'customer';
                $header['active_sub'] = 'customer_archive';
                $this->load->view('header' , $header);
                $this->load->view('customer/edit' , $data );
                $this->load->view('footer');
            }
        }
    }
}
?>