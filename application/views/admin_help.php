<script type="text/javascript">
  var url = '<?php echo base_url() ?>index.php/api/help/admin/';
  var fbcmpapp = angular.module('fbcmpapp', ['fbcmpapp.filters']);
  angular.module('fbcmpapp.filters', [])
  .filter('linebreak', function() {
      return function(text) {
          return text.replace(/\\n/g, '<br>');
      }
  }).filter('to_trusted', ['$sce', function($sce){
      return function(text) {
          return $sce.trustAsHtml(text);
      };
  }]);;
  fbcmpapp.controller('userController', function($scope, $http, $sce){
    $scope.records_preloader = true;
    $scope.records = [];
    $http.get(url).success(function(data){
     $scope.records_preloader = false;
     $scope.records = data;
    });
  });
</script>
<div class="row" ng-app="fbcmpapp" ng-controller="userController">
    <div class="col-md-12">
        <div class="x_panel">
          <div class="x_title">
            <h2><?php echo $this->lang->line('help_admin_heading') ?>
            </h2>
            <h5 class="pull-right"><?php if(defined('SCRIPT_VERSION')) echo $this->lang->line('help_admin_script_version').' : '. SCRIPT_VERSION ?></h5>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div class="col-sm-6">
              <form>
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon"><i class="fa fa-search"></i></div>
                    <input type="text" class="form-control" placeholder="<?php echo $this->lang->line('table_search_placeholder') ?>" ng-model="searchrecords">
                  </div>      
                </div>
              </form>
            </div>
            <div class="col-sm-12">
              <div class="list-group">
                <div class="list-group-item" ng-repeat="record in records | orderBy:sortType:sortReverse | filter:searchrecords">
                  <h4 class="list-group-item-heading"><span ng-bind-html="record.question | linebreak | to_trusted"></span><span ng-show="record.video != ''" class="pull-right"><a href="{{ record.video }}" target="_blank" >Watch Video</a></span></h4>
                  <p class="list-group-item-text" ng-bind-html="record.answer | linebreak | to_trusted"></p>
                </div>
                <div class="list-group-item" ng-show="records_preloader" ng-cloak>
                  <p class="list-group-item-text"><center><i class="fa fa-spin fa-cog fa-2x"></i></center></p>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
</div>
