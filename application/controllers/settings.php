<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {

    
    public function __construct(){
        parent::__construct();
    }
    public function set_unit(){
        $header['title'] = 'تنظیم ریت ارز ها';
        $header['active'] = 'settings';
        $header['active_sub'] = 'set_unit';
        $this->load->view('header', $header);
        $this->load->view('currency/unit');
        $this->load->view('footer');
    }
?>