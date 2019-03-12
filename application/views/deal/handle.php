<?php if($this->session->has_userdata('msg')){
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
		<li><a href="<?php echo base_url("deal/handle/").$this->uri->segment(3); ?>">معامله</a>
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
									<label>نام مشتری: </label>
									<p class="form-control"><?php echo $deal->fullname;?></p>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label>نوع معامله: </label>
									<p class="form-control"><?php if($deal->type_deal == 1){echo 'خرید';}else{echo 'فروش';}?></p>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label>تعداد ارز: </label>
									<p class="form-control"><?php echo number_format($deal->count_money)." ".$deal->name; ?></p>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label>حجم معامله : </label>
									<p class="form-control"  id="volume_deal" ><?php echo number_format($deal->volume_deal) . " ریـال ";?></p>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-3">
								<div class="form-group">
									<label>حجم پرداخت شده : </label>
									<p class="form-control"><?php echo number_format($deal->volume_pay) . " ریـال ";?></p>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label>حجم قابل پرداخت: </label>
									<p class="form-control"><?php echo number_format($deal->volume_rest). " ریـال ";?></p>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label>حجم هماهنگ شده : </label>
									<p class="form-control"><?php echo number_format($deal->vh). " ریـال ";?></p>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label>مانده هماهنگ شده: </label>
									<p class="form-control"><?php echo number_format($deal->vr)." ریـال ";?></p>
								</div>
							</div>
						</div>
						<?php if($deal->explain != ''){ ?>
						<div class="form-group">
							<label>توضیحات معامله: </label>
							<p class="form-control"><?php echo $deal->explain; ?></p>
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
			<a class="btn btn-success float-btn-left" href="#add_bank_modal" data-toggle="modal">افزودن بانک</a>
			<legend class="text-semibold"><i class="icon-credit-card position-left"></i> اطلاعات بانکی </legend>
			<table class="table datatable-basic">
				<thead>
					<tr>
						<th width="4%">ردیف</th>
						<th width="10%">نام بانک</th>
						<th width="17%">شماره شبا</th>
						<th width="15%">حجم تعیین شده</th>
						<th width="15%">حجم واریز شده</th>
						<th width="15%">توضیحات</th>
						<th width="10%">وضعیت</th>
						<th width="8%" class="text-center">ابزار</th>
					</tr>
				</thead>
				<tbody>
				<?php if(sizeof($bank) == 0){?>
				<tr><td colspan="8" class="text-center p-20">موردی یافت نشد</td></tr>
				<?php }else{
					 foreach($bank as $key => $rows){ ?>
					<tr>
						<td><?php echo $key+1; ?></td>
						<td><?php echo $rows->name_bank;?></td>
						<td><?php echo $rows->number_shaba;?></td>
						<td><?php echo number_format($rows->amount); ?></td>
						<td><?php echo number_format($rows->pay); ?></td>
						<td><?php echo $rows->explain; ?></td>
						<?php if($rows->active == 1){$class="success";$txt = 'فعال'; $act = 0;}else{$class = "danger"; $txt = 'غیرفعال'; $act = 1;} ?>
						<td><a href="<?php echo base_url('deal/active/').$deal->id."/".$rows->id."/".$act; ?>"><span class="label label-<?php echo $class; ?>"><?php echo $txt;?></span></td></a>
						<td class="text-center">
									<ul class="icons-list">
										<li title="ویرایش بانک" class="text-primary"><a data-toggle="modal" href="#edit_bank_modal"><i class="icon-credit-card"></i></li>
									</ul>
						</td>
					</tr>
					<tr>
					<?php  }}?>
			</table>
			<!-- add bank modal -->
			<div id="add_bank_modal" class="modal fade">
				<div class="modal-dialog">
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
													<label>شماره شبا: </label>
													<input onkeyup="show_bank(this)" data-mask="aa-99-999-9999999999999999999" type="text" placeholder="IR-06-017-0000000123014682799" name="number_shaba" class="form-control" required>
												</div>
											</div>



											<div class="col-md-6">
												<div class="form-group">
													<label>بانک:</label>
													<input type="text" name="name_bank" placeholder="ملت،ملی،.." class="form-control" readonly>
												</div>
											</div>
										</div>


									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>مبلغ معامله: </label>
												<input type="text" id="ambank" placeholder="100000" class="form-control" required>
												<input type="hidden" name="amount_bank">
												<p class="text-danger" style="display:none;">مبلغ وارد شده بیشتر از حجم معامله است</p>
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
				<!-- /add bank modal -->

			</div>
		</div>
		
			
			
		</div>
	</div>
<form action="<?php echo base_url('deal/handle/').$deal->id;?>" method="post">
	<div class="panel panel-flat">
		<div class="panel-body">
			<div class="row field_wrapper3">
				<div>
					<legend class="text-semibold"><i class="icon-address-book position-left"></i> افزودن هماهنگی</legend>
					<div class="col-md-4">
						<div class="form-group">
							<label>نام مشتری:</label>
							<input class="form-control" onFocus="search_customer(this)" name="customer[]" type="text" placeholder="نام مشتری خود را وارد کنید" autocomplete="off" required>
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
												<option value="<?php echo $selects->id;?>"><?php echo $selects->number_shaba. " | ". $selects->name_bank ?></option>
                                                <?php }} ?>
											</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group input-group">
							<label>مبلغ هماهنگی:</label>
							<input type="text" onkeyup="volume_handle(this)" placeholder="111,000,000" class="form-control" required>
							<input type="hidden" name="volume_handle[]">
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
				<button type="submit" name="sub" class="btn btn-primary">ثبت هماهنگی <i class="icon-arrow-left13 position-right"></i></button>
			</div>
		</div>
	</div>
	</form>
	<div class="panel panel-flat">
		<div class="panel-body">
			<legend class="text-semibold"><i class="icon-notebook position-left"></i> اطلاعات هماهنگی</legend>
			<table class="table datatable-basic">
				<thead>
					<tr>
						<th width="4%">ردیف</th>
						<th width="12%">نام مشتری</th>
						<th width="12%">حجم هماهنگ شده</th>
						<th width="12%">حجم پرداخت شده</th>
						<th width="12%">حجم باقی مانده</th>
						<th width="12%">اطلاعات حساب</th>
						<th width="10%">تاریخ ثبت</th>
						<th width="12%">آخرین ویرایش</th>
						<th width="14%" class="text-center">ابزار</th>
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
						<td><?php echo number_format($row->handle_pay); ?></td>
						<td><?php echo number_format($row->handle_rest); ?></td>
						<td><?php echo $row->number_shaba."<br>".$row->name_bank; ?></td>
						<td><?php echo $row->date_handle."</br>".$row->time_handle; ?></td>
						<td><?php if($row->date_modified == ''){echo '-';}else{echo $row->$date_modified;} ?></td>
						</td>
						<td class="text-center">
								<ul class="icons-list">
				<li title="پرداخت کامل" class="text-success"><a data-toggle="modal" href="#modal_theme_success"><i onclick="pay_all(<?php echo $row->id;?> , <?php echo $row->handle_rest;?>)" class="icon-checkmark4"></i></a></li>
				<li title="پرداخت جزئی" class="text-primary"><a data-toggle="modal" href="#modal_form_minor"><i onclick="pay_slice(<?php echo $row->id;?>)" class="icon-stack-empty"></i></li>
				<li title="حذف هماهنگی" class="text-danger"><a data-toggle="modal" href="#modal_theme_danger"><i class="icon-cross2"></i></a></li>
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
						<form method = "post" id="form_slice">
							<div class="modal-body">
								<div class="form-group input-group">
									<label>مبلغ هماهنگی:</label>
									<input type="text" placeholder="111,000,000" onkeyup ='slice_input(this)' class="form-control">
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
							<a id="button_all"  class="btn btn-success">بله </a>
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

						<h5 class="text-center">آیا میخواهید هماهنگی حذف شود ؟</h5>


					</div>

					<div class="modal-footer text-center">
						<button type="button" class="btn btn-danger" data-dismiss="modal">خیر</button>
						<button type="button" class="btn btn-success">بله </button>
					</div>
				</div>
			</div>
		</div>
		<!-- /success modal -->
	</div>
	<!-- edit bank modal -->
	<div id="edit_bank_modal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title text-center">ویرایش بانک</h5>

				</div>
				<hr>
				<form action="#">
					<div class="modal-body">
						<div class="field_wrapper2">
							<div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label>شماره شبا: </label>
											<input onkeyup="show_bank(this)" data-mask="aa-99-999-9999999999999999999" type="text" placeholder="IR-06-017-0000000123014682799" name="number_shaba[]" class="form-control">
										</div>
									</div>



									<div class="col-md-6">
										<div class="form-group">
											<label>بانک:</label>
											<input type="text" name="name_bank[]" placeholder="ملت،ملی،.." class="form-control" readonly>
										</div>
									</div>
								</div>


							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>مبلغ معامله: </label>
										<input type="text" onKeyUp="amount_ban(this)" placeholder="100000" class="form-control">
										<input type="hidden" name="amount_bank[]">
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-group input-group">
										<label>توضیحات حساب:</label>
										<input type="text" name="bank_explain[]" placeholder="توضیحات خود را وارد کنید" class="form-control">
										<span class="input-group-btn "><button type="submit" style="top: 13px;" class="btn btn btn-success">ذخیره</button></span>
									</div>
								</div>
							</div>
						</div>
				</form>
				</div>
			</div>
		</div>
		<?php $str = '';foreach($customer as $row){$str .= "\"$row->fullname\",";}$str = trim($str , ",");?>
		<!-- /edit bank modal -->
		<script>
       	function autocomplete( inp, arr ) {
		var currentFocus;
		inp.addEventListener( "input", function ( e ) {
			var a, b, i, val = this.value;
			closeAllLists();
			if ( !val ) {
				return false;
			}
			currentFocus = -1;
			a = document.createElement( "DIV" );
			a.setAttribute( "id", this.id + "autocomplete-list" );
			a.setAttribute( "class", "autocomplete-items" );
			this.parentNode.appendChild( a );
			for ( i = 0; i < arr.length; i++ ) {
				let match;
				let search = val;
				let lastIndx = ( arr[ i ].length - 1 ) - arr[ i ].indexOf( search ) - ( search.length - 1 );
				if ( lastIndx == 0 ) {
					match = arr[ i ].slice( arr[ i ].indexOf( search ), arr[ i ].length );
				} else {
					match = arr[ i ].slice( arr[ i ].indexOf( search ), -lastIndx );
				}
				if ( match.length == search.length ) {
					let str = arr[ i ].slice( 0, arr[ i ].indexOf( search ) )+'<strong style="color:#46a64c;">'+match+'</strong>'+arr[ i ].slice( arr[ i ].length - lastIndx, arr[ i ].length );

					b = document.createElement( "DIV" );
					b.innerHTML = str + "<input type='hidden' value='" + arr[ i ] + "'>";
					b.addEventListener( "click", function ( e ) {
						inp.value = this.getElementsByTagName( "input" )[ 0 ].value;
						closeAllLists();
					} );
					a.appendChild( b );
				}
			}
		} );
		inp.addEventListener( "keydown", function ( e ) {
			var x = document.getElementById( this.id + "autocomplete-list" );
			if ( x ) x = x.getElementsByTagName( "div" );
			if ( e.keyCode == 40 ) {
				currentFocus++;
				addActive( x );
			} else if ( e.keyCode == 38 ) {
				currentFocus--;
				addActive( x );
			} else if ( e.keyCode == 13 ) {
				e.preventDefault();
				if ( currentFocus > -1 ) {
					if ( x ) x[ currentFocus ].click();
				}
			}
		} );

		function addActive( x ) {
			if ( !x ) return false;
			removeActive( x );
			if ( currentFocus >= x.length ) currentFocus = 0;
			if ( currentFocus < 0 ) currentFocus = ( x.length - 1 );
			x[ currentFocus ].classList.add( "autocomplete-active" );

		}

		function removeActive( x ) {
			for ( var i = 0; i < x.length; i++ ) {
				x[ i ].classList.remove( "autocomplete-active" );
			}
		}

		function closeAllLists( elmnt ) {
			var x = document.getElementsByClassName( "autocomplete-items" );
			for ( var i = 0; i < x.length; i++ ) {
				if ( elmnt != x[ i ] && elmnt != inp ) {
					x[ i ].parentNode.removeChild( x[ i ] );
				}
			}
		}
		document.addEventListener( "click", function ( e ) {
			closeAllLists( e.target );
		} );
	}
	var customer = [<?php echo $str; ?>];

	function search_customer( input ) {
		autocomplete( input, customer );
	}

			function show_bank( input ) {
		var txt = input.value;
		var name_bank = input.parentElement.parentElement.nextElementSibling.firstElementChild.lastElementChild;
		if ( txt[ 6 ] != '_' && txt[ 7 ] != '_' && txt[ 8 ] != '_' ) {
			var bank = txt.slice( 6, 9 );
			if ( bank == '055' ) {
				name_bank.value = 'بانک اقتصاد نوین';
			} else if ( bank == '054' ) {
				name_bank.value = 'بانک پارسیان';
			} else if ( bank == '057' ) {
				name_bank.value = 'بانک پاسارگاد';
			} else if ( bank == '021' ) {
				name_bank.value = 'پست بانک ایران';
			} else if ( bank == '018' ) {
				name_bank.value = 'بانک تجارت';
			} else if ( bank == '051' ) {
				name_bank.value = 'موسسه اعتباری توسعه';
			} else if ( bank == '020' ) {
				name_bank.value = 'بانک توسعه صادرات';
			} else if ( bank == '013' ) {
				name_bank.value = 'بانک رفاه';
			} else if ( bank == '056' ) {
				name_bank.value = 'بانک سامان';
			} else if ( bank == '015' ) {
				name_bank.value = 'بانک سپه';
			} else if ( bank == '058' ) {
				name_bank.value = 'بانک سرمایه';
			} else if ( bank == '019' ) {
				name_bank.value = 'بانک صادرات ایران';
			} else if ( bank == '011' ) {
				name_bank.value = 'بانک صنعت و معدن';
			} else if ( bank == '053' ) {
				name_bank.value = 'بانک کارآفرین';
			} else if ( bank == '016' ) {
				name_bank.value = 'بانک کشاورزی';
			} else if ( bank == '010' ) {
				name_bank.value = 'بانک مرکزی جمهوری اسلامی ایران';
			} else if ( bank == '014' ) {
				name_bank.value = 'بانک مسکن';
			} else if ( bank == '012' ) {
				name_bank.value = 'بانک ملت';
			} else if ( bank == '017' ) {
				name_bank.value = 'بانک ملی ایران';
			} else if ( bank == '022' ) {
				name_bank.value = 'بانک توسعه تعاون';
			} else if ( bank == '059' ) {
				name_bank.value = 'بانک سینا';
			} else if ( bank == '060' ) {
				name_bank.value = 'قرض الحسنه مهر';
			} else if ( bank == '061' ) {
				name_bank.value = 'بانک شهر';
			} else if ( bank == '062' ) {
				name_bank.value = 'بانک تات';
			} else if ( bank == '063' ) {
				name_bank.value = 'بانک انصار';
			} else if ( bank == '064' ) {
				name_bank.value = 'بانک گردشگری';
			} else if ( bank == '065' ) {
				name_bank.value = 'بانک حکمت ایرانیان';
			} else if ( bank == '066' ) {
				name_bank.value = 'بانک دی';
			} else if ( bank == '069' ) {
				name_bank.value = 'بانک ایران زمین';
			} else {
				name_bank.value = '';
			}
		} else {
			name_bank.value = '';
		}

	}
	var volume = document.getElementById( 'volume_deal');
	var ambank = document.getElementById( 'ambank');
	ambank.onkeyup = function(){
		ambank.value = numeral( ambank.value ).format( '0,0' );
		ambank.nextElementSibling.value = numeral( ambank.value ).value();
		if(numeral(volume.innerHTML).value() < numeral(ambank.value).value()){
         ambank.nextElementSibling.nextElementSibling.style.display = 'block';
		}else{
			ambank.nextElementSibling.nextElementSibling.style.display = 'none';
		}
	}
	function volume_handle(input){
		input.value  = numeral(input.value).format('0,0');
		input.nextElementSibling.value = numeral(input.value).value();
	}
	function pay_all(link  , rest){
      document.getElementById('button_all').setAttribute('href' , "<?php echo base_url("deal/pay_all/").$deal->id."/"?>" + link );
		document.getElementById('p_all').innerHTML = " آیا می خواهید تمام مبلغ "  + numeral(rest).format('0,0') + " پرداخت شود ؟";
	}	
	function pay_slice(link){
		document.getElementById('form_slice').setAttribute('action' , "<?php echo base_url("deal/pay_slice/").$deal->id."/"?>" + link );
	}
	function slice_input(input){
		input.value = numeral(input.value).format('0,0');
		input.nextElementSibling.value = numeral(input.value).value();
	}
		</script>