<div style="font-family: sans-serif;">
  <div style="background-color: #3B5998; width: 100%; height: 55px; color: white; text-align:center;">
      <div style="padding-top: 10px;     font-size: 30px;">
      <?php echo $this->lang->line('email_reset_password'); ?>
      </div>

  </div>
  <div style="margin-left:  32%; margin-top: 20px;" >
        <?php echo $this->lang->line('email_reset_request_recieved') ?><br>
        <?php echo $this->lang->line('email_reset_click_link') ?> <br><br>
        <a href="<?php echo base_url() ?>index.php/admin/reset_password/<?php echo $hash ?>"><?php echo $this->lang->line('forgot_button_reset_password') ?></a>
        
      </div>
</div>

