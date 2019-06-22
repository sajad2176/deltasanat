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
<p class="d-none" id="alert_status"><?php if($this->session->has_userdata('status')){echo $this->session->userdata('status');}else{echo 0;}?></p>	
<div class="breadcrumb-line breadcrumb-line-component mb-20">
	<ul class="breadcrumb">
		<li><a href="<?php echo base_url('home'); ?>"><i class="icon-home2 position-left"></i> داشبورد</a>
		</li>
		<li><a href="<?php echo base_url('deal/profile/').$this->uri->segment(3);?>">معاملات</a>
		</li>
		<li class="active">آرشیو معاملات</li>
	</ul>

</div>

<!---------------------------------->
<!-----------deal table------------->
<!---------------------------------->

<?php 
if($this->session->has_userdata('edit_deal')){$editPerm = 1;}else{$editPerm = 0;}
if($this->session->has_userdata('see_photo')){$photoPerm = 1;}else{$photoPerm = 0;}
if($this->session->has_userdata('delete_deal')){$deletePerm = 1;}else{$deletePerm = 0;}
if($this->session->has_userdata('pay_little')){$littlePerm = 1;}else{$littlePerm = 0;}
?>
<div class="panel panel-flat">
	<div class="panel-body">
<fieldset <?php if($dealCount > 7){?> style="height:620px;"<?php }?> >
		<legend class="text-semibold"><i class="icon-archive position-left"></i>آرشیو معاملات</legend>

	<table class="table datatable-selection-single table-responsive-lg ">
		<thead>
			<tr>
			    <th width="5%">شناسه</th>
				<th width="10%">نام مشتری</th>
				<th width="5%">نوع</th>
				<th width="10%">تعداد ارز</th>
				<th width="8%">نرخ تبدیل</th>
				<th width="15%">حجم معامله</th>
				<th width="15%">حجم پرداخت شده</th>
				<th width="15%">حجم باقی مانده</th>
				<th width="7%"> تاریخ ثبت</th>
				<th width="10%" class="text-center">ابزار</th>
			</tr>
		</thead>
		<tbody id="dealTable">
		<?php 
		if(empty($deal)){ ?>
            <tr><td colspan="10" class="text-center p-20">موردی یافت نشد</td></tr>
		<?php }else{
			foreach($deal as $key => $rows){ $check = abs($rows->volume - $rows->pay); ?>
			<tr class="<?php if($rows->state == 0){echo 'state_bg';}?>" >

				<td><?php echo $rows->id;?></td>

				<td><?php echo $rows->fullname; ?></td>

				<td><?php if($rows->type == 1){echo 'خرید';}else{echo 'فروش';}?></td>

				<td><?php echo number_format($rows->count_money)."</br>".$rows->name;?></td>

				<td><?php echo number_format($rows->convert); ?></td>

				<td class="lright <?php if($rows->volume < $rows->pay){echo 'text-danger';}?>">
				<span title="<?php echo " ( ".number_format($rows->count_money).' + '.$rows->wage." ) × ".number_format($rows->convert) ;?>" data-toggle="tooltip"><?php echo number_format($rows->volume);?></span>
				</td>

				<td class="lright <?php if($rows->volume < $rows->pay){echo 'text-danger';}?>"><?php echo number_format($rows->pay);?></td>

				<td class="lright <?php if($rows->rest < 0){echo 'text-danger';}?>">
				<span title="<?php echo number_format($rows->volume)." - ".number_format($rows->pay)?>" data-toggle="tooltip"><?php echo number_format($rows->rest);?></span>
				</td>

				<td><?php echo $rows->date_deal."</br>".$rows->time_deal; ?></td>

				<td class="text-center">
					<ul class="icons-list">
					<li class="dropdown" title="تنظیمات" data-toggle="tooltip">
													<a href="#" class="dropdown-toggle" data-toggle="dropdown">
														<i class="icon-cog7"></i>
													</a>

													<ul class="dropdown-menu dropdown-menu-right">
														<li onclick="settings(this)"><a>نمایش در داشبورد</a></li>
														<li onclick="settings(this)"><a>عدم نمایش در داشبورد</a></li>
													</ul>
												</li>
<?php if($littlePerm && $rows->pay != 0 && $rows->rest != 0 && $check != 0 && $check <= 50000){ ?><li title="پرداخت خرد" data-toggle="tooltip" class="text-blue-800"><a onclick="payLittle(<?php echo $rows->id; ?> , <?php echo $check; ?>)"><i class="icon-stack-up"></i></a></li><?php } ?>												
<?php if($editPerm && $rows->state == 1){ ?><li title="ویرایش معامله" data-toggle="tooltip" class="text-primary"><a href="<?php echo base_url('deal/edit/').$rows->id;?>"><i class=" icon-pencil6"></i></a></li><?php } ?>												
<?php if($photoPerm){?><li title="مشاهده قبض" data-toggle="tooltip" class="text-indigo-600"><a href="<?php echo base_url('deal/photo/').$rows->id;?>"><i class="icon-stack-picture"></i></a></li><?php } ?>
<?php if($deletePerm && $rows->state == 1){ ?><li title="حذف معامله"  data-toggle="tooltip" class="text-danger" ><a data-toggle="modal" href="#modal_delete_deal"><i  class="icon-trash" onclick = "deleteDeal(<?php echo $rows->id;?> , <?php echo $rows->pay; ?>)" ></i></a></li><?php } ?>
					</ul>
				</td>
			</tr>
			
			<?php }  }?>
		</tbody>
	</table>
</fieldset>
<?php if(!empty($deal)){?>
<br>
<div class="col-md-12 pr-0">
<div class="d-inline-block sumDeal"><b>مجموع : </b></div>
<div class="d-inline-block sumDeal lright"><b title=" حجم خرید - حجم فروش &#xA;<?php echo number_format($sumDeal[1]->volume).' - '.number_format($sumDeal[0]->volume);?>" data-toggle="tooltip"><?php echo number_format($sumDeal[1]->volume - $sumDeal[0]->volume);?></b></div>
<div class="d-inline-block sumDeal lright"><b title=" پرداخت خرید - پرداخت فروش &#xA;<?php echo number_format($sumDeal[1]->pay).' - '.number_format($sumDeal[0]->pay);?>" data-toggle="tooltip"><?php echo number_format($sumDeal[1]->pay - $sumDeal[0]->pay);?></b></div>
<div class="d-inline-block sumDeal lright"><b title=" باقیمانده خرید - باقیمانده فروش &#xA;<?php echo number_format($sumDeal[1]->rest).' - '.number_format($sumDeal[0]->rest);?>" data-toggle="tooltip"><?php echo number_format($sumDeal[1]->rest - $sumDeal[0]->rest);?></b></div>
</div>
<br>
<div class="text-left">
	 <ul class="pagination">
<?php
if($dealCount > 7){
$base = floor($dealCount / 7);
if($dealCount % 7 != 0 ){
	$count = $base + 1;
}else{
	$count = $base;
}
$offset = 0; for($i = 0 ; $i < $count ; $i++){ ?>
<li class="deal <?php if($i == 0){ echo 'active';}?>"><a onclick = "dealPagin(<?php echo $offset; ?> , <?php echo $editPerm;?> , <?php echo $photoPerm; ?> , <?php echo $deletePerm;?> , <?php echo $littlePerm;?> , this)"><?php echo $i + 1;?></a></li>
<?php $offset += 7; }
}
?>
  </ul>
</div>
<?php } ?>
</div>
</div>

<!---------------------------------->
<!-----------deal table------------->
<!---------------------------------->

<?php if($this->session->has_userdata('add_handle')){ ?>
	<div class="panel panel-flat" id="div_handle">
		<div class="panel-body">
		<form action="<?php echo base_url('deal/profile/').$this->uri->segment(3)."/profile";?>" method="post">
			<legend class="text-semibold"><i class="icon-address-book position-left"></i> افزودن هماهنگی</legend>
			<div class="row">
				<div>
					<div class="col-md-3">
						<div class="form-group">
							<label> مشتری خرید :</label>
							<input type="text" name="customer_buy" onFocus ="search_cust(this)" value="<?php if(!empty($deal)){echo $deal[0]->fullname; }?>" placeholder=" لطفا نام مشتری خرید را وارد کنید "  autocomplete="off" class="form-control" required>
							<p class="text-danger" style="display:none; position:absolute;font-size:12px;"></p>
						</div>
					</div>
						<div class="col-md-3">
						<div class="form-group">
							<label> مشتری فروش :</label>
							<input type="text" name="customer_sell" onFocus ="search_cust(this)" autocomplete="off" placeholder="لطفا نام مشتری فروش را وارد کنید" class="form-control" required autofocus>
							<p class="text-danger" style="display:none; position:absolute;font-size:12px;"></p>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>انتخاب حساب :</label>
                            <select class="form-control" name="bank_id" required>
						 <?php $check = 0; foreach($bank as $selects){ $check++;
							 ?>
							<option value="<?php echo $selects->id;?>"><?php echo $selects->explain." |  هماهنگ نشده :".number_format($selects->rest_handle)." | باقیمانده :  ".number_format($selects->rest)." | شناسه : ".$selects->id; ?></option>
						 <?php } if($check == 0){ ?> <option value="0" selected>شماره حسابی برای مشتری خرید ثبت نشده است</option> <?php }?>
											</select>
						</div>
					</div>
					<div class="col-md-1">
								<div class="form-group">
									<label for="j_created_date"> تاریخ  :</label>
									<input type="text" class="form-control" name="date_handle" id="j_created_date" readonly data-mddatetimepicker="true"  data-placement="bottom" value="<?php echo $date; ?>" placeholder="Jalali Created Date">
								</div>
							</div>
					<div class="col-md-2">
						<div class="form-group">
							<label>مبلغ هماهنگی :</label>
							<input type="text" placeholder="1,000,000"  onkeyup="insertAmount(this)" autocomplete="off" class="form-control" required>
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
<?php if($this->session->has_userdata('add_bank')){ ?><a class="btn btn-success float-btn-left ml-10" href="#add_bank_modal" data-toggle="modal">افزودن بانک</a><?php }?>
<?php if($this->session->has_userdata('active_bank')){ ?><a class="btn btn-success float-btn-left" href="<?php echo base_url('deal/disable_bank/').$this->uri->segment(3)?>" target="_blank" >بانک های غیرفعال</a><?php } ?>
		<legend class="text-semibold"><i class="icon-credit-card position-left"></i> اطلاعات بانکی </legend>
			<table class="table datatable-basic table-responsive-lg">
				<thead>
					<tr>
						<th width="5%" >شناسه</th>
						<th width="20%"> اطلاعات حساب</th>
						<th width="13%">حجم تعیین شده</th>
						<th width="13%"> پرداخت شده</th>
						<th width="13%"> باقی مانده</th>
						<th width="13%"> هماهنگ نشده</th>
						<th width="13%">توضیحات</th>
						<th width="5%" class="text-center">وضعیت</th>
						<th width="5%" class="text-center">ابزار</th>
					</tr>
				</thead>
				<tbody>
				
				<?php
				if(empty($bank)){ ?>
                  <tr><td colspan="9" class="text-center p-20">موردی یافت نشد</td></tr>
				<?php }else{
				$forbank = abs($sumDeal[0]->forbank - $sumDeal[1]->forbank);
				$sumAmount = 0; $sumPay = 0 ; $sumRest = 0; $sumRestHandle = 0;		
				foreach($bank as $key => $banks){ $sumAmount += $banks->amount; $sumPay += $banks->pay; $sumRest += $banks->rest; $sumRestHandle += $banks->rest_handle;
					?>
					<tr>
						<td><?php echo $banks->id;?></td>
						<td><?php echo $banks->shaba."</br>".$banks->name; ?></td>
						<td  class="<?php if($banks->amount < $banks->pay ){echo 'text-danger';}?>"><?php echo number_format($banks->amount); ?></td>
						<td  class="<?php if($banks->amount < $banks->pay ){echo 'text-danger';}?>"><?php echo number_format($banks->pay); ?></td>
						<td  class="<?php if($banks->rest < 0){echo 'text-danger';}?>"><?php echo number_format($banks->rest); ?></td>
						<td  class="<?php if($banks->rest_handle < 0){echo 'text-danger';}?>"><?php echo number_format($banks->rest_handle); ?></td>
						<td><?php echo $banks->explain; ?></td>
				        <td class="text-center"><?php if($this->session->has_userdata('active_bank')){ ?>
						<a href="<?php echo base_url('deal/active/').$this->uri->segment(3)."/".$banks->id."/0"; ?>"><span class="label label-success">فعال</span></a>
						<?php } ?>
						</td>
						<td class="text-center">
						 <ul class="icons-list">
				<?php if($this->session->has_userdata('edit_bank')){?><li title="ویرایش بانک" data-toggle="tooltip" class="text-primary"><a data-toggle="modal" href="#edit_bank_modal"><i onclick = "getBank(<?php echo $banks->id;?>)" class="icon-credit-card"></i></li><?php } ?>
				<li title="گردش حساب" data-toggle="tooltip" class="text-pink"><a href="<?php echo base_url('settings/bank/').$banks->id;?>" target="_blank"><i class="icon-spinner10"></i></li>
						</ul>
						</td>
					</tr>
						<?php } }?>
						</tbody>
			</table>
			<?php if(!empty($bank)){?>
<div class="col-md-12 pr-0">
<div class="d-inline-block sumBank"><b>مجموع : </b></div>
<div class="d-inline-block sumBank lright"><b title="مجموع تعیین شده : <?php echo number_format($sumAmount);?>" data-toggle="tooltip"><?php echo number_format($sumAmount);?></b></div>
<div class="d-inline-block sumBank lright"><b title="مجموع پرداخت شده : <?php echo number_format($sumPay);?>" data-toggle="tooltip"><?php echo number_format($sumPay);?></b></div>
<div class="d-inline-block sumBank lright"><b title="مجموع باقی  مانده : <?php echo number_format($sumRest);?>" data-toggle="tooltip"><?php echo number_format($sumRest);?></b></div>
<div class="d-inline-block sumBank lright"><b title="مجموع هماهنگ نشده : <?php echo number_format($sumRestHandle);?>" data-toggle="tooltip"><?php echo number_format($sumRestHandle);?></b></div>
<div class="d-inline-block sumBank lright"><b title=" حجم تعیین نشده : &#xA;<?php echo number_format($forbank)." - ".number_format($notBank);?>" data-toggle="tooltip"><?php echo number_format($forbank - $notBank);?></b></div>
</div>

			<?php  }  ?>
			</div>
		</div>

</div>
<?php 
// handle perm
if($this->session->has_userdata('pay_all')){$payAllPerm = 1;}else{$payAllPerm = 0;}
if($this->session->has_userdata('pay_slice')){$paySlicePerm = 1;}else{$paySlicePerm = 0;}
if($this->session->has_userdata('restore')){$restorePerm = 1;}else{$restorePerm = 0;}
if($this->session->has_userdata('edit_handle')){$editHandlePerm = 1;}else{$editHandlePerm = 0;}
if($this->session->has_userdata('delete_handle')){$deleteHandlePerm = 1;}else{$deleteHandlePerm = 0;}


// handle perm
?>
<div>
	<div class="panel panel-flat" id='archive_handle'>
		<div class="panel-body">
		<fieldset <?php if($handleCount > 7){?> style="height:620px;"<?php }?> >
			<legend class="text-semibold"><i class="icon-notebook position-left"></i> اطلاعات هماهنگی معاملات خرید</legend>
			<table class="table datatable-basic table-responsive-lg">
				<thead>
					<tr>
						<th width="4%">ردیف </th>
						<th width="10%"> مشتری فروش</th>
						<th width="14%">حجم هماهنگ شده</th>
						<th width="14%">حجم پرداخت شده</th>
						<th width="16%">حجم باقی مانده </th>
						<th width="7%"> شناسه بانک</th>
						<th width="13%">توضیحات بانک</th>
						<th width="8%">تاریخ ثبت</th>
						<th width="10%" class="text-center"> ابزار</th>
					</tr>
				</thead>
				<tbody id="handleTable">
				<?php if(empty($handle)){ ?>
                        <tr><td colspan="10" class="text-center p-20">موردی یافت نشد</td></tr>
				<?php }else{ foreach($handle as $key =>  $handles){ ?>
					<tr>
						<td><?php echo $key + 1;?></td>
						<td><a href="<?php echo base_url('deal/profile/').$handles->cust_id;?>" target="_blank" class="enterCustomer"><?php echo $handles->fullname;?></a></td>
						<td class="<?php if($handles->volume_handle < $handles->handle_pay){echo 'text-danger';}?>"><?php echo number_format($handles->volume_handle);?></td>
						<td class="<?php if($handles->volume_handle < $handles->handle_pay){echo 'text-danger';}?>"><?php echo number_format($handles->handle_pay);?></td>
						<td class="<?php if($handles->handle_rest < 0){echo 'text-danger';}?>"><span title=" پرداخت شده -  هماهنگی &#xA;<?php echo number_format($handles->handle_pay)." - ".number_format($handles->volume_handle); ?>" data-toggle="tooltip"><?php echo number_format($handles->handle_rest);?></span></td>
						<td><?php echo $handles->bank_id; ?></td>
						<td><?php echo $handles->explain; ?></td>
						<td><?php echo $handles->date_handle."</br>".$handles->time_handle;?></td>
						<td class="text-center">
											<ul class="icons-list">
												<?php if($handles->handle_rest > 0){ ?>
<?php if($payAllPerm){?><li title="پرداخت کامل" data-toggle="tooltip" class="text-success"><a data-toggle="modal" href="#modal_theme_success"><i onclick="pay_all(<?php echo $handles->id;?> , <?php echo $handles->handle_rest;?>)" class="icon-checkmark4"></i></a></li><?php } ?>
<?php if($paySlicePerm){?><li title="پرداخت جزئی" data-toggle="tooltip" class="text-primary"><a data-toggle="modal" href="#modal_form_minor"><i onclick="pay_slice(<?php echo $handles->id;?> , <?php echo $handles->handle_rest;?>)" class="icon-stack-empty"></i></li><?php } ?>
													<?php } ?>
													
<?php if($restorePerm){?><li title="بازگشت پرداخت " data-toggle="tooltip" class="text-warning-800"><a data-toggle="modal" href="#modal_form_dminor"><i onclick="history(<?php echo $handles->id;?>)" class="icon-file-minus"></i></li><?php } ?>
<?php if($editHandlePerm){?><li title="ویرایش هماهنگی" data-toggle="tooltip" class="text-primary"><a data-toggle="modal" href="#modal_form_sminor"><i class="icon-pencil6" onclick="edit_handle(<?php echo $handles->id;?> , <?php echo $handles->volume_handle;?>)" ></i></a></li><?php } ?>									
<?php if($deleteHandlePerm){?><li title="حذف هماهنگی" data-toggle="tooltip" class="text-danger"><a data-toggle="modal" href="#modal_theme_danger"><i onClick="deleteHandle(<?php echo $handles->id; ?>, <?php echo $handles->handle_pay; ?>)" class="icon-cross2"></i></a></li><?php } ?>
											</ul>
						</td>
					</tr>
					<?php } }?>
					</tbody>
			</table>
			</fieldset>
<?php if(!empty($handle)){ ?>
<div class="text-left">
  <ul class="pagination">
<?php
if($handleCount > 7){
$base = floor($handleCount / 7);
if($handleCount % 7 != 0 ){
	$count = $base + 1;
}else{
	$count = $base;
}
$offset = 0; for($i = 0 ; $i < $count ; $i++){ ?>
<li class="handle <?php if($i == 0){ echo 'active';}?>"><a onclick = "handlePagin(<?php echo $offset; ?> , <?php echo $payAllPerm;?> , <?php echo $paySlicePerm; ?> , <?php echo $restorePerm;?> , <?php echo $editHandlePerm;?> , <?php echo $deleteHandlePerm;?> , 1 , this)"><?php echo $i + 1;?></a></li>
<?php $offset += 7; }
}
?>
  </ul>
</div>
<?php } ?>
<br>
<br>
<fieldset <?php if($handleCount2 > 7){?> style="height:620px;"<?php }?> >
			<legend class="text-semibold"><i class="icon-notebook position-left"></i> اطلاعات هماهنگی معاملات فروش</legend>
			<table class="table datatable-basic table-responsive-lg">
				<thead>
					<tr>
						<th width="4%">ردیف </th>
						<th width="10%"> مشتری خرید</th>
						<th width="14%">حجم هماهنگ شده</th>
						<th width="14%">حجم پرداخت شده</th>
						<th width="16%">حجم باقی مانده </th>
						<th width="7%"> شناسه بانک</th>
						<th width="13%">توضیحات بانک</th>
						<th width="8%">تاریخ ثبت</th>
						<th width="10%" class="text-center"> ابزار</th>
					</tr>
				</thead>
				<tbody id="handleTable2">
				<?php if(empty($handle2)){ ?>
                        <tr><td colspan="10" class="text-center p-20">موردی یافت نشد</td></tr>
				<?php }else{ foreach($handle2 as $key =>  $handles){ ?>
					<tr>
						<td><?php echo $key + 1;?></td>
						<td><a href="<?php echo base_url('deal/profile/').$handles->cust_id;?>" target="_blank" class="enterCustomer"><?php echo $handles->fullname;?></a></td>
						<td class="<?php if($handles->volume_handle < $handles->handle_pay){echo 'text-danger';}?>"><?php echo number_format($handles->volume_handle);?></td>
						<td class="<?php if($handles->volume_handle < $handles->handle_pay){echo 'text-danger';}?>"><?php echo number_format($handles->handle_pay);?></td>
						<td class="<?php if($handles->handle_rest < 0){echo 'text-danger';}?>"><span title=" پرداخت شده -  هماهنگی &#xA;<?php echo number_format($handles->handle_pay)." - ".number_format($handles->volume_handle); ?>" data-toggle="tooltip"><?php echo number_format($handles->handle_rest);?></span></td>
						<td><?php echo $handles->bank_id; ?></td>
						<td><?php echo $handles->explain; ?></td>
						<td><?php echo $handles->date_handle."</br>".$handles->time_handle;?></td>
						<td class="text-center">
											<ul class="icons-list">
												<?php if($handles->handle_rest > 0){ ?>
<?php if($payAllPerm){?><li title="پرداخت کامل" data-toggle="tooltip" class="text-success"><a data-toggle="modal" href="#modal_theme_success"><i onclick="pay_all(<?php echo $handles->id;?> , <?php echo $handles->handle_rest;?>)" class="icon-checkmark4"></i></a></li><?php } ?>
<?php if($paySlicePerm){?><li title="پرداخت جزئی" data-toggle="tooltip" class="text-primary"><a data-toggle="modal" href="#modal_form_minor"><i onclick="pay_slice(<?php echo $handles->id;?> , <?php echo $handles->handle_rest;?>)" class="icon-stack-empty"></i></li><?php } ?>
													<?php } ?>
													
<?php if($restorePerm){?><li title="بازگشت پرداخت " data-toggle="tooltip" class="text-warning-800"><a data-toggle="modal" href="#modal_form_dminor"><i onclick="history(<?php echo $handles->id;?>)" class="icon-file-minus"></i></li><?php } ?>
<?php if($editHandlePerm){?><li title="ویرایش هماهنگی" data-toggle="tooltip" class="text-primary"><a data-toggle="modal" href="#modal_form_sminor"><i class="icon-pencil6" onclick="edit_handle(<?php echo $handles->id;?> , <?php echo $handles->volume_handle;?>)" ></i></a></li><?php } ?>									
<?php if($deleteHandlePerm){?><li title="حذف هماهنگی" data-toggle="tooltip" class="text-danger"><a data-toggle="modal" href="#modal_theme_danger"><i onClick="deleteHandle(<?php echo $handles->id; ?>, <?php echo $handles->handle_pay; ?>)" class="icon-cross2"></i></a></li><?php } ?>
											</ul>
						</td>
					</tr>
					<?php } }?>
					</tbody>
			</table>
			</fieldset>
<?php if(!empty($handle2)){ ?>
<div class="text-left">
  <ul class="pagination">
<?php
if($handleCount2 > 7){
$base = floor($handleCount2 / 7);
if($handleCount2 % 7 != 0 ){
	$count = $base + 1;
}else{
	$count = $base;
}
$offset = 0; for($i = 0 ; $i < $count ; $i++){ ?>
<li class="handle2 <?php if($i == 0){ echo 'active';}?>"><a onclick = "handlePagin(<?php echo $offset; ?> , <?php echo $payAllPerm;?> , <?php echo $paySlicePerm; ?> , <?php echo $restorePerm;?> , <?php echo $editHandlePerm;?> , <?php echo $deleteHandlePerm;?> , 2 , this)"><?php echo $i + 1;?></a></li>
<?php $offset += 7; }
}
?>
  </ul>
</div>
<?php } ?>
			<!-- minor form modal -->
				<div id="modal_form_minor" class="modal fade">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h5 class="modal-title text-center">بخشی از مبلغ هماهنگ شده را به صورت جزئی پرداخت کنید</h5>

							</div>
							<hr>
							<form method="post" id="formSlicePay">
								<div class="modal-body">
								<div class="form-group">
								<label>مبلغ پرداختی:</label>
										<p id = "rest_slice" class="d-none"></p>
										<input type="text" placeholder="111,000,000" id="autofocuss" onkeyup='slice_input(this)' class="form-control" required>
										<input type="hidden" name="pay" readonly>
										<p class="text-danger d-none" style="position:absolute;top:65px;"></p>
								</div>
								</br>
									<div class="form-group input-group">
									<label>انتخاب تاریخ:</label>
										<input type="text"  class="form-control" name="date_pay" id="j_created_date" readonly data-mddatetimepicker="true" data-placement="bottom" value="<?php echo $date; ?>">
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
			<!-- pay all modal -->
				<div id="modal_theme_success" class="modal fade">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header bg-success">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title">پرداخت کامل</h4>
							</div>
                          <form method="post" id="formPayAll">
							<div class="modal-body">
								<h5 class="text-center"> آیا می خواهید تمام مبلغ <span id="paySpan"></span> در تاریخ <input type="text" style="width: 27%;display: inline-block;" class="form-control" name="date_pay" id="j_created_date" readonly data-mddatetimepicker="true"  data-placement="bottom" value="<?php echo $date; ?>"> پرداخت شود؟</h5>
                                <input type="hidden" name="pay" value="0" readonly id="payInput">
							</div>

							<div class="modal-footer text-center">
								<button type="button" class="btn btn-danger" data-dismiss="modal">خیر</button>
								<button type="submit" name="sub" class="btn btn-success">بله </button>
								</form>
							</div>
						</div>
					</div>
				</div>
			<!-- /pay all modal -->
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

<!-- modal_delete_deal -->
	<div id="modal_delete_deal" class="modal fade">
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
<!-- modal_delete_deal -->


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
												<input type="text"   onKeyUp="ambank(this)" placeholder="100,000" class="form-control">
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
	<!-- add bank modal -->

	<!-- edit bank modal -->
	<div id="edit_bank_modal" class="modal fade">
			<div class="modal-dialog" style="width:750px;">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title text-center">ویرایش بانک</h5>
					</div>
					<hr>
					<form action="" method="post" id='actionEditBank'>
						<div class="modal-body">
							<div class="field_wrapper2">
								<div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>شماره شبا : </label>
												<input onkeyup="show_bank(this)" id="numberShabaEdit" value="" data-mask="99-999-9999999999999999999" type="text" placeholder="06-017-0000000123014682799" name="shaba" class="form-control">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>بانک :</label>
												<span class="text-primary" style="font-size:12px; display:none;">(طبق شماره شبا وارد شده بانکی پیدا نشد. نام بانک را وارد کنید)</span>
												<input type="text" name="name" id="nameBankEdit" value="" placeholder="ملت،ملی،.." class="form-control">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label>مبلغ معامله : </label>
											<input type="hidden" id="amountPay" value =''>
											<input type="text" onkeyup="ambank(this)" placeholder="100,000" value="" class="form-control">
											<input type="hidden" value='' id="amountBank" name="amount">
											<p class="text-danger" style="display:none;"></p>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group input-group">
											<label>توضیحات حساب :</label>
											<input type="text" id="explainBank" name="bank_explain" value='' placeholder="توضیحات خود را وارد کنید" class="form-control">
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
	<!-- edit bank modal  -->


	<!--pay little modal -->
<div id="modalLittle" class="modal_logout">
  <div class="modal_content animate_logout">
    <div class="dang_modal">
      <span class="dang_body"></span>
      <span class="dang_dot"></span>
    </div>
    <div class="container_logout">
      <h1>پرداخت خرد</h1>
      <h6>مبلغی به اندازه <span id="spanLittle"></span> ریال از این معامله باقی مانده است .</h6>
	  <h6> آیا مایل به صفر شدن این مبلغ می باشید؟ </h6>
    </div>
	<a  class="btn btn-secendery" onclick="document.getElementById('modalLittle').style.display = 'none'">انصراف</a>
    <a  class="btn btn-danger" href="" id="confirmLittle">بله</a>
  </div>
</div>
<!--pay little modal -->


<!-- for search customer -->
		<?php
		$str = ''; $str2 = ''; $str3 = '';
		$count = sizeof($search);
		for($i = 1 ; $i <= $count ; $i++){
			$name = $search[$i]['fullname'];
			$buy  = $search[$i]['buy'];
			$sell = $search[$i]['sell'];
			$str .= "\"$name\",";
		   $str2 .= "\"$buy\"," ;
		   $str3 .= "\"$sell\",";
		}
		$str = trim($str , ','); $str2 = trim($str2 , ','); $str3 = trim($str3 , ',');
		?>
<!-- for search customer -->

<script type="text/javascript" src="<?php echo base_url('files/');?>assets/mine/handle_group.js"></script>

<script>

//pay little ------------------
var modalLittle =  document.getElementById('modalLittle');
	function payLittle(deal_id , amount){
	var spanLittle =  document.getElementById('spanLittle');
	var confirmLittle =  document.getElementById('confirmLittle');
	spanLittle.innerHTML = numeral(amount).format('0,0');	
	modalLittle.style.display = 'block';
	confirmLittle.setAttribute('href' , "<?php echo base_url('deal/pay_little/')?>" + deal_id + '/group');
	}
//pay little ------------------

//delete deal -----------------
var titleDelete = document.getElementById('titleDelete');
	var closeDelete = document.getElementById('closeDelete');
	var confirmDelete = document.getElementById('confirmDelete');
	function deleteDeal(id , pay){
      if(pay != 0){
		  titleDelete.innerHTML = " حجم پرداختی این معامله صفر نمی باشد . اگر مایل به حذف معامله می باشید جهت جلوگیری از ناسازگاری در سامانه ابتدا مبالغ پرداختی را بازگردانید. ";
		  closeDelete.style.display = 'none';
		  confirmDelete.style.display = 'none';
		  return;
	  }else{
		  titleDelete.innerHTML = " آیا می خواهید ادامه دهید؟";
		  closeDelete.style.display = 'inline-block';
		  confirmDelete.style.display = 'inline-block';
		  confirmDelete.setAttribute('href' , "<?php echo base_url('deal/delete_deal/')?>" + id + "/group");
	  }
	}
//delete deal -----------------

//dealPagin ------------------
function dealPagin(offset , editPerm , photoPerm , deletePerm , littlePerm , li){

var classDeal = document.getElementsByClassName('deal');
for(i = 0 ; i < classDeal.length ; i++){
classDeal[i].classList.remove('active');
}
li.parentElement.classList.add('active');
	var xhr = new XMLHttpRequest();
		xhr.onload = function () {
			if ( ( xhr.status >= 200 && xhr.status < 300 ) || xhr.status == 304 ) {
				var result = JSON.parse( xhr.responseText );
				var url = "<?php echo base_url();?>";
				showDeal( result , url , editPerm , photoPerm , deletePerm , littlePerm);
			} else {
				alert( 'request was unsuccessful : ' + xhr.status );
				location.reload(); 
			}
		}
		xhr.open( 'post', "<?php echo base_url('deal/paginDeal/')?>", true );
		xhr.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' );
		xhr.send('offset='+offset+'&id='+<?php echo $this->uri->segment(3);?>);
}
//dealPagin -------------------


//get bank---------------------
function getBank(id){
	var xhr = new XMLHttpRequest();
		xhr.onload = function(){
			if((xhr.status >= 200 && xhr.status < 300) || xhr.status == 304){
				    var url = "<?php echo base_url('deal/edit_bank') ?>";
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
//get bank---------------------

//for search customer

var array  = [ <?php echo $str;?> ];
var array2 = [<?php echo $str2;?> ];
var array3 = [<?php echo $str3;?> ];
function search_cust( input ) {
autocomplete( input, array , array2 , array3);
}

//for search customer

//handlePagin ------------------
function handlePagin(offset , payAllPerm , paySlicePerm , restorePerm , editHandlePerm ,deleteHandlePerm , which ,  li){
if(which == 1){
	var classDeal = document.getElementsByClassName('handle');
}else{
	var classDeal = document.getElementsByClassName('handle2');
}
for(i = 0 ; i < classDeal.length ; i++){
classDeal[i].classList.remove('active');
}
li.parentElement.classList.add('active');
	var xhr = new XMLHttpRequest();
		xhr.onload = function () {
			if ( ( xhr.status >= 200 && xhr.status < 300 ) || xhr.status == 304 ) {
				var result = JSON.parse( xhr.responseText );
				var url = "<?php echo base_url();?>";
				showHandle( result , url , payAllPerm , paySlicePerm , restorePerm , editHandlePerm , deleteHandlePerm , which);
			} else {
				alert( 'request was unsuccessful : ' + xhr.status );
				location.reload(); 
			}
		}
		xhr.open( 'post', "<?php echo base_url('deal/paginHandle/')?>", true );
		xhr.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' );
		xhr.send('offset='+offset+'&which='+which+'&id='+<?php echo $this->uri->segment(3);?>);
}
//handlePagin -------------------

//pay all------------------------		
function pay_all( link, rest) {
				document.getElementById( 'formPayAll' ).setAttribute( 'action', "<?php echo base_url("deal/pay/").$this->uri->segment(3)."/";?>" + link + '/all' );
				document.getElementById( 'payInput' ).value =  numeral( rest ).value();
				document.getElementById( 'paySpan' ).innerHTML =  numeral( rest ).format( '0,0' );
			}
//pay all-----------------------

var formEdit = document.getElementById('form_edit');
var ihandle = document.getElementById('ihandle');
function edit_handle(id , volume){
ihandle.value = numeral(volume).format('0,0');
ihandle.nextElementSibling.value = volume;
formEdit.action = "<?php echo base_url('deal/edit_handle/').$this->uri->segment(3)."/";?>" + id;
}

		
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

//pay slice----------------------				
			function pay_slice( id , rest ) {
				document.getElementById( 'formSlicePay' ).setAttribute( 'action', "<?php echo base_url("deal/pay/").$this->uri->segment(3)."/";?>"+ id );
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