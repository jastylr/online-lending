<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends My_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->log_out();
		redirect('/login');
	}
}