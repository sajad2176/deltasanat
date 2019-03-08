<?php if($this->session->has_userdata('msg')){
$msg = $this->session->userdata('msg');?>
<div class="alert bg-<?php echo $msg[1];?> alert-styled-left">
										<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
										<?php echo $msg[0];?>
								    </div>
<?php }?>
<div class="breadcrumb-line breadcrumb-line-component mb-20">
						<ul class="breadcrumb">
							<li><a href="<?php echo base_url('home');?>"><i class="icon-home2 position-left"></i>داشبورد</a></li>
							<li><a href=""> مشتریان</a></li>
							<li class="active"><a href="<?php echo base_url('customer/add/')?>">افزودن مشتری جدید</a></li>
						</ul>
					</div>

					
<form action="<?php echo base_url('customer/add');?>" method="post">
						<div class="panel panel-flat">
							<div class="panel-body">
								<div class="row">
									<div class="col-md-6">
										<fieldset>
											<legend class="text-semibold"><i class="icon-reading position-left"></i> اطلاعات فردی</legend>

											<div class="form-group">
												<label>نام و نام خانوادگی: </label>
												<input type="text" name="fullname" class="form-control" placeholder="علی شیرازی" required>
											</div>

											<div class="form-group">
												<label>آدرس:</label>
												<input type="text" name="address" class="form-control" placeholder="شیراز،خیابان ایمان شمالی،ساختمان...">
											</div>
											<div class="form-group">
												<label>ایمیل: </label>
												<input type="email" name="email" class="form-control" placeholder="info@gmail.com">
											</div>
											<div class="row field_wrapper">
												<div>
												<div class="col-md-4">
													<div class="form-group">
														<label>اطلاعات تماس:</label>
														<input type="text" name="tel_title[]" placeholder="عنوان" class="form-control">
													</div>
												</div>

												<div class="col-md-8">
													<div class="form-group mt-25 input-group">
													
														<input type="text" name="tel[]" placeholder="شماره تماس" class="form-control">
														<span class="input-group-btn">
															<button type="button" class="btn btn-success add_button">
																<span class="icon-plus3"></span>
															</button>
														</span>
													</div>
												</div>
												
												</div>
											</div>
											
												

										</fieldset>
									</div>
									</div>
<!--
								<div class="row">
									<div class="col-md-6">
										<fieldset>
						                	<legend class="text-semibold"><i class="icon-cash4 position-left"></i> اطلاعات بانکی</legend>
										<div class="field_wrapper2">
											<div>
											<div class="row">
												<div class="col-md-4">
													<div class="form-group">
														<label>نام صاحب حساب: </label>
														<input type="text" name="name_acount[]" placeholder="نام ونام خانوادگی" class="form-control">
													</div>
												</div>
											

											
												<div class="col-md-2">
													<div class="form-group">
														<label>بانک:</label>
														<input type="text" name="name_bank[]" placeholder="ملت،ملی،.." class="form-control">
													</div>
												</div>

												<div class="col-md-6">
													<div class="form-group">
														<label>شماره حساب:</label>
														<input type="text" name="number_acount[]" placeholder=" 0000000123014682799" class="form-control">
													</div>
												</div>
											
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label>شماره کارت: </label>
														<input maxlength="16" name='number_card[]' type="text" data-mask="9999-9999-9999-9999" placeholder="____-____-____-____" class="form-control">
													</div>
												</div>

												<div class="col-md-6">
													<div class="form-group input-group">
														<label>شماره شبا:</label>
														<input type="text" name = 'number_shaba[]' data-mask="aa-99-999-9999999999999999999" placeholder="IR-06-017-0000000123014682799" class="form-control">
														<span class="input-group-btn "><button type="button" style="top: 14px;" class="btn btn btn-success icon-plus3 add_button2"></button></span>
													</div>
												</div>
											</div>

									
										</div>
												
											</div>
										</fieldset>
									</div>
								</div>
-->

								<div class="text-right">
									<button type="submit" name="sub" class="btn btn-success">ثبت مشتری <i class="icon-arrow-left13 position-right"></i></button>
								</div>
							
						</div>
						</div>
					</form>








	