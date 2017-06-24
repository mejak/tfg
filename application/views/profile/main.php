<?php $this->load->view('profile/jsController') ?>
<div class="row">
  <div class="col-sm-12">
    <?php echo $this->session->flashdata('alert') ?>
  </div>
</div>
<div class="row" ng-app="fbcmpapp" ng-controller="profileController"> 
  <div class="col-sm-12" ng-bind-html="alert">
  </div>
  <div class="col-sm-12">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">
        <?php echo $user->user_name.' - '.$this->lang->line('profile_heading') ?>
        &nbsp;
        <span class="label label-<?php echo $user->user_role == USER_TYPE_ADMIN ? 'primary' : 'warning' ?>">
              <?php echo $user->user_role == USER_TYPE_ADMIN ? $this->lang->line('user_type_admin') : $this->lang->line('user_type_user') ?>
        </span>
        &nbsp;
        <span class="label label-<?php echo $user->user_status == USER_STATUS_ACTIVE ? 'success' : 'default' ?>">
              <?php echo $user->user_status == USER_STATUS_ACTIVE ? $this->lang->line('user_status_active') : $this->lang->line('user_status_disabled') ?>
        </span>
        &nbsp;
        <i class="fa fa-envelope-o"></i> <?php echo $user->user_email ?>
        &nbsp;
        <i class="fa fa-calendar-plus-o"></i> <?php echo date('M d, Y', strtotime($user->date_created)) ?>
        </h3>
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-sm-12">
            <ul class="nav nav-tabs post_tabs margin-btm-20" role="tablist">
              <li role="presentation" class="active"><a href="#accounts" aria-controls="accounts" role="tab" data-toggle="tab"><?php echo $this->lang->line('profile_tab_accounts') ?> <span class="badge" ng-cloak>{{ accounts.records.length }}</span></a></li>
              <li role="presentation"><a href="#nodes" aria-controls="nodes" role="tab" data-toggle="tab"><?php echo $this->lang->line('profile_tab_pages') ?> <span class="badge" ng-cloak>{{ nodes.records.length }}</span></a></li>
              <li role="presentation"><a href="#posts" aria-controls="posts" role="tab" data-toggle="tab"><?php echo $this->lang->line('profile_tab_node_posts') ?> <span class="badge" ng-cloak>{{ posts.records.length }}</span></a></li>
            </ul>
            <div class="tab-content">
              <div role="tabpanel" class="tab-pane active" id="accounts">
                <?php $this->load->view('profile/accounts') ?>
              </div>
              <div role="tabpanel" class="tab-pane" id="nodes">
                <?php $this->load->view('profile/nodes') ?>
              </div>
              <div role="tabpanel" class="tab-pane" id="posts">
                <?php $this->load->view('profile/posts') ?>
              </div>
            </div>
          </div>
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
        <p><?php echo $this->lang->line('modal_delete_record') ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('modal_btn_close') ?></button>
        <a class="btn btn-danger"> <i class="fa fa-trash"></i> <?php echo $this->lang->line('modal_btn_delete') ?></a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


