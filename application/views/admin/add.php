<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if($this->session->has_userdata('msg')){
$msg = $this->session->userdata('msg');?>
<div class="alert bg-<?php echo $msg[1];?> alert-styled-left">
										<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
										<?php echo $msg[0];?>
								    </div>
<?php }?>
<div class="breadcrumb-line breadcrumb-line-component mb-20">
  <ul class="breadcrumb">
    <li><a href="<?php echo base_url('home');?>"><i class="icon-home2 position-left"></i> داشبورد </a></li>
    <li><a href="<?php echo base_url('admin/add')?>">کاربران </a></li>
    <li class="active">افزودن کاربر </li>
  </ul>
</div>
<!-- 2 columns form -->
<form action="<?php echo base_url('admin/add');?>" method="post" enctype="multipart/form-data" id="sub">
	<div class="panel panel-flat">
		<div class="panel-body">
			<div class="row">
				<div class="col-md-6">
					<fieldset>
						<legend class="text-semibold"><i class="icon-user-plus position-left"></i> افزودن کاربر</legend>
						<div class="row">

							<div class="form-group col-md-6">
								<label>نام  : </label>
								<input type="text" name="firstname" class="form-control" placeholder="First Name " required>
							</div>
							<div class="form-group col-md-6">
								<label>  نام خانوادگی : </label>
								<input type="text" name="lastname" class="form-control" placeholder="Last Name" required>
							</div>
						</div>

						<div class="form-group">
							<label>نام کاربری :</label>
							<input type="text" name="username" class="form-control" placeholder="User Name" required>
						</div>
						<div class="form-group">
							<label>کلمه عبور : </label>
							<input type="password"  id ="pass" name ="password" class="form-control" placeholder="Password" required>
						</div>
						<div class="form-group">
							<label> تکرار کلمه عبور  : </label>
							<input type="password" id="repeat" name="repeat" class="form-control" placeholder="Repeat Password" required>
							<p class="text-danger" style="display:none">کلمه عبور و تکرار کلمه عبور باید یکسان باشند</p>
						</div>
						
						<div class="form-group">
									<label> انتخاب آواتار :</label>
									<input type="file" class="file-styled" name="picname">
								</div>
											
										<label >دسترسی ها:</label>
												<div class="row">
										<div class="checkbox col-md-3">
											<label>
												<input type="checkbox" class="styled" checked="checked">
												مدیر کل
											</label>
										</div>

										<div class="checkbox col-md-3 mt-5">
											<label>
												<input type="checkbox" class="styled">
												ادیتر
											</label>
										</div>

										<div class="checkbox col-md-3 mt-5">
											<label>
												<input type="checkbox" class="styled">
												ویزیتور
											</label>
										</div>
												<div class="checkbox col-md-3 mt-5">
											<label>
												<input type="checkbox" class="styled">
												Disabled styled
											</label>
										</div>
									</div>
												
				</div>

			</div>
			<div class="text-right">
	<button type="submit" name="sub" class="btn btn-success">افزودن کاربر <i class="icon-arrow-left13 position-right"></i></button>
		</div>
		</div>

		
	</div>
	</div>
</form>
		<!-- /2 columns form -->
<script type="text/javascript" src="<?php echo base_url('files/');?>assets/mine/valid_user.js"></script>