<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


class Asset extends WWF_Controller {
	function __construct() {
		parent::__construct();
		
	}
	
	function index(){
		redirect('asset/operation');
	}
	
	
	function operation(){
		$this->load->view($this->uri->uri_string());
	}
	
	function billing(){
		$this->load->view($this->uri->uri_string());
	}
	
	function account_mgr(){
		$this->load->view($this->uri->uri_string());
	}
	
	function deposit_list(){
		$this->load->view($this->uri->uri_string());
	}
	
	function depreciation_asset(){
		$this->load->view($this->uri->uri_string());
	}
	
	function asset_mgr(){
		$this->load->view($this->uri->uri_string());
	}
	
	function donation(){
		$this->load->view($this->uri->uri_string());
	}
	


}
