<link href="<?php echo base_url() ?>assets/css/switchery/switchery.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/switchery/switchery.min.js"></script>
<div class="row">
    <div class="col-md-12">
        <div class="x_panel">
          <div class="x_title">
            <h2><?php echo $this->config->item('site_name').' '.$this->lang->line('app_configuration_heading') ?></h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <?php echo $this->session->flashdata('alert') ?>
            <?php if(validation_errors()) echo get_alert_html(validation_errors(), ALERT_TYPE_ERROR); ?>
            <form id="app_settings"  class="form-horizontal" action="<?php echo base_url() ?>index.php/config/update" method="post">
              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name() ?>" value="<?php echo $this->security->get_csrf_hash() ?>">
                <div class="row">
                  <div class="col-sm-6">
                  <?php foreach ($config_data as $index => $config_item) 
                  {
                   ?>
                   
                  <?php if($config_item->type == 'boolean' && $config_data[$index - 1]->type == 'text'){ ?>
                  </div>
                  <div class="col-sm-5 col-sm-offset-1">
                  <?php } ?>
                  <div class="form-group">
                    <label for="<?php echo $config_item->key ?>" class="col-sm-<?php echo  $config_item->type == 'boolean' ? 9 : 4; ?> control-label"><?php echo $config_item->label_text ?></label>
                    <div class="col-sm-<?php echo  $config_item->type == 'boolean' ? 3 : 8; ?>">
                      <?php if($config_item->type == 'text' && $config_item->key != 'default_language') {?>
                      <input type="text" name="<?php echo $config_item->key ?>" class="form-control" id="<?php echo $config_item->key ?>" value="<?php echo $config_item->value ?>" placeholder="<?php echo $config_item->label_text ?>" <?php if($config_item->required) echo 'required'; ?>>
                      <?php }else if($config_item->type == 'boolean') {?>
                        <div class="">
                          <label>
                            <input type="hidden" name="<?php echo $config_item->key ?>" value="0" />
                            <input type="checkbox" class="js-switch" name="<?php echo $config_item->key ?>" value="1" <?php if($config_item->value == 1) echo 'checked' ?>  />
                          </label>
                        </div>
                       <?php }else{ ?>
                        <select class="form-control" name="<?php echo $config_item->key ?>" id="<?php echo $config_item->key ?>" <?php if($config_item->required) echo 'required'; ?>>
                          <?php foreach ($languages as $key => $value) { 
                            if($value == '.' || $value == '..' || !is_dir(APPPATH.'language/'.$value))
                              continue;
                            ?>
                          <option value="<?php echo $value ?>" <?php if($config_item->value == $value) echo 'selected' ?>><?php echo $value ?></option>
                          <?php } ?>
                        </select>
                      <?php } ?>
                    </div>
                  </div>
                  <?php } ?>
                  </div>
                </div>
                <div class="col-sm-offset-5 col-sm-2">
                  <div class="form-group">
                    <div class="col-sm-12">
                      <button type="submit"  class="btn btn-primary btn-block" >Update</button>
                    </div>
                  </div>
                </div>
              </form>
          </div>
        </div>
    </div>
</div>                            