<link href="<?php echo base_url() ?>assets/css/switchery/switchery.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/switchery/switchery.min.js"></script>
<div class="row">
    <div class="col-md-12">
        <div class="x_panel">
          <div class="x_title">
            <h2><?php echo $this->lang->line('edit_user_heading') ?></h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <?php echo $this->session->flashdata('alert') ?>
            <?php if(validation_errors()) echo get_alert_html(validation_errors(), ALERT_TYPE_ERROR); ?>
            <div class="row">
              <div class="col-sm-8">
                <form id="add_user_form"  class="form-horizontal" action="<?php echo base_url() ?>index.php/users/edit/<?php echo $user->user_id ?>" method="post">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name() ?>" value="<?php echo $this->security->get_csrf_hash() ?>">
                  <div class="form-group">
                    <label for="username" class="col-sm-4 control-label"><?php echo $this->lang->line('user_label_username') ?></label>
                    <div class="col-sm-8">
                      <input type="text" name="username" class="form-control" id="username"  value="<?php echo $user->user_name ?>" placeholder="<?php echo $this->lang->line('signup_placeholder_username') ?>" >
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="email" class="col-sm-4 control-label"><?php echo $this->lang->line('user_label_email') ?></label>
                    <div class="col-sm-8">
                      <input type="email" name="email" class="form-control" id="email" value="<?php echo $user->user_email ?>" placeholder="<?php echo $this->lang->line('signup_placeholder_email') ?>" >
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="usertype" class="col-sm-4 control-label"><?php echo $this->lang->line('user_label_usertype') ?></label>
                    <div class="col-sm-8">
                      <select class="form-control" id="usertype" name="usertype" > 
                        <option value="<?php echo USER_TYPE_USER ?>"><?php echo $this->lang->line('user_type_user') ?></option>
                        <option value="<?php echo USER_TYPE_ADMIN ?>"><?php echo $this->lang->line('user_type_admin') ?></option>
                      </select>
                    </div>
                  </div>
                  <script type="text/javascript">
                    $('#usertype').val('<?php echo $user->user_role ?>')
                  </script>
                  <div class="form-group">
                    <label for="password" class="col-sm-4 control-label"><?php echo $this->lang->line('user_label_password') ?></label>
                    <div class="col-sm-8">
                      <input type="password" name="password" class="form-control" id="password" placeholder="<?php echo $this->lang->line('signup_placeholder_password') ?>" >
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="confirm_password" class="col-sm-4 control-label"><?php echo $this->lang->line('user_label_confirm_password') ?></label>
                    <div class="col-sm-8">
                      <input type="password" name="confirm_password" class="form-control" id="confirm_password" placeholder="<?php echo $this->lang->line('signup_placeholder_confirm_password') ?>" >
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="page_limit" class="col-sm-4 control-label"><?php echo $this->lang->line('user_label_page_limit') ?></label>
                    <div class="col-sm-8">
                      <input type="number" name="page_limit" class="form-control" id="page_limit" value="<?php echo $user_setting->page_limit ?>" placeholder="<?php echo $this->lang->line('user_label_page_limit') ?>" >
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="insights_allowed" class="col-sm-4 control-label"><?php echo $this->lang->line('user_label_insights_allowed') ?></label>
                    <div class="col-sm-8">
                      <label>
                        <input type="hidden" name="insights_allowed" value="<?php echo INSIGHTS_ALLOWED_NO ?>" />
                        <input type="checkbox" class="js-switch" name="insights_allowed" value="<?php echo INSIGHTS_ALLOWED_YES ?>" <?php if($user_setting->insights_allowed) echo  'checked' ?>  />
                      </label>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="btn_save" class="col-sm-4 control-label"></label>
                    <div class="col-sm-8">
                      <button type="submit"  class="btn btn-primary" ><?php echo $this->lang->line('user_btn_edit_user') ?></button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
    </div>
</div> 
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript">
$(function(){
  //validate signup form
  $('#add_user_form').validate({
      rules:{
        username: {
          required : true,
          minlength : 4,
          maxlength : 50, 
          no_space : true,
          remote : '<?php echo base_url() ?>index.php/users/isusernameexist/<?php echo $this->uri->segment(3) ?>'
        },
        email : {
          required : true,
          email : true,
          remote : '<?php echo base_url() ?>index.php/users/isemailexist/<?php echo $this->uri->segment(3) ?>'
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