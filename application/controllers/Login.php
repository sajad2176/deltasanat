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
				
             $ses = array(
			 'id' => $res->id , 
			 'name' => $res->firstname." ".$res->lastname,
			 'pic_name' => $res->picname,
			  'username'=>$res->username,
			  'login' => TRUE
			 );	
			$this->session->set_userdata($ses);
				redirect('home');	
			}
			}else{
				redirect('login');
			}
   }
}

?>