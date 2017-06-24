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
    <div id="wrapper">
      <div id="login" class="form">
        <section class="login_content">
          <form method="post" id="reset_password" action="<?php echo base_url().'index.php/admin/reset_password/'.$hash ?>">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name() ?>" value="<?php echo $this->security->get_csrf_hash() ?>">
            <h1><?php echo $this->config->item('site_name') ?></h1>
            <?php if(isset($fb_login_url) && !empty($fb_login_url)) {?>
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
              <input type="password" id="password" name="password" class="form-control" placeholder="<?php echo $this->lang->line('reset_placeholder_password') ?>" required autofocus>
            </div>
            <div>
              <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="<?php echo $this->lang->line('reset_placeholder_confirm_password') ?>" required>
            </div>
            <div>
              <button class="btn btn-default submit" ><?php echo $this->lang->line('reset_button_reset_password') ?></button>
            </div>
            <div class="clearfix"></div>
            <div class="separator">
                <?php if($this->config->item('signup')){ ?>
                    <a href="<?php echo base_url() ?>index.php/admin/signup" class="pull-left"><?php echo $this->lang->line('login_link_register') ?></a>
                    <a class="pull-right" href="<?php echo base_url() ?>index.php/admin/login"><?php echo $this->lang->line('signup_link_login') ?></a>
                <?php } ?>
                <div class="clearfix"></div>
                <br />
            </div>
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
  //validate signup form
  $('#reset_password').validate({
      rules:{
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
});
</script>
</body>
</html>
