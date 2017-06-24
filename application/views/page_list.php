<script type="text/javascript">
  var url = '<?php echo base_url() ?>index.php/api/page/all/';
  var user_id = '<?php echo $this->session->userdata('user_id') ?>';
  var angapp = angular.module('angapp', []);
  angapp.controller('pageController', function($scope, $http, $sce){
    $scope.searchtable = '';
    $scope.sortType  = '';
    $scope.sortReverse  = false;
    $scope.records = [];
    $scope.records_preloader = true;
    $scope.get_pages = function(){
      $scope.records = [];
      $scope.searchtable = '';
      $scope.sortType  = '';
      $scope.sortReverse  = false;
      $scope.records_preloader = true;
      $http.get(url + user_id).success(function(data) {
        angular.forEach(data, function(record){
          record.page_likes = parseInt(record.page_likes);
        });
        $scope.records_preloader = false;
        $scope.records = data;
      });
    };
    $scope.get_pages();

    $scope.show_alert = function(alert){
      $scope.alert = $sce.trustAsHtml(alert);
    }

    $scope.delete_records = function(){
      if($scope.filteredResults.length == 0) return;
      $scope.groups_deleting = true;
      $http.post('<?php echo base_url() ?>index.php/api/page/delete', {pages: $scope.filteredResults}).success(function(data, status, headers){
        $scope.groups_deleting = false;
        var data_type = headers('Content-Type');
        if(data.type == <?php echo AJAX_RESPONSE_TYPE_REDIRECT ?>){
          window.location.href = data.message;
          return true;
        }else if(data.type == <?php echo AJAX_RESPONSE_TYPE_SUCCESS ?> || data.type == <?php echo AJAX_RESPONSE_TYPE_ERROR ?>){
          $scope.alert =  $sce.trustAsHtml(data.message);
          $scope.searchtable = '';
          $scope.get_pages();
        }
      });
    };

    
  });

  //jquery
  $(document).ready(function(){
    $('#delete_all').click(function(){
      $('#delete_all_modal').modal('show');
      return false;
    });
    $('#delete_all_modal_btn').click(function(){
      $('#delete_all_modal').modal('hide');
    });
  });

</script>
<div class="row">
  <div class="col-sm-12">
    <?php echo $this->session->flashdata('alert') ?>
  </div>
</div>
<div class="row" ng-app="angapp" ng-controller="pageController"> 
  <div class="col-sm-12" ng-bind-html="alert">
  </div>
  <div class="col-sm-12">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title"><?php echo $this->lang->line('page_list_heading') ?>
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
            <div class="col-sm-1 col-sm-offset-6">
              <i class="fa fa-spin fa-cog fa-2x" ng-show="groups_deleting" ></i>
            </div>
            <div class="col-sm-2 " ng-show="records.length" ng-cloak>
              <button id="delete_all" class="btn btn-danger btn-block"><i class="fa fa-trash"></i> <?php echo $this->lang->line('page_list_btn_delete') ?></button>
            </div>
        </div>
        <div class="table-responsive">
          <table class="table table-striped table-hover table-bordered text-center">
              <thead>
                <tr>
                  <th class="col-sm-1">
                    <a href="#" ng-click="sortType = page_id; sortReverse = !sortReverse">
                      <?php echo $this->lang->line('table_th_id') ?> 
                      <i ng-show="sortType == page_id && !sortReverse" class="fa fa-sort-asc"></i>
                      <i ng-show="sortType == page_id && sortReverse" class="fa fa-sort-desc"></i>
                    </a>
                  </th>
                  <th class="col-sm-3">
                    <a href="#" ng-click="sortType = 'page_name'; sortReverse = !sortReverse">
                       <?php echo $this->lang->line('table_th_page_name') ?> 
                      <i ng-show="sortType == 'page_name' && !sortReverse" class="fa fa-sort-asc"></i>
                      <i ng-show="sortType == 'page_name' && sortReverse" class="fa fa-sort-desc"></i>
                    </a>
                  </th>
                  <th class="col-sm-3">
                    <a href="#" ng-click="sortType = 'profile_name'; sortReverse = !sortReverse">
                       <?php echo $this->lang->line('table_th_profile_name') ?> 
                      <i ng-show="sortType == 'profile_name' && !sortReverse" class="fa fa-sort-asc"></i>
                      <i ng-show="sortType == 'profile_name' && sortReverse" class="fa fa-sort-desc"></i>
                    </a>
                  </th>
                  <th class="col-sm-1">
                    <a href="#" ng-click="sortType = 'page_likes'; sortReverse = !sortReverse">
                      <?php echo $this->lang->line('table_th_likes') ?> 
                      <i ng-show="sortType == 'page_likes' && !sortReverse" class="fa fa-sort-asc"></i>
                      <i ng-show="sortType == 'page_likes' && sortReverse" class="fa fa-sort-desc"></i>
                    </a>
                  </th>
                  <th class="col-sm-4"> <?php echo $this->lang->line('table_th_action') ?> </th>
                </tr>
              </thead>
              <tbody>
                  <tr ng-cloak ng-repeat="record in ( filteredResults = (records | orderBy:sortType:sortReverse | filter:searchtable))">
                    <td>{{ record.page_id}}</td>
                    <td><a href="https://www.facebook.com/{{ record.page_fb_id }}" target="_blank">{{ record.page_name}}</a></td>
                    <td><a href="https://www.facebook.com/{{ record.profile_fb_id }}" target="_blank">{{ record.profile_name}}</a></td>
                    <td>{{ record.page_likes }}</td>
                    <td>
                    <a class="btn btn-primary" href="<?php echo base_url() ?>index.php/page/index/{{ record.page_id }}" ><i class="fa fa-facebook"></i> <?php echo $this->lang->line('table_action_post_list') ?></a>
                    <a class="btn btn-danger delete_record" href="<?php echo base_url() ?>index.php/page/delete/{{ record.page_id }}" ><i class="fa fa-trash"></i> <?php echo $this->lang->line('table_action_delete') ?></a>
                    <?php if($this->session->userdata('admin_login') === TRUE || $this->session->userdata('insights_allowed')){ ?>
                    <a class="btn btn-success" href="<?php echo base_url() ?>index.php/page/insights/{{record.page_id}}">
                      <i class="fa fa-line-chart"></i> <?php echo $this->lang->line('table_action_insights') ?>
                    </a>
                    <?php } ?>
                    </td>
                  </tr>
                  <tr ng-show="records_preloader" ng-cloak>
                    <td colspan="5"> <center><i class="fa fa-spin fa-cog fa-2x"></i></center> </td>
                  </tr>
              </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="delete_all_modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><?php echo $this->lang->line('modal_delete_all_header') ?></h4>
        </div>
        <div class="modal-body">
          <p><?php echo $this->lang->line('modal_delete_pages') ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('modal_btn_close') ?></button>
          <a class="btn btn-danger" ng-click="delete_records()" id="delete_all_modal_btn"> <i class="fa fa-trash"></i> <?php echo $this->lang->line('modal_btn_delete') ?></a>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

</div>

<div class="modal fade" id="delete_modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?php echo $this->lang->line('modal_delete_header') ?></h4>
      </div>
      <div class="modal-body">
        <p><?php echo $this->lang->line('modal_delete_page') ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('modal_btn_close') ?></button>
        <a class="btn btn-danger"> <i class="fa fa-trash"></i> <?php echo $this->lang->line('modal_btn_delete') ?></a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



