
//page buy
var Btable = document.getElementById('buy_table');
function buyTable(res , url){
    count = res.length;
    var str = '';
    for(i = 0 ; i < count ; i++){
        var amm = res[i].volume - res[i].handle;
        if(res[i].rest == 0){name = 'tr_rest';}else{name = '';}
        str += '<tr class='+name+'><td><a href='+url+'deal/handle_profile/'+res[i].customer_id+'>'+res[i].fullname+'</a></td><td>'+numeral(res[i].volume).format('0,0')+'</td><td>'+numeral(res[i].handle).format('0,0')+'</td><td>'+numeral(amm).format('0,0')+'</td></tr>';
    }
    Btable.innerHTML = str;
}

//page sell
var stable = document.getElementById('sell_table');
function sellTable(res , url){
    count = res.length;
    var str = '';
    for(i = 0 ; i < count ; i++){
        var amm = res[i].volume - res[i].handle;
        if(res[i].rest == 0){name = 'tr_rest';}else{name = '';}
        str += '<tr class='+name+'><td><a href='+url+'deal/handle_profile/'+res[i].customer_id+'>'+res[i].fullname+'</a></td><td>'+numeral(res[i].volume).format('0,0')+'</td><td>'+numeral(res[i].handle).format('0,0')+'</td><td>'+numeral(amm).format('0,0')+'</td></tr>';
    }
    stable.innerHTML = str;
}

//search 
var tbody_buy = document.getElementById('tbody_buy');
var tbody_sell = document.getElementById('tbody_sell');
var select = document.getElementById('money_id');
function autocomplete( inp, arr , buy , sell , type ) {
    var currentFocus;
    inp.addEventListener( "input", function ( e ) {
        var a, b, i, val = this.value;
        val = val.trim();
        closeAllLists();
        if ( !val ) {
            inp.nextElementSibling.style.display = 'none';
            Default(type);
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
                let str = arr[ i ].slice( 0, arr[ i ].indexOf( search ) ) + '<strong style="color:#46a64c;">' + match + '</strong>' + arr[ i ].slice( arr[ i ].length - lastIndx, arr[ i ].length ) + '</br>'+" ه.خ : " + numeral(buy[i]).format('0,0') + " | ه.ف :" + numeral(sell[i]).format('0,0') ;

                b = document.createElement( "DIV" );
                b.innerHTML = str + "<input type='hidden' value='" + arr[ i ] + "'>";
                b.addEventListener( "click", function ( e ) {
                    inp.value = this.getElementsByTagName( "input" )[ 0 ].value;
                    show(inp.value , type);
                    closeAllLists();
                } );
                matchHistory++;
                a.appendChild( b );
            }
        }
        if(matchHistory == 1){
            show(inp.value , type);
        }else{
            Default(type);
        }
        if(a.childElementCount == 0){
            inp.nextElementSibling.style.display = 'block';
            inp.nextElementSibling.innerHTML = '<span class="icon-alert"></span>' + ' با مشتری  ' + val + ' ثبت نشده است ';
            Default(type);
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
function Default(type){
if(type == 'buy'){
Btable.style.display = 'contents';
tbody_buy.style.display = 'none';
select.innerHTML = '<option value="0">برای مشتری خرید شماره حسابی ثبت نشده است</option>';
}else if(type == 'sell'){
stable.style.display = 'contents';
tbody_sell.style.display = 'none';
}
}
function showTable(res , type , url){
    var baseTable;
    var searchTable;
    if(type == 'buy'){
        baseTable = Btable;
        searchTable = tbody_buy;
        }else if(type == 'sell'){
        baseTable = stable;
        searchTable = tbody_sell;
        }
        baseTable.style.display = 'none';
        searchTable.style.display = 'contents';   
    if(res.cust.length == 0){
   searchTable.innerHTML  = "<tr class='text-center p-20'><td colspan='4'>موردی یافت نشد</td></tr>";
    }else {
        var div = document.createElement( 'tbody' );
        var len = res.cust.length;
        for ( var i = 0; i < len ; i++ ) {
            var tr = div.appendChild( document.createElement( 'tr' ) );
            if(type =='buy'){
                tr.style.backgroundColor = '#d5f6f2';
            }else{
                tr.style.backgroundColor = '#f6d5d7';
            }
    
            var td_fullname = tr.appendChild( document.createElement( 'td' ) );
            var a_customer = td_fullname.appendChild(document.createElement('a'));
            a_customer.setAttribute('href' , url + "deal/handle_profile/" + res.cust[i].customer_id);
            a_customer.setAttribute('target' ,  '_blank');
            a_customer.innerHTML = res.cust[i].fullname;
            
            var td_volume = tr.appendChild(document.createElement('td'));
            td_volume.innerHTML = numeral(res.cust[i].volume).format('0,0');
            
            var td_handle = tr.appendChild(document.createElement('td'));
            td_handle.innerHTML = numeral(res.cust[i].handle).format('0,0');
            
            var amm = res.cust[i].volume - res.cust[i].handle;
            var td_rest = tr.appendChild(document.createElement('td'));
            td_rest.innerHTML = numeral(amm).format('0,0');
        }
        searchTable.replaceChild( div, searchTable.firstChild );
    }
    if(type == 'buy'){
      if(res.bank.length == 0){
          select.innerHTML = '<option value="0">برای مشتری خرید شماره حسابی ثبت نشده است</option>';
      }else{
          var str = '';
          for(i = 0 ; i < res.bank.length ; i++){
              str += '<option value='+res.bank[i].id+'>' + res.bank[i].explain + ' | هماهنگ نشده : '+ numeral(res.bank[i].rest_handle).format('0,0') + ' | باقی مانده : '+ numeral(res.bank[i].rest).format('0,0') +'</option>'
          }
        select.innerHTML = str;
      }
    }

}
function amhandle(input){
    input.value = numeral( input.value ).format( '0,0' );
    input.nextElementSibling.value = numeral( input.value ).value();
}