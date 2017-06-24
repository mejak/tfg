<script type="text/javascript" src="<?php echo base_url() ?>assets/js/ng-infinite-scroll.min.js"></script>
<script type="text/javascript">
  var url = '<?php echo base_url() ?>index.php/api/post/all/<?php echo $this->session->userdata('user_id') ?>';
  var fbcmpapp = angular.module('fbcmpapp', ['infinite-scroll']);
  fbcmpapp.controller('userController', function($scope, $http, $sce){
    $scope.showForm = false;
    $scope.searchTable = '';
    $scope.sortType  = '';
    $scope.sortReverse  = false;
    $scope.records = [];
    $scope.alert = '';
    $scope.loading_records = false;
    $scope.all_records_loaded = false;
    $scope.loadNextPage =  function(){
      if($scope.all_records_loaded)  return;
      $scope.loading_records = true;
      $http.get(url+'/'+$scope.records.length).success(function(data) {
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
          $scope.all_records_loaded = true;
       $scope.records = $scope.records.concat(data);
       $scope.loading_records = false;
      });
    };
    $scope.loadNextPage();
    $scope.changeStatus = function(record){
      record.changing_status= true;
      var  status_url = '<?php echo base_url() ?>index.php/post/'+ (record.status == '<?php echo CAMPAIGN_STATUS_INPROGRESS ?>' ? 'pause_campaign' : 'play_campaign');
      $http.get(status_url+'/'+record.post_id).success(function(data, status, headers){
        record.changing_status= false;
        var data_type = headers('Content-Type');
        if(data_type == 'application/json' && data.type == <?php echo AJAX_RESPONSE_TYPE_REDIRECT ?>){
          window.location.href = data.message;
          return true;
        }else if(data_type == 'application/json' && data.type == <?php echo AJAX_RESPONSE_TYPE_ERROR ?>){
          $scope.alert = $sce.trustAsHtml(data.message);
          return true;
        }
        record.status = record.status === '<?php echo CAMPAIGN_STATUS_INPROGRESS ?>' ? '<?php echo CAMPAIGN_STATUS_PAUSED ?>' : '<?php echo CAMPAIGN_STATUS_INPROGRESS ?>' ;
        switch(record.status){
          case '<?php echo CAMPAIGN_STATUS_INPROGRESS ?>' :
           record.campaign_status_label = 'primary';
           record.campaign_status_text = '<?php echo $this->lang->line('post_list_campaign_status_inprogress') ?>';
           break;
          case '<?php echo CAMPAIGN_STATUS_PAUSED ?>' :
           record.campaign_status_label = 'default';
           record.campaign_status_text = '<?php echo $this->lang->line('post_list_campaign_status_paused') ?>';
           break; 
        }
      });
    }

  });
</script>
<div class="row">
  <div class="col-sm-12">
    <?php echo $this->session->flashdata('alert') ?>
  </div>
</div>
<div class="row" ng-app="fbcmpapp" ng-controller="userController"> 
  <div class="col-sm-12" ng-bind-html="alert">
  </div>
  <div class="col-sm-12">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title"><?php echo $this->lang->line('post_list_heading') ?>
          <span class="badge" ng-cloak>{{ records.length }}</span>
        </h3>
      </div>
      <div class="panel-body">
        <div class="row margin-btm-5">
          <div class="col-sm-3">
            <form>
              <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon"><i class="fa fa-search"></i></div>
                  <input type="text" class="form-control" placeholder="<?php echo $this->lang->line('table_search_placeholder') ?>" ng-model="searchtable">
                </div>      
              </div>
            </form>
          </div>
          <div class="col-sm-2 col-sm-offset-7">
            <a href="<?php echo base_url() ?>index.php/post/create" class="btn btn-success btn-block ">
              <i class="fa fa-plus"></i>
              <?php echo $this->lang->line('post_list_btn_new_post') ?>
            </a>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-striped table-hover table-bordered text-center">
              <thead>
                <tr>
                  <th class="col-sm-1">
                    <a href="#" ng-click="sortType = post_id; sortReverse = !sortReverse">
                      <?php echo $this->lang->line('table_th_id') ?> 
                      <i ng-show="sortType == post_id && !sortReverse" class="fa fa-sort-asc"></i>
                      <i ng-show="sortType == post_id && sortReverse" class="fa fa-sort-desc"></i>
                    </a>
                  </th>
                  <th class="col-sm-2">
                    <a href="#" ng-click="sortType = 'post_name'; sortReverse = !sortReverse">
                       <?php echo $this->lang->line('table_th_name') ?> 
                      <i ng-show="sortType == 'post_name' && !sortReverse" class="fa fa-sort-asc"></i>
                      <i ng-show="sortType == 'post_name' && sortReverse" class="fa fa-sort-desc"></i>
                    </a>
                  </th>
                  
                  <th class="col-sm-1">
                    <a href="#" ng-click="sortType = 'post_type'; sortReverse = !sortReverse">
                       <?php echo $this->lang->line('table_th_type') ?> 
                      <i ng-show="sortType == 'post_type' && !sortReverse" class="fa fa-sort-asc"></i>
                      <i ng-show="sortType == 'post_type' && sortReverse" class="fa fa-sort-desc"></i>
                    </a>
                  </th>
                  <th class="col-sm-1">
                    <a href="#" ng-click="sortType = 'campaign_status_text'; sortReverse = !sortReverse">
                       <?php echo $this->lang->line('table_th_status') ?> 
                      <i ng-show="sortType == 'campaign_status_text' && !sortReverse" class="fa fa-sort-asc"></i>
                      <i ng-show="sortType == 'campaign_status_text' && sortReverse" class="fa fa-sort-desc"></i>
                    </a>
                  </th>
                  <th class="col-sm-2">
                    <a href="#" ng-click="sortType = 'post_likes'; sortReverse = !sortReverse">
                       <?php echo $this->lang->line('table_th_insights') ?> 
                      <i ng-show="sortType == 'post_likes' && !sortReverse" class="fa fa-sort-asc"></i>
                      <i ng-show="sortType == 'post_likes' && sortReverse" class="fa fa-sort-desc"></i>
                    </a>
                  </th>
                  <th class="col-sm-2">
                    <a href="#" ng-click="sortType = 'date_started'; sortReverse = !sortReverse">
                       <?php echo $this->lang->line('table_th_scheduled_date') ?> 
                      <i ng-show="sortType == 'date_started' && !sortReverse" class="fa fa-sort-asc"></i>
                      <i ng-show="sortType == 'date_started' && sortReverse" class="fa fa-sort-desc"></i>
                    </a>
                  </th>
                  <th class="col-sm-3" > <?php echo $this->lang->line('table_th_action') ?> </th>
                </tr>
              </thead>
              <tbody infinite-scroll="loadNextPage()" infinite-scroll-distance="1" infinite-scroll-disabled="loading_records">
                  <tr ng-cloak ng-repeat="record in records | orderBy:sortType:sortReverse | filter:searchtable">
                    <td>{{ record.post_id}}</td>
                    <td>{{ record.post_name}}</td>
                    <td><i class="fa {{ record.post_type_icon}}" title="{{ record.post_type_title }}"></i></td>
                    <td><label class="label label-{{ record.campaign_status_label}}" >{{ record.campaign_status_text }}</label></td>
                    <td><i class="fa fa-thumbs-o-up"></i> {{ record.post_likes }} <i class="fa fa-comment-o"></i> {{ record.post_comments }} <i class="fa fa-share"></i> {{ record.post_shares }}</td>
                    <td>{{ record.date_started}}</td>
                    <td class="text-left">
                    <a title="<?php echo $this->lang->line('table_action_view') ?>" class="btn btn-primary"   href="<?php echo base_url() ?>index.php/post/detail/{{ record.post_id }}"><i class="fa fa-folder-open"></i> </a>
                    <a title="<?php echo $this->lang->line('table_action_repost') ?>" class="btn btn-primary" href="<?php echo base_url() ?>index.php/post/repost_campaign/{{ record.post_id }}" ><i class="fa fa-retweet"></i></a>
                    <a title="<?php echo $this->lang->line('table_action_edit') ?>" class="btn btn-primary"   href="<?php echo base_url() ?>index.php/post/edit/{{ record.post_id }}"><i class="fa fa-edit"></i></a>
                    <a title="<?php echo $this->lang->line('table_action_delete') ?>" class="btn btn-danger delete_post" href="<?php echo base_url() ?>index.php/post/delete_campaign/{{ record.post_id }}" ><i class="fa fa-trash"></i></a>
                    </td>
                  </tr>
                  <tr ng-show="loading_records" ng-cloak>
                    <td colspan="7"> <center><i class="fa fa-spin fa-cog fa-2x"></i></center> </td>
                  </tr>
              </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="delete_post_model">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?php echo $this->lang->line('modal_delete_header') ?></h4>
      </div>
      <div class="modal-body">
        <p><?php echo $this->lang->line('modal_delete_campaign') ?></p>
        <div class="checkbox" id="del_fb_check">
          <label>
            <input type="checkbox" id="del_fb" value="1">
            <?php echo $this->lang->line('modal_delete_campaign_facebook') ?>
          </label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('modal_btn_close') ?></button>
        <a class="btn btn-danger" id="delete_post_btn"> <i class="fa fa-trash"></i> <?php echo $this->lang->line('modal_btn_delete') ?></a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript">
jQuery(document).ready(function($){
  $(document).on('click', '.delete_post', function(){
    $('#delete_post_btn').attr('href', $(this).attr('href'));
    $('#del_fb').prop('checked', false);
    $('#delete_post_model').modal('show');
    return false;
  });
  $(document).on('click', '#delete_post_btn', function(){
    if($('input[id="del_fb"]:checked').length > 0)
      window.location.href = $(this).attr('href') + "/true";
    else
      window.location.href = $(this).attr('href');
    return false; 
  });
});
</script>



