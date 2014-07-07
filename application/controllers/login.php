<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends My_Controller {

	public function __construct()
	{
		parent::__construct();

		// See if the user is already logged in
		// and redirect them if necessary
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
			$this->login();
		}
		else {
			$data['page_title'] = 'Login';
			$this->template->show('login_view', $data);
		}
	}
	
	public function login()
	{
		$data['page_title'] = 'Login';

		$this->form_validation->set_rules('loginEmail', 'Email Address', 'trim|required|valid_email|xss_clean');
		$this->form_validation->set_rules('loginPassword', 'Password', 'required|xss_clean');

		if ($this->form_validation->run() === FALSE)
		{
			$this->template->show('login_view');
		}
		else
		{
			$user = array('email' => $this->input->post('loginEmail'),
										'password' => $this->input->post('loginPassword')
									);
			
			$result = $this->user_model->validate_user($user);		
			if ($result)
			{
				$data['user_id'] = $result['id'];
      	$data['is_logged_in'] = TRUE;
      	$data['role_id'] = $result['user_role_id'];
      	
        $this->session->set_userdata('user_session', $data);

        // Redirect the user based on the role (Lender or Borrower)
        if ($this->get_user_role() == 1)
					redirect(base_url('lender/' . $data['user_id']));
				else
					redirect(base_url('borrower/' . $data['user_id']));	      
	    }
	    else 
	    {
	    	$data['error_msg'] =  'Could not login using the specified username or password';
				$this->template->show('login_view', $data);
			}
		}
	}
}
/* End of file login.php */
/* Location: ./application/controllers/login.php */