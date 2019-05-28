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
<p style="display:none" id="alert_status"><?php if($this->session->has_userdata('status')){echo $this->session->userdata('status');}else{echo 0;}?></p>	
<div class="breadcrumb-line breadcrumb-line-component mb-20">
	<ul class="breadcrumb">
		<li><a href="<?php echo base_url('home'); ?>"><i class="icon-home2 position-left"></i> داشبورد</a>
		</li>
		<li><a href="<?php echo base_url('deal/handle_profile/').$this->uri->segment(3);?>">معاملات</a>
		</li>
		<li class="active">آرشیو معاملات</li>
	</ul>

</div>

<div class="panel panel-flat">
	<div class="panel-body">
		<legend class="text-semibold"><i class="icon-archive position-left"></i>آرشیو معاملات</legend>

	<table class="table datatable-selection-single table-responsive-lg ">
		<thead>
			<tr>
				<th class="text-center">شناسه معامله</th>
				<th class="text-center">نام مشتری</th>
				<th class="text-center">نوع معامله</th>
				<th class="text-center">تعداد ارز</th>
				<th class="text-center">نرخ تبدیل</th>
				<th class="text-center">حجم معامله</th>
				<th class="text-center">حجم پرداخت شده</th>
				<th class="text-center">حجم باقی مانده</th>
				<th class="text-center">تاریخ ثبت</th>
				<th class="text-center">آخرین ویرایش</th>
				<th class="text-center">ابزار</th>
			</tr>
		</thead>
		<tbody>
		<?php 
		if(empty($deal)){ ?>
            <tr><td colspan="11" class="text-center p-20">موردی یافت نشد</td></tr>
		<?php }else{
			$sum_volume = 0; $sum_pay = 0; $sum_rest = 0; $forbank = 0;
			foreach($deal as  $deals){?>
			<tr class="<?php if($deals->state == 0){echo 'state_bg';}?>">
                <td class="text-center"><?php echo $deals->id + 100; ?></td>
                <td class="text-center"><?php echo $deals->fullname; ?></td>
				<td class="text-center"><?php if($deals->type == 1 ){echo 'خرید'; $sum_pay -= $deals->pay; $sum_volume -= $deals->volume; $sum_rest -= $deals->rest; $forbank += $deals->volume; }else{echo 'فروش'; $sum_pay += $deals->pay; $sum_volume += $deals->volume; $sum_rest += $deals->rest; $forbank -= $deals->volume;} ?></td>
				<td class="text-center"><?php echo number_format($deals->count_money) . " " . $deals->name;?></td>
				<td class="text-center"><?php echo number_format($deals->convert); ?></td>
				<td class="text-center <?php if($deals->volume < $deals->pay){echo 'text-danger';}?>"><?php echo number_format($deals->volume); ?> </td>
				<td class="text-center <?php if($deals->volume < $deals->pay){echo 'text-danger';}?>"><?php echo number_format($deals->pay); ?> </td>
				<td class="text-center <?php if($deals->rest < 0){echo 'text-danger';}?>" ><?php echo number_format($deals->rest); ?></td>
				<td class="text-center"><?php echo $deals->date_deal."</br>".$deals->time_deal; ?> </td>
				<td class="text-center"><?php echo $deals->date_modified; ?></td>
				<td class="text-center">
					<ul class="icons-list">
<?php if($this->session->has_userdata('edit_deal')){?><li title="ویرایش معامله" data-toggle="tooltip" class="text-primary"><a href="<?php echo base_url('deal/edit/').$deals->id;?>"><i class=" icon-pencil6"></i></a></li><?php } ?>
<?php if($this->session->has_userdata('see_photo')){?><li title="مشاهده قبض" data-toggle="tooltip" class="text-indigo-600"><a href="<?php echo base_url('deal/photo/').$deals->id;?>"><i class="icon-stack-picture"></i></a></li><?php }?>
<?php if($this->session->has_userdata('delete_deal')){?><li class="text-danger" data-toggle="tooltip" title="حذف معامله"><a data-toggle="modal" href="#modal_theme_danger1"><i  class="icon-trash" onclick = "deleteDeal(<?php echo $deals->id;?> , <?php echo $deals->pay; ?>)" ></i></a></li><?php } ?>
					</ul>
				</td>
			
			</tr>
		<?php } ?>
			<tr>
				<td class="text-center"><b> مجموع : </b></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td class="lright" style="text-align:center !important; " ><b><?php echo number_format($sum_volume);?></b></td>
				<td class="lright" style="text-align:center !important; " ><b><?php echo number_format($sum_pay);?></b></td>
				<td class="lright" style="text-align:center !important; " ><b><?php echo number_format($sum_rest);?></b></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<?php } ?>
		</tbody>

	</table>


</div>
</div>
<?php if($this->session->has_userdata('add_handle')){ ?>
	<div class="panel panel-flat" id="div_handle">
		<div class="panel-body">
		<form action="<?php echo base_url('deal/handle_profile/').$this->uri->segment(3);?>" method="post">
			<legend class="text-semibold"><i class="icon-address-book position-left"></i> افزودن هماهنگی</legend>
			<div class="row">
				<div>
					<div class="col-md-3">
						<div class="form-group">
							<label> مشتری خرید :</label>
							<input type="text" name="customer_buy" onFocus ="search_buy(this)" value="<?php if(!empty($deal)){echo $deal[0]->fullname; }?>" placeholder=" لطفا نام مشتری خرید را وارد کنید "  autocomplete="off" class="form-control" required>
							<p class="text-danger" style="display:none; position:absolute;font-size:12px;"></p>
						</div>
					</div>
						<div class="col-md-3">
						<div class="form-group">
							<label> مشتری فروش :</label>
							<input type="text" name="customer_sell" onFocus ="search_sell(this)" autocomplete="off" placeholder="لطفا نام مشتری فروش را وارد کنید" class="form-control" required autofocus>
							<p class="text-danger" style="display:none; position:absolute;font-size:12px;"></p>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>انتخاب حساب :</label>
                            <select class="form-control" name="bank_id" required>
                         <?php if(empty($select)){ ?>
							<option value="0">شماره حسابی برای مشتری خرید ثبت نشده است</option>
						 <?php } else { foreach($select as $selects){
							 $aa = $selects->id + 1000;
							 ?>
							<option value="<?php echo $selects->id;?>"><?php echo $selects->explain." |  هماهنگ نشده :".number_format($selects->rest_handle)." | باقیمانده :  ".number_format($selects->rest)." | شناسه : ".$aa; ?></option>
						 <?php } }?>
											</select>
						</div>
					</div>
					
					<div class="col-md-3">
						<div class="form-group">
							<label>مبلغ هماهنگی :</label>
							<input type="text" placeholder="111,000,000"  onkeyup="amhandle(this)" autocomplete="off" class="form-control" required>
							<input type = "hidden" name='volume_handle' value="0">
						</div>
					</div>
				</div>
			</div>
			<div class="text-right">
				<button type="submit" name="sub" class="btn btn-primary mt-25">ثبت هماهنگی <i class="icon-arrow-left13 position-right"></i></button>
			</div>
			</form>
		</div>
	</div>
<?php } ?>
<div>
	<div class="panel panel-flat" id="div_bank">
		<div class="panel-body">
		<?php if($this->session->has_userdata('add_bank')){ ?><a class="btn btn-success float-btn-left" href="#add_bank_modal" data-toggle="modal">افزودن بانک</a><?php }?>
			<legend class="text-semibold"><i class="icon-credit-card position-left"></i> اطلاعات بانکی </legend>
			<table class="table datatable-basic">
				<thead>
					<tr>
						<th width="5%" >شناسه بانک</th>
						<th width="6%">نام بانک</th>
						<th width="14%">شماره شبا</th>
						<th class="text-center" width="13%">حجم تعیین شده</th>
						<th class="text-center" width="13%"> پرداخت شده</th>
						<th class="text-center" width="13%"> باقی مانده</th>
						<th class="text-center" width="13%"> هماهنگ نشده</th>
						<th width="13%">توضیحات</th>
						<th width="5%" class="text-center">وضعیت</th>
						<th width="5%" class="text-center">ابزار</th>
					</tr>
				</thead>
				<tbody>
				
				<?php
				if(empty($bank)){ ?>
                  <tr><td colspan="10" class="text-center p-20">موردی یافت نشد</td></tr>
				<?php }else{
				$amount = 0; $pay = 0; $rest = 0;	
				foreach($bank as $key => $banks){ $amount += $banks->amount; $pay += $banks->pay; $rest += $banks->rest; 
					?>
					<tr>
						<td><?php echo $banks->id + 1000 ;?></td>
						<td><?php echo $banks->name; ?></td>
						<td><?php echo $banks->shaba; ?></td>
						<td  class="text-center"><?php echo number_format($banks->amount); ?></td>
						<td  class="text-center <?php if($banks->pay > $banks->amount){echo 'text-danger';}?>"><?php echo number_format($banks->pay); ?></td>
						<td  class="text-center <?php if($banks->rest < 0){echo 'text-danger';}?>"><?php echo number_format($banks->rest); ?></td>
						<td  class="text-center <?php if($banks->rest_handle < 0){echo 'text-danger';}?>"><?php echo number_format($banks->rest_handle); ?></td>
						<td><?php echo $banks->explain; ?></td>
						<?php if($banks->active == 1){$class="success";$txt = 'فعال'; $act = 0;}else{$class = "danger"; $txt = 'غیرفعال'; $act = 1;} ?>
				<td class="text-center"><?php if($this->session->has_userdata('active_bank')){ ?><a href="<?php echo base_url('deal/active/').$this->uri->segment(3)."/".$banks->id."/".$act; ?>"><span class="label label-<?php echo $class; ?>"><?php echo $txt;?></span></a><?php } ?></td>
						</td>
						<td class="text-center">
									<ul class="icons-list">
				<?php if($this->session->has_userdata('edit_bank')){?><li title="ویرایش بانک" data-toggle="tooltip" class="text-primary"><a data-toggle="modal" href="#edit_bank_modal"><i onclick = "edit_bank(<?php echo $banks->id;?>)" class="icon-credit-card"></i></li><?php } ?>
				<li title="گردش حساب" data-toggle="tooltip" class="text-pink"><a href="<?php echo base_url('settings/bank/').$banks->id;?>" target="_blank"><i class="icon-spinner10"></i></li>
									</ul>
						</td>
					</tr>
					<?php } ?>
					<tr>
				<td class="text-center"><b> مجموع : </b></td>
				<td></td>
				<td></td>
				<td class="lright" style="text-align:center !important; "><b><?php echo number_format($amount);?></b></td>
				<td class="lright" style="text-align:center !important; "><b><?php echo number_format($pay);?></b></td>
				<td class="lright" style="text-align:center !important; "><b><?php echo number_format($rest);?></b></td>
				<td class="lright" style="text-align:center !important; " ><b><?php echo number_format($forbank - $amount);?></b></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
				<?php  }  ?>
			</table>
			</div>
		</div>

		</div>
<div>
	<div class="panel panel-flat" id='archive_handle'>
		<div class="panel-body">
		
			<legend class="text-semibold"><i class="icon-notebook position-left"></i> اطلاعات هماهنگی معاملات خرید</legend>
			<table class="table datatable-basic">
				<thead>
					<tr>
						<th width="5%">ردیف </th>
						<th width="10%">نام مشتری فروش</th>
						<th width="12%">حجم هماهنگ شده</th>
						<th width="12%">حجم پرداخت شده</th>
						<th width="12%">حجم باقی مانده </th>
						<th width="7%"> شناسه بانک</th>
						<th width="12%">توضیحات بانک</th>
						<th width="8%">تاریخ ثبت</th>
						<th width="8%"> آخرین تغییر</th>
						<th width="10%" class="text-center"> ابزار</th>
					</tr>
				</thead>
				<tbody>
				<?php if(empty($handle)){ ?>
                        <tr><td colspan="10" class="text-center p-20">موردی یافت نشد</td></tr>
				<?php }else{ foreach($handle as $key =>  $handles){ ?>
					<tr>
						<td><?php echo $key + 1;?></td>
						<td><a href="<?php echo base_url('deal/handle_profile/').$handles->cust_id;?>" target="_blank"><?php echo $handles->fullname;?></a></td>
						<td><?php echo number_format($handles->volume_handle);?></td>
						<td class="<?php if($handles->handle_pay > $handles->volume_handle){echo 'text-danger';}?>"><?php echo number_format($handles->handle_pay);?></td>
						<td class="<?php if($handles->handle_rest < 0){echo 'text-danger';}?>"><?php echo number_format($handles->handle_rest);?></td>
						<td><?php echo $handles->bank_id + 1000; ?></td>
						<td><?php echo $handles->explain; ?></td>
						<td><?php echo $handles->date_handle."</br>".$handles->time_handle;?></td>
						<td><?php echo $handles->date_modified?></td>
						<td class="text-center">
											<ul class="icons-list">
												<?php if($handles->handle_rest > 0){?>
<?php if($this->session->has_userdata('pay_all')){?><li title="پرداخت کامل" data-toggle="tooltip" class="text-success"><a data-toggle="modal" href="#modal_theme_success"><i onclick="pay_all(<?php echo $handles->id;?> , <?php echo $handles->handle_rest;?>)" class="icon-checkmark4"></i></a></li><?php } ?>
<?php if($this->session->has_userdata('pay_slice')){?><li title="پرداخت جزئی" data-toggle="tooltip" class="text-primary"><a data-toggle="modal" href="#modal_form_minor"><i onclick="pay_slice(<?php echo $handles->id;?> , <?php echo $handles->handle_rest;?>)" class="icon-stack-empty"></i></li><?php } ?>
													<?php } ?>
													
<?php if($this->session->has_userdata('restore')){?><li title="بازگشت پرداخت " data-toggle="tooltip" class="text-warning-800"><a data-toggle="modal" href="#modal_form_dminor"><i onclick="history(<?php echo $handles->id;?>)" class="icon-file-minus"></i></li><?php } ?>
<?php if($this->session->has_userdata('edit_handle')){?><li title="ویرایش هماهنگی" data-toggle="tooltip" class="text-primary"><a data-toggle="modal" href="#modal_form_sminor"><i class="icon-pencil6" onclick="edit_handle(<?php echo $handles->id;?> , <?php echo $handles->volume_handle;?>)" ></i></a></li><?php } ?>									
<?php if($this->session->has_userdata('delete_deal')){?><li title="حذف هماهنگی" data-toggle="tooltip" class="text-danger"><a data-toggle="modal" href="#modal_theme_danger"><i onClick="deleteHandle(<?php echo $handles->id; ?>, <?php echo $handles->handle_pay; ?>)" class="icon-cross2"></i></a></li><?php } ?>
											</ul>
						</td>
					</tr>
					<?php } }?>
			</table>
			<br>
			<br>
			<legend class="text-semibold"><i class="icon-notebook position-left"></i> اطلاعات هماهنگی معاملات فروش</legend>
			<table class="table datatable-basic">
				<thead>
					<tr>
						<th width="5%">ردیف </th>
						<th width="10%">نام مشتری خرید</th>
						<th width="12%">حجم هماهنگ شده</th>
						<th width="12%">حجم پرداخت شده</th>
						<th width="12%">حجم باقی مانده </th>
						<th width="7%"> شناسه بانک</th>
						<th width="12%">توضیحات بانک</th>
						<th width="8%">تاریخ ثبت</th>
						<th width="8%"> آخرین تغییر</th>
						<th width="10%" class="text-center"> ابزار</th>
					</tr>
				</thead>
				<tbody>
				<?php if(empty($handle2)){ ?>
                        <tr><td colspan="10" class="text-center p-20">موردی یافت نشد</td></tr>
				<?php }else{ foreach($handle2 as $key =>  $handles){ ?>
					<tr>
						<td><?php echo $key + 1;?></td>
						<td><a href="<?php echo base_url('deal/handle_profile/').$handles->cust_id;?>" target="_blank"><?php echo $handles->fullname;?></a></td>
						<td><?php echo number_format($handles->volume_handle);?></td>
						<td class="<?php if($handles->handle_pay > $handles->volume_handle){echo 'text-danger';}?>"><?php echo number_format($handles->handle_pay);?></td>
						<td class="<?php if($handles->handle_rest < 0){echo 'text-danger';}?>"><?php echo number_format($handles->handle_rest);?></td>
						<td><?php echo $handles->bank_id + 1000; ?></td>
						<td><?php echo $handles->explain; ?></td>
						<td><?php echo $handles->date_handle."</br>".$handles->time_handle;?></td>
						<td><?php echo $handles->date_modified?></td>
						<td class="text-center">
											<ul class="icons-list">
												<?php if($handles->handle_rest > 0){?>
<?php if($this->session->has_userdata('pay_all')){?><li title="پرداخت کامل" data-toggle="tooltip" class="text-success"><a data-toggle="modal" href="#modal_theme_success"><i onclick="pay_all(<?php echo $handles->id;?> , <?php echo $handles->handle_rest;?>)" class="icon-checkmark4"></i></a></li><?php } ?>
<?php if($this->session->has_userdata('pay_slice')){?><li title="پرداخت جزئی" data-toggle="tooltip" class="text-primary"><a data-toggle="modal" href="#modal_form_minor"><i onclick="pay_slice(<?php echo $handles->id;?> , <?php echo $handles->handle_rest;?>)" class="icon-stack-empty"></i></li><?php } ?>
													<?php } ?>
													
<?php if($this->session->has_userdata('restore')){?><li title="بازگشت پرداخت " data-toggle="tooltip" class="text-warning-800"><a data-toggle="modal" href="#modal_form_dminor"><i onclick="history(<?php echo $handles->id;?>)" class="icon-file-minus"></i></li><?php } ?>
<?php if($this->session->has_userdata('edit_handle')){?><li title="ویرایش هماهنگی" data-toggle="tooltip" class="text-primary"><a data-toggle="modal" href="#modal_form_sminor"><i class="icon-pencil6" onclick="edit_handle(<?php echo $handles->id;?> , <?php echo $handles->volume_handle;?>)" ></i></a></li><?php } ?>									
<?php if($this->session->has_userdata('delete_deal')){?><li title="حذف هماهنگی" data-toggle="tooltip" class="text-danger"><a data-toggle="modal" href="#modal_theme_danger"><i onClick="deleteHandle(<?php echo $handles->id; ?>, <?php echo $handles->handle_pay; ?>)" class="icon-cross2"></i></a></li><?php } ?>
											</ul>
						</td>
					</tr>
					<?php } }?>
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
								<div class="form-group">
								<label>مبلغ پرداختی:</label>
										<p id = "rest_slice" class="d-none"></p>
										<input type="text" placeholder="111,000,000" id="autofocuss" onkeyup='slice_input(this)' class="form-control" required>
										<input type="hidden" name="slice">
										<p class="text-danger d-none" style="position:absolute;top:65px;"></p>
								</div>
								</br>
									<div class="form-group input-group">
									<label>انتخاب تاریخ:</label>
										<input type="text"  class="form-control" name="date_slice" id="j_created_date" readonly data-mddatetimepicker="true" data-enabletimepicker="true" data-placement="bottom" value="<?php echo $date; ?>" placeholder="Jalali Created Date">
										<span class="input-group-btn">
							<button type="submit" name="sub" class="btn btn-success mt-25">پرداخت</button>
											</span>
									</div>
							</form>
							</div>
						</div>
					</div>
				<!-- /minor form modal -->

			</div>
			<div id="modal_form_sminor" class="modal fade">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h5 class="modal-title text-center">مبلغ هماهنگی را ویرایش کنید </h5>

							</div>
							<hr>
							<form method="post" id="form_edit">
								<div class="modal-body">
									<div class="form-group input-group">
										<label>مبلغ هماهنگی :</label>
										<input type="text" id="ihandle" placeholder="111,000,000" onkeyup='amhandle(this)' class="form-control" required>
										<input type="hidden" name="edit">
										<p class="text-danger d-none" style="position:absolute;top:65px;"></p>
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
                          <form method="post" id="form_all">
							<div class="modal-body">

								<h5 class="text-center"> آیا می خواهید تمام مبلغ <span id="p_all"></span> در تاریخ <input type="text" style="width: 27%;display: inline-block;" class="form-control" name="date_all" id="j_created_date" readonly data-mddatetimepicker="true" data-enabletimepicker="true" data-placement="bottom" value="<?php echo $date; ?>" placeholder="Jalali Created Date"> پرداخت شود؟</h5>


							</div>

							<div class="modal-footer text-center">
								<button type="button" class="btn btn-danger" data-dismiss="modal">خیر</button>
								<button type="submit" class="btn btn-success">بله </button>
								</form>
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
												<input onkeyup="show_bank(this)" id="num_shaba" value="" data-mask="99-999-9999999999999999999" type="text" placeholder="06-017-0000000123014682799" name="shaba" class="form-control">
											</div>
										</div>



										<div class="col-md-6">
											<div class="form-group">
												<label>بانک :</label>
												<span class="text-primary" style="font-size:12px; display:none;">(طبق شماره شبا وارد شده بانکی پیدا نشد. نام بانک را وارد کنید)</span>
												<input type="text" name="name" id="nam_bank" value="" placeholder="ملت،ملی،.." class="form-control">
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
											<input type="hidden" value='' id="amo_bank" name="amount">
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
	<div id="modal_theme_danger1" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header bg-danger">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">حذف معامله</h4>
						</div>

						<div class="modal-body">

							<h6 class="text-center" id="titleDelete"></h6>


						</div>

						<div class="modal-footer text-center">
							<button type="button" class="btn btn-danger" data-dismiss="modal" id='closeDelete'>بستن</button>
							<a class="btn btn-success" id="confirmDelete">بله </a>
						</div>
					</div>
				</div>
			</div>
		<!-- /dminor form modal -->
		<!-- /edit bank modal -->
					<!-- add bank modal -->


				<div id="add_bank_modal" class="modal fade">
				<div class="modal-dialog" style="width:750px;">
					<div class="modal-content">
						
						<form action="<?php echo base_url('deal/add_bank/').$this->uri->segment(3);?>" method="post">
							<div class="modal-body">
								<div class="field_wrapper2">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								
						
						<legend class="text-semibold"><i class=" icon-credit-card position-left"></i>افزودن بانک</legend>

					
									<div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label>شماره شبا : </label>
													<input onkeyup="show_bank(this)" data-mask="99-999-9999999999999999999" type="text" placeholder="06-017-0000000123014682799" name="shaba" class="form-control">
												</div>
											</div>



											<div class="col-md-6">
												<div class="form-group">
													<label>بانک :</label>
													<span class="text-primary" style="font-size:12px; display:none;">(طبق شماره شبا وارد شده بانکی پیدا نشد. نام بانک را وارد کنید)</span>
													<input type="text" name="name" placeholder="ملت،ملی،.." class="form-control">
												</div>
											</div>
										</div>


									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label> تعیین حجم : </label>
												<input type="hidden" value ='0'>
												<input type="text" onKeyUp="ambank(this)" placeholder="100,000" class="form-control">
												<input type="hidden" name="amount" value="0">
												<p class="text-danger" style="display:none;"></p>
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group input-group">
												<label>توضیحات حساب:</label>
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

	</div>
				<!-- /add bank modal -->
	<!-- edit bank modal -->
		<?php
		$b_str = ''; $b_str2 = '';
		foreach($buy as $row){ $am = $row->volume - $row->handle; $b_str .= "\"$row->fullname\","; $b_str2 .= "\"$am\",";} $b_str = trim($b_str , ','); $b_str2 = trim($b_str2 , ',');
		$s_str = ''; $s_str2 = '';
		foreach($sell as $row){ $am = $row->volume - $row->handle; $s_str .= "\"$row->fullname\","; $s_str2 .= "\"$am\",";} $s_str = trim($s_str , ','); $s_str2 = trim($s_str2 , ',');
		?>
		<script type="text/javascript" src="<?php echo base_url('files/');?>assets/mine/handle_group.js"></script>
		<script>
var formEdit = document.getElementById('form_edit');
var ihandle = document.getElementById('ihandle');
function edit_handle(id , volume){
ihandle.value = numeral(volume).format('0,0');
ihandle.nextElementSibling.value = volume;
formEdit.action = "<?php echo base_url('deal/edit_handle/').$this->uri->segment(3)."/";?>" + id;
}

var b_array = [ <?php echo $b_str; ?> ];
var b_array2 = [<?php echo $b_str2; ?>];
var s_array = [ <?php echo $s_str; ?> ];
var s_array2 = [<?php echo $s_str2; ?>];
function search_buy( input ) {
				autocomplete( input, b_array , b_array2 , 1);
}
function search_sell( input ) {
				autocomplete( input, s_array , s_array2 , 2 );
}		
//delete deal -----------------
	var titleDelete = document.getElementById('titleDelete');
	var closeDelete = document.getElementById('closeDelete');
	var confirmDelete = document.getElementById('confirmDelete');
	function deleteDeal(id , rest){
      if(rest != 0){
		  titleDelete.innerHTML = " حجم پرداختی این معامله صفر نمی باشد . اگر مایل به حذف معامله می باشید جهت جلوگیری از ناسازگاری در سامانه ابتدا مبالغ پرداختی را بازگردانید. ";
		  closeDelete.style.display = 'none';
		  confirmDelete.style.display = 'none';
		  return;
	  }else{
		  titleDelete.innerHTML = " آیا می خواهید ادامه دهید؟";
		  closeDelete.style.display = 'inline-block';
		  confirmDelete.style.display = 'inline-block';
		  confirmDelete.setAttribute('href' , "<?php echo base_url('deal/delete_deal/')?>" + id + "<?php echo "/".$this->uri->segment(3)."/group";?>");
	  }
	}
//delete deal -----------------
//delete handle -----------------	
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
				   confirmHandle.setAttribute('href' , "<?php echo base_url('deal/delete_handle/').$this->uri->segment(3)."/";?>" + id);
			   }
			}			
//delete handle -----------------	
//pay all -----------------------		
			function pay_all( link, rest) {
	            document.getElementById( 'form_all' ).setAttribute( 'action', "<?php echo base_url("deal/pay_all/").$this->uri->segment(3)."/";?>" + link );
				document.getElementById( 'p_all' ).innerHTML =  numeral( rest ).format( '0,0' );
			}
//pay all -----------------------
//pay slice----------------------				
			function pay_slice( id , rest ) {
				document.getElementById( 'form_slice' ).setAttribute( 'action', "<?php echo base_url("deal/pay_slice/").$this->uri->segment(3)."/";?>"+ id );
				document.getElementById( 'rest_slice' ).innerHTML = rest;
			}
			function slice_input( input ) {
				input.value = numeral( input.value ).format( '0,0' );
				input.nextElementSibling.value = numeral( input.value ).value();
				if(numeral( input.value ).value() > document.getElementById( 'rest_slice' ).innerHTML ){
					input.nextElementSibling.nextElementSibling.style.display = "block";
					input.nextElementSibling.nextElementSibling.innerHTML = ' مبلغ وارد شده از حجم باقی مانده برای این هماهنگی بیشتر است ';
				}else{
					input.nextElementSibling.nextElementSibling.style.display = "none";
				}

			}
//pay slice----------------------
	

//show deal----------------------
//show bank---------------------
function edit_bank(id){
				var xhr = new XMLHttpRequest();
		xhr.onload = function(){
			if((xhr.status >= 200 && xhr.status < 300) || xhr.status == 304){
				    var url = "<?php echo base_url('deal/edit_bank/').$this->uri->segment(3);?>";
					var result = JSON.parse(xhr.responseText);
				    showBank(result , url);
				}else{
					alert('request was unsuccessful : ' + xhr.status);
				}
		}
		xhr.open('post' , "<?php echo base_url('deal/show_bank/')?>" , true);
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhr.send('bank_id=' + id);
}

//show bank---------------------
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
			            a.setAttribute('href' , "<?php echo base_url('deal/restore/').$this->uri->segment(3)."/";?>"+res[i].id  );
						a.innerHTML = 'بازگشت';
					}
					modal.replaceChild(div , modal.firstChild);
				}
			}

		</script>