<div style="font-family: sans-serif;">
  <div style="background-color: #3B5998; width: 100%; height: 55px; color: white; text-align:center;">
      <div style="padding-top: 10px;     font-size: 30px;">
      <?php echo $this->lang->line('email_password_changed').' - ' . $this->config->item('site_name'); ?>
      </div>

  </div>
  <div style="margin-left:  32%; margin-top: 20px;" >
        <?php echo $this->lang->line('email_password_changed_text') ?><br><br>
        <a href="<?php echo base_url() ?>index.php/admin/login"><?php echo $this->lang->line('email_password_changed_login_button') ?></a>
        
      </div>
</div>

