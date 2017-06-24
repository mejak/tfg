<?php $this->load->view('insights/insights_js'); ?>
<div class="row" ng-app="angapp" ng-controller="pageController"> 
  <div class="col-sm-12" >
    <img src="<?php echo $page->profile_picture ?>" alt="<?php echo $page->page_name ?>" width="70">
    <strong><a href="https://www.facebook.com/<?php echo $page->page_fb_id ?>" target="_blank"><?php echo $page->page_name ?></a></strong>
  </div>
  <div class="col-sm-12" ng-bind-html="alert">
    <?php echo $this->session->flashdata('alert') ?>
  </div>
  <div class="col-sm-12 text-center" ng-show="!token_loaded || !sdk_loaded">
    <i class="fa fa-spin fa-circle-o-notch fa-2x" ></i>
  </div>
  <div class="col-sm-12" ng-cloak ng-show="token_loaded && sdk_loaded">
    <div class="" role="tabpanel" data-example-id="togglable-tabs">
      <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
          <li role="presentation" class="active" ><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="false"><?php echo $this->lang->line('insights_tab_overview') ?></a>
          </li>
          <li role="presentation"><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('insights_tab_advanced') ?></a>
          </li>
      </ul>
      <div id="myTabContent" class="tab-content" >
          <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
              <div class="row">
                <nav class="col-sm-2" id="myScrollspy">
                  <ul class="nav nav-pills nav-stacked" data-spy="affix" data-offset-top="120">
                    <li><a href="#stories"><?php echo $this->lang->line('insights_nav_stories') ?></a></li>
                    <li><a href="#reach"><?php echo $this->lang->line('insights_nav_reach') ?></a></li>
                    <li><a href="#engagement"><?php echo $this->lang->line('insights_nav_engagement') ?></a></li>
                    <li><a href="#reactions"><?php echo $this->lang->line('insights_nav_reactions') ?></a></li>
                    <li><a href="#cta_clicks"><?php echo $this->lang->line('insights_nav_cta_clicks') ?></a></li>
                    <li><a href="#fans"><?php echo $this->lang->line('insights_nav_fans') ?></a></li>
                    <li><a href="#page_views"><?php echo $this->lang->line('insights_nav_page_views') ?></a></li>
                    <li><a href="#videos"><?php echo $this->lang->line('insights_nav_videos') ?></a></li>
                    <li><a href="#posts"><?php echo $this->lang->line('insights_nav_posts') ?></a></li>
                  </ul>
                </nav>
                <div class="col-sm-10" >
                  <div class="x_panel" id="stories" >
                      <div class="x_title">
                          <h2><?php echo $this->lang->line('insights_section_stories') ?> <i class="fa fa-spin fa-circle-o-notch " ng-show="!stories_loaded"></i></h2>
                          <ul class="nav navbar-right panel_toolbox">
                              <li><a class="collapse-link" href=""><i class="fa fa-chevron-up"></i></a></li>
                              <li><a class="close-link" href=""><i class="fa fa-close"></i></a></li>
                          </ul>
                          <div class="clearfix"></div>
                      </div>
                      <div class="x_content">                     
                          <!-- content starts here -->
                    
                          <!-- content ends here -->
                      </div>
                  </div>
                  <div class="x_panel" id="reach" >
                      <div class="x_title">
                          <h2><?php echo $this->lang->line('insights_section_reach') ?> <i class="fa fa-spin fa-circle-o-notch " ng-show="!reach_loaded"></i></h2>
                          <ul class="nav navbar-right panel_toolbox">
                              <li><a class="collapse-link" href=""><i class="fa fa-chevron-up"></i></a></li>
                              <li><a class="close-link" href=""><i class="fa fa-close"></i></a></li>
                          </ul>
                          <div class="clearfix"></div>
                      </div>
                      <div class="x_content">                     
                          <!-- content starts here -->
                    
                          <!-- content ends here -->
                      </div>
                  </div>
                  <div class="x_panel" id="engagement" >
                      <div class="x_title">
                          <h2><?php echo $this->lang->line('insights_section_engagement') ?> <i class="fa fa-spin fa-circle-o-notch " ng-show="!engagement_loaded"></i></h2>
                          <ul class="nav navbar-right panel_toolbox">
                              <li><a class="collapse-link" href=""><i class="fa fa-chevron-up"></i></a></li>
                              <li><a class="close-link" href=""><i class="fa fa-close"></i></a></li>
                          </ul>
                          <div class="clearfix"></div>
                      </div>
                      <div class="x_content">                     
                          <!-- content starts here -->
                    
                          <!-- content ends here -->
                      </div>
                  </div>
                  <div class="x_panel" id="reactions" >
                      <div class="x_title">
                          <h2><?php echo $this->lang->line('insights_section_reactions') ?> <i class="fa fa-spin fa-circle-o-notch " ng-show="!reactions_loaded"></i></h2>
                          <ul class="nav navbar-right panel_toolbox">
                              <li><a class="collapse-link" href=""><i class="fa fa-chevron-up"></i></a></li>
                              <li><a class="close-link" href=""><i class="fa fa-close"></i></a></li>
                          </ul>
                          <div class="clearfix"></div>
                      </div>
                      <div class="x_content">                     
                          <!-- content starts here -->
                    
                          <!-- content ends here -->
                      </div>
                  </div>
                  <div class="x_panel" id="cta_clicks" >
                      <div class="x_title">
                          <h2><?php echo $this->lang->line('insights_section_cta_clicks') ?> <i class="fa fa-spin fa-circle-o-notch " ng-show="!cta_clicks_loaded"></i></h2>
                          <ul class="nav navbar-right panel_toolbox">
                              <li><a class="collapse-link" href=""><i class="fa fa-chevron-up"></i></a></li>
                              <li><a class="close-link" href=""><i class="fa fa-close"></i></a></li>
                          </ul>
                          <div class="clearfix"></div>
                      </div>
                      <div class="x_content">                     
                          <!-- content starts here -->
                    
                          <!-- content ends here -->
                      </div>
                  </div>
                  <div class="x_panel" id="fans" >
                      <div class="x_title">
                          <h2><?php echo $this->lang->line('insights_section_fans') ?> <i class="fa fa-spin fa-circle-o-notch " ng-show="!fans_loaded"></i></h2>
                          <ul class="nav navbar-right panel_toolbox">
                              <li><a class="collapse-link" href=""><i class="fa fa-chevron-up"></i></a></li>
                              <li><a class="close-link" href=""><i class="fa fa-close"></i></a></li>
                          </ul>
                          <div class="clearfix"></div>
                      </div>
                      <div class="x_content">                     
                          <!-- content starts here -->
                    
                          <!-- content ends here -->
                      </div>
                  </div>
                  <div class="x_panel" id="page_views" >
                      <div class="x_title">
                          <h2><?php echo $this->lang->line('insights_section_page_views') ?> <i class="fa fa-spin fa-circle-o-notch " ng-show="!views_loaded"></i></h2>
                          <ul class="nav navbar-right panel_toolbox">
                              <li><a class="collapse-link" href=""><i class="fa fa-chevron-up"></i></a></li>
                              <li><a class="close-link" href=""><i class="fa fa-close"></i></a></li>
                          </ul>
                          <div class="clearfix"></div>
                      </div>
                      <div class="x_content">                     
                          <!-- content starts here -->
                    
                          <!-- content ends here -->
                      </div>
                  </div>
                  <div class="x_panel" id="videos" >
                      <div class="x_title">
                          <h2><?php echo $this->lang->line('insights_section_videos') ?> <i class="fa fa-spin fa-circle-o-notch " ng-show="!videos_loaded"></i></h2>
                          <ul class="nav navbar-right panel_toolbox">
                              <li><a class="collapse-link" href=""><i class="fa fa-chevron-up"></i></a></li>
                              <li><a class="close-link" href=""><i class="fa fa-close"></i></a></li>
                          </ul>
                          <div class="clearfix"></div>
                      </div>
                      <div class="x_content">                     
                          <!-- content starts here -->
                    
                          <!-- content ends here -->
                      </div>
                  </div>
                  <div class="x_panel" id="posts" >
                      <div class="x_title">
                          <h2><?php echo $this->lang->line('insights_section_posts') ?> <i class="fa fa-spin fa-circle-o-notch " ng-show="!posts_loaded"></i></h2>
                          <ul class="nav navbar-right panel_toolbox">
                              <li><a class="collapse-link" href=""><i class="fa fa-chevron-up"></i></a></li>
                              <li><a class="close-link" href=""><i class="fa fa-close"></i></a></li>
                          </ul>
                          <div class="clearfix"></div>
                      </div>
                      <div class="x_content">                     
                          <!-- content starts here -->
                    
                          <!-- content ends here -->
                      </div>
                  </div>
                </div>
              </div>
          </div>
          <div role="tabpanel" class="tab-pane fade " id="tab_content2" aria-labelledby="profile-tab">
            <div class="row margin-btm-20">
              <div class="col-sm-4">
                <select class="form-control" ng-model="selected_metric" ng-change="load_period()">
                  <option value=""><?php echo $this->lang->line('insights_select_metric') ?></option>
                  <option ng-repeat="record in metrics" value="{{record.metric_name}}">
                    {{ record.metric_name }}
                  </option>
                </select>
              </div>
              <div class="col-sm-2">
                <select class="form-control" ng-options="b for b in periods track by b" ng-model="selected_period">
                   
                </select>
              </div>
              <div class="col-sm-2">
                <input type="text" id="since" class="form-control" ng-model="metric_since" placeholder="<?php echo $this->lang->line('insights_placeholder_since') ?>">
              </div>
              <div class="col-sm-2">
                <input type="text" id="until" class="form-control" ng-model="metric_until" placeholder="<?php echo $this->lang->line('insights_placeholder_until') ?>">
              </div>
              <div class="col-sm-2">
                <button class="btn btn-success btn-block" ng-disabled="!selected_metric || graph_preloader" ng-click="load_graph()"><?php echo $this->lang->line('insights_btn_load_graph') ?> <i class="fa fa-circle-o-notch fa-spin" ng-show="graph_preloader"></i></button>
              </div>
            </div>
            <div id="chart_advanced" class="am-chart"></div>
          </div>
      </div>
    </div>
  </div>
</div>


