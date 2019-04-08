//show_bank
	function show_bank( input ) {
		var txt = input.value;
		var name_bank = input.parentElement.parentElement.nextElementSibling.firstElementChild.lastElementChild;
		if ( txt[ 3 ] != '_' && txt[ 4 ] != '_' && txt[ 5 ] != '_' ) {
			name_bank.previousElementSibling.style.display = 'none';
			var bank = txt.slice( 3, 6 );
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
				name_bank.previousElementSibling.style.display = 'inline';
			}
		}
	}
//show bank
// count wage convert volume
	var count = document.getElementById( 'count' );
	var wage = document.getElementById( 'wage' );
	var convert = document.getElementById( 'convert' );
	var volume = document.getElementById( 'volume_deal' );
	// var money_name = document.getElementById('money_id');

	// money_name.onchange = function(){
	// 	name = money_name.options[money_name.selectedIndex].innerHTML;
	// 	value1 = numeral(count.value).value();
	// 	value2 = numeral(wage.value).value();
	// 	if(value1 == null){
	// 	  count.nextElementSibling.value = 0;
	// 	}else{
	// 	  count.nextElementSibling.value = value1;
	// 	}
	// 	if(value2 == null){
    //       wage.nextElementSibling.value = 0;
	// 	}else{
	// 	  wage.nextElementSibling.value = value2;
	// 	}
	// 	count.value = numeral(value1).format('0,0') + ' '+ name;
	// 	wage.value = numeral(value2).format('0,0') + ' ' + name;
	// }
	count.onkeyup = function (e) {
		count.value = numeral( count.value ).format( '0,0' );
		var x = numeral( count.value ).value();
		var y = numeral( wage.value ).value();
		var z = numeral( convert.value ).value();
		volume.innerHTML = numeral( ( x + y ) * z ).format( '0 , 0' ) + ' ریـال  ';
		count.nextElementSibling.value = x;
	}
	wage.onkeyup = function (e) {
		wage.value = numeral( wage.value ).format( '0,0' );
		var x = numeral( count.value ).value();
		var y = numeral( wage.value ).value();
		var z = numeral( convert.value ).value();
		volume.innerHTML = numeral( ( x + y ) * z ).format( '0 , 0' ) + ' ریـال  ';
		wage.nextElementSibling.value = y;
	}
	convert.onkeyup = function (e) {
		convert.value = numeral( convert.value ).format( '0,0' );
		var x = numeral( count.value ).value();
		var y = numeral( wage.value ).value();
		var z = numeral( convert.value ).value();
		volume.innerHTML = numeral( ( x + y ) * z ).format( '0 , 0' ) + ' ریـال  ';
		convert.nextElementSibling.value = z;
	}
	// count wage convert volume
   // bank
	function amount_bank( input) {
		var volume = document.getElementById( 'volume_deal');
		input.nextElementSibling.value = numeral( input.value ).value();
		if(numeral(volume.innerHTML).value() < numeral(input.value).value()){
         input.nextElementSibling.nextElementSibling.style.display = 'block';
		}else{
			input.nextElementSibling.nextElementSibling.style.display = 'none';
		}
		input.value = numeral( input.value ).format( '0,0' );
	}
	// bank
	//search customer
	function autocomplete( inp, arr ) {
		var currentFocus;
		inp.addEventListener( "input", function ( e ) {
			var a, b, i, val = this.value;
			closeAllLists();
			if ( !val ) {
				inp.nextElementSibling.style.display = 'none';
				showDefault();
				return false;
			}
			currentFocus = -1;
			a = document.createElement( "DIV" );
			a.setAttribute( "id", this.id + "autocomplete-list" );
			a.setAttribute( "class", "autocomplete-items" );
			this.parentNode.appendChild( a );
			matchHistory = 0;
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
						showHistory(inp.value);
						closeAllLists();
					} );
					matchHistory++;
					a.appendChild( b );
				}
			}
			if(matchHistory == 1){
				showHistory(val);
			}else{
				showDefault();
			}
			if(a.childElementCount == 0){
				inp.nextElementSibling.style.display = 'block';
				inp.nextElementSibling.innerHTML = 'بعد از اتمام معامله مشتری ' + val + ' به لیست مشتریان افزوده خواهد شد ';
				showDefault();
			}else{
	            inp.nextElementSibling.style.display = 'none';
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
					if ( x ){ x[ currentFocus ].click();}
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
var	nameCustomer = document.getElementById('name_customer');
var dollar = document.getElementById('dollar');
var euro = document.getElementById('euro');
var yuan = document.getElementById('yuan');
var derham = document.getElementById('derham');
var rial = document.getElementById('rial');	
	function showDefault(){
		nameCustomer.innerHTML = '';
		dollar.innerHTML = '-';
		euro.innerHTML = '-';
		yuan.innerHTML = '-';
		derham.innerHTML = '-';
		rial.innerHTML = '-'
	}
	function showCustResult(res , txt){
		if(res == false){
			showDefault();
		}else{
			nameCustomer.innerHTML = txt;
			dollar.innerHTML = numeral(res.dollar).format('0,0');
			euro.innerHTML = numeral(res.euro).format('0,0');
			yuan.innerHTML = numeral(res.yuan).format('0,0');
			derham.innerHTML = numeral(res.derham).format('0,0');
			rial.innerHTML = numeral(res.rial).format('0,0');
		}
	}