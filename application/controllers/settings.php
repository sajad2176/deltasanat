<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {

    
    public function __construct(){
        parent::__construct();
    }
    public function set_unit(){
        if(!$this->session->has_userdata('set_unit') or $this->session->userdata('set_unit') != TRUE){
            show_404();
        }
        $header['title'] = 'تنظیم ارز ها';
        $header['active'] = 'settings';
        $header['active_sub'] = 'set_unit';
        $this->load->view('header', $header);
        $this->load->view('currency/unit');
        $this->load->view('footer');
    }
	    public function primitive_unit(){
        if(!$this->session->has_userdata('set_primitive') or $this->session->userdata('set_primitive') != TRUE){
                show_404();
        }
        $header['title'] = 'ارز اولیه';
        $header['active'] = 'settings';
        $header['active_sub'] = 'primitive_unit';
        $this->load->view('header', $header);
        $this->load->view('currency/primitive');
        $this->load->view('footer');
    }
    public function rest_unit(){
        if(!$this->session->has_userdata('rest_unit') or $this->session->userdata('rest_unit') != TRUE){
            show_404();
    }
        $header['title'] = 'مانده حساب';
        $header['active'] = 'settings';
        $header['active_sub'] = 'rest_unit';
        $this->load->view('header', $header);
        $this->load->view('currency/rest');
        $this->load->view('footer');
    }
	 }
?>