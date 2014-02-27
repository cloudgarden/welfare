<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


class Pay_mgr extends WWF_Controller {
	function __construct() {
		parent::__construct();
		
	}
	
	function index(){
		redirect('pay_mgr/payment');
	}
	

	function payment(){
		$this->load->view($this->uri->uri_string());
	}

	function payment_process(){
		$this->load->view($this->uri->uri_string());
	}

	
}
