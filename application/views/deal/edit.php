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
		<li><a href="<?php echo base_url('home');?>"><i class="icon-home2 position-left"></i> داشبورد</a>
		</li>
		<li><a href="<?php echo base_url('deal/edit/').$deal->id;?>">معاملات</a>
		</li>
		<li class="active"> ویرایش معامله </li>
	</ul>

</div>
<!-- Vertical form options -->
<!-- 2 columns form -->
<div class="row">
	<div class="col-md-8">
		<form action="<?php echo base_url('deal/edit/').$deal->id;?>" method="post" enctype="multipart/form-data">
			<div class="panel panel-flat">
				<div class="panel-body">
					<div class="row">
						<div class="">
							<fieldset>
<?php if($deal->type == 1){$legend = 'اطلاعات خرید'; $icon = 'icon-cart5'; $txt = 'نام فروشنده : '; $readonly = 'readonly'; $style = 'style = "background-color :#e2e2e2;"';}else{$legend = 'اطلاعات فروش'; $icon = 'icon-coins'; $txt = 'نام خریدار : '; $readonly = ''; $style = '';} $money = $deal->money_id;?>
								<legend class="text-semibold"><i class="<?php echo $icon; ?> position-left"></i><?php echo $legend;?></legend>
								<div class="form-group">
									<label><?php echo $txt;?></label>
									<input class="form-control" value="<?php echo $deal->fullname;?>" name="customer" type="text" placeholder="نام مشتری خود را وارد کنید" autocomplete="off" required>
                                    <input type="hidden" name="cust_id" value="<?php echo $deal->cust_id?>">
								</div>

								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label>نام ارز : </label>
											<select class="form-control" name="money_id" required>
											<?php if($money != 10){
												foreach($unit as $units){ ?>
												<option value="<?php echo $units->id; ?>"<?php if($units->id == $money){echo 'selected';} ?>><?php echo $units->name; ;?></option>
												 <?php }?>
											<?php  } else {?>
												<option value="10" selected>ریال</option>
											<?php } ?>
											</select>
										</div>
									</div>



									<div class="col-md-6">
										<div class="form-group">
											<label>تعداد ارز :</label>
											<input type="text"  value="<?php echo number_format($deal->count_money);?>" id="count" placeholder="100,000" class="form-control" autocomplete="off" required>
											<input type="hidden" value="<?php echo $deal->count_money;?>" name="count_money">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label>کارمزد :</label>
											<input type="text"  value="<?php echo number_format($deal->wage); ?>" id="wage" placeholder="100" autocomplete="off" class="form-control" <?php echo $readonly." ".$style;?> required>
											<input type="hidden" value="<?php echo  $deal->wage; ?>" name="wage">
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label>نرخ تبدیل :</label>
											<input type="text" value="<?php echo number_format($deal->convert);?>" id="convert" placeholder="100,000" autocomplete="off" class="form-control" required >
											<input type="hidden" value="<?php echo $deal->convert;?>" name="convert">
										</div>
									</div>
								</div>
								<div class="">
									<div class="form-group">
										<label>مبلغ ریالی :</label>
										<p class="form-control" id="volume_deal"><?php echo number_format($deal->volume);?></p>
										<p id="alert" class="text-danger"></p>
									</div>
								</div>
								<input type="hidden" value = "<?php echo $deal->pay?>" name="pay" id="vpay">

						</div>
						</fieldset>
					</div>

					<div class="row">
						<div class="">
							<fieldset>
								<legend class="text-semibold"><i class="icon-cash4 position-left"></i> اطلاعات بانک مشتری</legend>
								<div class="field_edit">
              <?php if(empty($bank)){ ?>
									<div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label>شماره شبا : </label>
													<input onkeyup="show_bank(this)" data-mask="99-999-9999999999999999999" type="text" placeholder="06-017-0000000123014682799" name="send_shaba[]" class="form-control">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>بانک :</label>
													<span class="text-primary" style="font-size:12px; display:none;">(طبق شماره شبا وارد شده بانکی پیدا نشد. نام بانک را وارد کنید)</span>
													<input type="text" name="send_bank[]" placeholder="ملت،ملی،.." class="form-control">
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label> تعیین حجم : </label>
												<input type="hidden" value="0">
												<input type="text" onKeyUp="amount_bank(this)" placeholder="100,000" class="form-control">
												<input type="hidden" name="send_amount[]">
												<p class="text-danger" style ="display: none;">مبلغ وارد شده بیشتر از حجم معامله است</p>
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group input-group">
												<label>توضیحات حساب :</label>
												<input type="text" name="send_explain[]" placeholder="توضیحات خود را وارد کنید" class="form-control">
												<span class="input-group-btn "><button type="button" style="top: 13px;" class="btn btn btn-success icon-plus3 add_edit"></button></span>
											</div>
										</div>
									</div>
								</div>

								  <?php }else{
								  foreach($bank as $key => $rows){ ?>
									<div>
										<div class="row">
											<div class="col-md-6">
											
												<div class="form-group">
													<label>شماره شبا : </label>
													<input onkeyup="show_bank(this)" value="<?php echo $rows->shaba;?>" data-mask="99-999-9999999999999999999" type="text" placeholder="06-017-0000000123014682799" name="shaba[]" class="form-control">
												</div>
											</div>


                                                  
											<div class="col-md-6">
												<div class="form-group">
													<label>بانک :</label>
													<span class="text-primary" style="font-size:12px; display:none;">(طبق شماره شبا وارد شده بانکی پیدا نشد. نام بانک را وارد کنید)</span>
													<input type="text" name="name[]" value="<?php echo $rows->name; ?>" placeholder="ملت،ملی،.." class="form-control">
												</div>
											</div>
										</div>
										<input type="hidden" name="bank_id[]" value="<?php echo $rows->id;?>" >
										<input type="hidden" name="rest[]" value="<?php echo $rows->rest;?>" >
										<input type="hidden" name="rest_handle[]" value="<?php echo $rows->rest_handle;?>" >
										<input type="hidden" name="real_amount[]" value="<?php echo $rows->amount;?>" >
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label> تعیین حجم : </label>
												<input type="hidden" name="pay_bank[]" value="<?php echo $rows->pay;?>">
												<input type="text" onKeyUp="amount_bank(this)" value = "<?php echo number_format($rows->amount); ?>" placeholder="100,000" class="form-control">
												<input type="hidden" name="amount[]" value="<?php echo $rows->amount;?>">
												<p class="text-danger" style ="display: none;"></p>
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group">
												<label>توضیحات حساب :</label>
												<input type="text" name="bank_explain[]" value="<?php echo $rows->explain;?>" placeholder="توضیحات خود را وارد کنید" class="form-control">
											</div>
										</div>
									</div>

									</div>
                                  <?php } } ?>
								</div>
								<div class="row">
								<div class="col-md-12">
								<div class="form-group">
									<label for="j_created_date"> تاریخ ثبت :</label>
									<input type="text" class="form-control" name="date_deal" id="j_created_date" readonly data-mddatetimepicker="true" data-enabletimepicker="true" data-placement="bottom" value="<?php echo $date_deal." ".$deal->time_deal;?>" placeholder="Jalali Created Date">
								</div>
								</div>
								</div>
								<div class="form-group">
									<label>توضیحات معامله :</label>
									<textarea rows="5" cols="5" name="explain" class="form-control" placeholder="توصیحات خود را وارد کنید"><?php echo $deal->explain;?></textarea>
								</div>


							</fieldset>

							<div class="text-right">
								<button type="submit" name="sub" class="btn btn-primary">ویرایش معامله <i class="icon-arrow-left13 position-right"></i></button>
							</div>
						</div>



					</div>
				</div>
			</div>

		</form>
	</div>
</div>
<script type="text/javascript" src="<?php echo base_url('files/');?>assets/mine/edit_deal.js"></script>