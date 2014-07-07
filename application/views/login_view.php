		<?php
			// Load Menu
			$this->template->menu('notlogged');
		?>
		<?php echo (isset($error_msg)) ? '<div class="alert alert-danger">' . $error_msg . '</div>' : ''; ?>
    <?php echo (validation_errors()) ? '<div class="alert alert-danger">' . validation_errors() . '</div>' : ''; ?>
    <h1><?=$page_title;?></h1>
    <div class="row">
      <div class="col-md-4">
        <?php echo form_open('login'); ?>
				  <div class="form-group">
				    <label for="loginEmail">Email Address</label>
				    <input type="email" class="form-control" name="loginEmail" id="loginEmail" placeholder="Enter email"
		    						value="<?php echo set_value('loginEmail'); ?>">
				  </div>
				  <div class="form-group">
				    <label for="loginPassword">Password</label>
				    <input type="password" class="form-control" name="loginPassword" id="loginPassword" placeholder="Password">
				  </div>
				  <button type="submit" class="btn btn-success">Log In</button>
				</form>
				<div class="login-register margin-top">
					<a href="<?php echo base_url()?>register">Click here to register.</a>
				</div>
      </div>
    </div>