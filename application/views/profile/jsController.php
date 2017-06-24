<script type="text/javascript" src="<?php echo base_url() ?>assets/js/ng-infinite-scroll.min.js"></script>
<script type="text/javascript">
  var base_url = '<?php echo base_url() ?>';
  var user_id = '<?php echo $user->user_id ?>';
  var fbcmpapp = angular.module('fbcmpapp', ['infinite-scroll']);
  fbcmpapp.controller('profileController', function($scope, $http, $sce){
    //accounts tab
    $scope.accounts = {};
    $scope.accounts.searchTable = '';
    $scope.accounts.sortType  = '';
    $scope.accounts.sortReverse  = false;
    $scope.accounts.records = [];
    $scope.accounts.preloader = true;
    //nodes tab
    $scope.nodes = {};
    $scope.nodes.searchTable = '';
    $scope.nodes.sortType  = '';
    $scope.nodes.sortReverse  = false;
    $scope.nodes.records = [];
    $scope.nodes.preloader = true;
    $scope.nodes.profile_id = '<?php echo isset($profiles[0]) ? $profiles[0]->profile_id : 0   ?>';
    $scope.nodes.node_type = '<?php echo FB_NODE_TYPE_MANAGE_PAGE ?>';
    
    //posts tab
    $scope.posts = {};
    $scope.posts.searchTable = '';
    $scope.posts.sortType  = '';
    $scope.posts.sortReverse  = false;
    $scope.posts.records = [];
    $scope.posts.preloader = true;
    $scope.posts.all_records_loaded = false;
    $scope.posts.loading_records = true;
    $scope.loadPostsNextPage =  function(){
      if($scope.posts.all_records_loaded)  return;
      $scope.posts.loading_records = true;
      $http.get(base_url+'index.php/api/post/all/'+user_id+'/'+$scope.posts.records.length).success(function(data) {
        angular.forEach(data, function(record){
          switch(record.post_type){
            case '<?php echo POST_TYPE_STATUS ?>' :
             record.post_type_icon = 'fa-pencil-square-o';
             record.post_type_title = '<?php echo $this->lang->line('create_post_type_status') ?>';
             break;
            case '<?php echo POST_TYPE_LINK ?>' :
             record.post_type_icon = 'fa-link';
             record.post_type_title = '<?php echo $this->lang->line('create_post_type_link') ?>';
             break;
            case '<?php echo POST_TYPE_PHOTO ?>' :
             record.post_type_icon = 'fa-picture-o';
             record.post_type_title = '<?php echo $this->lang->line('create_post_type_photo') ?>';
             break;
            case '<?php echo POST_TYPE_VIDEO ?>' :
             record.post_type_icon = 'fa-video-camera';
             record.post_type_title = '<?php echo $this->lang->line('create_post_type_video') ?>';
             break; 
          }
          switch(record.status){
            case '<?php echo CAMPAIGN_STATUS_INPROGRESS ?>' :
             record.campaign_status_label = 'primary';
             record.campaign_status_text = '<?php echo $this->lang->line('post_list_campaign_status_inprogress') ?>';
             break;
            case '<?php echo CAMPAIGN_STATUS_COMPLETED ?>' :
             record.campaign_status_label = 'success';
             record.campaign_status_text = '<?php echo $this->lang->line('post_list_campaign_status_completed') ?>';
             break;
            case '<?php echo CAMPAIGN_STATUS_PAUSED ?>' :
             record.campaign_status_label = 'default';
             record.campaign_status_text = '<?php echo $this->lang->line('post_list_campaign_status_paused') ?>';
             break;
            case '<?php echo CAMPAIGN_STATUS_PENDING ?>' :
             record.campaign_status_label = 'warning';
             record.campaign_status_text = '<?php echo $this->lang->line('post_list_campaign_status_pending') ?>';
             break; 
          }
          record.changing_status = false;
        });
       if(data.length == 0)
          $scope.posts.all_records_loaded = true;
       $scope.posts.records = $scope.posts.records.concat(data);
       $scope.posts.loading_records = false;
      });
    };
    // load all tabs
    $http.get(base_url+'index.php/api/profiles/all/'+user_id)
      .then(function(result){
      $scope.accounts.preloader = false;
      $scope.accounts.records = result.data;
      return $http.get(base_url+'index.php/api/page/all/'+user_id);
    }).then(function(result){
      angular.forEach(result.data, function(record){
        record.page_likes = parseInt(record.page_likes);
      });
      $scope.nodes.preloader = false;
      $scope.nodes.records = result.data;
      return $http.get(base_url+'index.php/api/post/all/'+user_id+'/'+$scope.posts.records.length);
    }).then(function(result){
        angular.forEach(result.data, function(record){
          switch(record.post_type){
            case '<?php echo POST_TYPE_STATUS ?>' :
             record.post_type_icon = 'fa-pencil-square-o';
             record.post_type_title = '<?php echo $this->lang->line('create_post_type_status') ?>';
             break;
            case '<?php echo POST_TYPE_LINK ?>' :
             record.post_type_icon = 'fa-link';
             record.post_type_title = '<?php echo $this->lang->line('create_post_type_link') ?>';
             break;
            case '<?php echo POST_TYPE_PHOTO ?>' :
             record.post_type_icon = 'fa-picture-o';
             record.post_type_title = '<?php echo $this->lang->line('create_post_type_photo') ?>';
             break;
            case '<?php echo POST_TYPE_VIDEO ?>' :
             record.post_type_icon = 'fa-video-camera';
             record.post_type_title = '<?php echo $this->lang->line('create_post_type_video') ?>';
             break; 
          }
          switch(record.status){
            case '<?php echo CAMPAIGN_STATUS_INPROGRESS ?>' :
             record.campaign_status_label = 'primary';
             record.campaign_status_text = '<?php echo $this->lang->line('post_list_campaign_status_inprogress') ?>';
             break;
            case '<?php echo CAMPAIGN_STATUS_COMPLETED ?>' :
             record.campaign_status_label = 'success';
             record.campaign_status_text = '<?php echo $this->lang->line('post_list_campaign_status_completed') ?>';
             break;
            case '<?php echo CAMPAIGN_STATUS_PAUSED ?>' :
             record.campaign_status_label = 'default';
             record.campaign_status_text = '<?php echo $this->lang->line('post_list_campaign_status_paused') ?>';
             break;
            case '<?php echo CAMPAIGN_STATUS_PENDING ?>' :
             record.campaign_status_label = 'warning';
             record.campaign_status_text = '<?php echo $this->lang->line('post_list_campaign_status_pending') ?>';
             break; 
          }
          record.changing_status = false;
        });
       if(result.data.length == 0)
          $scope.posts.all_records_loaded = true;
       $scope.posts.records = $scope.posts.records.concat(result.data);
       $scope.posts.preloader = false;
       $scope.posts.loading_records = false;
    });
  });
</script>