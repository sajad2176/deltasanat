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

					
<form action="<?php echo base_url("customer/edit/$customer->id");?>" method="post">
						<div class="panel panel-flat">
							<div class="panel-body">
								<div class="row">
									<div class="col-md-6">
										<fieldset>
											<legend class="text-semibold"><i class="icon-reading position-left"></i> اطلاعات فردی</legend>

											<div class="form-group">
												<label>نام و نام خانوادگی: </label>
												<input type="text" name="fullname" value="<?php echo $customer->fullname;?>" class="form-control" placeholder="علی شیرازی" required>
											</div>

											<div class="form-group">
												<label>آدرس:</label>
												<input type="text" name="address" value="<?php echo $customer->address; ?>" class="form-control" placeholder="شیراز،خیابان ایمان شمالی،ساختمان...">
											</div>
											<div class="form-group">
												<label>ایمیل: </label>
												<input type="email" name="email" value="<?php echo $customer->email; ?>" class="form-control" placeholder="info@gmail.com">
											</div>
											<?php $tel = json_decode($customer->customer_tel);?>
											<div class="row field_wrapper">
												<div>
												<div class="col-md-4">
													<div class="form-group">
														<label>اطلاعات تماس:</label>
														<input type="text" name="tel_title[]" value="<?php echo $tel[0][0];?>" placeholder="عنوان" class="form-control">
													</div>
												</div>

												<div class="col-md-8">
													<div class="form-group mt-25 input-group">
													
														<input type="text" name="tel[]" value="<?php echo $tel[1][0];?>" placeholder="شماره تماس" class="form-control">
														<span class="input-group-btn">
															<button type="button" class="btn btn-success add_button">
																<span class="icon-plus3"></span>
															</button>
														</span>
													</div>
												</div>
                                                </div>
                                                <?php
                                                if(sizeof($tel[0]) > 1){
                                                    for($i = 1 ; $i < sizeof($tel[0]) ; $i++){ ?>
                                             <div>
												<div class="col-md-4">
													<div class="form-group">
														<label>اطلاعات تماس:</label>
														<input type="text" name="tel_title[]" value="<?php echo $tel[0][$i];?>" placeholder="عنوان" class="form-control">
													</div>
												</div>

												<div class="col-md-8">
													<div class="form-group mt-25 input-group">
													
														<input type="text" name="tel[]" value="<?php echo $tel[1][$i];?>" placeholder="شماره تماس" class="form-control">
														<span class="input-group-btn remove_button">
															<button type="button" class="btn btn-danger">
																<span class="icon-minus2"></span>
															</button>
														</span>
													</div>
												</div>
                                             </div>
                                                <?php }}?>


												
											</div>
											
												

										</fieldset>
									</div>
									</div>

								<div class="text-right">
									<button type="submit" name="sub" class="btn btn-success">ویرایش مشتری <i class="icon-arrow-left13 position-right"></i></button>
								</div>
							
						</div>
						</div>
					</form>








	