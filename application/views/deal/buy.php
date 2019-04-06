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
		<li><a href="<?php echo base_url('home');?>"><i class="icon-home2 position-left"></i> داشبورد </a>
		</li>
		<li><a href="<?php echo base_url('deal/buy')?>">معاملات</a>
		</li>
		<li class="active"> خرید </li>
	</ul>

</div>
<!-- Vertical form options -->
<!-- 2 columns form -->
<div class="row">
	<div class="col-md-8">
		<form action="<?php echo base_url('deal/buy')?>" method="post" enctype="multipart/form-data">
			<div class="panel panel-flat">
				<div class="panel-body">
					<div class="row">
						<div class="">
							<fieldset>
								<legend class="text-semibold"><i class="icon-cart5 position-left"></i> اطلاعات خرید</legend>
								<div class="form-group">
									<label>نام فروشنده : </label>
									<input class="form-control" onFocus="search_customer(this)" name="customer" type="text" placeholder="نام فروشنده خود را وارد کنید" autocomplete="off" required>
									<p class="text-primary" style="display:none;"></p>
								</div>
							

								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label>نام ارز: </label>
											<select class="form-control" name="money_id" id="money_id" required>
												<option value="1">دلار</option>
												<option value="2">یورو</option>
												<option value="3">یوان</option>
												<option value="4">درهم</option>
											</select>
										</div>
									</div>



									<div class="col-md-6">
										<div class="form-group">
											<label>تعداد ارز :</label>
											<input type="text" id="count" placeholder="100,000" class="form-control" autocomplete="off" required>
											<input type="hidden" name="count_money">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label>کارمزد :</label>
											<input type="text" id="wage" placeholder="100" autocomplete="off" class="form-control" required>
											<input type="hidden" name="wage">
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label>نرخ تبدیل :</label>
											<input type="text" id="convert" placeholder="100,000" autocomplete="off" class="form-control" required >
											<input type="hidden" name="convert_money">
										</div>
									</div>
								</div>
								<div class="">
									<div class="form-group">
										<label>مبلغ ریالی :</label>
										<p class="form-control" id="volume_deal"></p>
									</div>
								</div>

						</div>
						</fieldset>
					</div>

					<div class="row">
						<div class="">
							<fieldset>
								<legend class="text-semibold"><i class="icon-cash4 position-left"></i> اطلاعات بانکی</legend>
								<div class="field_wrapper2">
									<div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label>شماره شبا : </label>
													<input onkeyup="show_bank(this)" data-mask="99-999-9999999999999999999" type="text" placeholder="06-017-0000000123014682799" name="number_shaba[]" class="form-control">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>بانک :</label>
													<span class="text-primary" style="font-size:12px; display:none;">(طبق شماره شبا وارد شده بانکی پیدا نشد. نام بانک را وارد کنید)</span>
													<input type="text" name="name_bank[]" placeholder="ملت،ملی،.." class="form-control" readonly>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>مبلغ واریزی : </label>
												<input type="text" onKeyUp="amount_bank(this)" placeholder="100,000" class="form-control">
												<input type="hidden" name="amount_bank[]">
												<p class="text-danger" style ="display: none;">مبلغ وارد شده بیشتر از حجم معامله است</p>
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group input-group">
												<label>توضیحات حساب :</label>
												<input type="text" name="bank_explain[]" placeholder="توضیحات خود را وارد کنید" class="form-control">
												<span class="input-group-btn "><button type="button" style="top: 13px;" class="btn btn btn-success icon-plus3 add_button2"></button></span>
											</div>
										</div>
									</div>
								</div>
								<input type="hidden" name="deal_type" value="1">
								<div class="form-group">
									<label>ارسال عکس (برای انتخاب چند عکس لطفا دکمه ctrl را نگه دارید)</label>
									<input type="file" class="file-styled" name="deal_pic[]" multiple="multiple">
								</div>
								<div class="form-group">
									<label>توضیحات معامله :</label>
									<textarea rows="5" cols="5" name="explain" class="form-control" placeholder="توصیحات خود را وارد کنید"></textarea>
								</div>


							</fieldset>

							<div class="text-right">
								<button type="submit" name="sub" class="btn btn-primary">ثبت معامله <i class="icon-arrow-left13 position-right"></i></button>
							</div>
						</div>



					</div>
				</div>
			</div>

		</form>
	</div>
	<div class="col-md-4">
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h6 class="panel-title">Balance changes</h6>
				<div class="heading-elements">
					<span class="heading-text"><i class="icon-arrow-down22 text-danger"></i> <span class="text-semibold">- 29.4%</span></span>
				</div>
			</div>

			<div class="panel-body">
				<div class="chart-container">
					<div class="chart" id="visits" style="height: 300px;"></div>
				</div>
			</div>
		</div>

	</div>
</div>
<?php $str = '';foreach($customer as $row){$str .= "\"$row->fullname\",";}$str = trim($str , ",");?>


<script>
	var customer = [<?php echo $str; ?>];

	function search_customer( input ) {
		autocomplete( input, customer );
	}
</script>
<script type="text/javascript" src="<?php echo base_url('files/');?>assets/mine/deal.js"></script>