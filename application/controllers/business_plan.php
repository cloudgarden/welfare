<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


class Business_plan extends WWF_Controller {
	function __construct() {
		parent::__construct();
		
	}
	
	function index(){
		redirect('business_plan/budget/income');
	}
	

	function budget(){
		$this->{$this->uri->segment(3)}();
	} // end of bunge()

	function income(){
		$this->load->view($this->uri->uri_string());
	}
	
	function expenses(){
		$this->load->view($this->uri->uri_string());
	}
	
	function budget_asset(){
		$this->load->view($this->uri->uri_string());
	}
	
	function bizplan(){
		$this->load->view($this->uri->uri_string());
	}
	
	function data(){
		$this->{$this->uri->segment(3)}();
	} // end of bunge()

	function profitsnlosses(){
		$this->load->view($this->uri->uri_string());
	}
	
	function balancesheet(){
		$this->load->view($this->uri->uri_string());
	}
	
	function assetplan(){
		$this->load->view($this->uri->uri_string());
	}
	
	function managementplan(){
		$this->load->view($this->uri->uri_string());
	}
	
}
