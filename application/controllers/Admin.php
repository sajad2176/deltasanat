<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    
    public function __construct(){
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('Convertdate');
        $this->load->library('pagination');
    }
    // -----archive-----//
    public function archive(){
        if(!$this->session->has_userdata('see_users') or $this->session->userdata('see_users') != TRUE){
         show_404();
        }
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
    // -----archive-----//
    // -----active-----//
    public function active(){
        if(!$this->session->has_userdata('active_user') or $this->session->userdata('active_user') != TRUE){
         show_404();
        }
        $user_id = $this->uri->segment(3);
        $active = $this->uri->segment(4);
        if(isset($user_id) and isset($active) and is_numeric($user_id) and is_numeric($active)){
        $data['active'] = $active;
        $this->base_model->update_data('member' , $data , array('id'=>$user_id));
        $name = $this->base_model->get_data('member'  , 'username' , 'row' , array('id'=>$user_id));
        $date = $this->convertdate->convert(time());
        $log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
        $log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
        $log['user_id'] = $this->session->userdata('id');
        $log['activity_id'] = 5;
        if($active == 0){$txt = ' را غیر فعال کرد '; $txt2 = ' غیرفعال شد ';}else{$txt = ' را فعال کرد '; $txt2 = '  فعال شد ';}
        $log['explain'] = ' حساب کاربری '. $name->username . $txt;
        $this->base_model->insert_data('log' , $log);
        $message['msg'][0] = "حساب کاربری ".$name->username." با موفقیت  ".$txt2;
        $message['msg'][1] = 'success';
        $this->session->set_flashdata($message);
        redirect('admin/archive');
        }else{
            show_404();
        }
    }
    // -----active-----//
    //------add--------//
    public function add(){
    if(!$this->session->has_userdata('add_user') or $this->session->userdata('add_user') != TRUE){
        show_404();
    }
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
        $user['username'] = $this->db->escape_str($this->input->post('username'));
        $check = $this->base_model->get_data('member' , 'username' , 'row' , array('username'=>$user['username']));
        if(sizeof($check) != 0){
            $message['msg'][0] = "این نام کاربری قبلا استفاده شده است . لطفا نام کاربری دیگری انتخاب کنید";
            $message['msg'][1] = 'danger';
            $this->session->set_flashdata($message);
            redirect("admin/add");
        }
        $pass = $this->db->escape_str($this->input->post('password'));
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
        $count = sizeof($_POST['perm']);
        $perm = array();
        for($i = 0 ; $i < $count ; $i++){
         $perm[] = array(
             'user_id' => $res_user,
             'perm_id' => $_POST['perm'][$i]
         );
        }
        $this->base_model->insert_batch('member_perm' , $perm);
        $date = $this->convertdate->convert(time());
        $log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
        $log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
        $log['user_id'] = $this->session->userdata('id');
        $log['activity_id'] = 3;
        $log['explain'] = ' کاربر ' . $user['username'] . ' را به کاربران سامانه افزود ';
        $res_log = $this->base_model->insert_data('log' , $log);
        $message['msg'][0] = " کاربر  ".$user['username'] . " با موفقیت افزوده شد ";
        $message['msg'][1] = 'success';
        $this->session->set_flashdata($message);
        redirect('admin/add/');
    }else{
        $data['perm'] = $this->base_model->get_data('permission' , '*');
        $header['title'] = 'افزودن کاربر جدید';
        $header['active'] = 'admin';
        $header['active_sub'] = 'admin_add';
        $this->load->view('header' , $header);
        $this->load->view('admin/add' , $data);
        $this->load->view('footer');
    }
    }
    //-----add------//
    //-----edit-----//
  public function edit(){
      if(!$this->session->has_userdata('edit_user') or $this->session->userdata('edit_user') != TRUE){
          show_404();
      }
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
                redirect("admin/edit/$id");
}
if($this->input->post('password') != '' or $this->input->post('repeat') != ''){
    if($this->input->post('password') != $this->input->post('repeat')){
    $message['msg'][0] = "کلمه عبور و تکرار کلمه عبور باید یکسان باشد";
    $message['msg'][1] = 'danger';
    $this->session->set_flashdata($message);
    redirect("admin/edit/$id");
}else{
    $pass = $this->db->escape_str($this->input->post('password'));
    $user['password'] = password_hash($pass, PASSWORD_DEFAULT);
}
}
$user['firstname'] = $this->input->post('firstname');
$user['lastname'] = $this->input->post('lastname');
$user['username']  = $this->db->escape_str($this->input->post('username'));
$check = $this->base_model->get_data('member' , 'id ,username' , 'row' , array('username'=>$user['username']));
if($check != NULL and $check->id != $id){
    $message['msg'][0] = "این نام کاربری قبلا استفاده شده است . لطفا نام کاربری دیگری انتخاب کنید";
    $message['msg'][1] = 'danger';
    $this->session->set_flashdata($message);
    redirect("admin/edit/$id");
}
if($_FILES['picname']['name'] != '' or $_FILES['picname']['size'] != 0){
    $config = array(
        'upload_path' => "./uploads/avatar",
        'allowed_types' => "gif|jpg|png|jpeg",
        'max_size' => "4100"
    );
    $this->load->library('upload', $config);
    if ($this->upload->do_upload('picname')) {
        $user['picname'] = $_FILES['picname']['name'];
        if($this->session->userdata('id') == $id){
            $pic = $user['picname'];
            $avatar = array(
                'pic_name' => $pic
            );
            $this->session->set_userdata( $avatar );
        }
    } else {
        $message['msg'][0] = "مشکلی در ارسال عکس پیش آمده است لطفا دوباره سعی کنید";
        $message['msg'][1] = 'danger';
        $this->session->set_flashdata($message);
        redirect("admin/edit/$id");
    }
}
$perm = array();
$count = sizeof($_POST['perm']);
    for($i = 0 ; $i < $count ; $i++){
     $perm[] = array(
      'user_id'=> $id,
      'perm_id' => $_POST['perm'][$i]
     );
    }
    $this->base_model->delete_data('member_perm' , array('user_id' => $id));
    $this->base_model->insert_batch('member_perm' , $perm);
$date = $this->convertdate->convert(time());
$log['user_id'] = $this->session->userdata('id');
$log['date_log'] = $date['year']."-".$date['month_num']."-".$date['day'];
$log['time_log'] = $date['hour'].":".$date['minute'].":".$date['second'];
$log['activity_id'] = 4;
$log['explain'] = ' مشخصات کاربر  '. $user['username'] . ' را ویرایش کرد ';
$res = $this->base_model->update_data('member' , $user , array('id' => $id));
if($res == FALSE){
    $message['msg'][0] = "مشکلی در ثبت اطلاعات رخ داده است . لطفا دوباره سعی کنید";
    $message['msg'][1] = 'danger';
    $this->session->set_flashdata($message);
    redirect("admin/edit/$id");
}
$this->base_model->insert_data('log' , $log);
$message['msg'][0] = "اطلاعات با موفقیت ویرایش شد";
$message['msg'][1] = 'success';
$this->session->set_flashdata($message);
redirect("admin/edit/$id");
}else{
            $data['user'] = $this->base_model->get_data('member' , 'id , firstname , lastname , username' , 'row' , array('id' => $id));
            if(sizeof($data['user']) == 0){
                show_404();
            }
            else{
                $header['title'] =  ' ویرایش کاربر '. $data['user']->firstname." ".$data['user']->lastname;
                $header['active'] = 'admin';
                $header['active_sub'] = 'admin_archive';
                $data['perm'] = $this->base_model->get_data('permission' , '*');
                $data['permission'] = $this->base_model->get_data('member_perm' , 'perm_id' , 'result' , array('user_id' => $id));
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
      if(!$this->session->has_userdata('see_log') or $this->session->userdata('see_log') != TRUE){
          show_404();
      }
      $id = $this->uri->segment(3);
      if(isset($id) and is_numeric($id)){
        if(isset($_POST['sub'])){
            if($_POST['start_date'] != $_POST['end_date']){
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
            $between = "log.date_log BETWEEN '$date_start' AND '$date_end'";
            if($_POST['select_act'] == 'all'){
                $where = array('log.user_id' => $id);
                $total_rows = $this->base_model->get_count_between("log" , array('user_id'=> $id) , $between);
            }else{
               $where = array('log.user_id'=> $id , 'log.activity_id' => $_POST['select_act']);
               $total_rows = $this->base_model->get_count_between("log" , array('user_id'=> $id , 'log.activity_id'=> $_POST['select_act']) , $between);
            }
            }else{
                if($_POST['select_act'] == 'all'){
                    $where = array('log.user_id' => $id);
                    $total_rows = $this->base_model->get_count("log" , array('user_id'=> $id));
                    $between = NULL;
                }else{
                   $where = array('log.user_id'=> $id , 'log.activity_id' => $_POST['select_act']);
                   $total_rows = $this->base_model->get_count("log" , array('log.user_id'=> $id , 'log.activity_id'=> $_POST['select_act']));
                   $between = NULL;
                }
            }
        }else{
            $between = NULL;
            $where = array('log.user_id' => $id);
            $total_rows = $this->base_model->get_count("log" , array('user_id'=> $id));
        }
            $header['title'] = 'فعالیت کاربران';
            $header['active'] = 'admin';
            $header['active_sub'] = 'admin_archive';
            $config['base_url'] = base_url("admin/log/$id");
            $config['total_rows'] = $total_rows;
            $config['per_page'] = '10';
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
            $data['logs'] = $this->base_model->get_data_join('log','activity' , 'log.* , activity.name' , 'log.activity_id = activity.id','result' , $where , $config['per_page'] , $page , array('log.id' , 'DESC') , NULL , NULL , $between);
            $date = $this->convertdate->convert(time());
            $data['date'] = $date['year']."/".$date['month_num']."/".$date['day']." ".$date['hour'].":".$date['minute'].":".$date['second'];
            $data['page'] = $this->pagination->create_links();
            $data['count'] = $config['total_rows'];
                $this->load->view('header' , $header);
                $this->load->view('admin/log', $data);
                $this->load->view('footer');
       }else{
          show_404();
      }
    }
    

}
?>