<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if ( $this->session->has_userdata( 'msg' ) ) {
	$msg = $this->session->userdata( 'msg' );
	?>
	<div class="alert bg-<?php echo $msg[1];?> alert-styled-left">
		<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
		<?php echo $msg[0];?>
	</div>
	<?php }?>
	<div class="breadcrumb-line breadcrumb-line-component mb-20">
		<ul class="breadcrumb">
			<li><a href="<?php echo base_url('home');?>"><i class="icon-home2 position-left"></i> داشبورد</a>
			</li>
			<li><a href="<?php echo base_url(" deal/handle/ ").$deal->id; ?>">معامله</a>
			</li>
			<li class="active">هماهنگی </li>
		</ul>

	</div>


	<div class="panel panel-flat">
		<div class="panel-body">
			<div class="row">
				<div class="">
					<fieldset>
						<legend class="text-semibold"><i class="icon-share4 position-left"></i> اطلاعات معامله</legend>
						<div class="row">
							<div class="col-sm-3">
								<div class="form-group">
									<label>نام مشتری : </label>
									<p class="form-control">
										<a href="<?php echo base_url('deal/handle_profile/').$deal->cust_id?>">	<?php echo $deal->fullname;?></a>
									</p>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label>نوع معامله : </label>
									<p class="form-control">
										<?php if($deal->type_deal == 1){echo 'خرید';}else{echo 'فروش';}?>
									</p>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label>تعداد ارز : </label>
									<p class="form-control">
										<?php echo number_format($deal->count_money)." ".$deal->name; ?>
									</p>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label>حجم معامله : </label>
									<p class="form-control" id="volume_deal">
										<?php echo number_format($deal->volume_deal) . " ریـال ";?>
									</p>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-3">
								<div class="form-group">
									<label>حجم پرداخت شده : </label>
									<p class="form-control <?php if($deal->volume_pay > $deal->volume_deal ){echo 'text-danger';}?>">
										<?php echo number_format($deal->volume_pay) . " ریـال ";?>
									</p>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label>حجم قابل پرداخت : </label>
									<p class="form-control <?php if($deal->volume_rest < 0 ){echo 'text-danger';}?>">
										<?php echo number_format($deal->volume_rest). " ریـال ";?>
									</p>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label>حجم هماهنگ شده : </label>
									<p class="form-control">
										<?php echo number_format($deal->vh). " ریـال ";?>
									</p>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label>مانده هماهنگ شده: </label>
									<p class="form-control <?php if($deal->vr < 0){echo 'text-danger';}?>">
										<?php echo number_format($deal->vr)." ریـال ";?>
									</p>
								</div>
							</div>
						</div>
						<?php if($deal->explain != ''){ ?>
						<div class="form-group">
							<label>توضیحات معامله: </label>
							<p class="form-control">
								<?php echo $deal->explain; ?>
							</p>
						</div>
						<?php } ?>
					</fieldset>
				</div>
			</div>
		</div>
	</div>

	<div>
		<div class="panel panel-flat">
			<div class="panel-body">
<?php if($this->session->has_userdata('add_bank') and $this->session->userdata('add_bank') == TRUE){ ?><a class="btn btn-success float-btn-left" href="#add_bank_modal" data-toggle="modal">افزودن بانک</a><?php }?>
				<legend class="text-semibold"><i class="icon-credit-card position-left"></i> اطلاعات بانکی </legend>
				<table class="table datatable-basic">
					<thead>
						<tr>
							<th width="4%">ردیف</th>
							<th width="10%">نام بانک</th>
							<th width="19%">شماره شبا</th>
							<th width="15%">حجم تعیین شده</th>
							<th width="15%">حجم واریز شده</th>
							<th width="15%">توضیحات</th>
							<th width="10%">وضعیت</th>
							<th width="6%" class="text-center">ابزار</th>
						</tr>
					</thead>
					<tbody>
						<?php if(sizeof($bank) == 0){?>
						<tr>
							<td colspan="8" class="text-center p-20">موردی یافت نشد</td>
						</tr>
						<?php }else{
					 foreach($bank as $key => $rows){ ?>
						<tr>
							<td>
								<?php echo $key+1; ?>
							</td>
							<td>
								<?php echo $rows->name_bank;?>
							</td>
							<td>
								<?php echo $rows->number_shaba;?>
							</td>
							<td>
								<?php echo number_format($rows->amount). " ریـال  "; ?>
							</td>
							<td class="<?php if($rows->pay > $rows->amount){echo 'text-danger';}?>">
								<?php echo number_format($rows->pay)." ریـال "; ?>
							</td>
							<td>
								<?php echo $rows->explain; ?>
							</td>
							<?php if($rows->active == 1){$class="success";$txt = 'فعال'; $act = 0;}else{$class = "danger"; $txt = 'غیرفعال'; $act = 1;} ?>
					 <td><?php if($this->session->has_userdata('active_bank') and $this->session->userdata('active_bank') == TRUE){?><a href="<?php echo base_url('deal/active/').$deal->id."/".$rows->id."/".$act; ?>"><span class="label label-<?php echo $class; ?>"><?php echo $txt;?></span><?php } ?></td></a>
								<td class="text-center">
									<ul class="icons-list">
					 <?php if($this->session->has_userdata('edit_bank') and $this->session->userdata('edit_bank') == TRUE){?><li title="ویرایش بانک" class="text-primary"><a data-toggle="modal" href="#edit_bank_modal"><i onclick = "edit_bank(<?php echo $rows->id;?>)" class="icon-credit-card"></i></li><?php } ?>
									</ul>
						</td>
					</tr>
					<tr>
					<?php  }}?>
			</table>
			<!-- add bank modal -->
			<div id="add_bank_modal" class="modal fade">
				<div class="modal-dialog" style="width:750px;">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h5 class="modal-title text-center">افزودن بانک</h5>

						</div>
						<hr>
						<form action="<?php echo base_url('deal/add_bank/').$deal->id;?>" method="post">
							<div class="modal-body">
								<div class="field_wrapper2">
									<div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label>شماره شبا : </label>
													<input onkeyup="show_bank(this)" data-mask="99-999-9999999999999999999" type="text" placeholder="06-017-0000000123014682799" name="number_shaba" class="form-control" required>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>بانک :</label>
													<span class="text-primary" style="font-size:12px; display:none;">(طبق شماره شبا وارد شده بانکی پیدا نشد. نام بانک را وارد کنید)</span>
													<input type="text" name="name_bank" placeholder="ملت،ملی،.." class="form-control" readonly>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>مبلغ معامله : </label>
												<input type="hidden" value ='0'>
												<input type="text" onkeyup = "ambank(this)" placeholder="100,000" class="form-control" required>
												<input type="hidden" name="amount_bank">
												<p class="text-danger" style="display:none;"></p>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group input-group">
												<label>توضیحات حساب :</label>
												<input type="text" name="bank_explain" placeholder="توضیحات خود را وارد کنید" class="form-control">
												<span class="input-group-btn "><button type="submit" name="sub" style="top: 13px;" class="btn btn btn-success">ذخیره</button></span>
											</div>
										</div>
									</div>
								</div>
						</form>
						</div>
					</div>
				</div>
				<!-- /add bank modal -->

			</div>
		</div>
		</div>
	</div>
<?php if($this->session->has_userdata('add_handle') or $this->session->userdata('add_handle') == TRUE){?>
<form action="<?php echo base_url('deal/handle/').$deal->id;?>" method="post">
	<div class="panel panel-flat">
		<div class="panel-body">
			<div class="row field_wrapper3">
				<div>
					<legend class="text-semibold"><i class="icon-address-book position-left"></i> افزودن هماهنگی</legend>
					<div class="col-md-4">
						<div class="form-group">
							<label>نام مشتری :</label>
							<input class="form-control" onFocus="search_customer(this)" name="customer[]" type="text" placeholder="نام مشتری خود را وارد کنید" autocomplete="off" required>
							<p class="text-primary" style="display:none; position:absolute;font-size:12px;"></p>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>انتخاب  حساب:</label>
							<select class="form-control" name="bank_id[]" required>
                        <?php if(sizeof($select) == 0){ ?>
                                                <option value = '0'>شماره حسابی ثبت نشده است</option>
											  <?php }else{
												  foreach($select as $selects){ ?>
												<option value="<?php echo $selects->id;?>"><?php echo $selects->number_shaba. " | ". $selects->name_bank;?></option>
                          <?php }} ?>
											</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group input-group">
							<label>مبلغ هماهنگی :</label>
							<input type="text" onkeyup="volume_handle(this)" placeholder="111,000,000" class="form-control" required>
							<input type="hidden" name="volume_handle[]">
							<p class="text-danger" style="display:none;position:absolute;top:65px;"></p>
							<span class="input-group-btn">
							<button type="button" class="btn btn-success add_button3 mt-25">
								<span class="icon-plus3"></span>
							</button>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="text-right">
				<button type="submit" name="sub" class="btn btn-primary mt-25">ثبت هماهنگی <i class="icon-arrow-left13 position-right"></i></button>
			</div>
		</div>
	</div>
	<div>
	</form>
	<?php } ?>
	<div class="panel panel-flat">
		<div class="panel-body">
			<legend class="text-semibold"><i class="icon-notebook position-left"></i> اطلاعات هماهنگی</legend>
			<table class="table datatable-basic">
				<thead>
					<tr>
						<th width="4%">ردیف</th>
						<th width="12%">نام مشتری</th>
						<th width="13%">حجم هماهنگ شده</th>
						<th width="13%">حجم پرداخت شده</th>
						<th width="13%">حجم باقی مانده</th>
						<th width="13%">اطلاعات حساب</th>
						<th width="10%">تاریخ ثبت</th>
						<th width="10%">آخرین تغییر</th>
						<th width="12%" class="text-center">ابزار</th>
					</tr>
				</thead>
				<tbody>
				<?php if(sizeof($handle) == 0){ ?>
				<tr><td colspan="8" class="text-center p-20">موردی یافت نشد</td></tr>
				<?php } else{ 
				 foreach($handle as $key => $row){ ?>
					<tr>
						<td><?php echo $key+1; ?></td>
						<td><?php echo $row->fullname; ?></td>
						<td><?php echo number_format($row->volume_handle); ?></td>
						<td class= "<?php if($row->handle_pay > $row->volume_handle){echo 'text-danger';}?>"><?php echo number_format($row->handle_pay); ?></td>
						<td class="<?php if($row->handle_rest < 0){echo 'text-danger';}?>"><?php echo number_format($row->handle_rest); ?></td>
						<td><?php echo $row->number_shaba."<br>".$row->name_bank; ?></td>
						<td><?php echo $row->date_handle."</br>".$row->time_handle; ?></td>
						<td><?php if($row->date_modified == ''){echo 'ثبت نشده است';}else {echo $row->date_modified;} ?></td>
						</td>
						<td class="text-center">
								<ul class="icons-list">
        <?php if($row->handle_rest > 0){?>
<?php if($this->session->has_userdata('pay_all') and $this->session->userdata('pay_all') == TRUE){ ?><li title="پرداخت کامل" class="text-success"><a data-toggle="modal" href="#modal_theme_success"><i onclick="pay_all(<?php echo $row->id;?> , <?php echo $row->handle_rest;?>)" class="icon-checkmark4"></i></a></li><?php } ?>
<?php if($this->session->has_userdata('pay_slice') and $this->session->userdata('pay_slice') == TRUE){ ?><li title="پرداخت جزئی" class="text-primary"><a data-toggle="modal" href="#modal_form_minor"><i onclick="pay_slice(<?php echo $row->id;?>)" class="icon-stack-empty"></i></li><?php } ?>
				<?php } ?>
<?php if($this->session->has_userdata('restore') and $this->session->userdata('restore') == TRUE){ ?><li title="بازگشت پرداخت " class="text-warning-800"><a data-toggle="modal" href="#modal_form_dminor"><i onclick="history(<?php echo $row->id;?>)" class="icon-file-minus"></i></li><?php } ?>
<?php if($this->session->has_userdata('delete_handle') and $this->session->userdata('delete_handle') == TRUE){?><li title="حذف هماهنگی" class="text-danger"><a data-toggle="modal" href="#modal_theme_danger"><i onClick="deleteHandle(<?php echo $row->id; ?>, <?php echo $row->handle_pay; ?>)" class="icon-cross2"></i></a></li><?php } ?>
									</ul>
								</td>
						</tr>
						<?php }} ?>
						<tr>
				</table>
				<!-- minor form modal -->
				<div id="modal_form_minor" class="modal fade">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h5 class="modal-title text-center">بخشی از مبلغ هماهنگ شده را به صورت جزئی پرداخت کنید</h5>

							</div>
							<hr>
							<form method="post" id="form_slice">
								<div class="modal-body">
									<div class="form-group input-group">
										<label>مبلغ هماهنگی :</label>
										<input type="text" placeholder="111,000,000" onkeyup='slice_input(this)' class="form-control">
										<input type="hidden" name="slice">
										<span class="input-group-btn">
							<button type="submit" name="sub" class="btn btn-success mt-25">ذخیره</button>
											</span>
									</div>
							</form>
							</div>
						</div>
					</div>
					<!-- /minor form modal -->

				</div>
				<!-- Success modal -->
				<div id="modal_theme_success" class="modal fade">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header bg-success">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title">پرداخت کامل</h4>
							</div>

							<div class="modal-body">

								<h5 class="text-center" id="p_all"></h5>


							</div>

							<div class="modal-footer text-center">
								<button type="button" class="btn btn-danger" data-dismiss="modal">خیر</button>
								<a id="button_all" class="btn btn-success">بله </a>
							</div>
						</div>
					</div>
				</div>
				<!-- /success modal -->
			</div>
			<!-- Success modal -->
			<div id="modal_theme_danger" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header bg-danger">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">حذف هماهنگی</h4>
						</div>

						<div class="modal-body">

							<h5 class="text-center" id="titleHandle"></h5>


						</div>

						<div class="modal-footer text-center">
							<button type="button" class="btn btn-danger" data-dismiss="modal" id="closeHandle">خیر</button>
							<a id="confirmHandle" class="btn btn-success">بله </a>
						</div>
					</div>
				</div>
			</div>
			<!-- /success modal -->
		</div>
		<!-- edit bank modal -->
		<div id="edit_bank_modal" class="modal fade">
			<div class="modal-dialog" style="width:750px;">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title text-center">ویرایش بانک</h5>

					</div>
					<hr>
					<form action="" method="post" id='act_edit'>
						<div class="modal-body">
							<div class="field_wrapper2">
								<div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>شماره شبا : </label>
												<input onkeyup="show_bank(this)" id="num_shaba" value="" data-mask="99-999-9999999999999999999" type="text" placeholder="06-017-0000000123014682799" name="number_shaba" class="form-control">
											</div>
										</div>



										<div class="col-md-6">
											<div class="form-group">
												<label>بانک :</label>
												<span class="text-primary" style="font-size:12px; display:none;">(طبق شماره شبا وارد شده بانکی پیدا نشد. نام بانک را وارد کنید)</span>
												<input type="text" name="name_bank" id="nam_bank" value="" placeholder="ملت،ملی،.." class="form-control" readonly>
											</div>
										</div>
									</div>


								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label>مبلغ معامله : </label>
											<input type="hidden" id="amo_pay" value =''>
											<input type="text" onkeyup="ambank(this)" placeholder="100,000" value="" class="form-control">
											<input type="hidden" value='' id="amo_bank" name="amount_bank">
											<p class="text-danger" style="display:none;"></p>
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group input-group">
											<label>توضیحات حساب :</label>
											<input type="text" id="exp_bank" name="bank_explain" value='' placeholder="توضیحات خود را وارد کنید" class="form-control">
											<span class="input-group-btn "><button type="submit" name="sub" style="top: 13px;" class="btn btn btn-success">ذخیره</button></span>
										</div>
									</div>
								</div>
							</div>
					</form>
					</div>
				</div>
			</div>

			
			<!-- /edit bank modal -->
		</div>
		<!-- dminor form modal -->
		<div id="modal_form_dminor" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title text-center">پرداخت های هماهنگ شده را برگردانید</h5>

					</div>
					<hr>
						<div class="modal-body" id="showhistory">
							<div>
							</div>
						</div>

				</div>
			</div>
		</div>
		<?php $str = '';foreach($customer as $row){$str .= "\"$row->fullname\",";}$str = trim($str , ",");?>
		<!-- /dminor form modal -->
		<script type="text/javascript" src="<?php echo base_url('files/');?>assets/mine/handle_deal.js"></script>
		<script>
			var titleHandle = document.getElementById('titleHandle');
			var closeHandle = document.getElementById('closeHandle');
			var confirmHandle = document.getElementById('confirmHandle');
			function deleteHandle(id , pay){
               if(pay != 0){
				   titleHandle.innerHTML = 'حجم پرداختی این هماهنگی صفر نمی باشد . اگر مایل به حذف هماهنگی  می باشید جهت جلوگیری از ناسازگاری در سامانه ابتدا مبالغ پرداختی را بازگردانید. ';
				   closeHandle.style.display = 'none';
				   confirmHandle.style.display = 'none';
				   return;
			   }else{
				   titleHandle.innerHTML = 'آیا مایل به حذف هماهنگی می باشید ؟';
				   closeHandle.style.display = 'inline-block';
				   confirmHandle.style.display = 'inline-block';
				   confirmHandle.setAttribute('href' , "<?php echo base_url('deal/delete_handle/');?>" + id + "<?php echo "/".$this->uri->segment(3); ?>");
			   }
			}
			var customer = [ <?php echo $str; ?> ];

			function search_customer( input ) {
				autocomplete( input, customer );
			}

			function pay_all( link, rest ) {
				document.getElementById( 'button_all' ).setAttribute( 'href', "<?php echo base_url("deal/pay_all/").$deal->id."/"?>" + link );
				document.getElementById( 'p_all' ).innerHTML = " آیا می خواهید تمام مبلغ " + numeral( rest ).format( '0,0' ) + " پرداخت شود ؟";
			}

			function pay_slice( link ) {
				document.getElementById( 'form_slice' ).setAttribute( 'action', "<?php echo base_url("deal/pay_slice/").$deal->id."/"?>" + link );
			}
			
			function history(id){
		var xhr = new XMLHttpRequest();
		xhr.onload = function(){
			if((xhr.status >= 200 && xhr.status < 300) || xhr.status == 304){
				
					var result = JSON.parse(xhr.responseText);
				    showHistory(result);
				}else{
					alert('request was unsuccessful : ' + xhr.status);
				}
		}
		xhr.open('post' , "<?php echo base_url('deal/get_history/')?>" , true);
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhr.send('handle_id=' + id);
			}
			function showHistory(res){
				var modal = document.getElementById('showhistory');
				var len = res.length ;
				if(len == 0){
					modal.innerHTML = '<div class="text-center pb-20">پرداختی صورت نگرفته است</div>';
				}else{
					var div = document.createElement('div');
					for(var i = 0 ; i < len ; i++ ){
						var row = div.appendChild(document.createElement('div'));
						row.setAttribute('class' , 'row');
						
						var col4 = row.appendChild(document.createElement('div'));
						col4.setAttribute('class' , 'col-md-4');
						
						var group = col4.appendChild(document.createElement('div'));
						group.setAttribute('class' , 'form-group');
						
						var label = group.appendChild(document.createElement('label'));
						label.innerHTML = 'تاریخ پرداخت';
						
						var p_date = group.appendChild(document.createElement('p'));
						p_date.setAttribute('class' , 'form-control');
						p_date.innerHTML = res[i].date_pay;
						
						var col8 = row.appendChild(document.createElement('div'));
						col8.setAttribute('class' , 'col-md-8');
						
						var group1 = col8.appendChild(document.createElement('div'));
						group1.setAttribute('class' , 'form-group input-group');
						
						var lable1 = group1.appendChild(document.createElement('label'));
						lable1.innerHTML = 'مبلغ پرداخت';
						
						var p1 = group1.appendChild(document.createElement('p'));
						p1.innerHTML = numeral(res[i].volume).format('0,0') + ' ریـال ';
						p1.setAttribute('class' , 'form-control');
						
						var span = group1.appendChild(document.createElement('span'));
						span.setAttribute('class' , 'input-group-btn');
						 
						var a = span.appendChild(document.createElement('a'));
						a.setAttribute('class' , 'btn btn-danger mt-25');
						a.setAttribute('href' , "<?php echo base_url('deal/restore/') ?>" + res[i].id + '/' + "<?php echo $deal->id; ?>");
						a.innerHTML = 'حذف';
						
					}
					modal.replaceChild(div , modal.firstChild);
				}
			}
// edit bank
			var num_shaba = document.getElementById( 'num_shaba' );
			var nam_bank = document.getElementById( 'nam_bank' );
			var act_edit = document.getElementById( 'act_edit' );
			var amo_pay = document.getElementById( 'amo_pay' );
			var amo_bank = document.getElementById( 'amo_bank' );
			var exp_bank = document.getElementById( 'exp_bank' );
			function edit_bank(id){
				var xhr = new XMLHttpRequest();
		xhr.onload = function(){
			if((xhr.status >= 200 && xhr.status < 300) || xhr.status == 304){
				
					var result = JSON.parse(xhr.responseText);
				    showBank(result);
				}else{
					alert('request was unsuccessful : ' + xhr.status);
				}
		}
		xhr.open('post' , "<?php echo base_url('deal/show_bank/')?>" , true);
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhr.send('bank_id=' + id);
			}
			function showBank(result){
				act_edit.action = "<?php echo base_url('deal/edit_bank/')?>"+result.deal_id +"/"+result.id;
				num_shaba.value = result.number_shaba;
				nam_bank.value = result.name_bank;
				amo_pay.value = result.pay;
				amo_bank.value = result.amount;
        amo_bank.previousElementSibling.value = numeral(result.amount).format('0,0') + ' ریال ';

			}
			// edit bank
		</script>