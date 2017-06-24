<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/colorpicker.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/jquery-ui-1.8.21.custom.css">
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui-1.10.0.custom.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/fabric.min.js"  > </script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/colorpicker.js"  > </script>

<?php $this->load->view('repost_controller'); ?>
<div class="row well" ng-app="fbcmpapp" ng-controller="postController">
  <h4><?php echo $this->lang->line('repost_heading') ?></h4>
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
          <li role="presentation" class="{{ post_type_constant == '<?php echo POST_TYPE_STATUS ?>' ? 'active' : '' }}"><a ng-click="post_type='status'" href="#status" aria-controls="status" role="tab" data-toggle="tab"><?php echo $this->lang->line('create_post_type_status') ?></a></li>
          <li role="presentation" class="{{ post_type_constant == '<?php echo POST_TYPE_LINK ?>' ? 'active' : '' }}"><a ng-click="post_type='link'" href="#link" aria-controls="link" role="tab" data-toggle="tab"><?php echo $this->lang->line('create_post_type_link') ?></a></li>
          <li role="presentation" class="{{ post_type_constant == '<?php echo POST_TYPE_PHOTO ?>' ? 'active' : '' }}"><a ng-click="post_type='photo'" href="#photo" aria-controls="photo" role="tab" data-toggle="tab"><?php echo $this->lang->line('create_post_type_photo') ?></a></li>
          <li role="presentation" class="{{ post_type_constant == '<?php echo POST_TYPE_VIDEO ?>' ? 'active' : '' }}"><a ng-click="post_type='video'" href="#video" aria-controls="video" role="tab" data-toggle="tab"><?php echo $this->lang->line('create_post_type_video') ?></a></li>
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
                <input type="text" class="form-control" ng-model="link_url" name="link_url" id="link_url" placeholder="<?php echo $this->lang->line('create_post_placeholder_link_url') ?>">
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
                <input ng-model="link_title" type="text" class="form-control" name="link_title" id="link_title" placeholder="<?php echo $this->lang->line('create_post_placeholder_link_title') ?>" maxlength="255">
              </div>
            </div>
            <div class="form-group">
              <label for="link_description" class="col-sm-2 control-label"><?php echo $this->lang->line('create_post_label_link_description') ?></label>
              <div class="col-sm-8">
                <textarea ng-model="link_description" name="link_description" id="link_description" class="form-control" placeholder="<?php echo $this->lang->line('create_post_placeholder_link_description') ?>"></textarea>
              </div>
            </div>
            <div class="form-group">
              <label for="link_caption" class="col-sm-2 control-label"><?php echo $this->lang->line('create_post_label_link_caption') ?></label>
              <div class="col-sm-4">
                <input type="text" class="form-control" ng-model="link_caption" name="link_caption" id="link_caption" placeholder="<?php echo $this->lang->line('create_post_placeholder_link_caption') ?>">
              </div>
            </div>
            <div class="form-group">
              <label for="linkOptions" class="col-sm-2 control-label"><?php echo $this->lang->line('create_post_label_link_image') ?></label>
              <div class="col-sm-10">
                <div class="radio">
                  <label>
                    <input type="radio" ng-model="link_options" class="link_options" name="linkOptions" id="optionsRadios1" value="option1" checked>
                    <?php echo $this->lang->line('create_post_image_option_url') ?>
                  </label>
                </div>
                <div class="radio">
                  <label>
                    <input type="radio" ng-model="link_options" class="link_options" name="linkOptions" id="optionsRadios2" value="option2">
                    <?php echo $this->lang->line('create_post_image_option_upload') ?>
                  </label>
                </div>
                <div class="radio">
                  <label>
                    <input type="radio" ng-model="link_options" class="link_options" name="linkOptions" id="optionsRadios3" value="option3">
                    <?php echo $this->lang->line('create_post_image_option_canvas') ?>
                  </label>
                </div>
              </div>
            </div> 
            <div class="form-group img_url">
              <div class="col-sm-offset-2 col-sm-4">
                <input type="text" class="form-control" ng-model="link_image_url" name="link_image_url" id="link_image_url" placeholder="<?php echo $this->lang->line('create_post_placeholder_link_image_url') ?>">
              </div>
              <div class="col-sm-2 control-label text-left" ng-show="link_image_url !== undefined && link_image_url != ''">
                <a href="{{ link_image_url }}"  target="_blank"><?php echo $this->lang->line('repost_link_view_image') ?></a>
              </div>
            </div>
            <div class="form-group local_img" style="display:none">
              <div class="col-sm-offset-2 col-sm-4">
                <input type="file" class="form-control" ng-model="link_local_image" name="link_local_image" id="link_local_image" accept="image/*">
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
                    <input type="radio" class="image_option" ng-model="image_options" name="photoOptions" id="optionsRadios1" value="option1" checked>
                    <?php echo $this->lang->line('create_post_image_option_url') ?>
                  </label>
                </div>
                <div class="radio">
                  <label>
                    <input type="radio" class="image_option" ng-model="image_options" name="photoOptions" id="optionsRadios2" value="option2">
                    <?php echo $this->lang->line('create_post_image_option_upload') ?>
                  </label>
                </div>
                <div class="radio">
                  <label>
                    <input type="radio" class="image_option" ng-model="image_options" name="photoOptions" id="optionsRadios3" value="option3">
                    <?php echo $this->lang->line('create_post_image_option_canvas') ?>
                  </label>
                </div>
              </div>
            </div> 
            <div class="form-group img_url">
              <div class="col-sm-offset-2 col-sm-4">
                <input type="text" class="form-control" ng-model="photo_image_url" name="photo_image_url" id="photo_image_url" placeholder="<?php echo $this->lang->line('create_post_placeholder_link_image_url') ?>">
              </div>
              <div class="col-sm-2 control-label text-left" ng-show="photo_image_url !== undefined && photo_image_url != ''">
                <a href="{{ photo_image_url }}"  target="_blank"><?php echo $this->lang->line('repost_link_view_image') ?></a>
              </div>
            </div>
            <div class="form-group local_img" style="display:none">
              <div class="col-sm-offset-2 col-sm-4">
                <input type="file" class="form-control" name="photo_local_image" id="photo_local_image" onchange="angular.element(this).scope().validate_post()" >
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
                <input type="text" class="form-control" ng-model="video_url " name="video_url" id="video_url" placeholder="<?php echo $this->lang->line('create_post_placeholder_video_url') ?>">
              </div>
            </div>
          </div> <!-- end video Tab -->

          <div class="row canvas_box" style="display:none">
            <div class="col-sm-3 well">
                <button id="add_rect" class="btn btn-primary btn-block"><i class="fa fa-square" ></i> <?php echo $this->lang->line('canvas_btn_rectangle') ?></button>
                <button id="add_circle" class="btn btn-primary btn-block"><i class="fa fa-circle" ></i> <?php echo $this->lang->line('canvas_btn_circle') ?></button>
                <button id="add_triangle" class="btn btn-primary btn-block"><i class="fa fa-exclamation-triangle" ></i> <?php echo $this->lang->line('canvas_btn_triangle') ?></button>
                <button id="add_line" class="btn btn-primary btn-block"><i class="fa fa-minus" ></i> <?php echo $this->lang->line('canvas_btn_line') ?></button> 
                <hr>
                <textarea id="c_text" class="form-control"></textarea>
                <div class="margin-btm-5"></div>
                <select class="form-control" id="font_family" >
                  <option value=""><?php echo $this->lang->line('canvas_select_option_text') ?></option>
                  <option value="Arial Black">Arial Black</option>
                  <option value="Arial">Arial</option>
                  <option value="Book Antiqua">Book Antiqua</option>
                  <option value="Charcoal">Charcoal</option>
                  <option value="Comic Sans MS">Comic Sans MS</option>
                  <option value="Courier New">Courier New</option>
                  <option value="Courier">Courier</option>
                  <option value="cursive">cursive</option>
                  <option value="Gadget">Gadget</option>
                  <option value="Geneva">Geneva</option>
                  <option value="Georgia">Georgia</option>
                  <option value="Helvetica">Helvetica</option>
                  <option value="Impact">Impact</option>
                  <option value="Lucida Console">Lucida Console</option>
                  <option value="Lucida Grande">Lucida Grande</option>
                  <option value="Lucida Sans Unicode">Lucida Sans Unicode</option>
                  <option value="Monaco">Monaco</option>
                  <option value="monospace">monospace</option>
                  <option value="Palatino Linotype">Palatino Linotype</option>
                  <option value="Palatino">Palatino</option>
                  <option value="sans-serif">sans-serif</option>
                  <option value="serif">serif</option>
                  <option value="Tahoma">Tahoma</option>
                  <option value="Times New Roman">Times New Roman</option>
                  <option value="Times">Times</option>
                  <option value="Trebuchet MS">Trebuchet MS</option>
                  <option value="Verdana">Verdana</option>
                </select>
                <div class="margin-btm-5"></div>
                <button id="add_text" class="btn btn-primary btn-block"><i class="fa fa-font" ></i> <?php echo $this->lang->line('canvas_btn_add_text') ?></button> 
                <hr>
                <input type="text" class="form-control" placeholder="<?php echo $this->lang->line('canvas_placeholder_img_url') ?>" id="img_url">
                <div class="margin-btm-5"></div>
                <input type="file" class="form-control" id="canvas_img_file">
                <label class="error"></label>
                <button id="add_img" class="btn btn-primary btn-block"><i class="fa fa-picture-o" ></i> <?php echo $this->lang->line('canvas_btn_add_image') ?> <i id="canvas_img_preloader" class="fa fa-cog hide fa-spin" ></i></button> 
                <hr>
                <input type="text" class="form-control" placeholder="<?php echo $this->lang->line('canvas_placeholder_canvas_width') ?>" id="canvas_width" value="640">
                <div class="margin-btm-5"></div>
                <input type="text" class="form-control" placeholder="<?php echo $this->lang->line('canvas_placeholder_canvas_height') ?>" id="canvas_height" value="480">
                <div class="margin-btm-5"></div>
                <button id="resize_canvas" class="btn btn-primary btn-block"><i class="fa fa-arrows" ></i> <?php echo $this->lang->line('canvas_btn_resize') ?> </button> 
            </div>
            <div class="col-sm-9 well">
              <div class="row canvas-icons margin-btm-20">
                <div class="col-sm-12 text-center">
                  <button id="fill_color" class="btn btn-success color_pick" title="<?php echo $this->lang->line('canvas_title_fill_color') ?>"><i class="fa fa-circle fa-2x" ></i></button>
                  <button id="stroke_color" class="btn btn-success color_pick" title="<?php echo $this->lang->line('canvas_title_border_color') ?>"><i class="fa fa-circle-thin fa-2x" ></i></button>
                  <button id="remove_obj" class="btn btn-danger" title="<?php echo $this->lang->line('canvas_title_remove_object') ?>"><i class="fa fa-times fa-2x" ></i></button>
                  <button id="send_backward" class="btn btn-primary" title="<?php echo $this->lang->line('canvas_title_send_backward') ?>"><?php echo $this->lang->line('canvas_btn_send_backward') ?></button>
                  <button id="send_back" class="btn btn-primary" title="<?php echo $this->lang->line('canvas_title_send_back') ?>"><?php echo $this->lang->line('canvas_btn_send_back') ?></button>
                  <button id="bring_forward" class="btn btn-primary" title="<?php echo $this->lang->line('canvas_title_bring_forward') ?>"><?php echo $this->lang->line('canvas_btn_bring_forward') ?></button>
                  <button id="bring_front" class="btn btn-primary" title="<?php echo $this->lang->line('canvas_title_bring_front') ?>"><?php echo $this->lang->line('canvas_btn_bring_front') ?></button>
                </div>
              </div>
              <hr>
              <div class="row margin-btm-20">
                <div class="col-sm-12">
                  <center>
                    <canvas id="canvas" width="640" height="480"></canvas>
                  </center>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12">
                  <center>
                    <button class="btn btn-primary" id="preview_canvas"><?php echo $this->lang->line('canvas_btn_preview') ?></button>
                  </center>
                </div>
              </div>
            </div>
          </div> <!-- end canvas Box -->
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
                          <input type="checkbox"  ng-model="node.selected">
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
              <button class="btn btn-success" ng-disabled="post_incomplete || campaign_preloader" ng-click="create_campaign(false)"><?php echo $this->lang->line('create_post_btn_create_campaign') ?> <i class="fa fa-cog fa-spin" ng-show="campaign_preloader"></i></button>
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
<!-- preview Modal -->
<div id="preview_modal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header text-center">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?php echo $this->lang->line('canvas_modal_preview_header') ?></h4>
      </div>
      <div class="modal-body text-center">
        <img src="">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('canvas_btn_close') ?></button>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('canvas_js'); ?>
<script type="text/javascript">
$(document).ready(function(){
    //link/photo image options choose between three choices
    $('.link_options, .image_option').click(function(){
        var option = $(this).val();
        if(option == 'option1'){
            $(this).parents('.form-group').siblings('.img_url').show();
            $(this).parents('.form-group').siblings('.local_img').hide();
            $('.canvas_box').hide();
        }else if(option == 'option2'){
            $(this).parents('.form-group').siblings('.form-group.img_url').hide();
            $(this).parents('.form-group').siblings('.form-group.local_img').show();
            $('.canvas_box').hide();
        }else if(option == 'option3'){
            $(this).parents('.form-group').siblings('.form-group.img_url').hide();
            $(this).parents('.form-group').siblings('.form-group.local_img').hide();
            $('.canvas_box').show();
        }
    });
    //show hide canvas box on switch between post type status, link, photo
    $('.post_tabs a').click(function(){
        var href = $(this).attr('href');
        $('.canvas_box').hide();
        if(href == '#status' || href == '#video'){
            $('.canvas_box').hide();
        }else if(href == '#link'){
            if($('#link input[value="option3"]:checked').length)
                $('.canvas_box').show();
        }else if(href == '#photo'){
            if($('#photo input[value="option3"]:checked').length)
                $('.canvas_box').show();
        }
    });
});    
</script>
