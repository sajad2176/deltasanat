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
			// matchHistory = 0;
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
						showDefault();
						showHistory(inp.value);
						closeAllLists();
					} );
					// matchHistory++;
					a.appendChild( b );
				}
			}
			// if(matchHistory == 1){
			// 	showHistory(val);
			// }else{
			// 	showDefault();
			// }
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
var setDefault = document.getElementsByClassName('setDefault');
var unitRial = document.getElementById('rial');
	function showDefault(){
		var countDefault = setDefault.length;
		for(var i = 0 ; i < countDefault ; i++){
		   setDefault[i].innerHTML = '0';
		   setDefault[i].style.color = 'black';
		}
		nameCustomer.innerHTML = '';
	}
	function showCustResult(res , txt){
		if(res == false){
			showDefault();
		}else{
			var countOther = res.other.length;
			for(var i = 0 ; i < countOther ; i++){
				var change = document.getElementById(res.other[i].id);
				if(res.other[i].type == 1){
					change.innerHTML = numeral(numeral(change.innerHTML).value() + (res.other[i].rest/res.other[i].convert)).format('0,0');
				}else{
					change.innerHTML = numeral(numeral(change.innerHTML).value() - (res.other[i].rest/res.other[i].convert)).format('0,0');
				}
				if(numeral(change.innerHTML).value() >= 0){
					change.style.color = '#4caf50';
				}else{
					change.style.color = '#f44336';
				}
             
			}
			var countRial = res.rial.length;
			for(var j = 0 ; j < countRial ; j++){
				if(res.rial[j].type == 1){
					unitRial.innerHTML = numeral(numeral(unitRial.innerHTML).value() - (res.rial[j].rest)).format('0,0');
				}else{
					unitRial.innerHTML = numeral(numeral(unitRial.innerHTML).value() + (res.rial[j].rest)).format('0,0');
				}
				if(numeral(unitRial.innerHTML).value() >= 0){
					unitRial.style.color = '#4caf50';
				}else{
					unitRial.style.color = '#f44336';
				}
			} 
			nameCustomer.innerHTML = txt;

		}
	}