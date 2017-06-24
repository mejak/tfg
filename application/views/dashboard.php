<div class="row">
    <div class="col-md-12"><?php echo $this->session->flashdata('alert') ?></div>    
    <div class="col-md-2 col-sm-offset-<?php echo isset($login_url) ? 8 : 10;  ?>">
       <a class="btn btn-block btn-primary" href="<?php echo base_url() ?>index.php/post/create"><i class="fa fa-pencil"></i> <?php echo $this->lang->line('dashboard_nav_new_post') ?></a>
    </div>
    <?php if(isset($login_url)){ ?>
    <div class="col-md-2">
       <a class="btn btn-block btn-primary" href="<?php echo $login_url ?>"><i class="fa fa-facebook"></i> <?php echo $this->lang->line('dashboard_nav_import_pages') ?></a>
    </div>
    <?php } ?>
    <div class="col-md-6">
        <div class="x_panel">
          <div class="x_title">
            <h2><?php echo $this->lang->line('dashboard_latest_campaigns') ?></h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div class="row">
                <?php if(count($posts)){ 
                    foreach ($posts as $key => $post){
                    ?>
                    <div class="row brdr-btm margin-btm-10">
                    <div class="col-sm-6">
                        <a href="<?php echo base_url() ?>index.php/post/detail/<?php echo $post->post_id ?>"><h4><?php echo $post->post_name ?></h4></a>
                    </div>
                    <div class="col-sm-6 text-right ">
                        <a title="<?php echo $this->lang->line('table_action_view') ?>" class="btn btn-primary" href="<?php echo base_url() ?>index.php/post/detail/<?php echo $post->post_id ?>"><i class="fa fa-folder-open"></i></a>
                        <a title="<?php echo $this->lang->line('table_action_repost') ?>" class="btn btn-primary" href="<?php echo base_url() ?>index.php/post/repost_campaign/<?php echo $post->post_id ?>"><i class="fa fa-retweet"></i></a>
                        <a title="<?php echo $this->lang->line('table_action_edit') ?>" class="btn btn-primary" href="<?php echo base_url() ?>index.php/post/edit/<?php echo $post->post_id ?>"><i class="fa fa-edit"></i></a>
                        <a title="<?php echo $this->lang->line('table_action_delete') ?>" class="btn btn-danger  delete_post" href="<?php echo base_url() ?>index.php/post/delete_campaign/<?php echo $post->post_id ?>"><i class="fa fa-trash"></i></a>
                    </div>
                    </div>
                <?php }}else {?>
                    <div class="col-sm-12 text-center">
                        <?php echo $this->lang->line('dashboard_zero_campaigns') ?>
                    </div>
                <?php }?>
            </div>
          </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="x_panel">
          <div class="x_title">
            <h2><?php echo $this->lang->line('dashboard_pages') ?></h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div class="row">
                <?php if(count($pages)){ 
                   foreach ($pages as $key => $page){
                    ?>
                    <div class="row brdr-btm margin-btm-10">
                    <div class="col-sm-6">
                        <a href="https://www.facebook.com/<?php echo $page->page_fb_id ?>" target="_blank"><h4><?php echo $page->page_name ?></h4></a>
                    </div>
                    <div class="col-sm-6 text-right ">
                       <a title="<?php echo $this->lang->line('table_action_delete') ?>" class="btn btn-danger  delete_record" href="<?php echo base_url() ?>index.php/page/delete/<?php echo $page->page_id ?>"><i class="fa fa-trash"></i></a>
                       <?php if($this->session->userdata('admin_login') === TRUE || $this->session->userdata('insights_allowed')){ ?>
                    <a class="btn btn-success" href="<?php echo base_url() ?>index.php/page/insights/<?php echo $page->page_id ?>">
                      <i class="fa fa-line-chart"></i> <?php echo $this->lang->line('table_action_insights') ?>
                    </a>
                    <?php } ?>
                    </div>
                    </div>
                <?php }}else {?>
                    <div class="col-sm-12 text-center">
                        <?php echo $this->lang->line('dashboard_zero_page') ?>
                    </div>
                <?php }?>
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
        <p><?php echo $this->lang->line('modal_delete_page') ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('modal_btn_close') ?></button>
        <a class="btn btn-danger"> <i class="fa fa-trash"></i> <?php echo $this->lang->line('modal_btn_delete') ?></a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="delete_post_model">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?php echo $this->lang->line('modal_delete_header') ?></h4>
      </div>
      <div class="modal-body">
        <p><?php echo $this->lang->line('modal_delete_campaign') ?></p>
        <div class="checkbox" id="del_fb_check">
          <label>
            <input type="checkbox" id="del_fb" value="1">
            <?php echo $this->lang->line('modal_delete_campaign_facebook') ?>
          </label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('modal_btn_close') ?></button>
        <a class="btn btn-danger" id="delete_post_btn"> <i class="fa fa-trash"></i> <?php echo $this->lang->line('modal_btn_delete') ?></a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript">
jQuery(document).ready(function($){
  $(document).on('click', '.delete_post', function(){
    $('#delete_post_btn').attr('href', $(this).attr('href'));
    $('#del_fb').prop('checked', false);
    $('#delete_post_model').modal('show');
    return false;
  });
  $(document).on('click', '#delete_post_btn', function(){
    if($('input[id="del_fb"]:checked').length > 0)
      window.location.href = $(this).attr('href') + "/true";
    else
      window.location.href = $(this).attr('href');
    return false; 
  });
});
</script>