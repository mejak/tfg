<div class="row well" >
  <div class="col-sm-12">
    <h4>Add New Config Item</h4>
    <hr>
  </div>
  <div class="col-sm-6">
    <form id="add_new_config"  class="form-horizontal" action="<?php echo base_url() ?>index.php/config/add" method="post">
      <!--  disable chrome autofill -->
      <div class="col-sm-4"></div>
      <div class="col-sm-8">
        <?php echo $this->session->flashdata('alert') ?>
        <?php if(validation_errors()) echo get_alert_html(validation_errors(), ALERT_TYPE_ERROR); ?>
      </div>
      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name() ?>" value="<?php echo $this->security->get_csrf_hash() ?>">
      <div class="form-group">
        <label for="label_text" class="col-sm-4 control-label">Label Text</label>
        <div class="col-sm-8">
          <input type="text" name="label_text" class="form-control" id="label_text" placeholder="Label Text" required>
        </div>
      </div>
      <div class="form-group">
        <label for="key" class="col-sm-4 control-label">Key</label>
        <div class="col-sm-8">
          <input type="text" name="key" class="form-control" id="key" placeholder="key" required>
        </div>
      </div>
      <div class="form-group">
        <label for="value" class="col-sm-4 control-label">Value</label>
        <div class="col-sm-8">
          <input type="text" name="value" class="form-control" id="value" placeholder="value" >
        </div>
      </div>
      <div class="form-group">
        <label for="new_password" class="col-sm-4 control-label">Type</label>
        <div class="col-sm-8">
          <select class="form-control" id="type" name="type" required> 
            <option value="text">Text</option>
            <option value="boolean">Boolean</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label for="order" class="col-sm-4 control-label">Order</label>
        <div class="col-sm-8">
          <input type="text" name="order" class="form-control" id="order" placeholder="order" required >
        </div>
      </div>
      <div class="form-group">
        <label for="is_required" class="col-sm-4 control-label">Is Required</label>
        <div class="col-sm-8">
            <label class="radio-inline"><input type="radio" name="is_required" value="1" required>Yes</label>
            <label class="radio-inline"><input type="radio" name="is_required" value="0" required>No</label>
        </div>
      </div>
      <div class="form-group">
        <label for="btn_save" class="col-sm-4 control-label"></label>
        <div class="col-sm-8">
          <button type="submit"  class="btn btn-primary" >Add</button>
        </div>
      </div>
    </form>
  </div>
</div>