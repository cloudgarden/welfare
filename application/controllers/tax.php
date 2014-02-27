<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


class Tax extends WWF_Controller {
	function __construct() {
		parent::__construct();
		
	}
	
	function index(){
		redirect('tax/surtax_mgr');
	}
	
	function surtax_mgr(){
		$this->load->view($this->uri->uri_string());
	}
	
	function surtax_list(){
		$this->load->view($this->uri->uri_string());
	}
	
	function income_tax(){
		$this->load->view($this->uri->uri_string());
	}

	function surtax_statement(){
		$this->load->view($this->uri->uri_string());
	}

	function corporate_tax(){
		$this->load->view($this->uri->uri_string());
	}

	function fund_report(){
		$this->load->view($this->uri->uri_string());
	}

	function foreign_data(){
		$this->load->view($this->uri->uri_string());
	}

	function donations_list(){
		$this->load->view($this->uri->uri_string());
	}
	


}
