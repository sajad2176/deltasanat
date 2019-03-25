<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Customer extends CI_Controller{

    function __construct(){
        parent::__construct();
        $this->load->library('pagination');
        $this->load->library('convertdate');
        
    }
    public function archive(){
        $total_rows = $this->base_model->get_count("customer" , 'ALL');
        $config['base_url'] = base_url('customer/archive');
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
$data['customer'] = $this->base_model->get_data_left('customer','deal','customer.id , customer.fullname , count(deal.customer_id) as deal_count , sum(deal.volume_deal) as vd, sum(deal.volume_rest) as vr' , 'customer.id = deal.customer_id','left','result' , NULL, $config['per_page'] , $page , array('customer.id' , 'DESC') , 'fullname');
$data['page'] = $this->pagination->create_links();
$data['count'] = $total_rows;
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
            $tel[0] = $this->input->post('tel_title');
            $tel[1] = $this->input->post('tel');
            $customer['customer_tel'] = json_encode($tel);
            $res = $this->base_model->insert_data('customer' , $customer);
            $date = $this->convertdate->convert(time());
            $log['user_id'] = $this->session->userdata('id');
            $log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
            $log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
            $log['activity_id'] = 6;
            $log['explain'] = ' مشتری  '.$customer['fullname'] . " را به لیست مشتریان افزود ";
            $this->base_model->insert_data('log' , $log);
            if($res == FALSE){
                $message['msg'][0] = 'مشکلی در ثبت اطلاعات رخ داده است . لطفا دوباره سعی کنید';
                $message['msg'][1] = 'danger';
                $this->session->set_flashdata($message);
                redirect('customer/add');
            }else{
                $message['msg'][0] = ' اطلاعات مشتری  '. $customer['fullname'] . ' با موفقیت ثبت شد ';
                $message['msg'][1] = 'success';
                $this->session->set_flashdata($message);
                redirect('customer/add');
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
    public function edit(){
        $id = $this->uri->segment(3);
        if(isset($id) and is_numeric($id)){
            if(isset($_POST['sub'])){
                $customer['fullname'] = $this->input->post('fullname');
                $customer['address'] = $this->input->post('address');
                $customer['email'] = $this->input->post('email');
                $tel[0] = $this->input->post('tel_title');
                $tel[1] = $this->input->post('tel');
                $customer['customer_tel'] = json_encode($tel);
                $res = $this->base_model->update_data('customer' , $customer , array('id'=>$id));
                $date = $this->convertdate->convert(time());
                $log['user_id'] = $this->session->userdata('id');
                $log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
                $log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
                $log['activity_id'] = 7;
                $log['explain'] = ' مشخصات مشتری  '.$customer['fullname'] . " را ویرایش کرد";
                $this->base_model->insert_data('log' , $log);
                if($res == TRUE){
                   $message['msg'][0] = 'ویرایش مشتری '. $customer['fullname'] . " با موفقیت انجام شد ";
                   $message['msg'][1] = 'success';
                   $this->session->set_flashdata($message);
                   redirect("customer/edit/$id");
                 }else{
                     $message['msg'][0] = 'متاسفانه مشکلی در ثبت اطلاعات رخ داده است . لطفا دوباره سعی کنید';
                     $message['msg'][1] = 'danger';
                     $this->session->set_flashdata($message);
                     redirect("customer/edit/$id");
                }
                }else{
                    $data['customer'] = $this->base_model->get_data('customer' , '*' , 'row' , array('id' => $id));
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

        }else{
            show_404();
        }

    }
    public function search(){
		  if(isset($_POST['text_search'])){
            $title = $this->db->escape_str($this->input->post('text_search'));
            $result = $this->base_model->search_data('customer','deal' ,'customer.id , customer.fullname , count(deal.customer_id) as deal_count , sum(deal.volume_deal) as vd, sum(deal.volume_rest) as vr', 'customer.id = deal.customer_id' , 'left' , array('customer.fullname' => $title) , NULL , array('customer.id' , 'DESC') , 'customer.fullname');
			echo json_encode($result);
		}else{
			show_404();
		}	
    }
    public function delete(){
        $id = $this->uri->segment(3);
        if(isset($id) and is_numeric($id)){

        }else{
            show_404();
        }
    }
}
?>