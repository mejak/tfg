<?php $this->load->view('header'); ?>
<div class="container body">
	<div class="main_container">
		<div class="col-md-3 left_col">
		    <div class="left_col scroll-view">
		 
		        <div class="navbar nav_title" style="border: 0;">
		            <a href="<?php echo base_url() ?>" class="site_title"><?php echo $this->config->item('site_name') ?></a>
		        </div>
		        <br>
		        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
		            <div class="menu_section">
		                <h3><?php echo $this->lang->line('nav_user_area') ?></h3>
		                <ul class="nav side-menu">
		                    <li>
		                    	<a href="<?php echo base_url() ?>index.php/home/index"><i class="fa fa-home"></i>  <?php echo $this->lang->line('nav_dashboard') ?> </a>
		                    </li>
		                    <li>
		                    	<a href="<?php echo base_url() ?>index.php/profiles/list_all"><i class="fa fa-facebook"></i>  <?php echo $this->lang->line('nav_accounts') ?> </a>
		                    </li>
		                    <li>
		                    	<a href="<?php echo base_url() ?>index.php/page/list_all"><i class="fa fa-flag"></i><?php echo $this->lang->line('nav_pages') ?></a>
		                    </li>
		                    <li>
		                    	<a href="<?php echo base_url() ?>index.php/post/post_list"><i class="fa fa-pencil"></i><?php echo $this->lang->line('nav_posts') ?></a>
		                    </li>
		                </ul>
		            </div>
		            <?php if($this->session->userdata('admin_login')){ ?>
		            <div class="menu_section">
		                <h3><?php echo $this->lang->line('nav_admin_area') ?></h3>
		                <ul class="nav side-menu">
		                    <li>
		                    	<a href="<?php echo base_url() ?>index.php/users/list_users"><i class="fa fa-users"></i>  <?php echo $this->lang->line('nav_users') ?> </a>
		                    </li>
		                    <li>
		                    	<a href="<?php echo base_url() ?>index.php/help/admin"><i class="fa fa-question-circle"></i><?php echo $this->lang->line('nav_help_admin') ?></a>
		                    </li>
		                    <li>
		                    	<a href="<?php echo base_url() ?>index.php/update/index"><i class="fa fa-download"></i><?php echo $this->lang->line('nav_update') ?></a>
		                    </li>
		                    <li>
		                    	<a href="<?php echo base_url() ?>index.php/config/update"><i class="fa fa-cog"></i><?php echo $this->lang->line('nav_app_configuration') ?></a>
		                    </li>
		                </ul>
		            </div>
		 			<?php } ?>
		        </div>
		    </div>
		</div>
		<div class="top_nav">
	        <div class="nav_menu">
	            <nav class="" role="navigation">
	                <div class="nav toggle">
	                    <a id="menu_toggle"><i class="fa fa-bars"></i></a>
	                </div>

	                <ul class="nav navbar-nav navbar-right">
	                    <li class="">
	                        <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
	                            <?php echo $this->session->userdata('user_name') ?>
	                            <span class=" fa fa-angle-down"></span>
	                        </a>
	                        <ul class="dropdown-menu dropdown-usermenu animated fadeInDown pull-right">
	                            <li>
	                                <a href="<?php echo base_url() ?>index.php/users/settings">
	                                    <span><i class="fa fa-cog pull-right"></i><?php echo $this->lang->line('nav_settings') ?></span>
	                                </a>
	                            </li>
	                            <li>
	                                <a href="<?php echo base_url() ?>index.php/help/user"><i class="fa fa-question pull-right"></i><?php echo $this->lang->line('nav_help') ?></a>
	                            </li>
	                            <li><a href="<?php echo base_url() ?>index.php/admin/logout"><i class="fa fa-sign-out pull-right"></i><?php echo $this->lang->line('nav_logout') ?></a>
	                            </li>
	                        </ul>
	                    </li>
	                </ul>
	            </nav>
	        </div>
	    </div>
		<div class="right_col" role="main">
			<div class="clearfix"></div>
			<?php  $this->load->view($view); ?>
		</div>
    </div>
</div>
<!-- end container -->
<div class="clearfix"></div>
<?php $this->load->view('footer'); ?>