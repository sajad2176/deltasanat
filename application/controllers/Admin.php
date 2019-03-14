<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    
    public function __construct(){
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('Convertdate');
        $this->load->library('pagination');
    }
    public function archive(){
        $total_rows = $this->base_model->get_count("member" , 'ALL');
        $config['base_url'] = base_url('admin/archive/');
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
$data['user'] = $this->base_model->get_data('member','*','result' , NULL ,$config['per_page'],$page , array('id', 'DESC'));
$data['page'] = $this->pagination->create_links();
$data['count'] = $config['total_rows'];
        $header['title'] = 'آرشیو کاربران';
        $header['active'] = 'admin';
        $header['active_sub'] = 'admin_archive';
        $this->load->view('header' , $header);
        $this->load->view('admin/archive' ,$data);
        $this->load->view('footer');
    }
    public function active(){
        $user_id = $this->uri->segment(3);
        $active = $this->uri->segment(4);
        if(isset($user_id) and isset($active) and is_numeric($user_id) and is_numeric($active)){
        $data['active'] = $active;
        $this->base_model->update_data('member' , $data , array('id'=>$user_id));
        $user_log = $this->session->userdata('id');
        $name = $this->base_model->get_data('member'  , 'username' , 'row' , array('id'=>$user_id));
        $date = $this->convertdate->convert(time());
        $log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
        $log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
        $log['user_id'] = $user_log;
        $log['activity_id'] = 5;
        if($active == 0){$txt = ' را غیر فعال کرد ';}else{$txt = 'را فعال کرد ';}
        $log['explain'] = ' حساب کاربری '. $name->username . $txt;
        $this->base_model->insert_data('log' , $log);
        redirect('admin/archive');
        }else{
            show_404();
        }
    }

    public function add(){
    if(isset($_POST['sub'])){
        $this->form_validation->set_rules('firstname' , 'firstname' , 'required');
        $this->form_validation->set_rules('lastname' , 'lastname' , 'required');
        $this->form_validation->set_rules('username' , 'username' , 'required');
        $this->form_validation->set_rules('password' , 'password' , 'required');
        $this->form_validation->set_rules('repeat','repeat' , 'required');
        if($this->form_validation->run() == FALSE){
            $message['msg'][0] = "لطفا اطلاعات خواسته شده را وارد کنید";
            $message['msg'][1] = 'danger';
            $this->session->set_flashdata($message);
            redirect('admin/add');
        }else if($this->input->post('password') !=  $this->input->post('repeat')){
            $message['msg'][0] = "کلمه عبور و تکرار کلمه عبور باید یکسان باشد";
            $message['msg'][1] = 'danger';
            $this->session->set_flashdata($message);
            redirect('admin/add');
        }
        $user['firstname'] = $this->input->post('firstname'); 
        $user['lastname'] = $this->input->post('lastname');
        $user['username'] = $this->input->post('username');
        $check = $this->get_data('member' , 'username' , 'row' , array('username'=>$user['username']));
        $pass = $this->input->post('password');
        $user['password'] = password_hash($pass, PASSWORD_DEFAULT);
        $user['active'] = 1;
        if($_FILES['picname']['name'] == '' or $_FILES['picname']['size'] == 0){
         $user['picname'] = 'default_avatar.png';
        }else{
            $config = array(
				'upload_path' => "./uploads/avatar",
				'allowed_types' => "gif|jpg|png|jpeg",
				'max_size' => "4100"
			);
			$this->load->library('upload', $config);
			if ($this->upload->do_upload('picname')) {
				$user['picname'] = $_FILES['picname']['name'];
			} else {
                $message['msg'][0] = "مشکلی در ارسال عکس پیش آمده است لطفا دوباره سعی کنید";
				$message['msg'][1] = 'danger';
				$this->session->set_flashdata($message);
				redirect('admin/add/');
			}
        }
        $user['date_login'] = '0000-00-00';
        $user['time_login'] = 'ثبت نشده است';
        $res_user = $this->base_model->insert_data('member' , $user);
        if($res_user == FALSE){
            $message['msg'][0] = "مشکلی در ثبت اطلاعات رخ داده است . لطفا دوباره سعی کنید";
            $message['msg'][1] = 'danger';
            $this->session->set_flashdata($message);
            redirect('admin/add/');
        }
        $date = $this->convertdate->convert(time());
        $log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
        $log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
        $log['user_id'] = $this->session->userdata('id');
        $log['activity_id'] = 3;
        $log['explain'] = ' کاربر ' . $user['username'] . 'را به کاربران سامانه افزود';
        $res_log = $this->base_model->insert_data('log' , $log);
        $message['msg'][0] = " کاربر  ".$user['username'] . "با موفقیت افزوده شد";
        $message['msg'][1] = 'success';
        $this->session->set_flashdata($message);
        redirect('admin/add/');
    }else{
        $header['title'] = 'افزودن کاربر جدید';
        $header['active'] = 'admin';
        $header['active_sub'] = 'admin_add';
        $this->load->view('header' , $header);
        $this->load->view('admin/add');
        $this->load->view('footer');
    }
    }
  public function edit(){
      $id = $this->uri->segment(3);
      if(isset($id) and is_numeric($id)){
        if(isset($_POST['sub'])){
            $this->form_validation->set_rules('firstname' , 'firstname' , 'required');
            $this->form_validation->set_rules('lastname' , 'lastname' , 'required');
            $this->form_validation->set_rules('username' , 'username' , 'required');
            if($this->form_validation->run() == FALSE){
                $message['msg'][0] = "لطفا اطلاعات خواسته شده را وارد کنید";
                $message['msg'][1] = 'danger';
                $this->session->set_flashdata($message);
                redirect('admin/add');
}
$user['username']  = $this->input->post('username');

$check = $this->base_model->get_data('member' , 'username' , 'row' , array('username'=>$user['username']));
if(count($check) == 0){
    echo 'sd';
}
echo "<pre>";
var_dump($check);
var_dump($_FILES);
var_dump($_POST);
echo "</pre>";
            // else if($this->input->post('password') !=  $this->input->post('repeat')){
            //     $message['msg'][0] = "کلمه عبور و تکرار کلمه عبور باید یکسان باشد";
            //     $message['msg'][1] = 'danger';
            //     $this->session->set_flashdata($message);
            //     redirect('admin/add');
            // }
            // $user['firstname'] = $this->input->post('firstname');
            // $user['lastname'] = $this->input->post('lastname');
            // $user['username'] = $this->input->post('username');
            // $pass = $this->input->post('password');
            // $user['password'] = password_hash($pass, PASSWORD_DEFAULT);
            // $user['active'] = 1;
            // if($_FILES['picname']['name'] == '' or $_FILES['picname']['size'] == 0){
            //  $user['picname'] = 'default_avatar.png';
            // }else{
            //     $config = array(
            //         'upload_path' => "./uploads/avatar",
            //         'allowed_types' => "gif|jpg|png|jpeg",
            //         'max_size' => "4100"
            //     );
            //     $this->load->library('upload', $config);
            //     if ($this->upload->do_upload('picname')) {
            //         $user['picname'] = $_FILES['picname']['name'];
            //     } else {
            //         $message['msg'][0] = "مشکلی در ارسال عکس پیش آمده است لطفا دوباره سعی کنید";
            //         $message['msg'][1] = 'danger';
            //         $this->session->set_flashdata($message);
            //         redirect('admin/add/');
            //     }
            // }
            // $user['date_login'] = '0000-00-00';
            // $user['time_login'] = 'ثبت نشده است';
            // $res_user = $this->base_model->insert_data('member' , $user);
            // if($res_user == FALSE){
            //     $message['msg'][0] = "مشکلی در ثبت اطلاعات رخ داده است . لطفا دوباره سعی کنید";
            //     $message['msg'][1] = 'danger';
            //     $this->session->set_flashdata($message);
            //     redirect('admin/add/');
            // }
            // $date = $this->convertdate->convert(time());
            // $log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
            // $log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
            // $log['user_id'] = $this->session->userdata('id');
            // $log['activity_id'] = 3;
            // $log['explain'] = ' کاربر ' . $user['username'] . 'را به کاربران سامانه افزود';
            // $res_log = $this->base_model->insert_data('log' , $log);
            // $message['msg'][0] = " کاربر  ".$user['username'] . "با موفقیت افزوده شد";
            // $message['msg'][1] = 'success';
            // $this->session->set_flashdata($message);
            // redirect('admin/add/');
        }else{
            $data['user'] = $this->base_model->get_data('member' , 'id , firstname , lastname , username , picname' , 'row' , array('id' => $id));
            if(sizeof($data['user']) == 0){
                show_404();
            }
            else{
                $header['title'] =  ' ویرایش کاربر '. $data['user']->firstname." ".$data['user']->lastname;
                $header['active'] = 'admin';
                $header['active_sub'] = 'admin_archive';
                $this->load->view('header' , $header);
                $this->load->view('admin/edit' , $data);
                $this->load->view('footer');
            }
        }
      }else{
          show_404();
      }
  }
  public function log(){
        $header['title'] = 'فعالیت کاربران';
        $header['active'] = 'admin';
        $header['active_sub'] = 'admin_archive';
        $this->load->view('header' , $header);
        $this->load->view('admin/log');
        $this->load->view('footer');
    }
    

}

/* End of file Controllername.php */

?>