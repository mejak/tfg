<script type="text/javascript">
  var url = '<?php echo base_url() ?>index.php/api/profiles/all/<?php echo $this->session->userdata('user_id') ?>';
  var fbcmpapp = angular.module('fbcmpapp', []);
  fbcmpapp.controller('userController', function($scope, $http){
    $scope.searchTable = '';
    $scope.sortType  = 'profile_id';
    $scope.sortReverse  = false;
    $scope.records = [];
    $scope.records_preloader = true;
    $http.get(url).success(function(data) {
     $scope.records_preloader = false;
     $scope.records = data;
    });
  });
</script>
<div class="row">
  <div class="col-sm-12">
    <?php echo $this->session->flashdata('alert') ?>
    <?php echo $alert ?>
  </div>
</div>
<div class="row" ng-app="fbcmpapp" ng-controller="userController"> 
  <div class="col-sm-12">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title"><?php echo $this->lang->line('fb_accounts_heading') ?>
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
          <?php if(isset($login_url)){ ?>
          <div class="col-sm-3 col-sm-offset-6">
            <a href="<?php echo $login_url ?>" class="btn btn-primary btn-block">
              <i class="fa fa-facebook"></i>
              <?php echo $this->lang->line('fb_accounts_import_account') ?>
            </a>
          </div>
          <?php } ?>
        </div>
        <div class="table-responsive">
          <table class="table table-striped table-hover table-bordered text-center">
              <thead>
                <tr>
                  <th class="col-sm-1">
                    <a href="#" ng-click="sortType = profile_id; sortReverse = !sortReverse">
                      <?php echo $this->lang->line('table_th_id') ?> 
                      <i ng-show="sortType == profile_id && !sortReverse" class="fa fa-sort-asc"></i>
                      <i ng-show="sortType == profile_id && sortReverse" class="fa fa-sort-desc"></i>
                    </a>
                  </th>
                  <th class="col-sm-3">
                    <a href="#" ng-click="sortType = 'profile_name'; sortReverse = !sortReverse">
                       <?php echo $this->lang->line('table_th_name') ?> 
                      <i ng-show="sortType == 'profile_name' && !sortReverse" class="fa fa-sort-asc"></i>
                      <i ng-show="sortType == 'profile_name' && sortReverse" class="fa fa-sort-desc"></i>
                    </a>
                  </th>
                  <th class="col-sm-2">
                    <a href="#" ng-click="sortType = 'profile_fb_id'; sortReverse = !sortReverse">
                       <?php echo $this->lang->line('table_th_fb_id') ?> 
                      <i ng-show="sortType == 'profile_fb_id' && !sortReverse" class="fa fa-sort-asc"></i>
                      <i ng-show="sortType == 'profile_fb_id' && sortReverse" class="fa fa-sort-desc"></i>
                    </a>
                  </th>
                  <th class="col-sm-1">
                    <a href="#" ng-click="sortType = 'page_count'; sortReverse = !sortReverse">
                       <?php echo $this->lang->line('table_th_page_count') ?> 
                      <i ng-show="sortType == 'page_count' && !sortReverse" class="fa fa-sort-asc"></i>
                      <i ng-show="sortType == 'page_count' && sortReverse" class="fa fa-sort-desc"></i>
                    </a>
                  </th>
                  <th class="col-sm-3"> <?php echo $this->lang->line('table_th_action') ?> </th>
                </tr>
              </thead>
              <tbody>
                  <tr ng-cloak ng-repeat="record in records | orderBy:sortType:sortReverse | filter:searchtable">
                    <td>{{ record.profile_id}}</td>
                    <td>{{ record.profile_name}}</td>
                    <td>{{ record.profile_fb_id}}</td>
                    <td>{{ record.page_count}}</td>
                    <td>
                    <a class="btn btn-danger delete_record" href="<?php echo base_url() ?>index.php/profiles/delete/{{ record.profile_id }}" ><i class="fa fa-trash"></i> <?php echo $this->lang->line('table_action_delete') ?></a>
                    <a class="btn btn-primary" href="<?php echo base_url() ?>index.php/profiles/import_pages/{{ record.profile_id }}" ><i class="fa fa-facebook"></i> <?php echo $this->lang->line('table_action_import_pages') ?></a>
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
</div>

<div class="modal fade" id="delete_modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?php echo $this->lang->line('modal_delete_header') ?></h4>
      </div>
      <div class="modal-body">
        <p><?php echo $this->lang->line('modal_delete_fb_account') ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('modal_btn_close') ?></button>
        <a class="btn btn-danger"> <i class="fa fa-trash"></i> <?php echo $this->lang->line('modal_btn_delete') ?></a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



