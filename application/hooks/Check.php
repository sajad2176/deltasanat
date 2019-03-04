<?php 
class Check{
	public function Check_login(){
		$CI =& get_instance();
		$CI->load->library('session');
		$class_name = $CI->uri->segment("1");
		if ($class_name != 'login') {
			if($CI->session->userdata('login') != TRUE)
			redirect('login');
		}
	}
}
?>