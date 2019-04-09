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
				<div class="col-md-12">
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
                                       <?php foreach($perm as $key => $rows ){ 
                                          if($key == 0 or $key == 1 or $key == 6 or $key == 9 or $key == 14 or $key == 19 or $key == 24){
                                             $row = '<div class="row">';
                                          }else{
                                             $row = '';
                                          }
                                          if($key == 0 or $key == 5 or $key == 8 or $key == 13 or $key == 18 or $key == 23 or $key == 27){
                                             $row_div = '</div>';
                                          }else{
                                             $row_div = '';
                                          }
                                          if($key == 1){
                                             $select_all = '<div class="row"><div class="col-md-12"><label><input type="checkbox" class="option-input" id="select_users" checked /> کاربران</label></div></div>';
                                          }else if($key == 6){
                                             $select_all = '<div class="row"><div class="col-md-12"><label><input type="checkbox" class="option-input" id="select_customer" checked/> مشتریان</label></div></div>';
                                          }else if($key == 9){
                                             $select_all = '<div class="row"><div class="col-md-12"><label><input type="checkbox" class="option-input" id="select_deal" checked/> معاملات</label></div></div>';
														}else if($key == 24){
															$select_all = '<div class="row"><div class="col-md-12"><label><input type="checkbox" class="option-input" id="select_settings" checked/> تظیمات</label></div></div>';
														}else{
                                             $select_all = '';
                                          }if($key >= 1 and $key <= 5){
                                              $select_class = 'check_users';
                                          }else if($key >= 6 and $key <= 8){
                                             $select_class = 'check_customer';
                                          }else if($key >= 9 and $key <= 23){
                                             $select_class = 'check_deal';
                                          }else if($key >= 24 and $key <= 27){
															$select_class = 'check_settings';
														}else{
                                             $select_class = '';
                                          }
                                          ?>
                                        <?php
                                        echo $select_all;
                                        echo $row;
                                        ?>

                                          <div class="col-md-2">
                                                   <label>
	                                          <input name='perm[]' value="<?php echo $rows->id; ?>" type="checkbox" class="option-input <?php echo $select_class;?>" checked/>
	                                              <?php echo $rows->name_perm;?>
                                                 </label>
                                                 </div>
                                       <?php echo $row_div; 
                                       if($key == 0 or $key == 5 or $key == 8 or $key == 23){echo '<hr>';}
                                       ?>
                                    
                                       <?php } ?>							
				</div>

			</div>
			<div class="text-right mt-50">
	<button type="submit" name="sub" class="btn btn-success">افزودن کاربر <i class="icon-arrow-left13 position-right"></i></button>
		</div>
		</div>

		
	</div>
	</div>
</form>
		<!-- /2 columns form -->
<script type="text/javascript" src="<?php echo base_url('files/');?>assets/mine/valid_user.js"></script>