<div class="breadcrumb-line breadcrumb-line-component mb-20">
	<ul class="breadcrumb">
		<li><a href="<?php echo base_url('home');?>"><i class="icon-home2 position-left"></i> داشبورد</a>
		</li>
		<li><a href="<?php echo base_url('deal/buy')?>">معاملات</a>
		</li>
		<li class="active"><a href="<?php echo base_url('deal/buy')?>"> فروش </a>
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
									<input class="form-control" onFocus="search_customer(this)" name="customer[]" type="text" placeholder="نام فروشنده خود را وارد کنید" autocomplete="off" required>
								</div>

								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label>نام ارز: </label>
											<select class="form-control" name="money_id" required>
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
											<input type="text" id="count" placeholder="999999" class="form-control" required>
											<input type="hidden" name="count_money">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label>کامزد:</label>
											<input type="text" onkeyup="wage_money()" id="wage" placeholder="999999" class="form-control" required>
											<input type="hidden" name="wage">
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label>نرخ تبدیل:</label>
											<input type="text" onkeyup="convert_money()" id="convert" placeholder="100000" class="form-control" required>
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
												<label>مبلغ معامله: </label>
												<input type="text" onKeyUp="amount_bank(this)" placeholder="100000" class="form-control">
												<input type="hidden" name="amount_bank[]">
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
								<input type="hidden" name="deal_type" value="1">
								<div class="form-group">
									<label>عکس را قرار دهید </label>
									<input type="file" name="deal_pic[]" class="file-styled" multiple="multiple">
								</div>
								<div class="form-group">
									<label>توضیحات فروش:</label>
									<textarea rows="5" cols="5" name="explain" class="form-control" placeholder="توصیحات خود را وارد کنید"></textarea>
								</div>


							</fieldset>

							<div class="text-right">
								<button type="submit" name="sub" class="btn btn-primary">ثبت فروش <i class="icon-arrow-left13 position-right"></i></button>
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
					let str = arr[ i ].slice( 0, arr[ i ].indexOf( search ) ) + '<strong style="color:#46a64c;">' + match + '</strong>' + arr[ i ].slice( arr[ i ].length - lastIndx, arr[ i ].length );

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
		/*execute a function presses a key on the keyboard:*/
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

	/*An array containing all the country names in the world:*/
	var customer = [ "Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Anguilla", "Antigua & Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia & Herzegovina", "Botswana", "Brazil", "British Virgin Islands", "Brunei", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central Arfrican Republic", "Chad", "Chile", "China", "Colombia", "Congo", "Cook Islands", "Costa Rica", "Cote D Ivoire", "Croatia", "Cuba", "Curacao", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands", "Faroe Islands", "Fiji", "Finland", "France", "French Polynesia", "French West Indies", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guam", "Guatemala", "Guernsey", "Guinea", "Guinea Bissau", "Guyana", "Haiti", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Isle of Man", "Israel", "Italy", "Jamaica", "Japan", "Jersey", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Kosovo", "Kuwait", "Kyrgyzstan", "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Mauritania", "Mauritius", "Mexico", "Micronesia", "Moldova", "Monaco", "Mongolia", "Montenegro", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauro", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "North Korea", "Norway", "Oman", "Pakistan", "Palau", "Palestine", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russia", "Rwanda", "Saint Pierre & Miquelon", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Korea", "South Sudan", "Spain", "Sri Lanka", "St Kitts & Nevis", "St Lucia", "St Vincent", "Sudan", "Suriname", "Swaziland", "Sweden", "Switzerland", "Syria", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "Timor L'Este", "Togo", "Tonga", "Trinidad & Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks & Caicos", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States of America", "Uruguay", "Uzbekistan", "Vanuatu", "Vatican City", "Venezuela", "Vietnam", "Virgin Islands (US)", "Yemen", "Zambia", "Zimbabwe" ];

	function search_customer( input ) {
		autocomplete( input, customer );
	}

	function show_bank( input ) {
		var txt = input.value;
		if ( txt[ 6 ] != '_' && txt[ 7 ] != '_' && txt[ 8 ] != '_' ) {
			var name_bank = input.parentElement.parentElement.nextElementSibling.firstElementChild.lastElementChild;
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
	var count = document.getElementById( 'count' );
	var wage = document.getElementById( 'wage' );
	var convert = document.getElementById( 'convert' );
	var volume = document.getElementById( 'volume_deal' )
	count.onkeyup = function () {
		count.value = numeral( count.value ).format( '0,0' );
		var x = numeral( count.value ).value();
		var y = numeral( wage.value ).value();
		var z = numeral( convert.value ).value();
		count.nextElementSibling.value = x;
		volume.innerHTML = numeral( ( x + y ) * z ).format( '0 , 0' ) + ' ریـال  ';
	}
	wage.onkeyup = function () {
		wage.value = numeral( wage.value ).format( '0,0' );
		var x = numeral( count.value ).value();
		var y = numeral( wage.value ).value();
		var z = numeral( convert.value ).value();
		wage.nextElementSibling.value = y;
		volume.innerHTML = numeral( ( x + y ) * z ).format( '0 , 0' ) + ' ریـال  ';
	}
	convert.onkeyup = function () {
		convert.value = numeral( convert.value ).format( '0,0' );
		var x = numeral( count.value ).value();
		var y = numeral( wage.value ).value();
		var z = numeral( convert.value ).value();
		convert.nextElementSibling.value = z;
		volume.innerHTML = numeral( ( x + y ) * z ).format( '0 , 0' ) + ' ریـال  ';
	}

	function amount_bank( input ) {
		input.value = numeral( input.value ).format( '0,0' );
		input.nextElementSibling.value = numeral( input.value ).value();
	}
</script>