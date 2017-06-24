<script type="text/javascript" src="<?php echo base_url() ?>assets/js/ng-infinite-scroll.min.js"></script>
<script type="text/javascript">
  var url = '<?php echo base_url() ?>index.php/api/users/all';
  var fbcmpapp = angular.module('fbcmpapp', ['infinite-scroll']);
  fbcmpapp.controller('userController', function($scope, $http, $sce){
    $scope.searchTable = '';
    $scope.sortType  = 'ID';
    $scope.sortReverse  = false;
    $scope.records = [];
    $scope.alert = '';
    $scope.records_preloader = true;
    $scope.loading_records = false;
    $scope.all_records_loaded = false;
    $scope.loadNextPage =  function(){
      if($scope.all_records_loaded)  return;
      $scope.loading_records = true;
      $http.get(url+'/'+$scope.records.length).success(function(data){
        angular.forEach(data, function(record){
          switch(record.user_role){
            case '<?php echo USER_TYPE_ADMIN ?>' :
            record.user_role_label = 'primary';
            record.user_role_text = '<?php echo $this->lang->line('user_type_admin') ?>';
            break;
            case '<?php echo USER_TYPE_USER ?>' :
            record.user_role_label = 'warning';
            record.user_role_text = '<?php echo $this->lang->line('user_type_user') ?>';
            break;
          }
          switch(record.user_status){
            case '<?php echo USER_STATUS_ACTIVE ?>' :
            record.user_status_label = 'success';
            record.user_status_text = '<?php echo $this->lang->line('user_status_active') ?>';
            break;
            case '<?php echo USER_STATUS_INACTIVE ?>' :
            record.user_status_label = 'default';
            record.user_status_text = '<?php echo $this->lang->line('user_status_disabled') ?>';
            break;
          }
          record.changing_status = false;
        });
        if(data.length == 0)
          $scope.all_records_loaded = true;
       $scope.records = $scope.records.concat(data);
       $scope.loading_records = false;
      });
    }
    $scope.loadNextPage();
    $scope.changeStatus = function(record){
      record.changing_status= true;
     var  status_url = '<?php echo base_url() ?>index.php/users/'+ (record.user_status == '<?php echo USER_STATUS_ACTIVE ?>' ? 'disable' : 'enable');
     $http.get(status_url+'/'+record.user_id).success(function(data, status, headers){
      record.changing_status= false;
      var data_type = headers('Content-Type');
      if(data_type == 'application/json' && data.type == <?php echo AJAX_RESPONSE_TYPE_REDIRECT ?>){
        window.location.href = data.message;
        return true;
      }else if(data_type == 'application/json' && data.type == <?php echo AJAX_RESPONSE_TYPE_ERROR ?>){
        $scope.show_alert(data.message);
        return true;
      }
      record.user_status = record.user_status === '<?php echo USER_STATUS_ACTIVE ?>' ? '<?php echo USER_STATUS_INACTIVE ?>' : '<?php echo USER_STATUS_ACTIVE ?>' ;
      record.user_status_label = record.user_status === '<?php echo USER_STATUS_ACTIVE ?>' ? 'success' : 'default' ;
      record.user_status_text = record.user_status === '<?php echo USER_STATUS_ACTIVE ?>' ? '<?php echo $this->lang->line('user_status_active') ?>' : '<?php echo $this->lang->line('user_status_disabled') ?>' ;
     });
    };
    $scope.show_alert = function(alert){
      $scope.alert = $sce.trustAsHtml(alert);
    };
  });
</script>
<div class="row">
  <div class="col-sm-12">
    <?php echo $this->session->flashdata('alert') ?>
  </div>
</div>
<div class="row" ng-app="fbcmpapp" ng-controller="userController"> 
  <div class="col-sm-12" ng-bind-html="alert"></div>
  <div class="col-sm-12">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title"><?php echo $this->lang->line('user_list_heading') ?>
          <span class="badge" ng-cloak>{{ records.length }}</span>
        </h3>
      </div>
      <div class="panel-body">
        <div class="row">
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
            <a href="<?php echo base_url() ?>index.php/users/add" class="btn btn-success btn-block ">
              <i class="fa fa-plus"></i>
              <?php echo $this->lang->line('user_list_btn_add_user') ?>
            </a>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-striped table-hover table-bordered text-center">
              <thead>
                <tr>
                  <th class="col-sm-1">
                    <a href="#" ng-click="sortType = 'user_id'; sortReverse = !sortReverse">
                      <?php echo $this->lang->line('table_th_id') ?> 
                      <i ng-show="sortType == 'user_id' && !sortReverse" class="fa fa-sort-asc"></i>
                      <i ng-show="sortType == 'user_id' && sortReverse" class="fa fa-sort-desc"></i>
                    </a>
                  </th>
                  <th class="col-sm-2">
                    <a href="#" ng-click="sortType = 'user_name'; sortReverse = !sortReverse">
                       <?php echo $this->lang->line('table_th_username') ?> 
                      <i ng-show="sortType == 'user_name' && !sortReverse" class="fa fa-sort-asc"></i>
                      <i ng-show="sortType == 'user_name' && sortReverse" class="fa fa-sort-desc"></i>
                    </a>
                  </th>
                  <th class="col-sm-2">
                    <a href="#" ng-click="sortType = 'user_email'; sortReverse = !sortReverse">
                       <?php echo $this->lang->line('table_th_email') ?> 
                      <i ng-show="sortType == 'user_email' && !sortReverse" class="fa fa-sort-asc"></i>
                      <i ng-show="sortType == 'user_email' && sortReverse" class="fa fa-sort-desc"></i>
                    </a>
                  </th>
                  <th class="col-sm-1">
                    <a href="#" ng-click="sortType = 'user_role'; sortReverse = !sortReverse">
                       <?php echo $this->lang->line('table_th_type') ?> 
                      <i ng-show="sortType == 'user_role' && !sortReverse" class="fa fa-sort-asc"></i>
                      <i ng-show="sortType == 'user_role' && sortReverse" class="fa fa-sort-desc"></i>
                    </a>
                  </th>
                  <th class="col-sm-1">
                    <a href="#" ng-click="sortType = 'user_status'; sortReverse = !sortReverse">
                       <?php echo $this->lang->line('table_th_status') ?> 
                      <i ng-show="sortType == 'user_status' && !sortReverse" class="fa fa-sort-asc"></i>
                      <i ng-show="sortType == 'user_status' && sortReverse" class="fa fa-sort-desc"></i>
                    </a>
                  </th>
                  <th class="col-sm-2">
                    <a href="#" ng-click="sortType = 'created_at'; sortReverse = !sortReverse">
                       <?php echo $this->lang->line('table_th_created') ?> 
                      <i ng-show="sortType == 'created_at' && !sortReverse" class="fa fa-sort-asc"></i>
                      <i ng-show="sortType == 'created_at' && sortReverse" class="fa fa-sort-desc"></i>
                    </a>
                  </th>
                  <th class="col-sm-3"> <?php echo $this->lang->line('table_th_action') ?> </th>
                </tr>
              </thead>
              <tbody infinite-scroll="loadNextPage()" infinite-scroll-distance="1" infinite-scroll-disabled="loading_records">
                  <tr ng-cloak ng-repeat="record in records | orderBy:sortType:sortReverse | filter:searchtable">
                    <td>{{ record.user_id}}</td>
                    <td>{{ record.user_name}}</td>
                    <td>{{ record.user_email}}</td>
                    <td><span class="label label-{{ record.user_role_label}}">{{ record.user_role_text }}</span></td>
                    <td><span class="label label-{{ record.user_status_label}}">{{ record.user_status_text }}</span></td>
                    <td>{{ record.created_at}}</td>
                    <td>
                    <a class="btn btn-success" href="<?php echo base_url() ?>index.php/users/profile/{{ record.user_id }}" title="<?php echo $this->lang->line('table_action_profile') ?>"><i class="fa fa-user"></i></a>
                    <a class="btn btn-primary" href="<?php echo base_url() ?>index.php/users/edit/{{ record.user_id }}" title="<?php echo $this->lang->line('table_action_edit') ?>" ><i class="fa fa-edit"></i></a>
                    <a class="btn btn-danger delete_record" href="<?php echo base_url() ?>index.php/users/delete/{{ record.user_id }}" title="<?php echo $this->lang->line('table_action_delete') ?>" ><i class="fa fa-trash"></i></a>
                    <a class="btn {{ record.user_status == '<?php echo USER_STATUS_ACTIVE ?>' ? 'btn-danger' : 'btn-success' }}" ng-click="changeStatus(record)" title="{{ record.user_status == '<?php echo USER_STATUS_ACTIVE ?>' ? '<?php echo $this->lang->line('table_action_disable') ?>' : '<?php echo $this->lang->line('table_action_enable') ?>' }} ">
                      <i class="fa fa-power-off"></i>
                    </a>
                    <i class="fa fa-spin fa-circle-o-notch" ng-show="record.changing_status"></i>
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

<div class="modal fade" id="delete_modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?php echo $this->lang->line('modal_delete_header') ?></h4>
      </div>
      <div class="modal-body">
        <p><?php echo $this->lang->line('modal_delete_user') ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('modal_btn_close') ?></button>
        <a class="btn btn-danger"> <i class="fa fa-trash"></i> <?php echo $this->lang->line('modal_btn_delete') ?></a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



