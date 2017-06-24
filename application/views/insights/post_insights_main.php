<?php $this->load->view('insights/post_insights_js'); ?>
<div class="row" ng-app="angapp" ng-controller="pageController"> 
  <br>
  <div class="col-sm-12" ng-bind-html="alert">
    <?php echo $this->session->flashdata('alert') ?>
  </div>
  <div class="col-sm-12 text-center" ng-show="!token_loaded || !sdk_loaded">
    <i class="fa fa-spin fa-circle-o-notch fa-2x" ></i>
  </div>
  <div class="col-sm-12" ng-cloak ng-show="token_loaded && sdk_loaded">
    <div class="x_panel" id="stories" >
        <div class="x_title">
            <h2>
              <a href="<?php echo $post->post_fb_url ?>" target="_blank"><?php echo $post->post_name ?></a> &nbsp;&nbsp;-&nbsp;&nbsp;
              <a href="https://www.facebook.com/<?php echo $post->page_fb_id ?>" target="_blank"><?php echo $post->page_name ?></a> &nbsp;&nbsp;
              <?php echo $this->lang->line('post_insights_title') ?> 
              <i class="fa fa-spin fa-circle-o-notch " ng-show="!insights_loaded"></i>
            </h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link" href=""><i class="fa fa-chevron-up"></i></a></li>
                <li><a class="close-link" href=""><i class="fa fa-close"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">                     
            <!-- content starts here -->
              <div class="row margin-btm-10 brdr-btm" ng-cloak ng-repeat="record in insights">
                  <div class="col-sm-12">
                    <h4>{{ record.title }}</h4>
                  </div>
                  <div class="col-sm-12">
                    <h5>{{ record.description }}</h5>
                  </div>
                  <div class="col-sm-12 insight_metric" >
                    {{ print_values(record.values) }}
                  </div>
                  <hr>
              </div>
            <!-- content ends here -->
        </div>
    </div>
  </div>
</div>


