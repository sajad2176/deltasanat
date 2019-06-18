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
    <li><a href="<?php echo base_url('admin/edit/').$user->id;?>">کاربران </a></li>
    <li class="active"> ویرایش کاربر </li>
  </ul>
</div>
<!-- 2 columns form -->
<form action="<?php echo base_url('admin/edit/').$user->id;?>" method="post" enctype="multipart/form-data" id="sub">
	<div class="panel panel-flat">
		<div class="panel-body">
			<div class="row">
				<div class="col-md-12">
					<fieldset>
						<legend class="text-semibold"><i class="icon-user-plus position-left"></i> ویرایش کاربر <?php echo $user->firstname." ".$user->lastname;?> </legend>
						<div class="row">

							<div class="form-group col-md-6">
								<label>نام  : </label>
								<input type="text" name="firstname" class="form-control" placeholder="First Name " value="<?php echo $user->firstname;?>" required>
							</div>
							<div class="form-group col-md-6">
								<label>  نام خانوادگی : </label>
								<input type="text" name="lastname" class="form-control" placeholder="Last Name" value="<?php echo $user->lastname; ?>" required>
							</div>
						</div>

						<div class="form-group">
							<label>نام کاربری :</label>
							<input type="text" name="username" class="form-control" placeholder="User Name" value="<?php echo $user->username;?>" required>
						</div>
						<div class="form-group">
							<label>کلمه عبور جدید : </label>
							<input type="password"  id ="pass" name ="password" class="form-control" placeholder="Password">
						</div>
						<div class="form-group">
							<label> تکرار کلمه عبور  : </label>
							<input type="password" id="repeat"  name="repeat" class="form-control" placeholder="Repeat Password">
							<p class="text-danger" style="display:none">کلمه عبور و تکرار کلمه عبور باید یکسان باشند</p>
						</div>
						
						<div class="form-group">
									<label>انتخاب آواتار جدید :</label>
									<input type="file" class="file-styled" name="picname">
								</div>

               </fieldset>
               <label >دسترسی ها:</label>
               <?php foreach($perm as $key => $rows ){ 
                  if(empty($permission)){$checked = '';}else{
                     foreach($permission as $ro){
                        if($ro->perm_id == $rows->id){
                           $checked = 'checked';
                           break;
                        }else{
                            $checked = '';
                        }
                     }
                  }
                  if($key == 0 or $key == 1 or $key == 6 or $key == 9 or $key == 14 or $key == 19 or $key == 24 or $key == 26 or $key == 31){
                     $row = '<div class="row">';
                  }else{
                     $row = '';
                  }
                  if($key == 0 or $key == 5 or $key == 8 or $key == 13 or $key == 18 or $key == 23 or $key == 25 or $key == 30 or $key == 31){
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
                  }else if($key == 26){
                     $select_all = '<div class="row"><div class="col-md-12"><label><input type="checkbox" class="option-input" id="select_settings" checked/> تظیمات</label></div></div>';
                  }else{
                     $select_all = '';
                  }if($key >= 1 and $key <= 5){
                      $select_class = 'check_users';
                  }else if($key >= 6 and $key <= 8){
                     $select_class = 'check_customer';
                  }else if($key >= 9 and $key <= 25){
                     $select_class = 'check_deal';
                  }else if($key >= 26 and $key <= 31){
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
                     <input name='perm[]' value="<?php echo $rows->id; ?>" type="checkbox" class="option-input <?php echo $select_class;?>" <?php echo $checked; ?>/>
                         <?php echo $rows->name_perm;?>
                         </label>
                         </div>
               <?php echo $row_div; 
               if($key == 0 or $key == 5 or $key == 8 or $key == 25){echo '<hr>';}
               ?>
                                    
                                       <?php } ?>
				</div>

			</div>
			<div class="text-right mt-50">
	<button type="submit" name="sub" class="btn btn-success">ویرایش کاربر <i class="icon-arrow-left13 position-right"></i></button>
		</div>
		</div>

		
	</div>
	</div>
</form>
		<!-- /2 columns form -->
<script type="text/javascript" src="<?php echo base_url('files/');?>assets/mine/valid_user.js"></script>
<script>
window.onload = function(){
   for (var i = 0 ; i< check_users.length ; i++){
     if(check_users[i].checked == false){
        select_users.checked = false;
     }
   }
   for (var j = 0 ;  j < check_customer.length ; j++){
     if(check_customer[j].checked == false){
        select_customer.checked = false;
     }
   }
   for (var z = 0 ;  z < check_deal.length ; z++){
     if(check_deal[z].checked == false){
        select_deal.checked = false;
     }
   }
   for (var z = 0 ;  z < check_settings.length ; z++){
     if(check_settings[z].checked == false){
        select_settings.checked = false;
     }
   }
}
   </script>