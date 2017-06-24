<div class="row">
    <div class="col-md-12">
        <div class="x_panel">
          <div class="x_title">
            <h2><?php echo $this->lang->line('user_settings_heading') ?></h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <?php echo $this->session->flashdata('alert') ?>
            <?php if(validation_errors()) echo get_alert_html(validation_errors(), ALERT_TYPE_ERROR); ?>
            <form id="settings_form"  class="form-horizontal" action="<?php echo base_url() ?>index.php/users/settings" method="post">
              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name() ?>" value="<?php echo $this->security->get_csrf_hash() ?>">
              <div class="form-group">
                <label for="username" class="col-sm-3 control-label"><?php echo $this->lang->line('user_label_username') ?></label>
                <div class="col-sm-6">
                  <input type="text" name="username" class="form-control" id="username"  value="<?php echo $user->user_name ?>" placeholder="<?php echo $this->lang->line('signup_placeholder_username') ?>" <?php if(!$this->config->item('username_email_change')) echo 'disabled="disabled"' ?>>
                </div>
              </div>
              <div class="form-group">
                <label for="email" class="col-sm-3 control-label"><?php echo $this->lang->line('user_label_email') ?></label>
                <div class="col-sm-6">
                  <input type="email" name="email" class="form-control" id="email" value="<?php echo $user->user_email ?>" placeholder="<?php echo $this->lang->line('signup_placeholder_email') ?>" <?php if(!$this->config->item('username_email_change')) echo 'disabled="disabled"' ?>>
                </div>
              </div>
              <div class="form-group">
                <label for="current_password" class="col-sm-3 control-label"><?php echo $this->lang->line('user_label_current_password') ?></label>
                <div class="col-sm-6">
                  <input type="password" name="current_password" class="form-control" id="current_password" placeholder="<?php echo $this->lang->line('user_placeholder_current_password') ?>" >
                </div>
              </div>
              <div class="form-group">
                <label for="password" class="col-sm-3 control-label"><?php echo $this->lang->line('user_label_new_password') ?></label>
                <div class="col-sm-6">
                  <input type="password" name="password" class="form-control" id="password" placeholder="<?php echo $this->lang->line('user_placeholder_new_password') ?>" >
                </div>
              </div>
              <div class="form-group">
                <label for="confirm_password" class="col-sm-3 control-label"><?php echo $this->lang->line('user_label_confirm_password') ?></label>
                <div class="col-sm-6">
                  <input type="password" name="confirm_password" class="form-control" id="confirm_password" placeholder="<?php echo $this->lang->line('signup_placeholder_confirm_password') ?>" >
                </div>
              </div>
              <div class="form-group">
                <label for="time_zone" class="col-sm-3 control-label"><?php echo $this->lang->line('user_label_timezone') ?></label>
                <div class="col-sm-6">
                  <select class="form-control" id="time_zone" name="time_zone" > 
                      <?php foreach($time_zones as $zone){ ?>
                      <option value="<?php echo $zone ?>" <?php if($user_setting->time_zone == $zone) echo 'selected'; ?>><?php echo $zone ?></option>
                      <?php } ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label for="language" class="col-sm-3 control-label"><?php echo $this->lang->line('user_label_language') ?></label>
                <div class="col-sm-6">
                  <select class="form-control" id="language" name="language" > 
                      <?php foreach ($languages as $key => $value) { 
                      if($value == '.' || $value == '..' || !is_dir(APPPATH.'language/'.$value))
                        continue;
                      ?>
                    <option value="<?php echo $value ?>" <?php if($user_setting->current_lang == $value) echo 'selected'; ?>><?php echo $value ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <?php if(!$this->config->item('use_default_app')){ ?>
              <div class="form-group">
                <label for="app_id" class="col-sm-3 control-label"><?php echo $this->lang->line('user_label_app_id') ?></label>
                <div class="col-sm-6">
                  <input type="text" name="app_id" class="form-control" id="app_id"  value="<?php echo $user_setting->app_id ?>" placeholder="<?php echo $this->lang->line('user_label_app_id') ?>" >
                </div>
              </div>
              <div class="form-group">
                <label for="app_secret" class="col-sm-3 control-label"><?php echo $this->lang->line('user_label_app_secret') ?></label>
                <div class="col-sm-6">
                  <input type="text" name="app_secret" class="form-control" id="app_secret"  value="<?php echo $user_setting->app_secret ?>" placeholder="<?php echo $this->lang->line('user_label_app_secret') ?>" >
                </div>
              </div>
              <?php } ?>
              <div class="form-group">
                <label for="btn_save" class="col-sm-3 control-label"></label>
                <div class="col-sm-8">
                  <button type="submit"  class="btn btn-primary" ><?php echo $this->lang->line('user_setting_btn_save') ?></button>
                </div>
              </div>
            </form>
          </div>
        </div>
    </div>
</div> 
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript">
$(function(){
  //validate signup form
  $('#settings_form').validate({
      rules:{
        username: {
          required : true,
          minlength : 4,
          maxlength : 50, 
          no_space  : true,
          remote : '<?php echo base_url() ?>index.php/users/isusernameexist/<?php echo $this->session->userdata('user_id') ?>'
        },
        email : {
          required : true,
          email : true,
          remote : '<?php echo base_url() ?>index.php/users/isemailexist/<?php echo $this->session->userdata('user_id') ?>'
        },
        password:{
          minlength: 6
  
        },
        confirm_password: {
          minlength: 6,
          password_match : true
        }
      },
      messages:{
        username: {
          required : '<?php echo $this->lang->line('validation_username').' '.$this->lang->line('validation_required') ?>',
          minlength : '<?php echo $this->lang->line('validation_username_minlength') ?>',
          maxlength : '<?php echo $this->lang->line('validation_username_maxlength') ?>',
          no_space :  '<?php echo $this->lang->line('validation_username_no_space') ?>',
          remote: '<?php echo $this->lang->line('validation_username_exist') ?>'
        },
        email : {
          required : '<?php echo $this->lang->line('validation_email_address').' '.$this->lang->line('validation_required') ?>',
          email : '<?php echo $this->lang->line('validation_invalid_email') ?>',
          remote : '<?php echo $this->lang->line('validation_email_exist') ?>'
        },
        usertype :{
          required : '<?php echo $this->lang->line('validation_usertype').' '.$this->lang->line('validation_required') ?>'
        },
        password:{
          minlength : '<?php echo $this->lang->line('validation_password_minlength') ?>'
        },
        confirm_password: {
          minlength : '<?php echo $this->lang->line('validation_password_minlength') ?>',
          password_match : '<?php echo $this->lang->line('validation_password_not_same') ?>'
        }
      }
    });
    jQuery.validator.addMethod("password_match", function(value, element) {
        return this.optional(element) || $('#password').val() == $('#confirm_password').val();
    });
    jQuery.validator.addMethod("no_space", function(value, element) { 
      return value.indexOf(" ") < 0 && value != ""; 
    });
});
</script>