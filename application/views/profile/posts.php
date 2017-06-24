<center><i class="fa fa-cog fa-spin" ng-show="posts.preloader"></i></center>
<div class="row margin-btm-5">
  <div class="col-sm-3">
    <form>
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon"><i class="fa fa-search"></i></div>
          <input type="text" class="form-control" placeholder="<?php echo $this->lang->line('table_search_placeholder') ?>" ng-model="posts.searchtable">
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
            <a href="#" ng-click="posts.sortType = post_id; posts.sortReverse = !posts.sortReverse">
              <?php echo $this->lang->line('table_th_id') ?> 
              <i ng-show="posts.sortType == post_id && !posts.sortReverse" class="fa fa-sort-asc"></i>
              <i ng-show="posts.sortType == post_id && posts.sortReverse" class="fa fa-sort-desc"></i>
            </a>
          </th>
          <th class="col-sm-3">
            <a href="#" ng-click="posts.sortType = 'post_name'; posts.sortReverse = !posts.sortReverse">
               <?php echo $this->lang->line('table_th_name') ?> 
              <i ng-show="posts.sortType == 'post_name' && !posts.sortReverse" class="fa fa-sort-asc"></i>
              <i ng-show="posts.sortType == 'post_name' && posts.sortReverse" class="fa fa-sort-desc"></i>
            </a>
          </th>
          
          <th class="col-sm-1">
            <a href="#" ng-click="posts.sortType = 'post_type'; posts.sortReverse = !posts.sortReverse">
               <?php echo $this->lang->line('table_th_type') ?> 
              <i ng-show="posts.sortType == 'post_type' && !posts.sortReverse" class="fa fa-sort-asc"></i>
              <i ng-show="posts.sortType == 'post_type' && posts.sortReverse" class="fa fa-sort-desc"></i>
            </a>
          </th>
          <th class="col-sm-1">
            <a href="#" ng-click="posts.sortType = 'campaign_status_text'; posts.sortReverse = !posts.sortReverse">
               <?php echo $this->lang->line('table_th_status') ?> 
              <i ng-show="posts.sortType == 'campaign_status_text' && !posts.sortReverse" class="fa fa-sort-asc"></i>
              <i ng-show="posts.sortType == 'campaign_status_text' && posts.sortReverse" class="fa fa-sort-desc"></i>
            </a>
          </th>
          <th class="col-sm-2">
            <a href="#" ng-click="posts.sortType = 'post_likes'; posts.sortReverse = !posts.sortReverse">
               <?php echo $this->lang->line('table_th_insights') ?> 
              <i ng-show="posts.sortType == 'post_likes' && !posts.sortReverse" class="fa fa-sort-asc"></i>
              <i ng-show="posts.sortType == 'post_likes' && posts.sortReverse" class="fa fa-sort-desc"></i>
            </a>
          </th>
          <th class="col-sm-2">
            <a href="#" ng-click="posts.sortType = 'date_started'; posts.sortReverse = !posts.sortReverse">
               <?php echo $this->lang->line('table_th_scheduled_date') ?> 
              <i ng-show="posts.sortType == 'date_started' && !posts.sortReverse" class="fa fa-sort-asc"></i>
              <i ng-show="posts.sortType == 'date_started' && posts.sortReverse" class="fa fa-sort-desc"></i>
            </a>
          </th>
          <th class="col-sm-2" > <?php echo $this->lang->line('table_th_action') ?> </th>
        </tr>
      </thead>
      <tbody infinite-scroll="loadPostsNextPage()" infinite-scroll-distance="1" infinite-scroll-disabled="posts.loading_records">
          <tr ng-cloak ng-repeat="record in posts.records | orderBy:posts.sortType:posts.sortReverse | filter:posts.searchtable">
            <td>{{ record.post_id}}</td>
            <td>{{ record.post_name}}</td>
            <td><i class="fa {{ record.post_type_icon}}" title="{{ record.post_type_title }}"></i></td>
            <td><label class="label label-{{ record.campaign_status_label}}" >{{ record.campaign_status_text }}</label></td>
            <td><i class="fa fa-thumbs-o-up"></i> {{ record.post_likes }} <i class="fa fa-comment-o"></i> {{ record.post_comments }} <i class="fa fa-share"></i> {{ record.post_shares }}</td>
            <td>{{ record.date_started}}</td>
            <td class="text-left">
            <a target="_blank" title="<?php echo $this->lang->line('table_action_view') ?>" class="btn btn-primary"   href="<?php echo base_url() ?>index.php/post/detail/{{ record.post_id }}"><i class="fa fa-folder-open"></i> </a>
            <a title="<?php echo $this->lang->line('table_action_delete') ?>" class="btn btn-danger delete_record" href="<?php echo base_url() ?>index.php/post/delete_campaign/{{ record.post_id }}" ><i class="fa fa-trash"></i></a>
            </td>
          </tr>
      </tbody>
  </table>
</div>