<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends CI_Controller{
   function __construct(){
         parent :: __construct();
         $this->load->library('form_validation');
         $this->load->library('Convertdate');
     }
	public function index(){
		if($this->session->userdata('login') == TRUE ){
            redirect('home');
        }else{
            $this->load->view('login/login');
        }
	}
   public function signin(){
       	if(isset($_POST['sub'])){
			$this->form_validation->set_rules('username' , 'username' , 'required');
			$this->form_validation->set_rules('password','password' , 'required');
			if($this->form_validation->run() == FALSE){
				$message['msg'][0] = "لطفا اطلاعات خواسته شده را وارد کنید";
				$message['msg'][1] = 0;
				$this->session->set_flashdata($message);
				redirect('login');
			}else{
				$username = $this->db->escape_str($this->input->post('username'));
				$password = $this->db->escape_str($this->input->post('password'));
	            $res = $this->base_model->get_data('member','id,firstname,username,lastname,password,active , picname','row' , array('username'=>$username));
				if(sizeof($res) == 0){
					$message['msg'][0] = 'نام کاربری یا رمز عبور اشتباه می باشد';
					$message['msg'][1] = 0;
					$this->session->set_flashdata($message);
					redirect('login');
				}else if(!password_verify($password , $res->password)){
					$message['msg'][0] = 'نام کاربری یا رمز عبور اشتباه می باشد';
					$message['msg'][1] = 0;
					$this->session->set_flashdata($message);
					redirect('login');
				}
				else if($res->active == 0){
					$message['msg'][0] = 'نام کاربری یا رمز عبور اشتباه می باشد یا حساب کاربری شما غیر فعال می باشد ';
					$message['msg'][1] = 0;
					$this->session->set_flashdata($message);
					redirect('login');
                }
            $date = $this->convertdate->convert(time());    
			$data['date_login'] = $date['year']. "-" .$date['month_num']."-".$date['day'];
			$data['time_login'] = $date['hour']. ":".$date['minute']. ":" .$date['second'];
			$this->base_model->update_data('member' , $data , array('id'=> $res->id));
			$log['user_id'] = $res->id;
			$log['date_log'] = $data['date_login'];
			$log['time_log'] = $data['time_login'];
			$log['activity_id'] = 1;
			$log['explain'] = 'ورود به سامانه';
			$this->base_model->insert_data('log' , $log);
			$id = $res->id;
			$perm = $this->base_model->get_data('member_perm' , 'perm_id' , 'result' , array('user_id' => $id));
			$sess = array();
			if(sizeof($perm) != 0){
			foreach($perm as $rows){
				if($rows->perm_id == 1){
					$sess['see_dashbord'] = TRUE;
				}else if($rows->perm_id == 2){
					$sess['see_users'] = TRUE;
				}else if($rows->perm_id == 3){
					$sess['add_user'] = TRUE;
				}else if($rows->perm_id == 4){
					$sess['edit_user'] = TRUE;
				}else if($rows->perm_id == 5){
					$sess['see_log'] = TRUE;
				}else if($rows->perm_id == 6){
					$sess['active_user'] = TRUE;
				}else if($rows->perm_id == 7){
					$sess['see_customer'] = TRUE;
				}else if($rows->perm_id == 8){
					$sess['add_customer'] = TRUE;
				}else if($rows->perm_id == 9){
					$sess['edit_customer'] = TRUE;
				}else if($rows->perm_id == 10){
					$sess['see_deal'] = TRUE;
				}else if($rows->perm_id == 11){
					$sess['add_buy'] = TRUE;
				}else if($rows->perm_id == 12){
					$sess['add_sell'] = TRUE;
				}else if($rows->perm_id == 13){
					$sess['edit_deal'] = TRUE;
				}else if($rows->perm_id == 14){
					$sess['delete_deal'] = TRUE;
				}else if($rows->perm_id == 15){
					$sess['see_photo'] = TRUE;
				}else if($rows->perm_id == 16){
					$sess['see_handle'] = TRUE;
				}else if($rows->perm_id == 17){
					$sess['add_bank'] = TRUE;
				}else if($rows->perm_id == 18){
					$sess['edit_bank'] = TRUE;
				}else if($rows->perm_id == 19){
					$sess['active_bank'] = TRUE;
				}else if($rows->perm_id == 20){
					$sess['add_handle'] = TRUE;
				}else if($rows->perm_id == 21){
					$sess['pay_all'] = TRUE;
				}else if($rows->perm_id == 22){
					$sess['pay_slice'] = TRUE;
				}else if($rows->perm_id == 23){
					$sess['restore'] = TRUE;
				}else if($rows->perm_id == 24){
					$sess['delete_handle'] = TRUE;
				}else if($rows->perm_id == 25){
					$sess['see_settings'] = TRUE;
				}else if($rows->perm_id == 26){
					$sess['set_unit'] = TRUE;
				}else if($rows->perm_id == 27){
					$sess['set_primitive'] = TRUE;
				}else if($rows->perm_id == 28){
					$sess['rest_unit'] = TRUE;
				}
			}
		}
			$sess['id'] = $res->id;
			$sess['name'] = $res->firstname." ".$res->lastname;
			$sess['pic_name'] = $res->picname;
			$sess['username'] = $res->username;
			$sess['login'] = TRUE;
			$this->session->set_userdata($sess);
				redirect('home');	
			}
			}else{
				redirect('login');
			}
   }
}

?>