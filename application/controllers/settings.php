<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {

    
    public function __construct(){
        parent::__construct();
    }
    public function set_unit(){
        $header['title'] = 'تنظیم ارز ها';
        $header['active'] = 'settings';
        $header['active_sub'] = 'set_unit';
        $this->load->view('header', $header);
        $this->load->view('currency/unit');
        $this->load->view('footer');
    }
	    public function first_unit(){
        $header['title'] = 'ارز اولیه';
        $header['active'] = 'settings';
        $header['active_sub'] = 'first_unit';
        $this->load->view('header', $header);
        $this->load->view('currency/first');
        $this->load->view('footer');
    }
	 }
?>