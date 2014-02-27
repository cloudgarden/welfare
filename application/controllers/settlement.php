<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


class Settlement extends WWF_Controller {
	function __construct() {
		parent::__construct();
		
	}

	function index(){
		redirect('settlement/balance_sheet');
	}
	
	
	function balance_sheet(){
		$this->load->view($this->uri->uri_string());
	}
	
	function settlement_jaemu(){
		$this->{$this->uri->segment(3)}();
	}

	function settlement_jaemusang(){
		$this->load->view($this->uri->uri_string());
	}

	function settlement_sonik(){
		$this->load->view($this->uri->uri_string());
	}

	function settlement_gesan(){
		$this->load->view($this->uri->uri_string());
	}

	function settlement_sisan(){
		$this->load->view($this->uri->uri_string());
	}

	function jaemusang_sub(){
		$this->{$this->uri->segment(3)}();
	}

	function sub1(){
		$this->load->view($this->uri->uri_string());
	}

	function sub2(){
		$this->load->view($this->uri->uri_string());
	}

	function sub3(){
		$this->load->view($this->uri->uri_string());
	}

	function sub4(){
		$this->load->view($this->uri->uri_string());
	}

	function sub5(){
		$this->load->view($this->uri->uri_string());
	}

	function sub6(){
		$this->load->view($this->uri->uri_string());
	}

	function sub7(){
		$this->load->view($this->uri->uri_string());
	}

	function sub8(){
		$this->load->view($this->uri->uri_string());
	}

	function sub9(){
		$this->load->view($this->uri->uri_string());
	}

	function sub10(){
		$this->load->view($this->uri->uri_string());
	}

	function sub11(){
		$this->load->view($this->uri->uri_string());
	}

	function sonik_sub(){
		$this->{$this->uri->segment(3)}();
	}

	function sonik_sub1(){
		$this->load->view($this->uri->uri_string());
	}

	function sonik_sub2(){
		$this->load->view($this->uri->uri_string());
	}

	function sonik_sub3(){
		$this->load->view($this->uri->uri_string());
	}

	function sonik_sub4(){
		$this->load->view($this->uri->uri_string());
	}

	function sonik_sub5(){
		$this->load->view($this->uri->uri_string());
	}

	function sonik_sub6(){
		$this->load->view($this->uri->uri_string());
	}

	function sonik_sub7(){
		$this->load->view($this->uri->uri_string());
	}

	function sonik_sub8(){
		$this->load->view($this->uri->uri_string());
	}

	function sonik_sub9(){
		$this->load->view($this->uri->uri_string());
	}


	function supplementary_schedules(){
		$this->{$this->uri->segment(3)}();
	}

	function supplementary_schedules1(){
		$this->load->view($this->uri->uri_string());
	}

	function supplementary_schedules2(){
		$this->load->view($this->uri->uri_string());
	}

	function supplementary_schedules3(){
		$this->load->view($this->uri->uri_string());
	}

	function supplementary_schedules4(){
		$this->load->view($this->uri->uri_string());
	}

	function supplementary_schedules5(){
		$this->load->view($this->uri->uri_string());
	}

	function supplementary_schedules6(){
		$this->load->view($this->uri->uri_string());
	}


	function data_room(){
		$this->load->view($this->uri->uri_string());
	}


}
