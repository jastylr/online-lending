		<?php
			// Load Menu
			$this->template->menu('notlogged');
		?>
    <?php echo (isset($error_msg)) ? '<div class="alert alert-danger">' . $error_msg . '</div>' : ''; ?>
    <?php echo (validation_errors()) ? '<div class="alert alert-danger">' . validation_errors() . '</div>' : ''; ?>
      <!-- Example row of columns -->
      <h1><?=$page_title;?></h1>
      <div class="row">
      	<div class="col-md-4">
      		<h3>Lender</h3>
      		<?php echo form_open('register'); ?>
          	<input type="hidden" name="role" value="lender">
					  <div class="form-group">
					    <label for="regFirstName">First Name:</label>
					    <input type="text" class="form-control" name="regFirstName" id="regFirstName"
					    				value="<?php echo set_value('regFirstName'); ?>">
					  </div>
					  <div class="form-group">
					    <label for="regLastName">Last Name:</label>
					    <input type="text" class="form-control" name="regLastName" id="regLastName"
					    				value="<?php echo set_value('regLastName'); ?>">
					  </div>
					  <div class="form-group">
					    <label for="regEmail">Email Address:</label>
					    <input type="email" class="form-control" name="regEmail" id="regEmail"
					    				value="<?php echo set_value('regEmail'); ?>">
					  </div>
					  <div class="form-group">
					    <label for="regPassword">Password:</label>
					    <input type="password" class="form-control" name="regPassword" id="regPassword">
					  </div>
					  <div class="form-group">
					    <label for="regConfirmPassword">Confirm Password:</label>
					    <input type="password" class="form-control" name="regConfirmPassword" id="regConfirmPassword">
					  </div>
					  <div class="form-group">
							<label for="regMoneyToLend">Money:</label>
							<div class="input-group">
							  <span class="input-group-addon">$</span>
							  <input name="regMoneyToLend" id="regMoneyToLend" type="number" class="form-control">
							  <span class="input-group-addon">.00</span>
							</div>	
					  </div>
					  <button type="submit" class="btn btn-success">Register</button>
					</form>	
					<div class="login-register margin-top">
						<a href="<?php echo base_url()?>login">Already a member?</a>
					</div>		
        </div>
        <div class="col-md-4 col-md-offset-3">
        	<h3>Borrower</h3>
          <?php echo form_open('register'); ?>
          	<input type="hidden" name="role" value="borrower">
					  <div class="form-group">
					    <label for="regFirstName">First Name:</label>
					    <input type="text" class="form-control" name="regFirstName" id="regFirstName"
					    				value="<?php echo set_value('regFirstName'); ?>">
					  </div>
					  <div class="form-group">
					    <label for="regLastName">Last Name:</label>
					    <input type="text" class="form-control" name="regLastName" id="regLastName"
					    				value="<?php echo set_value('regLastName'); ?>">
					  </div>
					  <div class="form-group">
					    <label for="regEmail">Email Address:</label>
					    <input type="email" class="form-control" name="regEmail" id="regEmail"
					    				value="<?php echo set_value('regEmail'); ?>">
					  </div>
					  <div class="form-group">
					    <label for="regPassword">Password:</label>
					    <input type="password" class="form-control" name="regPassword" id="regPassword">
					  </div>
					  <div class="form-group">
					    <label for="regConfirmPassword">Confirm Password:</label>
					    <input type="password" class="form-control" name="regConfirmPassword" id="regConfirmPassword">
					  </div>
					  <div class="form-group">
					    <label for="regNeedMoneyFor">Need Money For:</label>
					    <input type="text" class="form-control" name="regNeedMoneyFor" id="regNeedMoneyFor"
					    				value="<?php echo set_value('regNeedMoneyFor'); ?>">
					  </div>
					  <div class="form-group">
					    <label for="regDescription">Description:</label><br />
					    <textarea name="regDescription" id="regDescription" cols="55" rows="5" value="<?php echo set_value('regDescription'); ?>"></textarea>
					  </div>
					  <div class="form-group">
							<label for="regMoneyNeeed">Amount Needed:</label>
							<div class="input-group">
							  <span class="input-group-addon">$</span>
							  <input name="regMoneyNeeed" id="regMoneyNeeed" type="number" class="form-control">
							  <span class="input-group-addon">.00</span>
							</div>						
					  </div>
					  <button type="submit" class="btn btn-success">Register</button>
					</form>	
					<div class="login-register margin-top">
						<a href="<?php echo base_url()?>login">Already a member?</a>
					</div>		
        </div>
      </div>
    