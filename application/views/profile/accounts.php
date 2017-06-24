<center><i class="fa fa-cog fa-spin" ng-show="accounts.preloader"></i></center>
<div class="row margin-btm-5">
  <div class="col-sm-3">
    <form>
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon"><i class="fa fa-search"></i></div>
          <input type="text" class="form-control" placeholder="<?php echo $this->lang->line('table_search_placeholder') ?>" ng-model="accounts.searchtable">
        </div>      
      </div>
    </form>
  </div>
</div>
<div class="table-responsive">
  <table class="table table-striped table-hover table-bordered text-center">
      <thead>
        <tr>
          <th class="col-sm-1">
            <a href="#" ng-click="accounts.sortType = profile_id; accounts.sortReverse = !accounts.sortReverse">
              <?php echo $this->lang->line('table_th_id') ?> 
              <i ng-show="accounts.sortType == profile_id && !accounts.sortReverse" class="fa fa-sort-asc"></i>
              <i ng-show="accounts.sortType == profile_id && accounts.sortReverse" class="fa fa-sort-desc"></i>
            </a>
          </th>
          <th class="col-sm-5">
            <a href="#" ng-click="accounts.sortType = 'profile_name'; accounts.sortReverse = !accounts.sortReverse">
               <?php echo $this->lang->line('table_th_name') ?> 
              <i ng-show="accounts.sortType == 'profile_name' && !accounts.sortReverse" class="fa fa-sort-asc"></i>
              <i ng-show="accounts.sortType == 'profile_name' && accounts.sortReverse" class="fa fa-sort-desc"></i>
            </a>
          </th>
          <th class="col-sm-2">
            <a href="#" ng-click="accounts.sortType = 'profile_fb_id'; accounts.sortReverse = !accounts.sortReverse">
               <?php echo $this->lang->line('table_th_fb_id') ?> 
              <i ng-show="accounts.sortType == 'profile_fb_id' && !accounts.sortReverse" class="fa fa-sort-asc"></i>
              <i ng-show="accounts.sortType == 'profile_fb_id' && accounts.sortReverse" class="fa fa-sort-desc"></i>
            </a>
          </th>
          <th class="col-sm-2">
            <a href="#" ng-click="accounts.sortType = 'page_count'; accounts.sortReverse = !accounts.sortReverse">
               <?php echo $this->lang->line('table_th_page_count') ?> 
              <i ng-show="accounts.sortType == 'page_count' && !accounts.sortReverse" class="fa fa-sort-asc"></i>
              <i ng-show="accounts.sortType == 'page_count' && accounts.sortReverse" class="fa fa-sort-desc"></i>
            </a>
          </th>
          <th class="col-sm-2"> <?php echo $this->lang->line('table_th_action') ?> </th>
        </tr>
      </thead>
      <tbody>
          <tr ng-cloak ng-repeat="record in accounts.records | orderBy:accounts.sortType:accounts.sortReverse | filter:accounts.searchtable">
            <td>{{ record.profile_id}}</td>
            <td>{{ record.profile_name}}</td>
            <td>{{ record.profile_fb_id}}</td>
            <td>{{ record.page_count}}</td>
            <td>
            <a class="btn btn-danger delete_record" href="<?php echo base_url() ?>index.php/profiles/delete/{{ record.profile_id }}" ><i class="fa fa-trash"></i> <?php echo $this->lang->line('table_action_delete') ?></a>
            </td>
          </tr>
      </tbody>
  </table>
</div>