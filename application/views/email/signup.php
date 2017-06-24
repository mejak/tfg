<div style="font-family: sans-serif;">
  <div style="background-color: #3B5998; width: 100%; height: 55px; color: white; text-align:center;">
      <div style="padding-top: 10px;     font-size: 30px;">
      <?php echo $this->lang->line('email_signup_welcome') . ' ' . $this->config->item('site_name'); ?>
      </div>

  </div>
  <div style="margin-left:  32%; margin-top: 20px;" >
        <?php echo $this->lang->line('email_signup_new_account') ?><br>
        <?php echo $this->lang->line('email_signup_details') ?> <br><br>
        <?php echo $this->lang->line('validation_email_address') ?> :  <?php echo $email ?><br>
        <?php echo $this->lang->line('validation_username') ?> : <?php echo $username ?><br>
        <?php echo $this->lang->line('validation_password') ?> : <?php echo $password ?> <br> <br>

        <?php echo $this->lang->line('email_signup_login_here') ?> <br> <br>
        <a href="<?php echo base_url() ?>index.php/admin/login"><?php echo $this->lang->line('email_signup_login_at') ?> <?php echo $this->config->item('site_name') ?> </a>
        
      </div>
</div>

