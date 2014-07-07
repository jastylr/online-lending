<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Borrower extends My_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!$this->is_logged_in())
			redirect('login');

		// Make sure that only borrowers see this page
		if ($this->get_user_role() == 1)
			redirect('lender/' . $this->get_user_id());

		$this->load->model('user_model');
	}

	public function index()
	{
		$data['page_title'] = '';
		$this->template->show('borrower_view', $data);
	}

	public function show($borrower_id)
	{
		// Get data for this borrower
		$borrower_data = $this->user_model->getBorrower($borrower_id);
    $lender_data = $this->user_model->getLendersByID($borrower_id);
    $data['borrower'] = $borrower_data;
    $data['lenders'] = $lender_data;
    $this->template->show('borrower_view', $data);
	}

}