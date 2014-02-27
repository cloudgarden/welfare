<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * 
 */

class Make_sample extends WWF_Controller {
	function __construct() {
		parent::__construct();
	}
	
	function index(){
		redirect($this->uri->uri_string());
	}
	
}