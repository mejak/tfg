<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?php echo base_url() ?>assets/img/favicon.ico">
    <title><?php echo $page_title .' | '.$this->config->item('site_name') ?></title>
    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>assets/css/animate.min.css" rel="stylesheet">
    <!-- Custom styling plus plugins -->
    <link href="<?php echo base_url() ?>assets/css/custom.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>assets/css/style.css" rel="stylesheet">
    <script src="<?php echo base_url() ?>assets/js/jquery.min.js"></script>
    <script src="<?php echo base_url() ?>assets/js/bootstrap.min.js"></script>

    <!--[if lt IE 9]>
        <script src="<?php echo base_url() ?>/assets/js/ie8-responsive-file-warning.js"></script>
        <![endif]-->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
</head>
<body id="login-body">
  <div class="">
    <a class="hiddenanchor" id="tologin"></a>
    <a class="hiddenanchor" id="toregister"></a>
    <div id="wrapper">
      <div id="login" class="animate form">
        <section class="login_content">
          <form method="post" action="<?php echo base_url().'index.php/admin/login' ?>">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name() ?>" value="<?php echo $this->security->get_csrf_hash() ?>">
            <h1><?php echo $this->config->item('site_name') ?></h1>
            <?php if($this->config->item('facebook_login') && isset($fb_login_url) && !empty($fb_login_url)) {?>
            <a href="<?php echo $fb_login_url; ?>">
              <img src="<?php echo base_url() ?>assets/img/facebook-login-button.png" width="65%" >
            </a>
            <br> 
            <br> 
            <?php } ?>
            <?php echo $this->session->flashdata('alert'); ?>
            <?php  if(validation_errors())
                    echo get_alert_html(validation_errors(), ALERT_TYPE_ERROR); ?>
            <div>
              <input type="text" name="username" class="form-control" placeholder="<?php echo $this->lang->line('login_placeholder_username') ?>" required autofocus/>
            </div>
            <div>
              <input type="password" name="password" class="form-control" placeholder="<?php echo $this->lang->line('login_placeholder_password') ?>" required/>
            </div>
            <div id="remember" class="checkbox text-left">
              <label>
                  <input type="checkbox" name="remember" value="1"> <?php echo $this->lang->line('login_label_remember_me') ?>
              </label>
            </div>
            <div>
              <button class="btn btn-default submit" > <?php echo $this->lang->line('login_button_signin') ?></button>
              <a class="reset_pass" href="<?php echo base_url() ?>index.php/admin/forgot_password"><?php echo $this->lang->line('login_link_forgot_password') ?></a>
            </div>
            <div class="clearfix"></div>
            <div class="separator">
                <?php if($this->config->item('signup')){ ?>
                <p class="change_link">
                    <a href="#toregister" class="to_register"><?php echo $this->lang->line('login_link_register') ?></a>
                </p>
                <?php } ?>
                <div class="clearfix"></div>
                <br />
            </div>
          </form>
          <!-- form -->
        </section>
        <!-- content -->
      </div>
      <div id="register" class="animate form">
        <section class="login_content">
            <form id="add_user_form" method="post" action="<?php echo base_url().'index.php/admin/signup' ?>">
              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name() ?>" value="<?php echo $this->security->get_csrf_hash() ?>">
              <h1><?php echo $this->config->item('site_name') ?></h1>
              <?php if($this->config->item('facebook_signup') && isset($fb_login_url) && !empty($fb_login_url)) {?>
              <a href="<?php echo $fb_login_url; ?>">
                <img src="<?php echo base_url() ?>assets/img/fb-signup.png" width="75%" >
              </a>
              <br> 
              <br> 
              <?php } ?>
              <?php echo $this->session->flashdata('alert'); ?>
              <?php  if(validation_errors())
                      echo get_alert_html(validation_errors(), ALERT_TYPE_ERROR); ?>
                <div>
                  <input type="email" id="email" name="email" class="form-control" placeholder="<?php echo $this->lang->line('signup_placeholder_email') ?>" required autofocus>
                </div>
                <div>
                  <input type="text" id="username" name="username" class="form-control" placeholder="<?php echo $this->lang->line('signup_placeholder_username') ?>" required >
                </div>
                <div>
                  <input type="password" id="password" name="password" class="form-control" placeholder="<?php echo $this->lang->line('signup_placeholder_password') ?>" required>
                </div>
                <div>
                  <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="<?php echo $this->lang->line('signup_placeholder_confirm_password') ?>" required>
                </div>
                <div>
                  <button class="btn btn-default submit" type="submit"><?php echo $this->lang->line('signup_button_register') ?></button>
                </div>
                <div class="clearfix"></div>
                <div class="separator">
                  <p class="change_link"><?php echo $this->lang->line('signup_text_already_member') ?>
                    <a href="#tologin" class="to_register"> <?php echo $this->lang->line('signup_link_login') ?> </a>
                  </p>
                  <div class="clearfix"></div>
            </form>
            <!-- form -->
        </section>
        <!-- content -->
      </div>
    </div>
  </div>

<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript">
$(function(){
  window.location.hash = 'toregister';
  //validate signup form
  $('#add_user_form').validate({
      rules:{
        username: {
          required : true,
          minlength : 4,
          maxlength : 50, 
          no_space  : true,
          remote : '<?php echo base_url() ?>index.php/users/isusernameexist'
        },
        email : {
          required : true,
          email : true,
          remote : '<?php echo base_url() ?>index.php/users/isemailexist'
        },
        password:{
          required: true,
          minlength: 6
  
        },
        confirm_password: {
          required : true,
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
          required: '<?php echo $this->lang->line('validation_password').' '.$this->lang->line('validation_required') ?>',
          minlength : '<?php echo $this->lang->line('validation_password_minlength') ?>'
        },
        confirm_password: {
          required : '<?php echo $this->lang->line('validation_reenter_password') ?>',
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
</body>
</html>