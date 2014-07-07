<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register extends MY_Controller {

	public function __construct()
	{
		parent::__construct();

		// If the user is already logged in, redirect them
		// based on whether they are a borrower or lender
		if ($this->is_logged_in()) {
			$user_id = $this->get_user_id();
			if ($this->get_user_role() == 1)
				redirect('lender/'. $user_id);
			else
				redirect('borrower/' . $user_id);
		}

		$this->load->library('form_validation');
		$this->load->model('user_model');

	}

	
	public function index()
	{
		if($this->input->post()){
			$this->register();
		}
		else {
			$data['page_title'] = 'Register';
			$this->template->show('register_view', $data);
		}
	}

	public function register()
	{
		$data['page_title'] = 'Register';

		// Determine if this is a lender registration or a 
		// borrower registration and then recirect accordingly
		$role = $this->input->post('role');
		
		// Perform form validation, first on common user registration fields and then by the user's role
		$this->form_validation->set_rules('regFirstName', 'First Name', 'trim|required|alpha|xss_clean');
		$this->form_validation->set_rules('regLastName', 'Last Name', 'trim|required|alpha|xss_clean');
		$this->form_validation->set_rules('regEmail', 'Email Address', 'trim|required|valid_email|alphanumeric|xss_clean');
		$this->form_validation->set_rules('regPassword', 'Password', 'required|min_length[6]|xss_clean');
		$this->form_validation->set_rules('regConfirmPassword', 'Confirm Password', 'required|min_length[6]|matches[regPassword]|xss_clean');

		// Additional validation depending on whether
		// registratnt is a lender or borrower
		if ($role == 'lender') {
			$this->form_validation->set_rules('regMoneyToLend', 'Money', 'trim|required|integer|greater_than[0]|xss_clean');
		}
		else {
			$this->form_validation->set_rules('regNeedMoneyFor', 'Need Money For', 'trim|required|alphanumeric|xss_clean');
			$this->form_validation->set_rules('regMoneyNeeed', 'Amount Needed', 'trim|required|integer|greater_than[0]|xss_clean');
			$this->form_validation->set_rules('regDescription', 'Description', 'trim|alphanumeric|xss_clean');	
		}

		// Run validation and display errors or go ahead and
		// register the user
		if ($this->form_validation->run() === FALSE)
		{			
			$this->template->show('register_view', $data);
		}
		else {
			// Save the POST info in a local variable
			$post = $this->input->post();
					
			// See if the supplied email address has already been registered
			if (!$this->user_model->checkUserExists($post['regEmail']))
			{			
				// hash the user's password before storing it in the db
				$hash = $this->user_model->hashpass($post['regPassword']);
				$post['regPassword'] = $hash;
				$result = $this->user_model->register_user($post);
				if ($result)
				{
					$data['is_logged_in'] = TRUE;
					$data['user_id'] = $result['user_id'];
	      	$data['role_id'] = $result['role_id'];
	      	
	        $this->session->set_userdata('user_session', $data);
					if ($data['role_id'] == 1)
						redirect(base_url('lender/' . $data['user_id']));
					else
						redirect(base_url('borrower/' . $data['user_id']));
				}
				else
				{
					$data['error_msg'] = 'Could not complete the registration process.';
					$this->template->show('register_view', $data);
				}
			}
			else
			{
				$data['error_msg'] = 'A user with email address '. $post['regEmail']. ' is already registered.';
				$this->template->show('register_view', $data);
			}
		}
	}
}
/* End of file register.php */
/* Location: ./application/controllers/register.php */