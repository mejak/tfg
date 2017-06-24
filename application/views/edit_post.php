<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/jquery-ui-1.8.21.custom.css">
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui-1.10.0.custom.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui-timepicker-addon.js"></script>
<?php $this->load->view('edit_post_controller'); ?>
<div class="row well" ng-app="fbcmpapp" ng-controller="postController">
  <h4><?php echo $this->lang->line('edit_post_heading') ?></h4>
  <hr> 
  <form role="form" class="form-horizontal">
    <div class="row setup-content" id="step-1" ng-cloak>
      <div class="form-group">
        <label for="post_name" class="col-sm-2 control-label required"><?php echo $this->lang->line('create_post_label_name') ?></label>
        <div class="col-sm-4">
          <input type="text" class="form-control" name="post_name" ng-model="post_name" id="post_name" placeholder="<?php echo $this->lang->line('create_post_placeholder_post_name') ?>">
        </div>
      </div>
      <div class="col-sm-12" >
        <ul class="nav nav-tabs post_tabs margin-btm-20" role="tablist">
          <li role="presentation" class="{{ post_type_constant == '<?php echo POST_TYPE_STATUS ?>' ? 'active' : 'disabled' }}"><a ng-click="post_type='status'" href="#status" aria-controls="status" role="tab" data-toggle="tab"><?php echo $this->lang->line('create_post_type_status') ?></a></li>
          <li role="presentation" class="{{ post_type_constant == '<?php echo POST_TYPE_LINK ?>' ? 'active' : 'disabled' }}"><a ng-click="post_type='link'" href="#link" aria-controls="link" role="tab" data-toggle="tab"><?php echo $this->lang->line('create_post_type_link') ?></a></li>
          <li role="presentation" class="{{ post_type_constant == '<?php echo POST_TYPE_PHOTO ?>' ? 'active' : 'disabled' }}"><a ng-click="post_type='photo'" href="#photo" aria-controls="photo" role="tab" data-toggle="tab"><?php echo $this->lang->line('create_post_type_photo') ?></a></li>
          <li role="presentation" class="{{ post_type_constant == '<?php echo POST_TYPE_VIDEO ?>' ? 'active' : 'disabled' }}"><a ng-click="post_type='video'" href="#video" aria-controls="video" role="tab" data-toggle="tab"><?php echo $this->lang->line('create_post_type_video') ?></a></li>
        </ul>
        <div class="tab-content">

          <div role="tabpanel" class="tab-pane {{ post_type_constant == '<?php echo POST_TYPE_STATUS ?>' ? 'active' : '' }}" id="status">
            <div class="form-group">
              <label for="fb_status_message" class="col-sm-2 control-label required"><?php echo $this->lang->line('create_post_label_status') ?></label>
              <div class="col-sm-8">
                <textarea ng-model="fb_status_message" name="fb_status_message" id="fb_status_message" class="form-control" placeholder="<?php echo $this->lang->line('create_post_placeholder_status') ?>"></textarea>
              </div>
            </div>
          </div> <!-- end status Tab -->
          <div role="tabpanel" class="tab-pane {{ post_type_constant == '<?php echo POST_TYPE_LINK ?>' ? 'active' : '' }}" id="link">
            <div class="form-group">
              <label for="link_url" class="col-sm-2 control-label required"><?php echo $this->lang->line('create_post_label_link_url') ?></label>
              <div class="col-sm-4">
                <input type="text" ng-disabled="true" class="form-control" ng-model="link_url" name="link_url" id="link_url" placeholder="<?php echo $this->lang->line('create_post_placeholder_link_url') ?>">
              </div>
            </div>
            <div class="form-group">
              <label for="fb_link_message" class="col-sm-2 control-label"><?php echo $this->lang->line('create_post_label_link_message') ?></label>
              <div class="col-sm-8">
                <textarea ng-model="fb_link_message" name="fb_link_message" id="fb_link_message" class="form-control" placeholder="<?php echo $this->lang->line('create_post_placeholder_link_message') ?>"></textarea>
              </div>
            </div>
            <div class="form-group">
              <label for="link_title" class="col-sm-2 control-label"><?php echo $this->lang->line('create_post_label_link_title') ?></label>
              <div class="col-sm-4">
                <input ng-model="link_title"  ng-disabled="true" type="text" class="form-control" name="link_title" id="link_title" placeholder="<?php echo $this->lang->line('create_post_placeholder_link_title') ?>" maxlength="255">
              </div>
            </div>
            <div class="form-group">
              <label for="link_description" class="col-sm-2 control-label"><?php echo $this->lang->line('create_post_label_link_description') ?></label>
              <div class="col-sm-8">
                <textarea ng-model="link_description" ng-disabled="true"  name="link_description" id="link_description" class="form-control" placeholder="<?php echo $this->lang->line('create_post_placeholder_link_description') ?>"></textarea>
              </div>
            </div>
            <div class="form-group">
              <label for="link_caption" class="col-sm-2 control-label"><?php echo $this->lang->line('create_post_label_link_caption') ?></label>
              <div class="col-sm-4">
                <input type="text" class="form-control" ng-disabled="true" ng-model="link_caption" name="link_caption" id="link_caption" placeholder="<?php echo $this->lang->line('create_post_placeholder_link_caption') ?>">
              </div>
            </div>
            <div class="form-group">
              <label for="linkOptions" class="col-sm-2 control-label"><?php echo $this->lang->line('create_post_label_link_image') ?></label>
              <div class="col-sm-10">
                <div class="radio">
                  <label>
                    <input ng-disabled="true" type="radio" ng-model="link_options" class="link_options" name="linkOptions" id="optionsRadios1" value="option1" checked>
                    <?php echo $this->lang->line('create_post_image_option_url') ?>
                  </label>
                </div>
                <div class="radio">
                  <label>
                    <input ng-disabled="true" type="radio" ng-model="link_options" class="link_options" name="linkOptions" id="optionsRadios2" value="option2">
                    <?php echo $this->lang->line('create_post_image_option_upload') ?>
                  </label>
                </div>
              </div>
            </div> 
            <div class="form-group img_url">
              <div class="col-sm-offset-2 col-sm-4">
                <input type="text" ng-disabled="true" class="form-control" ng-model="link_image_url" name="link_image_url" id="link_image_url" placeholder="<?php echo $this->lang->line('create_post_placeholder_link_image_url') ?>">
              </div>
              <div class="col-sm-2 control-label text-left" ng-show="link_image_url !== undefined && link_image_url != ''">
                <a href="{{ link_image_url }}"  target="_blank"><?php echo $this->lang->line('repost_link_view_image') ?></a>
              </div>
            </div>
            <div class="form-group local_img" style="display:none">
              <div class="col-sm-offset-2 col-sm-4">
                <input type="file" ng-disabled="true" class="form-control" ng-model="link_local_image" name="link_local_image" id="link_local_image" accept="image/*">
              </div>
            </div>
          </div> <!-- end Link Tab -->
          <div role="tabpanel" class="tab-pane {{ post_type_constant == '<?php echo POST_TYPE_PHOTO ?>' ? 'active' : '' }}" id="photo">
            <div class="form-group">
              <label for="fb_photo_message" class="col-sm-2 control-label"><?php echo $this->lang->line('create_post_label_photo_description') ?></label>
              <div class="col-sm-8">
                <textarea ng-model="fb_photo_message" name="fb_photo_message" id="fb_photo_message" class="form-control" placeholder="<?php echo $this->lang->line('create_post_placeholder_photo_description') ?>"></textarea>
              </div>
            </div>
            <div class="form-group">
              <label for="image_name" class="col-sm-2 control-label required"><?php echo $this->lang->line('create_post_label_photo_image') ?></label>
              <div class="col-sm-10">
                <div class="radio">
                  <label>
                    <input type="radio" ng-disabled="true" class="image_option" ng-model="image_options" name="photoOptions" id="optionsRadios1" value="option1" checked>
                    <?php echo $this->lang->line('create_post_image_option_url') ?>
                  </label>
                </div>
                <div class="radio">
                  <label>
                    <input type="radio" ng-disabled="true" class="image_option" ng-model="image_options" name="photoOptions" id="optionsRadios2" value="option2">
                    <?php echo $this->lang->line('create_post_image_option_upload') ?>
                  </label>
                </div>
              </div>
            </div> 
            <div class="form-group img_url">
              <div class="col-sm-offset-2 col-sm-4">
                <input type="text" ng-disabled="true" class="form-control" ng-model="photo_image_url" name="photo_image_url" id="photo_image_url" placeholder="<?php echo $this->lang->line('create_post_placeholder_link_image_url') ?>">
              </div>
              <div class="col-sm-2 control-label text-left" ng-show="photo_image_url !== undefined && photo_image_url != ''">
                <a href="{{ photo_image_url }}"  target="_blank"><?php echo $this->lang->line('repost_link_view_image') ?></a>
              </div>
            </div>
            <div class="form-group local_img" style="display:none">
              <div class="col-sm-offset-2 col-sm-4">
                <input type="file" ng-disabled="true" class="form-control" name="photo_local_image" id="photo_local_image" onchange="angular.element(this).scope().validate_post()" >
              </div>
            </div>
          </div> <!-- end Photo Tab -->
          <div role="tabpanel" class="tab-pane {{ post_type_constant == '<?php echo POST_TYPE_VIDEO ?>' ? 'active' : '' }}" id="video">
            <div class="form-group">
              <label for="video_title" class="col-sm-2 control-label"><?php echo $this->lang->line('create_post_label_video_title') ?></label>
              <div class="col-sm-4">
                <input type="text" class="form-control" ng-model="video_title" name="video_title" id="video_title" placeholder="<?php echo $this->lang->line('create_post_placeholder_video_title') ?>">
              </div>
            </div>
            <div class="form-group">
              <label for="video_description" class="col-sm-2 control-label"><?php echo $this->lang->line('create_post_label_video_description') ?></label>
              <div class="col-sm-8">
                <textarea name="video_description" ng-model="video_description" id="video_description" class="form-control" placeholder="<?php echo $this->lang->line('create_post_placeholder_video_description') ?>"></textarea>
              </div>
            </div>
            <div class="form-group">
              <label for="video_url" class="col-sm-2 control-label required"><?php echo $this->lang->line('create_post_label_video_url') ?></label>
              <div class="col-sm-4">
                <input type="text" ng-disabled="true" class="form-control" ng-model="video_url " name="video_url" id="video_url" placeholder="<?php echo $this->lang->line('create_post_placeholder_video_url') ?>">
              </div>
            </div>
          </div> <!-- end video Tab -->
          <hr>
        </div>
      </div>
    </div> <!-- end Step 1 -->

    <div class="row setup-content" id="step-2"  ng-cloak>
      <div class="form-group">
        <div class="col-sm-3">
          <div class="checkbox text-right">
            <label>
              <input type="checkbox" ng-change="validate_post()" ng-model="schedule_post" ng-true-value="1" ng-false-value="0"> <?php echo $this->lang->line('create_post_label_schedule_post') ?>
            </label>
          </div>
        </div>
        <label for="schedule_time" class="col-sm-3 control-label required"><?php echo $this->lang->line('create_post_label_schedule_time') ?></label>
        <div class="col-sm-4">
          <input type="text" class="form-control timepick" name="schedule_time" ng-model="schedule_time" id="schedule_time" placeholder="<?php echo $this->lang->line('create_post_placeholder_schedule') ?>">
        </div>
      </div>
      <hr>
    </div> <!-- end Step 2 -->

    <div class="row setup-content" id="step-3"  ng-cloak>
        <div class="col-sm-12 text-center" ng-show="!nodes_loaded">
          <i class="fa fa-circle-o-notch fa-spin"></i>
        </div>
        <div class="col-sm-12">
          <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12">
              <div ng-cloak class="row margin-btm-20 content">
                <div class="col-sm-12 col-xs-12 col-md-12 col-lg-12 margin-btm-10">
                  <div class="form-group">
                    <div class="col-sm-4" ng-show="nodes_list.length">
                      <div class="input-group">
                        <div class="input-group-addon"><i class="fa fa-search"></i></div>
                        <input type="text" class="form-control" placeholder="<?php echo $this->lang->line('table_search_placeholder') ?>" ng-model="search_nodes_list">
                      </div>      
                    </div>
                    <div class="col-sm-4 margin-btm-10" ng-show="nodes_list.length">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" ng-model="allSelected" ng-click="toggleAllSelected()"> <?php echo $this->lang->line('common_text_check_uncheck_all') ?>
                        </label>
                      </div>
                    </div>
                  </div> 
                </div>
                <div class="col-sm-12 nodes_list col-xs-12">
                  <ul class="list-group" >
                    <li class="list-group-item" ng-repeat="node in (filtered_nodes = (nodes_list | filter: search_nodes_list))">
                      <div class="checkbox node-check">
                        <label>
                          <input type="checkbox"  ng-model="node.selected" ng-disabled="node.disabled">
                          {{ node.page_name }}
                        </label>
                        <input type="text" class="pull-right timepick" ng-model="node.schedule_time" >
                        <label class="pull-right" >
                          <input type="checkbox" name="" ng-model="node.schedule" ng-true-value="1" ng-false-value="0"> <?php echo $this->lang->line('create_post_label_schedule') ?> &nbsp;&nbsp;&nbsp;&nbsp;
                        </label>
                        {{ $last ? set_picker() : '' }}
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row margin-btm-20" ng-show="no_page">
            <div class="col-sm-12" ng-bind-html="nodes_alert">
              <?php echo get_alert_html($this->lang->line('error_no_fb_account')) ?>
            </div>
          </div>

          <div class="row margin-btm-20" >
            <div class="col-sm-12 text-center">
              <button class="btn btn-success" ng-disabled="post_incomplete || campaign_preloader" ng-click="create_campaign(false)"><?php echo $this->lang->line('edit_post_btn_update_campaign') ?> <i class="fa fa-cog fa-spin" ng-show="campaign_preloader"></i></button>
            </div>
          </div>
        </div>
    </div> <!-- end Step 3 -->
    <div class="row margin-top-20">
      <div ng-bind-html="campaign_alert" class="col-sm-12 col-xs-12 col-md-12 col-lg-12 text-center">
      </div>
    </div>
  </form> <!-- end Form -->

</div>

<script type="text/javascript">
$(document).ready(function(){
    //show datetime picker on interval field
    
    //link/photo image options choose between three choices
    $('.link_options, .image_option').click(function(){
        var option = $(this).val();
        if(option == 'option1'){
            $(this).parents('.form-group').siblings('.img_url').show();
            $(this).parents('.form-group').siblings('.local_img').hide();
        }else if(option == 'option2'){
            $(this).parents('.form-group').siblings('.form-group.img_url').hide();
            $(this).parents('.form-group').siblings('.form-group.local_img').show();
        }
    });
    $(".nav-tabs a[data-toggle=tab]").on("click", function(e) {
      if ($(this).parent('li').hasClass("disabled")) {
        e.preventDefault();
        return false;
      }
    });
});    
</script>
