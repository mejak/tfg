<script type="text/javascript" src="<?php echo base_url() ?>assets/js/ng-infinite-scroll.min.js"></script>
<script type="text/javascript">
  angular.module('Repeat_Custom', []).directive('repeatDone', function() {
    return function(scope, element, attrs) {
      if (scope.$last) { // all are rendered
        scope.$eval(attrs.repeatDone);
      }
    }
  });
  var url = '<?php echo base_url() ?>index.php/api/post/post_list/<?php echo $page->page_id ?>';
  var fbcmpapp = angular.module('fbcmpapp', ['infinite-scroll', 'Repeat_Custom']);
  fbcmpapp.controller('userController', function($scope, $http, $sce, $timeout){
    $scope.showForm = false;
    $scope.searchTable = '';
    $scope.sortType  = 'ID';
    $scope.sortReverse  = false;
    $scope.records = [];
    $scope.alert = '';
    $scope.loading_records = false;
    $scope.all_records_loaded = false;
    $scope.insight_index = 0;
    $scope.insight_url = '<?php echo base_url() ?>index.php/post/stats/';
    $scope.loadNextPage =  function(){
      if($scope.all_records_loaded)  return;
      $scope.loading_records = true;
      $http.get(url+'/'+$scope.records.length).success(function(data){
        angular.forEach(data, function(record){
          switch(record.post_status){
            case '<?php echo POST_STATUS_PENDING ?>' :
             record.post_status_label = 'warning';
             record.post_status_text = '<?php echo $this->lang->line('campaign_post_status_pending') ?>';
             record.post_status_title = '<?php echo $this->lang->line('campaign_post_status_pending_title') ?>';
             break;
            case '<?php echo POST_STATUS_POSTED ?>' :
             record.post_status_label = 'success';
             record.post_status_text = '<?php echo $this->lang->line('campaign_post_status_posted') ?>';
             record.post_status_title = '<?php echo $this->lang->line('campaign_post_status_posted_title') ?>';
             break;
            case '<?php echo POST_STATUS_ERROR ?>' :
             record.post_status_label = 'danger';
             record.post_status_text = '<?php echo $this->lang->line('campaign_post_status_error') ?>';
             record.post_status_title = record.post_error;
             break;
          }
          record.loading_insights = false;
        });
        if(data.length == 0)
          $scope.all_records_loaded = true;
       $scope.records = $scope.records.concat(data);
       $scope.loading_records = false;
       <?php if($this->config->item('auto_insights')){ ?>
       $scope.loadInsights($scope.insight_index);
       <?php } ?>
      });
    }

    $scope.loadNextPage();
    $scope.manualInsights = function(record){
      if(record.post_status != '<?php echo POST_STATUS_POSTED ?>') return;
      record.loading_insights= true;
      var  stats_url = '<?php echo base_url() ?>index.php/post/stats';
      $http.get(stats_url+'/'+record.post_to_nodes_id).success(function(data, status, headers){
        record.loading_insights= false;
        var data_type = headers('Content-Type');
        if(data_type == 'application/json' && data.type == <?php echo AJAX_RESPONSE_TYPE_REDIRECT ?>){
          window.location.href = data.message;
          return true;
        }else if(data_type == 'application/json' && data.type == <?php echo AJAX_RESPONSE_TYPE_ERROR ?>){
          $scope.alert = $sce.trustAsHtml(data.message);
          return true;
        }
        record.post_likes = data.likes;
        record.post_comments = data.comments;
        record.post_shares = data.shares;        
      });
    }
    $scope.loadInsights = function (index){
      if($scope.insight_index >= $scope.records.length) return;
      if($scope.records[$scope.insight_index].post_status != '<?php echo POST_STATUS_POSTED ?>'  ) {
        $scope.insight_index = $scope.insight_index + 1;
        $scope.loadInsights($scope.insight_index);
        return;
      }
      $scope.records[$scope.insight_index].loading_insights = true;
      return $http({url:$scope.insight_url+$scope.records[$scope.insight_index].post_to_nodes_id,method:"GET"}).then(function(result) {
          if(result.data.message){
            result.data.likes = 0;
            result.data.comments = 0;
            result.data.shares = 0;
          }
          $scope.records[index].post_likes = result.data.likes;
          $scope.records[index].post_comments = result.data.comments;
          $scope.records[index].post_shares = result.data.shares;
          $scope.records[index].loading_insights = false;
          $scope.insight_index = $scope.insight_index + 1;
          return $scope.loadInsights($scope.insight_index);
      });
    }
    $scope.layoutDone = function() {
      //$('a[data-toggle="tooltip"]').tooltip(); // NOT CORRECT!
      $timeout(function() { jQuery('[data-toggle="tooltip"]').tooltip({'container':'body'}); }, 0); // wait...
    };
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
        <h3 class="panel-title"><?php echo $page->page_name ?>
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
                    <a href="#" ng-click="sortType = post_to_nodes_id; sortReverse = !sortReverse">
                      <?php echo $this->lang->line('table_th_id') ?> 
                      <i ng-show="sortType == post_to_nodes_id && !sortReverse" class="fa fa-sort-asc"></i>
                      <i ng-show="sortType == post_to_nodes_id && sortReverse" class="fa fa-sort-desc"></i>
                    </a>
                  </th>
                  <th class="col-sm-4">
                    <a href="#" ng-click="sortType = 'post_name'; sortReverse = !sortReverse">
                       <?php echo $this->lang->line('table_th_name') ?> 
                      <i ng-show="sortType == 'post_name' && !sortReverse" class="fa fa-sort-asc"></i>
                      <i ng-show="sortType == 'post_name' && sortReverse" class="fa fa-sort-desc"></i>
                    </a>
                  </th>
                  <th class="col-sm-1">
                    <a href="#" ng-click="sortType = 'post_status_text'; sortReverse = !sortReverse">
                       <?php echo $this->lang->line('table_th_status') ?> 
                      <i ng-show="sortType == 'post_status_text' && !sortReverse" class="fa fa-sort-asc"></i>
                      <i ng-show="sortType == 'post_status_text' && sortReverse" class="fa fa-sort-desc"></i>
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
                    <a href="#" ng-click="sortType = 'post_datetime'; sortReverse = !sortReverse">
                       <?php echo $this->lang->line('table_th_scheduled_date') ?> 
                      <i ng-show="sortType == 'post_datetime' && !sortReverse" class="fa fa-sort-asc"></i>
                      <i ng-show="sortType == 'post_datetime' && sortReverse" class="fa fa-sort-desc"></i>
                    </a>
                  </th>
                  <th class="col-sm-2" > <?php echo $this->lang->line('table_th_action') ?> </th>
                </tr>
              </thead>
              <tbody infinite-scroll="loadNextPage()" infinite-scroll-distance="1" infinite-scroll-disabled="loading_records">
                  <tr ng-cloak ng-repeat="record in records | orderBy:sortType:sortReverse | filter:searchtable" repeat-done="layoutDone()">
                    <td>{{ record.post_to_nodes_id}}</td>
                    <td>{{ record.post_name}}</td>
                    <td><label class="label label-{{ record.post_status_label}}" data-toggle="tooltip" data-placement="right" title="{{ record.post_status_title }}">{{ record.post_status_text }}</label></td>
                    <td>
                      <i class="fa fa-spin fa-circle-o-notch" ng-show="record.loading_insights"></i>
                      <?php if($this->config->item('auto_insights')){ ?>
                      <span ng-show="!record.loading_insights"> 
                      <?php }else{ ?>
                      <a ng-click="manualInsights(record)" ng-show="!record.loading_insights"> 
                      <?php } ?>
                        <i class="fa fa-thumbs-o-up"></i> {{ record.post_likes }} 
                        <i class="fa fa-comment-o"></i> {{ record.post_comments }} 
                        <i class="fa fa-share"></i> {{ record.post_shares }} 
                      <?php echo $this->config->item('auto_insights') ? '</span>' : '</a>' ?>
                    </td>
                    <td>{{ record.post_datetime}}</td>
                    <td class="text-left">
                    <a title="<?php echo $this->lang->line('table_action_delete') ?>" class="btn btn-danger delete_post" href="<?php echo base_url() ?>index.php/post/delete/{{ record.post_to_nodes_id }}" ><i class="fa fa-trash"></i></a>
                    <a class="btn btn-primary {{ record.post_status == '<?php echo POST_STATUS_POSTED ?>' ? 'view_post' : '' }}" target="_blank" ng-show="record.post_status == '<?php echo POST_STATUS_POSTED ?>'"   href="{{ record.post_fb_url }}"><i class="fa fa-facebook"></i></a>
                    <a class="btn btn-success" href="<?php echo base_url() ?>index.php/post/insights/{{record.post_to_nodes_id}}" ng-show="record.post_status == '<?php echo POST_STATUS_POSTED ?>'" title="<?php echo $this->lang->line('table_action_insights') ?>">
                      <i class="fa fa-line-chart"></i> 
                    </a>
                    </td>
                  </tr>
                  <tr ng-show="loading_records" ng-cloak>
                    <td colspan="6"> <center><i class="fa fa-spin fa-cog fa-2x"></i></center> </td>
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
        <p><?php echo $this->lang->line('modal_delete_post') ?></p>
        <div class="checkbox" id="del_fb_check">
          <label>
            <input type="checkbox" id="del_fb" value="1">
            <?php echo $this->lang->line('modal_delete_post_facebook') ?>
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
    if($(this).siblings('a.view_post').length == 0)
      $('#del_fb_check').hide();
    else
      $('#del_fb_check').show();
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


