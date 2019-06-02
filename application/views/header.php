<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>
		<?php echo $title; ?>
	</title>

	<!-- Global stylesheets -->
	<!-- <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css"> -->
	<link href="<?php echo base_url('files/');?>assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url('files/');?>assets/css/bootstrap.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url('files/');?>assets/css/rtl.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url('files/');?>assets/css/core.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url('files/');?>assets/css/components.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url('files/');?>assets/css/colors.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="<?php echo base_url('files/'); ?>assets/bootstrap-PersianDateTimePicker/jquery.Bootstrap-PersianDateTimePicker.min.css">
	<script type="text/javascript" src="<?php echo base_url('files/');?>Numeral/min/numeral.min.js"></script>

	<!-- /global stylesheets -->
</head>

<body>


	<div class="navbar navbar-default header-highlight navbar-fixed-top">
		<div class="navbar-header">
			<a class="navbar-brand" href="https://novin-network.com" target="_blank"><img src="<?php echo base_url('files/');?>assets/images/logo_light.png" alt=""></a>

			<ul class="nav navbar-nav visible-xs-block">
				<li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a>
				</li>
				<li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a>
				</li>
			</ul>
		</div>

		<div class="navbar-collapse collapse" id="navbar-mobile">
			<ul class="nav navbar-nav">
				<li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-paragraph-justify3"></i></a>
				</li>


			</ul>

<?php if($this->session->has_userdata('add_buy') and $this->session->userdata('add_buy') == TRUE){?><p class="navbar-text"><a href="<?php echo base_url('deal/buy');?>" class="label bg-warning">خرید</a></p><?php } ?>
<?php if($this->session->has_userdata('add_sell') and $this->session->userdata('add_sell') == TRUE){?><p class="navbar-text"><a href="<?php echo base_url('deal/sell');?>" class="label bg-primary">فروش</a></p><?php } ?>
			<ul class="nav navbar-nav navbar-right">




				<li class="dropdown dropdown-user">
					<a class="dropdown-toggle" data-toggle="dropdown">
						<img src="<?php echo base_url('uploads/avatar/').$this->session->userdata('pic_name');?>" alt="">

						<span><?php echo $this->session->userdata('name');?></span>

						<i class="caret"></i>
					</a>
				


					<ul class="dropdown-menu dropdown-menu-right">
					<?php if($this->session->has_userdata('edit_user')){?>
						<li><a href="<?php echo base_url('admin/edit/').$this->session->userdata('id');?>"><i class="icon-user-plus"></i> ویرایش حساب کاربری</a>
						</li>
					<?php } ?>
						<li><a onClick="document.getElementById('modal_logout').style.display='block'" style="width:auto;"><i class="icon-switch2"></i> خروج از حساب کاربری </a>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</div>


	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main sidebar -->
			<div class="sidebar sidebar-main sidebar-fixed">
				<div class="sidebar-content">

					<!-- User menu -->
					<div class="sidebar-user">
						<div class="category-content">
							<div class="media">

								<a class="media-left"><img src="<?php echo base_url('uploads/avatar/').$this->session->userdata('pic_name');?>" class="img-circle img-sm" alt=""></a>

								<div class="media-body">

									<span class="media-heading text-semibold display-inline-block mt-10">
										<?php echo $this->session->userdata('name');?>
				
									</span>

								</div>


							</div>
						</div>
					</div>
					<!-- /user menu -->


					<!-- Main navigation -->
					<div class="sidebar-category sidebar-category-visible">
						<div class="category-content no-padding">
							<ul class="navigation navigation-main navigation-accordion">
	
<?php if($this->session->has_userdata('see_dashbord')){ ?><li class="<?php if($active == 'dashbord'){echo 'active';}?>"><a href="<?php echo base_url('home'); ?>"><i class="icon-home4"></i> <span>داشبورد</span></a></li> <?php }?>
                           
						   <?php if($this->session->has_userdata('see_users')){?>
							<li>
									<a href=""><i class="icon-users4 <?php if($active == 'admin'){echo 'active';} ?>"></i> <span>کاربران</span></a>
									<ul>
										<li class="<?php if($active_sub == 'admin_archive'){echo 'active';} ?>"><a href="<?php echo base_url('admin/archive') ?>">آرشیو کاربران</a></li>
					
<?php if($this->session->has_userdata('add_user')){?><li class="<?php if($active_sub == 'admin_add'){echo 'active';} ?>"><a href="<?php echo base_url('admin/add') ?>">افزودن کاربر</a></li><?php }?>
									</ul>
								</li>
							<?php } ?>
                              <?php if($this->session->has_userdata('see_customer')){?>
								<li>
									<a href=""><i class="icon-user-tie"></i> <span>مشتریان</span></a>
									<ul class="<?php if($active == 'customer'){echo 'active';} ?>">
										<li class="<?php if($active_sub == 'customer_archive'){echo 'active';} ?>"><a href="<?php echo base_url('customer/archive/')?>">آرشیو مشتریان</a></li>
<?php if($this->session->has_userdata('add_customer')){?><li class="<?php if($active_sub == 'customer_add'){echo 'active';} ?>"><a href="<?php echo base_url('customer/add') ?>">افزودن مشتری </a></li><?php } ?>
									</ul>
								</li>
							  <?php } ?>
							  <?php if($this->session->has_userdata('see_deal')){?>
								<li>
									<a href=""><i class="icon-share4 <?php if($active == 'deal'){echo 'active';} ?>"></i> <span>معاملات</span></a>
									<ul>
										<li class="<?php if($active_sub == 'deal_archive'){echo 'active';} ?>"><a href="<?php echo base_url('deal/archive/')?>" id="layout1">آرشیو معاملات</a></li>
										<li class="<?php if($active_sub == 'deal_sheet'){echo 'active';} ?>"><a href="<?php echo base_url('deal/worksheet/')?>" id="layout1"> کاربرگ معاملات</a></li>
<?php if($this->session->has_userdata('add_buy')){?><li class="<?php if($active_sub == 'deal_buy'){echo 'active';} ?>"><a href="<?php echo base_url('deal/buy/')?>" id="layout2">افزودن خرید</a></li><?php }?>
<?php if($this->session->has_userdata('add_sell')){?><li class="<?php if($active_sub == 'deal_sell'){echo 'active';} ?>"><a href="<?php echo base_url('deal/sell/')?>" id="layout2">افزودن فروش</a></li><?php } ?>
									</ul>
								</li>
							  <?php } ?>
							  <?php if($this->session->has_userdata('see_settings')){?>
							    <li>
									<a href=""><i class="icon-transmission <?php if($active == 'settings'){echo 'active';} ?>"></i> <span>تنظیمات</span></a>
									<ul>
<?php if($this->session->has_userdata('set_unit')){?><li class="<?php if($active_sub == 'set_unit'){echo 'active';} ?>"><a href="<?php echo base_url('settings/set_unit')?>">تبدیل ارز</a></li><?php } ?>
<?php if($this->session->has_userdata('set_primitive')){?><li class="<?php if($active_sub == 'primitive_unit'){echo 'active';} ?>"><a href="<?php echo base_url('settings/primitive_unit')?>">ارز اولیه</a></li><?php } ?>
<?php if($this->session->has_userdata('rest_unit')){?><li class="<?php if($active_sub == 'rest_unit'){echo 'active';} ?>"><a href="<?php echo base_url('settings/rest_unit')?>"> مانده حساب ریالی </a></li><?php } ?>
<?php if($this->session->has_userdata('turnover')){?><li class="<?php if($active_sub == 'turnover'){echo 'active';} ?>"><a href="<?php echo base_url('settings/turnover')?>"> گردش حساب </a></li><?php } ?>
							  <?php if($this->session->has_userdata('add_unit')){ ?><li class="<?php if($active_sub == 'add_unit'){echo 'active';} ?>"><a href="<?php echo base_url('settings/add_unit')?>"> افزودن ارز </a></li> <?php } ?>
										
									</ul>
								</li>
							  <?php } ?>
								<!-- /main -->




							</ul>
						</div>
					</div>
					<!-- /main navigation -->

				</div>
			</div>
			<!-- /main sidebar -->
			<div class="content-wrapper">

				<!-- Content area -->
				<div class="content mt-50">