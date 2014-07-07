	<?php
		// Load Menu
		$this->template->menu('logged');
	?>

	<?php echo ($this->session->flashdata('msg') != '') ? '<div class="alert alert-success">' . $this->session->flashdata('msg') . '</div>' : ''; ?>
  <?php echo ($this->session->flashdata('error') != '') ? '<div class="alert alert-danger">' . $this->session->flashdata('error') . '</div>' : ''; ?>
  <?php echo (validation_errors()) ? '<div class="alert alert-danger">' . validation_errors() . '</div>' : ''; ?>
  <div class = "row">
		<div class="user-info">
			<p><span class="datalabel">Name:</span><?php echo $lender['lender']; ?></p>
			<p><span class="datalabel">Account Balance:</span><?php echo '$' . $lender['balance']; ?></p>
		</div>
  </div>
  <div class="row">
  	<h2>List of people in need of help:</h2>
    <?php if(!empty($borrowers)): ?>
    	<table id="all_borrowers_table" class="table table-striped table-bordered table-hover">
	      <tr>
	        <th>Name</th>
	        <th>Need Money for</th>
	        <th>Reason for Loan</th>
	        <th>Amount Needed</th>
	        <th>Amount Raised</th> 
	        <th>Action</th> 
	        <th></th>      
	      </tr>
    	<?php foreach ($borrowers as $borrower): ?>
        <tr>
          <td><?php echo $borrower['borrower_name']; ?></td>
          <td><?php echo $borrower['amount_for']; ?></td>
          <td><?php echo $borrower['description']; ?></td>
          <td><?php echo '$' . $borrower['amount_needed']; ?></td> 
          <td><?php echo ($borrower['amount_raised'] != NULL) ? '$' . $borrower['amount_raised'] : 'None'; ?></td>
          <td colspan="2">
          	<?php $attributes = array('id' => 'form_' . $borrower['user_id']); ?>
          	<?php echo form_open('lender/'.$user_id.'/lend', $attributes); ?>
	          	<input type="hidden" name="borrower_id" value="<?php echo $borrower['user_id']; ?>">
						  <div class="form-group">
								<div class="input-group">
								  <span class="input-group-addon">$</span>
								  <input name="amountToLend" type="number" class="form-control">
								  <span class="input-group-addon">.00</span>
								  <button type="submit" class="btn btn-success pull-right">Lend</button>
								</div>	
						  </div>
					  </form>				  
          </td>
        </tr>
    	<?php endforeach; ?>
    	</table>
    <?php else: ?>
    	<p>There is currently no one in need of any money.</p>
		<?php endif; ?>
  </div>

  <div class="row">
  	<h2>List of people you lend money to:</h2>
    <?php if(!empty($lent_to)): ?>
    	<table id="borrowers_table" class="table table-striped table-bordered table-hover">
	      <tr>
	        <th>Name</th>
	        <th>Need Money for</th>
	        <th>Reason for Loan</th>
	        <th>Amount Needed</th>
	        <th>Amount Raised</th>
	        <th>Amount Lent</th>        
	      </tr>
    	<?php foreach ($lent_to as $borrower): ?>
        <tr>
          <td><?php echo $borrower['borrower_name']; ?></td>
          <td><?php echo $borrower['amount_for']; ?></td>
          <td><?php echo $borrower['description']; ?></td>
          <td><?php echo '$' . $borrower['amount_needed']; ?></td> 
          <td><?php echo ($borrower['amount_raised'] != NULL) ? '$' . $borrower['amount_raised'] : 'None'; ?></td>
          <td><?php echo '$' . $borrower['lent']; ?></td> 
        </tr>
    	<?php endforeach; ?>
    	</table>
    <?php else: ?>
    	<p>You're not currently lending anyone any money.</p>
		<?php endif; ?>
  </div>