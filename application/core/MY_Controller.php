<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function is_logged_in()
    {
    	$this->user_session = $this->session->userdata('user_session');
    	return (isset($this->user_session) && $this->user_session['is_logged_in']);
    }

    public function get_user_role()
    {
    	$this->user_session = $this->session->userdata('user_session');
    	return $this->user_session['role_id'];
    }

    public function get_user_id()
    {
    	$this->user_session = $this->session->userdata('user_session');
    	return $this->user_session['user_id'];
    }

    public function log_out()
    {
    	$this->session->sess_destroy();
    	$this->session->unset_userdata('is_logged_in');
    	$this->session->unset_userdata('user_id');
    	$this->session->unset_userdata('user_session');
    }
}