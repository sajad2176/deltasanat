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
		<li><a href="<?php echo base_url('deal/buy')?>">معاملات</a>
		</li>
		<li class="active"><a href="<?php echo base_url('deal/buy')?>"> خرید </a>
		</li>
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
								<legend class="text-semibold"><i class="icon-coins position-left"></i> اطلاعات فروش</legend>
								<div class="form-group">
									<label>نام خریدار: </label>
									<input class="form-control" onFocus="search_customer(this)" name="customer[]" type="text" placeholder="نام خریدار خود را وارد کنید" autocomplete="off" required>
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
											<label>تعداد ارز:</label>
											<input type="text" id="count" placeholder="999999" class="form-control" autocomplete="off" required>
											<input type="hidden" name="count_money">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label>کامزد:</label>
											<input type="text" id="wage" placeholder="999999" autocomplete="off" class="form-control" required>
											<input type="hidden" name="wage">
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label>نرخ تبدیل:</label>
											<input type="text" id="convert" placeholder="100000" autocomplete="off" class="form-control" required >
											<input type="hidden" name="convert_money">
										</div>
									</div>
								</div>
								<div class="">
									<div class="form-group">
										<label>مبلغ ریالی:</label>
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
												<label>مبلغ واریزی: </label>
												<input type="text" onKeyUp="amount_bank(this)" placeholder="100000" class="form-control">
												<input type="hidden" name="amount_bank[]">
												<p class="text-danger" style ="display: none;">مبلغ وارد شده بیشتر از حجم معامله است</p>
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group input-group">
												<label>توضیحات حساب:</label>
												<input type="text" name="bank_explain[]" placeholder="توضیحات خود را وارد کنید" class="form-control">
												<span class="input-group-btn "><button type="button" style="top: 13px;" class="btn btn btn-success icon-plus3 add_button2"></button></span>
											</div>
										</div>
									</div>
								</div>
								<input type="hidden" name="deal_type" value="2">
								<div class="form-group">
									<label>عکس را قرار دهید </label>
									<input type="file" class="file-styled" name="deal_pic[]" multiple="multiple">
								</div>
								<div class="form-group">
									<label>توضیحات خرید:</label>
									<textarea rows="5" cols="5" name="explain" class="form-control" placeholder="توصیحات خود را وارد کنید"></textarea>
								</div>


							</fieldset>

							<div class="text-right">
								<button type="submit" name="sub" class="btn btn-primary">ثبت خرید <i class="icon-arrow-left13 position-right"></i></button>
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
		if ( txt[ 6 ] != '_' && txt[ 7 ] != '_' && txt[ 8 ] != '_' && txt[0].toLowerCase() == 'i' && txt[1].toLowerCase() == 'r' ) {
			name_bank.setAttribute("readonly" , 'readonley');
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
				name_bank.removeAttribute("readonly"); 
			}
		} else {
			name_bank.removeAttribute("readonly"); 
			name_bank.value = '';
		}

	}
	var count = document.getElementById( 'count' );
	var wage = document.getElementById( 'wage' );
	var convert = document.getElementById( 'convert' );
	var volume = document.getElementById( 'volume_deal' );
	var money_name = document.getElementById('money_id');
	money_name.onclick = function(){
		name = money_name.options[money_name.selectedIndex].innerHTML;
		value_count = numeral(count.value).value();
		count.value = numeral(value_count).format('0,0') + ' '+ name;
		value_wage = numeral(wage.value).value();
		wage.value = numeral(value_wage).format('0,0') + ' ' + name;
	}
	count.onkeyup = function () {
		var name = money_name.options[money_name.selectedIndex].innerHTML;
		count.value = numeral( count.value ).format( '0,0' ) + ' ' + name;
		var x = numeral( count.value ).value();
		var y = numeral( wage.value ).value();
		var z = numeral( convert.value ).value();
		count.nextElementSibling.value = x;
		volume.innerHTML = numeral( ( x + y ) * z ).format( '0 , 0' ) + ' ریـال  ';
	}
	wage.onkeyup = function () {
		var name = money_name.options[money_name.selectedIndex].innerHTML;
		wage.value = numeral( wage.value ).format( '0,0' ) + ' ' + name;
		var x = numeral( count.value ).value();
		var y = numeral( wage.value ).value();
		var z = numeral( convert.value ).value();
		wage.nextElementSibling.value = y;
		volume.innerHTML = numeral( ( x + y ) * z ).format( '0 , 0' ) + ' ریـال  ';
	}
	convert.onkeyup = function () {
		convert.value = numeral( convert.value ).format( '0,0' ) +  ' ریـال ';
		var x = numeral( count.value ).value();
		var y = numeral( wage.value ).value();
		var z = numeral( convert.value ).value();
		convert.nextElementSibling.value = z;
		volume.innerHTML = numeral( ( x + y ) * z ).format( '0 , 0' ) + ' ریـال  ';
	}

	function amount_bank( input ) {
		var volume = document.getElementById( 'volume_deal');
		input.value = numeral( input.value ).format( '0,0' ) + " ریـال ";
		input.nextElementSibling.value = numeral( input.value ).value();
		if(numeral(volume.innerHTML).value() < numeral(input.value).value()){
         input.nextElementSibling.nextElementSibling.style.display = 'block';
		}else{
			input.nextElementSibling.nextElementSibling.style.display = 'none';
		}
	}
</script>