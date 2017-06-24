<script type="text/javascript">
  var url = '<?php echo base_url() ?>index.php/api/profiles/';
  var profile_id = <?php echo $this->uri->segment(3) ?>;
  var angapp = angular.module('angapp', []);
  angapp.controller('appController', function($scope, $http, $sce){
    $scope.tokenLoaded = false;
    $scope. token_alert = '';

    $scope.manage_pages = [];
    $scope.manage_pages_before = '';
    $scope.manage_pages_after = '';
    $scope.manage_pages_loaded = false;

    $scope.get_pages = function(cursor, cursor_value){
      req_params = {};
      req_params[cursor] = cursor_value;
      $scope.manage_pages_loaded = false;
      $scope.manage_pages = [];
      $scope.manage_pages_before = '';
      $scope.manage_pages_after = '';
      $http.get(url+'pages/'+profile_id, {params: req_params}).success(function(data){
        $scope.manage_pages_loaded = true;
        if(data.type == <?php echo AJAX_RESPONSE_TYPE_ERROR ?>){
          $scope.manage_pages_alert = $sce.trustAsHtml(data.message);
          return;
        }
        $scope.manage_pages = data.nodes;
        if(data.next)
            $scope.manage_pages_after = data.next;
        if(data.previous)
            $scope.manage_pages_before = data.previous;
      });
    }

    $http.get(url+'debug/'+profile_id)
    .then(function(result){
      $scope.tokenLoaded = true;
      if(result.data.type == <?php echo AJAX_RESPONSE_TYPE_ERROR ?>){
        $scope.token_alert = $sce.trustAsHtml(result.data.message);          
      }else{
        $scope.token = result.data;
      }
      return $http.get(url+'pages/'+profile_id );
    }).then(function(result){
      $scope.manage_pages_loaded = true;
      if(result.data.type == <?php echo AJAX_RESPONSE_TYPE_ERROR ?>){
        $scope.manage_pages_alert = $sce.trustAsHtml(result.data.message);
      }else{
        $scope.manage_pages = result.data.nodes;
        if(result.data.next)
          $scope.manage_pages_after = result.data.next;
        if(result.data.previous)
          $scope.manage_pages_before = result.data.previous;
      }
    });
    
    $scope.savePages = function(){
      $scope.manage_pages_loaded = false;
      var post_data = {
        data: $scope.manage_pages, 
        <?php echo $this->security->get_csrf_token_name() ?> : '<?php echo $this->security->get_csrf_hash() ?>'
      };
      $http.post(url+'save_pages/'+profile_id,  post_data).success(function(data, status, headers) {
        $scope.manage_pages_loaded = true;
        var data_type = headers('Content-Type');
        if(data_type == 'application/json; charset=utf-8' && data.type == <?php echo AJAX_RESPONSE_TYPE_REDIRECT ?>){
          window.location.href = data.message;
          return true;
        }else if(data.type == <?php echo AJAX_RESPONSE_TYPE_SUCCESS ?> || data.type == <?php echo AJAX_RESPONSE_TYPE_ERROR ?>){
          $scope.manage_pages_alert = $sce.trustAsHtml(data.message);
        }
        if($scope.manage_pages_after != '')
          $scope.get_pages('after',  $scope.manage_pages_after);
      });      
    };

    $scope.allSelected = false;
    $scope.toggleAllSelected = function(){
      if($scope.allSelected)
        $scope.allSelected = true;
      else
        $scope.allSelected = false;
      for(var i=0; i<$scope.filter_manage_pages.length; i++)
            $scope.filter_manage_pages[i].selected = $scope.allSelected;
    }
  });
</script>
<div class="row">
  <div class="col-sm-12">
    <?php echo $this->session->flashdata('alert') ?>
  </div>
</div>
<div class="row" ng-app="angapp" ng-controller="appController"> 
  <div class="col-sm-12">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title"><?php echo $this->lang->line('import_pages_heading').' - '.$profile->profile_name; ?>
        </h3>
      </div>
      <div class="panel-body">

        <!-- Top section -->
        <div class="row margin-btm-20">
          <div class="col-sm-2 col-xs-12 text-center margin-btm-10">
            <img class="profile-picture" src="<?php echo $profile->profile_picture ?>" alt="<?php echo $profile->profile_name ?>">
          </div>
          <div class="col-sm-3 text-center">
            <h5><?php echo $profile->profile_name ?> </h5>
            <h5> <?php echo $profile->profile_fb_id ?> </h5>
          </div>
          <div class="col-sm-6">
            <div ng-cloak class="row margin-btm-10">
              <div class="col-sm-12 col-xs-12 col-md-12 col-lg-12 text-center" ng-show="!tokenLoaded">
                <i class="fa fa-spin fa-cog fa-2x"></i>
              </div>
              <div ng-bind-html="token_alert" class="col-sm-12 col-xs-12 col-md-12 col-lg-12 text-center">
              </div>
              <div  class="col-sm-12 col-xs-12" ng-show="tokenLoaded && token_alert == '' ">
                <h5><?php echo $this->lang->line('import_pages_text_app_id') ?> : {{ token.app_id }}</h5>
                <h5><?php echo $this->lang->line('import_pages_text_app') ?> : {{ token.application }}</h5>
                <h5><?php echo $this->lang->line('import_pages_text_permissions') ?>: </h5>
                <h5><span ng-repeat="perm in token.scopes">{{ perm }}, </span></h5>

              </div>
            </div>
          </div>
        </div>

        <!-- Manage Pages -->
        <div class="row section-header-primary margin-btm-20">
          <div class="col-sm-12">
            <h4 > <?php echo $this->lang->line('import_pages_section_manage_pages') ?> <span ng-cloak class="badge">{{ manage_pages.length }}</span> </h4>
          </div>
        </div>
        <div ng-cloak class="row margin-btm-20 content">
          
          <div class="col-sm-12 col-xs-12 col-md-12 col-lg-12" ng-show="manage_pages.length">
            <div class="row margin-btm-5"  >
              <div class="col-sm-3">
                <form>
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon"><i class="fa fa-search"></i></div>
                      <input type="text" class="form-control" placeholder="<?php echo $this->lang->line('table_search_placeholder') ?>" ng-model="search_manage_pages">
                    </div>      
                  </div>
                </form>
              </div>
              <div class="col-sm-3">
                <div class="checkbox">
                  <label>
                    <input type="checkbox" ng-model="allSelected" ng-click="toggleAllSelected()"> <?php echo $this->lang->line('common_text_check_uncheck_all') ?>
                  </label>
                </div>
              </div>
              <div class="col-sm-2 col-sm-offset-4">
                <a ng-disabled="!manage_pages_loaded" href="" class="btn btn-success btn-block " ng-click="savePages()">
                  <i class="fa fa-floppy-o"></i> 
                  <?php echo $this->lang->line('import_pages_btn_save_selected') ?>
                </a>
              </div>
            </div>
          </div>
          <div ng-cloak class="col-sm-12 col-xs-12 col-md-12 col-lg-12 text-center" >
            <?php if(isset($remaining_pages)) echo $remaining_pages ?>
          </div>
          <div ng-cloak class="col-sm-12 col-xs-12 col-md-12 col-lg-12 text-center" ng-show="!manage_pages_loaded">
            <i class="fa fa-spin fa-cog fa-2x"></i>
          </div>
          <div ng-cloak ng-bind-html="manage_pages_alert" class="col-sm-12 col-xs-12 col-md-12 col-lg-12 text-center">
          </div>
          <div class="col-sm-12 col-xs-12" ng-show="manage_pages_before || manage_pages_after" ng-cloak>
            <nav>
              <ul class="pager">
                <li><a ng-show="manage_pages_before != '' " href="" ng-click="get_pages('before', manage_pages_before)"><?php echo $this->lang->line('import_pages_btn_previous') ?></a></li>
                <li><a ng-show="manage_pages_after != '' "  href="" ng-click="get_pages('after', manage_pages_after)"><?php echo $this->lang->line('import_pages_btn_next') ?></a></li>
              </ul>
            </nav>
          </div>
          <div class="col-sm-12 nodes_list col-xs-12">
            <ul class="list-group" id="manage_pages">
              <li ng-cloak  class="list-group-item" ng-repeat="node in (filter_manage_pages = (manage_pages | filter: search_manage_pages))">
                <div class="checkbox node-check">
                  <label>
                   <input type="checkbox" name="{{node.id}}" id="{{node.id}}" ng-model="node.selected">
                   {{ node.name }}
                  </label>
                </div>
              </li>
            </ul>
          </div>
        <!-- End sections -->
      </div>
    </div>
  </div>
</div>




