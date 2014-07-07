<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lender extends My_Controller {

	public function __construct()
	{
		parent::__construct();

		if (!$this->is_logged_in())
			redirect('login');

		// Make sure that only borrowers see this page
		if ($this->get_user_role() == 2)
			redirect('borrower/' . $this->get_user_id());	

		$this->load->library('form_validation');
		$this->load->model('user_model');
	}

	public function index($user_id = NULL)
	{
		$data['page_title'] = '';
		$this->load->model('user_model');

		$this->template->show('lender_view', $data);
	}

	public function show($lender_id)
	{
		// Get data for this lender
		$lender_data = $this->user_model->getLender($lender_id);
    $borrowers = $this->user_model->getAllBorrowers();
    $lent_to = $this->user_model->getLenderBorrowers($lender_id);

    $data['lender'] = $lender_data;
    $data['user_id'] = $lender_id;
    $data['borrowers'] = $borrowers;
    $data['lent_to'] = $lent_to;
    $this->template->show('lender_view', $data);
	}

	public function lend($lender_user_id) {

		$this->form_validation->set_rules('amountToLend', 'Amount to Lend', 'trim|required|integer|greater_than[0]|xss_clean');
		if ($this->form_validation->run() === FALSE)
		{	
			$this->session->set_flashdata('error', validation_errors());			
		}
		else {
			$post = $this->input->post();
			// First, need to check that the lender has money
    	$balance = $this->user_model->getLenderBalance($lender_user_id);
    	
    	// If the lender has more than what they are lending then
    	// go ahead and lend the money
    	if ($balance >= intval($post['amountToLend'])) {
    		$this->user_model->lendMoney($lender_user_id, $post);
			}
			else {
				if ($balance == 0) {
					$this->session->set_flashdata('error', "Sorry, you have a $0 balance. You cannot lend any more money.");
				}
				else {
					// Error letting user know they don't have enough balance
					$this->session->set_flashdata('error', 'Your current balance is less than $' . $post['amountToLend'] . '.00. Please choose a different loan amount.');
				}
			}
		}
		redirect('lender/' . $lender_user_id);
	}
}