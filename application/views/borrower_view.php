<?php
	// Load Menu
	$this->template->menu('logged');
?>

	<?php echo ($this->session->flashdata('msg') != '') ? '<div class="alert alert-success">' . $this->session->flashdata('msg') . '</div>' : ''; ?>
  <?php echo ($this->session->flashdata('error') != '') ? '<div class="alert alert-danger">' . $this->session->flashdata('error') . '</div>' : ''; ?>
  <div class = "row">
		<div class="user-info">
			<p><span class="datalabel">Name:</span><?php echo $borrower['borrower']; ?></p>
			<p><span class="datalabel">Amount Need:</span><?php echo '$' . $borrower['amount_needed']; ?></p>
			<p><span class="datalabel">Amount Raised:</span><?php echo ($borrower['raised'] != NULL) ? '$' . $borrower['raised'] : 'None'; ?></p>
		</div>
  </div>
  <div class="row">
  	<h2>List of people who lend you money:</h2>
    <?php if(!empty($lenders)): ?>
    	<table id="users_table" class="table table-striped table-bordered table-hover">
	      <tr>
	        <th>Name</th>
	        <th>Email</th>
	        <th>Amount Lent</th>        
	      </tr>
    	<?php foreach ($lenders as $lender): ?>
        <tr>
          <td><?php echo $lender['lender_name']; ?></td>
          <td><?php echo $lender['email']; ?></td>
          <td><?php echo '$' . $lender['amount']; ?></td> 
        </tr>
    	<?php endforeach; ?>
    	</table>
    <?php else: ?>
    	<p>There is currently no one lending you any money.</p>
		<?php endif; ?>
  </div>