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
		<li class="active"> افزودن خرید </li>
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
									<input class="form-control" onFocus="search_customer(this)" name="customer" type="text" placeholder="نام فروشنده خود را وارد کنید" autocomplete="off" required autofocus>
									<p class="text-primary" style="display:none;"></p>
								</div>
							

								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label>نام ارز : </label>
											<select class="form-control" name="money_id" required>
											<?php foreach($unit as $units){ ?>
												<option value="<?php echo $units->id;?>"><?php echo $units->name;?></option>
											<?php } ?>
											</select>
										</div>
									</div>



									<div class="col-md-6">
										<div class="form-group">
											<label>تعداد ارز : </label>
											<input type="text" id="count" placeholder="100,000" class="form-control" autocomplete="off" required>
											<input type="hidden" name="count_money" value = "0">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label>کارمزد :</label>
											<p id="wage" class="form-control" style="background-color:#e2e2e2;">0</p>
											<input type="hidden" name="wage" value = "0">
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label>نرخ تبدیل :</label>
											<input type="text" id="convert" placeholder="100,000" autocomplete="off" class="form-control" required >
											<input type="hidden" name="convert" value ="0">
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
								<legend class="text-semibold"><i class="icon-cash4 position-left"></i> اطلاعات بانک مشتری </legend>
								<div class="field_wrapper2">
									<div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label>شماره شبا : </label>
													<input type="text" name="shaba[]" onkeyup="show_bank(this)" data-mask="99-999-9999999999999999999"  placeholder="06-017-0000000123014682799"  class="form-control">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>بانک :</label>
													<span class="text-primary" style="font-size:12px; display:none;">(طبق شماره شبا وارد شده بانکی پیدا نشد. نام بانک را وارد کنید)</span>
													<input type="text" name="name[]" placeholder="ملت،ملی،.." class="form-control">
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>تعیین حجم  : </label>
												<input type="text" onKeyUp="amount_bank(this)" placeholder="100,000" class="form-control">
												<input type="hidden" name="amount[]">
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
								<input type="hidden" name="type" value="1">
								<div class="row">
								<div class="col-md-6">
								<div class="form-group">
									<label for="j_created_date"> تاریخ ثبت :</label>
									<input type="text" class="form-control" name="date_deal" id="j_created_date" readonly data-mddatetimepicker="true" data-enabletimepicker="true" data-placement="bottom" value="<?php echo $date;?>" placeholder="Jalali Created Date">
								</div>
								</div>
								<div class="col-md-6">
								<div class="form-group">
									<label>ارسال عکس (برای انتخاب چند عکس لطفا دکمه ctrl را نگه دارید)</label>
									<input type="file" class="file-styled" name="deal_pic[]" multiple="multiple">
								</div>
								</div>
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
			<div class="panel-body">
				<div style="padding-right: 0px" class="panel-heading">
					<h6 class="panel-title rightbox display-inline-block mt-10"> آمار مشتری :  <span id="name_customer"></span></h6>
					<h6 class="lefttbox " ><?php echo substr($date , 0 , 10);?></h6>
				</div>

				<div class="table-responsive">
					<table class="table text-nowrap">
						<thead>
							<tr>
								<th width="50%">ارز</th>
								<th width="50%">مانده</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<div class="media-body">
										<div class="media-heading">
											<h5 class="letter-icon-title">دلار</h5>
										</div>
									</div>
								</td>
								<td>
									<h6 class="text-semibold no-margin " id="dollar">-</h6>
								</td>
							</tr>
							<tr>
							</tr>
							<tr>
								<td>
									<div class="media-body">
										<div class="media-heading">
											<h5 class="letter-icon-title">یورو</h5>
										</div>	
									</div>
								</td>
								<td>
									<h6 class=" text-semibold no-margin" id="euro">-</h6>
								</td>
							</tr>
							<tr>
								<td>
								
									<div class="media-body">
										<div class="media-heading">
											<h5  class="letter-icon-title">یوان</h5>
										</div>
									</div>
								</td>
								<td>
									<h6 class=" text-semibold no-margin" id="yuan">-</h6>
								</td>
							</tr>
							<tr>
								<td>
									<div class="media-body">
										<div class="media-heading">
											<h5 class="letter-icon-title">درهم</h5>
										</div>
									</div>
								</td>
								<td>
									<h6 class="text-semibold no-margin" id="derham">-</h6>
								</td>
							</tr>
							
						</tbody>
					</table>
				</div>
			</div>
		</div>
			<div class="panel panel-flat">
			<div class="panel-body">
				<div style="padding-right: 0px" class="panel-heading">
					<h6 class="panel-title rightbox display-inline-block mt-10"> مانده کلی :  <span id="name_customer"></span></h6>
					
				</div>

				<div class="table-responsive">
					<table class="table text-nowrap">
						<thead>
							<tr>
								<th width="50%">ارز</th>
								<th width="50%">مانده</th>
							</tr>
						</thead>
						<tbody>
						<tr>
							<td>
								<div class="media-body">
									<div class="media-heading">
										<h5 class="letter-icon-title">ریال</h5>
									</div>
								</div>
							</td>
							<td>
								<h6 class="text-semibold no-margin" id="rial">-</h6>
							</td>
						</tr>	
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	
<?php $str = '';foreach($customer as $row){$str .= "\"$row->fullname\",";}$str = trim($str , ",");?>

<script type="text/javascript" src="<?php echo base_url('files/');?>assets/mine/deal.js"></script>
<script>
	var customer = [<?php echo $str; ?>];
	function search_customer( input ) {
		autocomplete( input, customer );
	}

	function showHistory(text){
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
		// alert(xhttp.responseText);
		var result = JSON.parse( xhttp.responseText );
	    showCustResult( result , text );
    }
  };
  xhttp.open("POST", "<?php echo base_url('deal/customer_history/')?>" , true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send('text_search='+text);
	  
	}

</script>
