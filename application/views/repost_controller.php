<script type="text/javascript">
var url = '<?php echo base_url() ?>index.php/api/page/all/';
var user_id = '<?php echo $this->session->userdata('user_id') ?>';
var fbcmpapp = angular.module('fbcmpapp', []);
fbcmpapp.controller('postController', function($scope, $http, $sce){
  $scope.post_type = 'status';
  $scope.post_incomplete = true;
  $scope.link_options = 'option1';
  $scope.image_options = 'option1';
  $scope.schedule_time = "<?php date_default_timezone_set($this->session->userdata('time_zone')); echo date('Y-m-d h:i A'); ?>";
  $http.get('<?php echo base_url() ?>index.php/api/post/item/<?php echo $this->uri->segment(3) ?>').success(function(data){
    $scope.post_name = data.post_name;
    $scope.post_type_constant = data.post_type;
    switch($scope.post_type_constant){
      case '<?php echo POST_TYPE_STATUS ?>':
        $scope.post_type = 'status';
        $scope.fb_status_message = data.post_message;
        break;
      case '<?php echo POST_TYPE_LINK ?>':
        $scope.post_type = 'link';
        $scope.link_url = data.link_url;
        $scope.fb_link_message = data.post_message;
        $scope.link_title = data.link_title;
        $scope.link_description = data.link_description;
        $scope.link_caption = data.link_caption;
        $scope.link_image_url = data.image_url;
        break;
      case '<?php echo POST_TYPE_PHOTO ?>':
        $scope.post_type = 'photo';
        $scope.fb_photo_message = data.post_message;
        $scope.photo_image_url = data.image_url;
        break;
      case '<?php echo POST_TYPE_VIDEO ?>':
        $scope.post_type = 'video';
        $scope.video_title = data.link_title;
        $scope.video_description = data.link_description;
        $scope.video_url = data.link_url;
        break;
    }
  });
  //validate post
  $scope.validate_post = function(){
    if(typeof $scope.post_name === 'undefined' || $scope.post_name == ''){
      $scope.post_incomplete = true;
      return;
    }
    if($scope.post_type == 'status'){
      $scope.post_incomplete =  typeof $scope.fb_status_message !== 'undefined' && $scope.fb_status_message !='' ? false : true;
    }else if($scope.post_type == 'link'){
      $scope.post_incomplete = typeof $scope.link_url !== 'undefined' && $scope.link_url !='' ? false : true;
    }else if($scope.post_type == 'photo'){
      $scope.post_incomplete = ($scope.image_options == 'option1' && typeof $scope.photo_image_url !== 'undefined' && $scope.photo_image_url !='') ||
                               ($scope.image_options == 'option2' && jQuery('#photo_local_image').val() != '') ||
                               ($scope.image_options == 'option3') ? false : true;
      if ($scope.$root.$$phase != '$apply' && $scope.$root.$$phase != '$digest') {
          $scope.$apply();
      }
    }else if($scope.post_type == 'video'){
      $scope.post_incomplete = typeof $scope.video_url !== 'undefined' && $scope.video_url !='' ? false : true;
    }
    if($scope.schedule_post == '1' && $scope.schedule_time == '')
      $scope.post_incomplete = true;
    var selected = 0;
    angular.forEach($scope.nodes_list, function(record){
      if(record.selected)
        selected++;
    });
    if(selected == 0)
      $scope.post_incomplete = true;
  };
  //validate schedule
  $scope.set_records_schedule = function(){
    angular.forEach($scope.nodes_list, function(record){
      record.schedule_time = $scope.schedule_time;
    });
  }
  //watch post inputs
  $scope.$watch('[post_name, post_type, fb_status_message, link_url, link_options, link_local_image, video_url, image_options, photo_image_url, photo_local_image, nodes_list]', function(){
    $scope.validate_post();
  }, true);
  //watch schedule inputs
  $scope.$watch('[schedule_time]', function(){
    $scope.set_records_schedule();
    $scope.validate_post();
  });
  //watch schedule inputs
  $scope.$watch('[schedule_post]', function(){
    angular.forEach($scope.nodes_list, function(record){
      record.schedule = $scope.schedule_post;
    });
    $scope.validate_post();
  });

    $scope.nodes_list = [];
    $scope.selected_nodes = [];
    $scope.allSelected = false;
    $scope.get_nodes = function(){
      $scope.nodes_loaded = false;
      $scope.allSelected = false;
      $http.get(url + user_id +'/true').success(function(data, status, headers) {
        $scope.nodes_loaded = true;
        var data_type = headers('Content-Type');
        if(data_type == 'application/json' && data.type == <?php echo AJAX_RESPONSE_TYPE_REDIRECT ?>){
          window.location.href = data.message;
          return true;
        }else if(data.type == <?php echo AJAX_RESPONSE_TYPE_SUCCESS ?> || data.type == <?php echo AJAX_RESPONSE_TYPE_ERROR ?>){
          $scope.nodes_alert =  $sce.trustAsHtml(data.message);
          return true;
        }
        angular.forEach(data, function(record){
          record.selected = false;
          record.schedule_time = $scope.schedule_time;
          record.schedule = false;
        });
        $scope.nodes_list = data;
        
      });
    };
    $scope.get_nodes();
    
    $scope.toggleAllSelected = function(){
      if($scope.allSelected)
        $scope.allSelected = true;
      else
        $scope.allSelected = false;
      for(var i=0; i<$scope.filtered_nodes.length; i++)
        $scope.filtered_nodes[i].selected = $scope.allSelected;
    };
    $scope.campaign_preloader = false;
    $scope.campaign_alert = '';

    $scope.create_campaign = function(){
      $scope.campaign_preloader = true;
      var formdata = new FormData();
      var selected_nodes = []
      for(var i=0; i<$scope.nodes_list.length; i++){
        if($scope.nodes_list[i].selected)
          selected_nodes.push({
            "page_id" : $scope.nodes_list[i].page_id,
            "time" : $scope.nodes_list[i].schedule_time,
            "schedule" : $scope.nodes_list[i].schedule
          });
      }
      formdata.append('nodes', JSON.stringify(selected_nodes));
      formdata.append('post_name', $scope.post_name);
      formdata.append('schedule_time', $scope.schedule_time);
      formdata.append('schedule_post', $scope.schedule_post);
      formdata.append('<?php echo $this->security->get_csrf_token_name(); ?>', '<?php echo $this->security->get_csrf_hash() ?>');
      if($scope.post_type == 'status'){
          formdata.append('type', <?php echo POST_TYPE_STATUS ?>);
          formdata.append('fb_message', typeof $scope.fb_status_message === 'undefined' ? '' : $scope.fb_status_message);
      }else if($scope.post_type == 'link'){
          if($scope.link_options == 'option2' && jQuery('#link_local_image').val() != '')
              formdata.append('image', jQuery('#link_local_image')[0].files[0]);
          else if($scope.link_options == 'option3'){
              canvas.deactivateAll().renderAll();
              formdata.append('canvas_data', canvas.toDataURL())
          }
          else if($scope.link_options == 'option1')
              formdata.append('image_url', typeof $scope.link_image_url === 'undefined' ? '' : $scope.link_image_url);
          formdata.append('type', <?php echo POST_TYPE_LINK ?>);
          formdata.append('link_url', typeof $scope.link_url === 'undefined' ? '' : $scope.link_url);
          formdata.append('fb_message', typeof $scope.fb_link_message === 'undefined' ? '' : $scope.fb_link_message);
          formdata.append('link_title', typeof $scope.link_title === 'undefined' ? '' : $scope.link_title);
          formdata.append('link_description', typeof $scope.link_description === 'undefined' ? '' : $scope.link_description);
          formdata.append('link_caption', typeof $scope.link_caption === 'undefined' ? '' : $scope.link_caption);
      }else if($scope.post_type == 'photo'){
          if($scope.image_options == 'option2' && jQuery('#photo_local_image').val() != '')
              formdata.append('image', jQuery('#photo_local_image')[0].files[0]);
          else if($scope.image_options == 'option3'){
            canvas.deactivateAll().renderAll();
            formdata.append('canvas_data', canvas.toDataURL())
          }
          else if($scope.image_options == 'option1')
              formdata.append('image_url', typeof $scope.photo_image_url === 'undefined' ? '' : $scope.photo_image_url);
          formdata.append('type', <?php echo POST_TYPE_PHOTO ?>);
          formdata.append('fb_message', typeof $scope.fb_photo_message === 'undefined' ? '' : $scope.fb_photo_message);
      }else if($scope.post_type == 'video'){
          formdata.append('type', <?php echo POST_TYPE_VIDEO ?>);
          formdata.append('link_title', typeof $scope.video_title === 'undefined' ? '' : $scope.video_title);
          formdata.append('link_description', typeof $scope.video_description === 'undefined' ? '' : $scope.video_description);
          formdata.append('link_url', typeof $scope.video_url === 'undefined' ? '' : $scope.video_url);
      }
      var campaign_url = '<?php echo base_url() ?>index.php/post/create';
      $http.post(campaign_url, formdata, {
            withCredentials: true,
            headers: {'Content-Type': undefined },
            transformRequest: angular.identity
        }).success(function(data, status, headers){
          $scope.campaign_preloader = false;
          var data_type = headers('Content-Type');
          if(data_type.indexOf('application/json') > -1 && data.type == <?php echo AJAX_RESPONSE_TYPE_REDIRECT ?>){
            window.location.href = data.message;
            return true;
          }else if(data.type == <?php echo AJAX_RESPONSE_TYPE_SUCCESS ?> || data.type == <?php echo AJAX_RESPONSE_TYPE_ERROR ?>){
            $scope.campaign_alert =  $sce.trustAsHtml(data.message);
            return true;
          }
      });
    };
    $scope.set_picker = function()
    {
      jQuery('.timepick').datetimepicker({
            pick12HourFormat: true,
            dateFormat : 'yy-mm-dd',
            timeFormat : 'hh:mm TT'
        });
    }
});

 

</script>