<center><i class="fa fa-cog fa-spin" ng-show="nodes.preloader"></i></center>
<div class="row margin-btm-5">
  <form>
    <div class="col-sm-3">
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon"><i class="fa fa-search"></i></div>
          <input type="text" class="form-control" placeholder="<?php echo $this->lang->line('table_search_placeholder') ?>" ng-model="nodes.searchtable">
        </div>      
      </div>
    </div>
  </form>
</div>
<div class="table-responsive">
  <table class="table table-striped table-hover table-bordered text-center">
      <thead>
        <tr>
          <th class="col-sm-1">
            <a href="#" ng-click="nodes.sortType = page_id; nodes.sortReverse = !nodes.sortReverse">
              <?php echo $this->lang->line('table_th_id') ?> 
              <i ng-show="nodes.sortType == page_id && !nodes.sortReverse" class="fa fa-sort-asc"></i>
              <i ng-show="nodes.sortType == page_id && nodes.sortReverse" class="fa fa-sort-desc"></i>
            </a>
          </th>
          <th class="col-sm-4">
            <a href="#" ng-click="nodes.sortType = 'page_name'; nodes.sortReverse = !nodes.sortReverse">
               <?php echo $this->lang->line('table_th_name') ?> 
              <i ng-show="nodes.sortType == 'page_name' && !nodes.sortReverse" class="fa fa-sort-asc"></i>
              <i ng-show="nodes.sortType == 'page_name' && nodes.sortReverse" class="fa fa-sort-desc"></i>
            </a>
          </th>
          <th class="col-sm-4">
            <a href="#" ng-click="nodes.sortType = 'profile_name'; nodes.sortReverse = !nodes.sortReverse">
               <?php echo $this->lang->line('table_th_profile_name') ?> 
              <i ng-show="nodes.sortType == 'profile_name' && !nodes.sortReverse" class="fa fa-sort-asc"></i>
              <i ng-show="nodes.sortType == 'profile_name' && nodes.sortReverse" class="fa fa-sort-desc"></i>
            </a>
          </th>
          <th class="col-sm-2">
            <a href="#" ng-click="nodes.sortType = 'page_likes'; nodes.sortReverse = !nodes.sortReverse">
               <?php echo $this->lang->line('table_th_likes') ?> 
              <i ng-show="nodes.sortType == 'page_likes' && !nodes.sortReverse" class="fa fa-sort-asc"></i>
              <i ng-show="nodes.sortType == 'page_likes' && nodes.sortReverse" class="fa fa-sort-desc"></i>
            </a>
          </th>
          <th class="col-sm-1"> <?php echo $this->lang->line('table_th_action') ?> </th>
        </tr>
      </thead>
      <tbody>
          <tr ng-cloak ng-repeat="record in ( filteredResults = (nodes.records | orderBy:nodes.sortType:nodes.sortReverse | filter:nodes.searchtable))">
            <td>{{ record.page_id}}</td>
            <td><a href="https://www.facebook.com/{{ record.page_fb_id }}" target="_blank">{{ record.page_name}}</a></td>
            <td><a href="https://www.facebook.com/{{ record.profile_fb_id }}" target="_blank">{{ record.profile_name}}</a></td>
            <td>{{ record.page_likes}}</td>
            <td>
            <a class="btn btn-danger delete_record" href="<?php echo base_url() ?>index.php/page/delete/{{ record.page_id }}" ><i class="fa fa-trash"></i> <?php echo $this->lang->line('table_action_delete') ?></a>
            </td>
          </tr>
      </tbody>
  </table>
</div>




