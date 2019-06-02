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
		<li><a href="<?php echo base_url('deal/sell')?>">معاملات</a>
		</li>
		<li class="active"> افزودن فروش </li>
	</ul>

</div>
<!-- Vertical form options -->
<!-- 2 columns form -->
<div class="row">
	<div class="col-md-8">
		<form action="<?php echo base_url('deal/buy')?>" method="post">
			<div class="panel panel-flat">
				<div class="panel-body">
					<div class="row">
					<div class="">
							<fieldset>
								<legend class="text-semibold"><i class="icon-coins position-left"></i> اطلاعات فروش</legend>
								<div class="form-group">
									<label>نام خریدار : </label>
									<input class="form-control" onFocus="search_customer(this)" name="customer" type="text" placeholder="نام خریدار خود را وارد کنید" autocomplete="off" required autofocus>
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
											<input type="text"  id="wage" placeholder="100" autocomplete="off" class="form-control" value = "0" required >
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
								<input type="hidden" name="type" value="2">
								<div class="row">
								<div class="col-md-12">
								<div class="form-group">
									<label for="j_created_date"> تاریخ ثبت :</label>
									<input type="text" class="form-control" name="date_deal" id="j_created_date" readonly data-mddatetimepicker="true" data-enabletimepicker="true" data-placement="bottom" value="<?php echo $date;?>" placeholder="Jalali Created Date">
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
						<?php foreach($unit as $rows){ ?>
							<tr>
								<td>
									<div class="media-body">
										<div class="media-heading">
											<h5 class="letter-icon-title"><?php echo $rows->name;?></h5>
										</div>
									</div>
								</td>
								<td>
									<h6 class="text-semibold no-margin lright setDefault" id="<?php echo $rows->id;?>">0</h6>
								</td>
							</tr>
            <?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
			<div class="panel panel-flat">
			<div class="panel-body">
				<div style="padding-right: 0px" class="panel-heading">
					<h6 class="panel-title rightbox display-inline-block mt-10"> مانده ریالی : </h6>
					
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
								<h6 class="text-semibold no-margin lright setDefault" id="rial">0</h6>
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