<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * 
 */

class Main extends WWF_Controller {
	function __construct() {
		parent::__construct();
	}
	
	function index(){
		$this->load->view('main');
	}
}