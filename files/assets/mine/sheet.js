
//page buy
var baseBuy = document.getElementById('baseBuy');
function showBuy(res , url){
    count = res.length;
    var str = '' , notHandle , title;
    for(i = 0 ; i < count ; i++){
        notHandle = res[i].volume - res[i].handle;
        if(Number(res[i].rest) == 0){name = 'tr_rest';}else{name = '';}
        title = numeral(res[i].handle).format('0,0') + " - " + numeral(res[i].volume).format('0,0');
        str += '<tr class='+name+'><td><a href='+url+'deal/profile/'+res[i].customer_id+' class="enterCustomer" target="_blank">'+res[i].fullname+'</a></td><td>'+numeral(res[i].volume).format('0,0')+'</td><td>'+numeral(res[i].handle).format('0,0')+'</td><td><span title="'+title+'" data-toggle="tooltip">'+numeral(notHandle).format('0,0')+'</span></td></tr>';
    }
    baseBuy.innerHTML = str;
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    })
}

//page sell
var baseSell = document.getElementById('baseSell');
function showSell(res , url){
    count = res.length;
    var str = '' , notHandle , title;
    for(i = 0 ; i < count ; i++){
        var notHandle = res[i].volume - res[i].handle;
        if(Number(res[i].rest) == 0){name = 'tr_rest';}else{name = '';}
        title = numeral(res[i].handle).format('0,0') + " - " + numeral(res[i].volume).format('0,0');
        str += '<tr class='+name+'><td><a href='+url+'deal/profile/'+res[i].customer_id+' class="enterCustomer" target="_blank" >'+res[i].fullname+'</a></td><td>'+numeral(res[i].volume).format('0,0')+'</td><td>'+numeral(res[i].handle).format('0,0')+'</td><td><span title="'+title+'" data-toggle="tooltip">'+numeral(notHandle).format('0,0')+'</span></td></tr>';
    }
    baseSell.innerHTML = str;
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    })
}
//page sell

//search 
var searchBuy = document.getElementById('searchBuy');
var searchSell = document.getElementById('searchSell');
var select = document.getElementById('bankSelect');
function autocomplete( inp, arr , notBuy , notSell , type ) {
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
                let str = arr[ i ].slice( 0, arr[ i ].indexOf( search ) ) + '<strong style="color:#46a64c;">' + match + '</strong>' + arr[ i ].slice( arr[ i ].length - lastIndx, arr[ i ].length ) + '</br>'+" ه.ن خرید : " + numeral(notBuy[i]).format('0,0') + " </br> ه.ن فروش : " + numeral(notSell[i]).format('0,0');

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
            inp.nextElementSibling.innerHTML = '<span class="icon-alert"></span>' + ' با مشتری  ' + val + '   معامله ای ثبت نشده است ';
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

// search customer------
function Default(type){
if(type == 'buy'){
    baseBuy.style.display = 'contents';
    searchBuy.style.display = 'none';
select.innerHTML = '<option value="0">برای مشتری خرید شماره حسابی ثبت نشده است</option>';
}else if(type == 'sell'){
    baseSell.style.display = 'contents';
    searchSell.style.display = 'none';
}
}

function showTable(res , type , url){
    var baseTable;
    var searchTable;
    if(type == 'buy'){
        baseTable = baseBuy;
        searchTable = searchBuy;
    }else{
        baseTable = baseSell;
        searchTable = searchSell;
    }
        baseTable.style.display = 'none';
        searchTable.style.display = 'contents';   
    if(res.cust.length == 0){
   searchTable.innerHTML  = "<tr class='text-center p-20'><td colspan='4'>موردی یافت نشد</td></tr>";
    }else {
    var searchStr = '' , notHandle;
    classTr = 'buySheet';
    if(type == 'sell'){classTr = 'sellSheet';}
    notHandle = Number(res.cust.volume) - Number(res.cust.handle);
    searchStr += '<tr class='+classTr+'><td><a hre='+url+'deal/profile'+res.cust.customer_id+' target="_blank" class="enterCustomer" >'+res.cust.fullname+'</a></td><td>'+numeral(res.cust.volume).format('0,0')+'</td><td>'+numeral(res.cust.handle).format('0,0')+'</td><td>'+numeral(notHandle).format('0,0')+'</td></tr>';   
    searchTable.innerHTML = searchStr;    
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
// search customer-------

function amhandle(input){
    input.value = numeral( input.value ).format( '0,0' );
    input.nextElementSibling.value = numeral( input.value ).value();
}