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

	<table class="table datatable-selection-single table-hover table-responsive-lg ">
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
		<tbody id="search_deal" tyle="display: none;">
		</tbody>
		<tbody id="deal_cust">
		<?php 
		if(sizeof($deal) == 0){ ?>
            <tr><td colspan="11" class="text-center p-20">موردی یافت نشد</td></tr>
		<?php }else{
			foreach($deal as  $deals){?>
			<tr>
                <td class="text-center"><?php echo $deals->id + 100; ?></td>
                <td class="text-center"><?php echo $deals->fullname; ?></td>
				<td class="text-center"><?php if($deals->type_deal == 1 ){echo 'خرید';}else{echo 'فروش';} ?></td>
				<td class="text-center"><?php echo number_format($deals->count_money) . " " . $deals->name;?></td>
				<td class="text-center"><?php echo number_format($deals->convert_money); ?></td>
				<td class="text-center <?php if($deals->volume_deal < $deals->volume_pay){echo 'text-danger';}?>"><?php echo number_format($deals->volume_deal); ?> </td>
				<td class="text-center <?php if($deals->volume_deal < $deals->volume_pay){echo 'text-danger';}?>"><?php echo number_format($deals->volume_pay); ?> </td>
				<td class="text-center <?php if($deals->volume_rest < 0){echo 'text-danger';}?>" ><?php echo number_format($deals->volume_rest); ?></td>
				<td class="text-center"><?php echo $deals->date_deal."</br>".$deals->time_deal; ?> </td>
				<td class="text-center"><?php if($deals->date_modified == ''){echo 'ثبت نشده است';}else{ echo $deals->date_modified;}?></td>
				<td class="text-center">
					<ul class="icons-list">
<?php if($this->session->has_userdata('see_handle') and $this->session->userdata('see_handle') == TRUE and $deals->type_deal == 1){?><li title="هماهنگی ها" data-toggle="tooltip" class="text-success"><a href="<?php echo base_url('deal/handle/').$deals->id ;?>"><i class="icon-notebook"></i></a></li><?php } ?>
<?php if($this->session->has_userdata('edit_deal') and $this->session->userdata('edit_deal') == TRUE){?><li title="ویرایش معامله" data-toggle="tooltip" class="text-primary"><a href="<?php echo base_url('deal/edit/').$deals->id;?>"><i class=" icon-pencil6"></i></a></li><?php } ?>
<?php if($this->session->has_userdata('see_photo') and $this->session->userdata('see_photo') == TRUE){?><li title="مشاهده قبض" data-toggle="tooltip" class="text-indigo-600"><a href="<?php echo base_url('deal/photo/').$deals->id;?>"><i class="icon-stack-picture"></i></a></li><?php }?>
<?php if($this->session->has_userdata('add_bank') and $this->session->userdata('add_bank') == TRUE and $deals->type_deal == 1){?><li title="افزودن بانک" data-toggle="tooltip" class="text-primary"><a data-toggle="modal" href="#add_bank_modal"><i onclick = "add_bank(<?php echo $deals->id;?>)" class="icon-credit-card"></i></li><?php } ?>
<?php if($this->session->has_userdata('delete_deal') and $this->session->userdata('delete_deal') == TRUE){?><li class="text-danger" data-toggle="tooltip" title="حذف معامله"><a data-toggle="modal" href="#modal_theme_danger1"><i  class="icon-trash" onclick = "deleteDeal(<?php echo $deals->id;?> , <?php echo $deals->volume_pay; ?>)" ></i></a></li><?php } ?>
					</ul>
				</td>
			
			</tr>
			<?php } ?>
			<tr>
				<td colspan="6" class="pt-20 pb-20"></td>
				<td colspan="5" class="text-left pt-20 pb-20">
					<?php echo $page; ?>
				</td>
			</tr>
			<?php }?>
		</tbody>

	</table>


</div>
</div>
<p style="display: none;" id="cid"><?php echo $this->uri->segment(3)?></p>
<?php if($this->session->has_userdata('add_handle') and $this->session->userdata('add_handle') == TRUE){?>
	<div class="panel panel-flat">
		<div class="panel-body">
		<form action="<?php echo base_url('deal/handle_profile/').$this->uri->segment(3);?>" method="post">
			<legend class="text-semibold"><i class="icon-address-book position-left"></i> افزودن هماهنگی</legend>
			<div class="row field_wrapper4">
				<div>
					<div class="col-md-3">
						<div class="form-group">
							<label>نام مشتری :</label>
							<input type="text" name="customer[]" onFocus ="search_customer(this)" placeholder="نام مشتری خود را وارد کنید"  autocomplete="off" class="form-control" required>
							<p class="text-primary" style="display:none; position:absolute;font-size:12px;"></p>
						</div>
					</div>
						<div class="col-md-3">
						<div class="form-group">
							<label>انتخاب معامله:</label>
							<input type="text" name="deal_id[]" onkeyup="select_deal(this)" autocomplete="off" placeholder="لطفا شناسه معامله را وارد کنید" class="form-control" required>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>انتخاب حساب :</label>
                            <select class="form-control" name="bank_id[]" required>
                         <?php if(sizeof($select2) == 0){ ?>
							<option value="0">شماره حسابی ثبت نشده است</option>
						 <?php } else { foreach($select2 as $selects){
							 $a = $selects->deal_id + 100;
							 $aa = $selects->id + 1000;
							 ?>
						
							<option value="<?php echo $selects->id;?>"><?php echo ' شناسه بانک  : '. $aa .' | شناسه معامله  :'.$a; ?></option>
						 <?php } }?>
											</select>
						</div>
					</div>
					
					<div class="col-md-3">
						<div class="form-group input-group">
							<label>مبلغ هماهنگی :</label>
							<input type="text" placeholder="111,000,000"  onkeyup="amhandle(this)" autocomplete="off" class="form-control" required>
							<input type = "hidden" name='volume_handle[]'>
							<span class="input-group-btn">
							<button type="button" class="btn btn-success add_button4 mt-25">
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
			</form>
		</div>
	</div>
<?php } ?>
<div>
	<div class="panel panel-flat">
		<div class="panel-body">
			<legend class="text-semibold"><i class="icon-credit-card position-left"></i> اطلاعات بانکی </legend>
			<table class="table datatable-basic">
				<thead>
					<tr>
						<th width="8%"  class="text-center">شناسه بانک</th>
						<th width="8%" class="text-center">شناسه معامله</th>
						<th width="14%" class="text-center">نام بانک</th>
						<th width="16%" class="text-center">شماره شبا</th>
						<th width="18%" class="text-center">حجم تعیین شده</th>
						<th width="18%" class="text-center">حجم واریز شده</th>
						<th width="10%" class="text-center">وضعیت</th>
						<th width="8%" class="text-center">ابزار</th>
					</tr>
				</thead>
				<tbody>
				
				<?php
				if(sizeof($bank) == 0){ ?>
                  <tr><td colspan="8" class="text-center p-20">موردی یافت نشد</td></tr>
				<?php }
				foreach($bank as $key => $banks){
					?>
					<tr>
						<td  class="text-center"><?php echo $banks->id + 1000 ;?></td>
						<td  class="text-center"><?php echo $banks->deal_id + 100;?></td>
						<td  class="text-center"><?php echo $banks->name_bank; ?></td>
						<td  class="text-center"><?php echo $banks->number_shaba; ?></td>
						<td  class="text-center"><?php echo number_format($banks->amount); ?></td>
						<td  class="text-center <?php if($banks->pay > $banks->amount){echo 'text-danger';}?>"><?php echo number_format($banks->pay); ?></td>
						<?php if($banks->active == 1){$class="success";$txt = 'فعال'; $act = 0;}else{$class = "danger"; $txt = 'غیرفعال'; $act = 1;} ?>
				<td class="text-center"><?php if($this->session->has_userdata('active_bank') and $this->session->userdata('edit_bank') == TRUE){ ?><a href="<?php echo base_url('deal/active/').$this->uri->segment(3)."/".$banks->id."/".$act."/group"; ?>"><span class="label label-<?php echo $class; ?>"><?php echo $txt;?></span></a><?php } ?></td>
						</td>
						<td class="text-center">
									<ul class="icons-list">

				<?php if($this->session->has_userdata('edit_bank') and $this->session->userdata('edit_bank') == TRUE){?><li title="ویرایش بانک" data-toggle="tooltip" class="text-primary"><a data-toggle="modal" href="#edit_bank_modal"><i onclick = "edit_bank(<?php echo $banks->id;?>)" class="icon-credit-card"></i></li><?php } ?>
									</ul>
						</td>
					</tr>
					<tr>
				<?php } ?>
			</table>
			</div>
		</div>

		</div>
<div>
	<div class="panel panel-flat">
		<div class="panel-body">
			<legend class="text-semibold"><i class="icon-notebook position-left"></i> اطلاعات هماهنگی</legend>
			<table class="table datatable-basic">
				<thead>
					<tr>
						<th width="8%">شناسه معامله</th>
						<th width="10%">نام مشتری</th>
						<th width="12%">حجم هماهنگ شده</th>
						<th width="12%">حجم پرداخت شده</th>
						<th width="12%">حجم باقی مانده </th>
						<th width="16%">اطلاعات حساب</th>
						<th width="10%">تاریخ ثبت</th>
						<th width="10%"> آخرین تغییر</th>
						<th width="12%" class="text-center"> ابزار</th>
					</tr>
				</thead>
				<tbody>
				<?php if(sizeof($handle) == 0){ ?>
                        <tr><td colspan="9" class="text-center p-20">موردی یافت نشد</td></tr>
				<?php }else{ foreach($handle as $handles){ ?>
					<tr>
						<td><?php echo $handles->deal_id + 100;?></td>
						<td><?php echo $handles->fullname;?></td>
						<td><?php echo number_format($handles->volume_handle);?></td>
						<td><?php echo number_format($handles->handle_pay);?></td>
						<td><?php echo number_format($handles->handle_rest);?></td>
						<td><?php echo $handles->number_shaba ."</br>".$handles->name_bank; ?></td>
						<td><?php echo $handles->date_handle."</br>".$handles->time_handle;?></td>
						<td><?php if($handles->date_modified == ''){echo 'ثبت نشده است';}else{echo $handles->date_modified;}?></td>
						<td class="text-center">
											<ul class="icons-list">
												<?php if($handles->handle_rest > 0){?>
<?php if($this->session->has_userdata('pay_all') and $this->session->userdata('pay_all') == TRUE){?><li title="پرداخت کامل" data-toggle="tooltip" class="text-success"><a data-toggle="modal" href="#modal_theme_success"><i onclick="pay_all(<?php echo $handles->id;?> , <?php echo $handles->handle_rest;?> , <?php echo $handles->deal_id?>)" class="icon-checkmark4"></i></a></li><?php } ?>
<?php if($this->session->has_userdata('pay_slice') and $this->session->userdata('pay_slice') == TRUE){?><li title="پرداخت جزئی" data-toggle="tooltip" class="text-primary"><a data-toggle="modal" href="#modal_form_minor"><i onclick="pay_slice(<?php echo $handles->id;?> , <?php echo $handles->deal_id;?>)" class="icon-stack-empty"></i></li><?php } ?>
													<?php } ?>
													
<?php if($this->session->has_userdata('restore') and $this->session->userdata('restore') == TRUE){?><li title="بازگشت پرداخت " data-toggle="tooltip" class="text-warning-800"><a data-toggle="modal" href="#modal_form_dminor"><i onclick="history(<?php echo $handles->id;?> , <?php echo $handles->deal_id; ?>)" class="icon-file-minus"></i></li><?php } ?>
									
<?php if($this->session->has_userdata('delete_deal') and $this->session->userdata('delete_deal') == TRUE){?><li title="حذف هماهنگی" data-toggle="tooltip" class="text-danger"><a data-toggle="modal" href="#modal_theme_danger"><i onClick="deleteHandle(<?php echo $handles->id; ?>, <?php echo $handles->handle_pay; ?>)" class="icon-cross2"></i></a></li><?php } ?>
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
									<div class="form-group input-group">
										<label>مبلغ هماهنگی:</label>
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
											<input type="text" onkeyup="ambank(this)" placeholder="100000" value="" class="form-control">
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
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h5 class="modal-title text-center">افزودن بانک</h5>

						</div>
						<hr>
						<form action="" id = "act_bank" method="post">
							<div class="modal-body">
								<div class="field_wrapper2">
									<div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label>شماره شبا: </label>
													<input onkeyup="show_bank(this)" data-mask="99-999-9999999999999999999" type="text" placeholder="06-017-0000000123014682799" name="number_shaba" class="form-control">
												</div>
											</div>



											<div class="col-md-6">
												<div class="form-group">
													<label>بانک:</label>
													<span class="text-primary" style="font-size:12px; display:none;">(طبق شماره شبا وارد شده بانکی پیدا نشد. نام بانک را وارد کنید)</span>
													<input type="text" name="name_bank" placeholder="ملت،ملی،.." class="form-control">
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
												<input type="hidden" name="amount_bank">
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
		$str = '';foreach($customer as $row){$str .= "\"$row->fullname\",";}$str = trim($str , ",");
		$str2 = '';foreach($want_rial as $value){$str2 .= "\"$value\",";}$str2 = trim($str2 , ",");
		$str3 = '';foreach($give_rial as $value2){$str3 .= "\"$value2\",";}$str3 = trim($str3 , ",");
		?>
		<script type="text/javascript" src="<?php echo base_url('files/');?>assets/mine/handle_group.js"></script>
		<script>
//add bank --------------------
	var act_bank = document.getElementById('act_bank');
	function add_bank(id){
		act_bank.action = "<?php echo base_url('deal/add_bank/')?>" + id +"/<?php echo $this->uri->segment(3); ?>" + "/group"
	}
//add bank --------------------	
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
		  titleDelete.innerHTML = "با حذف معامله تمام اطلاعات مربوط به معامله ، هماهنگی ها ،اطلاعات بانکی حذف خواهد شد.</br> آیا می خواهید ادامه دهید؟";
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
				   confirmHandle.setAttribute('href' , "<?php echo base_url('deal/delete_handle/');?>" + id + "<?php echo "/".$this->uri->segment(3)."/group"; ?>");
			   }
			}			
//delete handle -----------------	
//pay all -----------------------		
			function pay_all( link, rest , deal_id ) {
	document.getElementById( 'button_all' ).setAttribute( 'href', "<?php echo base_url("deal/pay_all/")?>"+ deal_id +'/'+link + '/group/'+<?php echo $this->uri->segment(3);?> );
				document.getElementById( 'p_all' ).innerHTML = " آیا می خواهید تمام مبلغ " + numeral( rest ).format( '0,0' ) + " پرداخت شود ؟";
			}
//pay all -----------------------
//pay slice----------------------				
			function pay_slice( link , deal_id ) {
				document.getElementById( 'form_slice' ).setAttribute( 'action', "<?php echo base_url("deal/pay_slice/")?>"+deal_id+'/' + link + "/group/"+<?php echo $this->uri->segment(3);?> );
			}
			function slice_input( input ) {
				input.value = numeral( input.value ).format( '0,0' );
				input.nextElementSibling.value = numeral( input.value ).value();
			}
//pay slice----------------------
var customer = [ <?php echo $str; ?> ];
var want = [<?php echo $str2; ?>];
var give = [<?php echo $str3;?>];

			function search_customer( input ) {
				autocomplete( input, customer , want , give );
			}
//show deal----------------------			
			var search = document.getElementById('search_deal');
			var base = document.getElementById('deal_cust');
			var cid = document.getElementById('cid').innerHTML;
			function select_deal(input){
				var text = input.value;
				if(text == '' || isNaN(text) || text.length < 3){
			search.style.display = 'none';
			base.style.display = 'contents';
					return;
				}
		var xhr = new XMLHttpRequest();
		xhr.onload = function () {
			if ( ( xhr.status >= 200 && xhr.status < 300 ) || xhr.status == 304 ) {
				var result = JSON.parse( xhr.responseText );
				 showDeal( result, input, search );
			} else {
				alert( 'request was unsuccessful : ' + xhr.status );
			}
		}
		text = text - 100;
		xhr.open( 'post', "<?php echo base_url('deal/search_deal/')?>", true );
		xhr.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' );
		xhr.send( 'deal_id=' + text + "&customer_id=" + cid );
				
			}
			function showDeal(res , input , search){
			var len = res.length;
			base.style.display = 'none';
		 	search.style.display = 'contents';
		    if(len == 0){
		 	search.innerHTML = '<tr><td colspan="11" class="text-center p-20">موردی یافت نشد</td></tr>';
		 	return;
		    }else{
			var div = document.createElement( 'tbody' );
			for ( var i = 0; i < len ; i++ ) {
				var tr = div.appendChild( document.createElement( 'tr' ) );
				tr.style.backgroundColor = '#eafaf9';
				var td_row = tr.appendChild( document.createElement('td'));
				td_row.innerHTML = Number(res[i].id) + Number(100);
				td_row.setAttribute('class' , 'text-center');
				
				var td_fullname = tr.appendChild( document.createElement( 'td' ) );
			    td_fullname.innerHTML = res[i].fullname;
				td_fullname.setAttribute('class' , 'text-center');
				
				var td_type = tr.appendChild( document.createElement('td'));
				if(res[i].type_deal == 1){var type = 'خرید';}
				else{var type = 'فروش';}
				td_type.innerHTML = type;
				td_type.setAttribute('class' , 'text-center');
				
				var td_count = tr.appendChild( document.createElement( 'td' ) );
				td_count.innerHTML = numeral(res[i].count_money).format('0,0') + ' ' + res[i].name;
                td_count.setAttribute('class' , 'text-center');
				
				var td_convert = tr.appendChild(document.createElement('td'));
				td_convert.innerHTML = numeral(res[i].convert_money).format('0,0');
				td_convert.setAttribute('class' , 'text-center');
				
				var td_volume = tr.appendChild(document.createElement('td'));
				td_volume.innerHTML = numeral(res[i].volume_deal).format('0,0');
				td_volume.setAttribute('class' , 'text-center');
				
				var td_pay = tr.appendChild(document.createElement('td'));
				td_pay.innerHTML = numeral(res[i].volume_pay).format('0,0');
				td_pay.setAttribute('class' , 'text-center');
				
				var td_rest = tr.appendChild(document.createElement('td'));
				td_rest.innerHTML = numeral(res[i].volume_rest).format('0,0');
				td_rest.setAttribute('class' , 'text-center');
				
				var td_date = tr.appendChild(document.createElement('td'));
				td_date.innerHTML = res[i].date_deal + '</br>' + res[i].time_deal;
                 td_date.setAttribute('class' , 'text-center');
				
				var td_modify = tr.appendChild(document.createElement('td'));
				td_modify.innerHTML = res[i].date_modified;
                 td_modify.setAttribute('class' , 'text-center');
				
				var td_tool = tr.appendChild( document.createElement( 'td' ) );
				td_tool.setAttribute( 'class', 'text-center' );

				var ul_tool = td_tool.appendChild( document.createElement( 'ul' ) );
				ul_tool.setAttribute( 'class', 'icons-list' );

				var li_handle = ul_tool.appendChild( document.createElement( 'li' ) );
				li_handle.setAttribute( 'class', "text-success" );
				var a_handle = li_handle.appendChild( document.createElement( 'a' ) )
				a_handle.setAttribute( 'href', "<?php echo base_url('deal/handle/')?>" + res[ i ].id );
				var i_handle = a_handle.appendChild( document.createElement( 'i' ) );
				i_handle.setAttribute( 'class', 'icon-notebook' );

				var li_detail = ul_tool.appendChild( document.createElement( 'li' ) );
				li_detail.setAttribute( 'class', "text-primary" );
				var a_detail = li_detail.appendChild( document.createElement( 'a' ) )
				a_detail.setAttribute( 'href', "<?php echo base_url('deal/edit/')?>" + res[ i ].id );
				var i_detail = a_detail.appendChild( document.createElement( 'i' ) );
				i_detail.setAttribute( 'class', 'icon-pencil6' );
				
				var li_photo = ul_tool.appendChild( document.createElement( 'li' ) );
				li_photo.setAttribute( 'class', "text-indigo-600" );
				var a_photo = li_photo.appendChild( document.createElement( 'a' ) )
				a_photo.setAttribute( 'href', "<?php echo base_url('deal/photo/')?>" + res[ i ].id );
				var i_photo = a_photo.appendChild( document.createElement( 'i' ) );
				i_photo.setAttribute( 'class', 'icon-stack-picture' );

				var li_delete = ul_tool.appendChild( document.createElement( 'li' ) );
				li_delete.setAttribute( 'class', "text-danger-600" );
				var a_delete = li_delete.appendChild( document.createElement( 'a' ) )
				a_delete.setAttribute( 'href', "<?php echo base_url('deal/delete/')?>" + res[ i ].id );
				var i_delete = a_delete.appendChild( document.createElement( 'i' ) );
				i_delete.setAttribute( 'class', 'icon-trash' );
			}
			search.replaceChild( div, search.firstChild );
				
			}
			}
//show deal----------------------
//show bank---------------------
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
				act_edit.action = "<?php echo base_url('deal/edit_bank/').$this->uri->segment(3);?>" + "/" + result.id + '/group';
				num_shaba.value = result.number_shaba;
				nam_bank.value = result.name_bank;
				amo_pay.value = result.pay;
				amo_bank.value = result.amount;
                amo_bank.previousElementSibling.value = numeral(result.amount).format('0,0') ;

			}
//show bank---------------------
function history(id , deal_id){
		var xhr = new XMLHttpRequest();
		xhr.onload = function(){
			if((xhr.status >= 200 && xhr.status < 300) || xhr.status == 304){
				
					var result = JSON.parse(xhr.responseText);
				    showHistory(result , deal_id);
				}else{
					alert('request was unsuccessful : ' + xhr.status);
				}
		}
		xhr.open('post' , "<?php echo base_url('deal/get_history/')?>" , true);
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhr.send('handle_id=' + id);
			}
			function showHistory(res , deal_id){
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
			a.setAttribute('href' , "<?php echo base_url('deal/restore/') ?>"+res[i].id + '/' + deal_id + '/group/'+<?php echo $this->uri->segment(3);?> );
						a.innerHTML = 'حذف';
						
					}
					modal.replaceChild(div , modal.firstChild);
				}
			}
		</script>