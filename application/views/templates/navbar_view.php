    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?=base_url();?>">Online Lending App</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="<?php if ($this->uri->uri_string() == '') {echo 'active';}?>">
              <a href="<?=base_url();?>">Home</a>
            </li>                   
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <?php if($view == 'logged'): ?>
              <li><a href="<?=base_url();?>logout">Log off</a></li>
            <?php else: ?>
              <li><a href="<?=base_url();?>register">Register</a></li>
              <li><a href="<?=base_url();?>login">Log in</a></li> 
            <?php endif; ?>
          </ul>
        </div><!--/.navbar-collapse -->
      </div>
    </div>