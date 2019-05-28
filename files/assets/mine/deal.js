// count wage convert volume
	var count = document.getElementById( 'count' );
	var wage = document.getElementById( 'wage' );
	var convert = document.getElementById( 'convert' );
	var volume = document.getElementById( 'volume_deal' );

	count.onkeyup = function () {
		count.value = numeral( count.value ).format( '0,0' );
		var x = numeral( count.value ).value();
		var y = numeral( wage.value ).value();
		var z = numeral( convert.value ).value();
		volume.innerHTML = numeral( ( x + y ) * z ).format( '0 , 0' ) + ' ریـال  ';
		count.nextElementSibling.value = x;
	}
	wage.onkeyup = function () {
		wage.value = numeral( wage.value ).format( '0,0' );
		var x = numeral( count.value ).value();
		var y = numeral( wage.value ).value();
		var z = numeral( convert.value ).value();
		volume.innerHTML = numeral( ( x + y ) * z ).format( '0 , 0' ) + ' ریـال  ';
		wage.nextElementSibling.value = y;
	}
	convert.onkeyup = function () {
		convert.value = numeral( convert.value ).format( '0,0' );
		var x = numeral( count.value ).value();
		var y = numeral( wage.value ).value();
		var z = numeral( convert.value ).value();
		volume.innerHTML = numeral( ( x + y ) * z ).format( '0 , 0' ) + ' ریـال  ';
		convert.nextElementSibling.value = z;
	}
	// count wage convert volume
	//search customer



	function autocomplete( inp, arr ) {
		var currentFocus;
		inp.addEventListener( "input", function ( e ) {
			var a, b, i, val = this.value;
			val = val.trim();
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
						// showHistory(inp.value);
						closeAllLists();
					} );
					matchHistory++;
					a.appendChild( b );
				}
			}
			if(matchHistory == 1){
				// showHistory(val);
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
		dollar.style.color = '#333333';
		euro.style.color = '#333333';
		yuan.style.color = '#333333';
		derham.style.color = '#333333';
		rial.style.color = '#333333';
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
			if(res.dollar < 0){
				dollar.style.color = '#f44336';
			}else{
				dollar.style.color = '#4caf50';
			}
			if(res.euro < 0){
				euro.style.color = '#f44336';
			}else{
				euro.style.color = '#4caf50';
			}
			if(res.yuan < 0){
				yuan.style.color = '#f44336';
			}else{
				yuan.style.color = '#4caf50';
			}
			if(res.derham < 0){
				derham.style.color = '#f44336';
			}else{
				derham.style.color = '#4caf50';
			}
			if(res.rial < 0){
				rial.style.color = '#f44336';
			}else{
				rial.style.color = '#4caf50';
			}
		}
	}