<div class="row">
    <div class="col-md-12">
        <div class="x_panel">
          <div class="x_title">
            <h2><?php echo $this->lang->line('update_script_heading') ?></h2>
            <h5 class="pull-right"><?php echo $this->lang->line('update_script_current_version').': '. SCRIPT_VERSION ?></h5>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <?php echo $alert;   ?>
            <?php echo $this->session->flashdata('alert') ?>
            <?php if(validation_errors()) echo get_alert_html(validation_errors(), ALERT_TYPE_ERROR); ?>
            <?php if(!extension_loaded('zip')){ ?>
            <div class="col-sm-12">
              <?php echo get_alert_html($this->lang->line('error_zip_extension_not_installed'), ALERT_TYPE_ERROR); ?>
            </div>
            <?php }else if(version_compare(SCRIPT_VERSION, $available_version, '<')){ ?>
            <div class="col-sm-8">
              <form id="add_user_form"  class="form-horizontal" action="<?php echo base_url() ?>index.php/update/index" method="post">      
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name() ?>" value="<?php echo $this->security->get_csrf_hash() ?>">
                <input type="hidden" name="update_version" value="<?php echo $available_version ?>"> 
                <div class="form-group">
                  <label for="code" class="col-sm-4 control-label"><?php echo $this->lang->line('update_label_purchase_code') ?></label>
                  <div class="col-sm-8">
                    <input type="text" name="code" class="form-control" id="code" placeholder="<?php echo $this->lang->line('update_placeholder_purchase_code') ?>" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="buyer_username" class="col-sm-4 control-label"><?php echo $this->lang->line('update_label_buyer_username') ?></label>
                  <div class="col-sm-8">
                    <input type="text" name="buyer_username" class="form-control" id="buyer_username" placeholder="<?php echo $this->lang->line('update_placeholder_buyer_username') ?>" required>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-offset-4 col-sm-8">
                    <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('update_btn_update_script') ?></button>
                  </div>
                </div>
              </form>
            </div>
            <?php } ?>
            <div class="clearfix"></div>
            <?php if(version_compare(SCRIPT_VERSION, $available_version, '<')){ ?>
            <div class="alert alert-warning">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
              </button>
                <strong><i class="fa fa-bullhorn"></i> Important Instructions - Auto Update Requirements</strong>
                <ul>
                  <li>Script parent folder(which contain all script files) and its all sub-folders should have write permissions(755 or 775)</li>
                  <li>You have set write permissions on parent folder already during script installation</li>
                  <li>watch <a href="https://www.youtube.com/watch?v=oq0oM2w9lcQ" target="_blank"> <u>this youtube video </u> </a> to change permissions through FTP using filezilla software. Change permissions of parent folder, set 775, check "Recurse into subdirectories" and select first option "Apply to all files and directories"</li>
                  <li>Read <a href="https://forums.cpanel.net/threads/change-folders-and-subfolders-permission.137085/" target="_blank"> <u>this cPanel thread </u> </a> to change permissions through SSH if you have SSH access and you know SSH</li>
                  <li>If you are unable to do this yourself, contact your hosting support to do this for you.</li>
                  <li>Before updating files, script will take backup of changed files in 'backups' folder </li>
                  <li>If you have changed any script files, that changes will be lost</li>
                  <li>If after update, you find some text missing or you are seeing blank error/success messages. compare your language file with english files and update your language files as new text can be added to english language as script updates </li>
        <!--           <li>For manual update, search through <a href="http://codecanyon.net/item/facebook-campaigner-facebook-autoposter/11339821/comments?ref=alrazamc" target="_blank"><u> script comments on codecanyon </u></a> for update instructions</li>
         -->        </ul>
            </div>
            <?php } ?>
          </div>
        </div>
    </div>
</div>

