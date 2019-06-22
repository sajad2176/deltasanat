//name bank
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
            name_bank.previousElementSibling.style.display = 'inline';
            name_bank.value = '';
        }
    }
}
//name bank

//paginDeal
var dealTable = document.getElementById('dealTable');
function showDeal(res , url , editPerm , photoPerm , deletePerm , littlePerm){
    count = res.length;
    var str = '';
    var name , type , t1 , t2 ,title1 ,title2 , edit , photo , deleteli , littleli;

    for(i = 0 ; i < count ; i++){
        if(res[i].state == 0){name = 'tr_state';}else{name = '';}

        if(res[i].type == 1){type = 'خرید';}else{type = 'فروش';}

        if(Number(res[i].volume) < Number(res[i].pay)){ t1 = 'text-danger'; }else{t1 = '';}

        if(Number(res[i].rest) < Number(0)){t2 = 'text-danger';}else{t2 = '';}

        title1 = ' ( '+ numeral(res[i].count_money).format('0,0') + ' + ' + numeral(res[i].wage).format('0,0') + ' ) × ' + numeral(res[i].convert).format('0,0');

        title2 = numeral(res[i].volume).format('0,0') + ' - ' + numeral(res[i].pay).format('0,0');

        var check = Math.abs(res[i].volume - res[i].pay);
        if(littlePerm && res[i].pay != 0 && res[i].rest != 0 && check != 0 && Number(check) <= Number(50000)){littleli = '<li title="پرداخت خرد" data-toggle="tooltip" class="text-blue-800"><a onclick="payLittle('+res[i].id+' ,'+check+')"><i class="icon-stack-up"></i></a></li>';}else{littleli = '';}
        
        if(editPerm && res[i].state == 1){edit = '<li title="ویرایش معامله" data-toggle="tooltip" class="text-primary"><a href="'+url+'deal/edit/'+res[i].id+'"><i class=" icon-pencil6"></i></a></li>';}else{edit = '';}

        if(photoPerm){photo = '<li title="مشاهده قبض" data-toggle="tooltip" class="text-indigo-600"><a href="'+url+'deal/photo/'+res[i].id+'"><i class="icon-stack-picture"></i></a></li>';}else{photo = '';}

        if(deletePerm && res[i].state == 1){deleteli = '<li title="حذف معامله"  data-toggle="tooltip" class="text-danger" ><a data-toggle="modal" href="#modal_delete_deal"><i  class="icon-trash" onclick = "deleteDeal('+res[i].id+' ,'+res[i].pay+')" ></i></a></li>';}else{deleteli = '';}
        
        str += '<tr class='+name+'><td>'+res[i].id+'</td><td>'+res[i].fullname+'</td><td>'+type+'</td><td>'+numeral(res[i].count_money).format('0,0')+'</br>'+res[i].name+'</td><td>'+numeral(res[i].convert).format('0,0')+'</td><td class="lright '+t1+'"><span title="'+title1+'" data-toggle="tooltip">'+numeral(res[i].volume).format('0,0')+'</span></td><td class="lright '+t1+'">'+numeral(res[i].pay).format('0,0')+'</td><td class="lright '+t2+'"><span title="'+title2+'"data-toggle="tooltip">'+numeral(res[i].rest).format('0,0')+'</span></td><td>'+res[i].date_deal+'</br>'+res[i].time_deal+'</td><td class="text-center"><ul class="icons-list"><li class="dropdown" title="تنظیمات" data-toggle="tooltip"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-cog7"></i></a><ul class="dropdown-menu dropdown-menu-right"><li onclick="settings(this)"><a>نمایش در داشبورد</a></li><li onclick="settings(this)"><a>عدم نمایش در داشبورد</a></li></ul></li>'+littleli+edit+photo+deleteli+'</ul></td></tr>';
    }

    dealTable.innerHTML = str;
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    })
}

//paginDeal

//show and dont show
function settings(elem){
    var color = elem.parentElement.parentElement;
    if(elem.nextElementSibling == null){
        elem.previousElementSibling.removeAttribute("style");
    }else{
        elem.nextElementSibling.removeAttribute("style");
    }
    if(elem.style.backgroundColor == 'rgb(153, 255, 153)'){
        elem.removeAttribute("style");
        color.removeAttribute("style");

    }else{
        color.style.color=" #e65c00";
        elem.style.backgroundColor = '#99ff99';
    }
}
//show and dont show


//search customer
function autocomplete( inp, arr , notBuy , notSell ) {
    var currentFocus;
    inp.addEventListener( "input", function ( e ) {
        var a, b, i, val = this.value;
        val = val.trim();
        closeAllLists();
        if ( !val ) {
            inp.nextElementSibling.style.display = 'none';            
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
                let str = arr[ i ].slice( 0, arr[ i ].indexOf( search ) ) + '<strong style="color:#46a64c;">' + match + '</strong>' + arr[ i ].slice( arr[ i ].length - lastIndx, arr[ i ].length ) + '</br>'+" ه.ن خرید : " + numeral(notBuy[i]).format('0,0') + ' </br> ه.ن فروش :  ' + numeral(notSell[i]).format('0,0');

                b = document.createElement( "DIV" );
                b.innerHTML = str + "<input type='hidden' value='" + arr[ i ] + "'>";
                b.addEventListener( "click", function ( e ) {
                    inp.value = this.getElementsByTagName( "input" )[ 0 ].value;
                    closeAllLists();
                } );
                a.appendChild( b );
            }
        }
        if(a.childElementCount == 0){
            inp.nextElementSibling.style.display = 'block';
            inp.nextElementSibling.innerHTML = '<span class="icon-alert"></span>' + ' با مشتری  ' + val + ' معامله ای ثبت نشده است ';
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
//search customer

// check amount bank
function ambank( input ) {
    input.value = numeral( input.value ).format( '0,0' );
    input.nextElementSibling.value = numeral( input.value ).value();
    if(input.previousElementSibling.value > numeral(input.value).value()){
        input.nextElementSibling.nextElementSibling.style.display = 'block';
        input.nextElementSibling.nextElementSibling.innerHTML = ' مبلغ تعیین شده از مبلغی که به این حساب واریز شده  کمتر است  ';
        }else{
            input.nextElementSibling.nextElementSibling.style.display = 'none';
        }
}
// check amount bank

//insert amount
function insertAmount(input){
    input.value = numeral( input.value ).format( '0,0' );
    input.nextElementSibling.value = numeral( input.value ).value();
}
//insert amount

//edit bank
var actionEditBank = document.getElementById( 'actionEditBank' );
var numberShabaEdit = document.getElementById( 'numberShabaEdit' );
var nameBankEdit = document.getElementById( 'nameBankEdit' );
var amountPay = document.getElementById( 'amountPay' );
var amountBank = document.getElementById( 'amountBank' );
var explainBank = document.getElementById( 'explainBank' );

function showBank(result , url){
    actionEditBank.action = url + "/" + result.id;
    numberShabaEdit.value = result.shaba;
    nameBankEdit.value = result.name;
    amountPay.value = result.pay;
    amountBank.value = result.amount;
    amountBank.previousElementSibling.value = numeral(result.amount).format('0,0') ;
	explainBank.value = result.explain;    
}
//edit bank

//show handle
var handleTable = document.getElementById('handleTable');
var handleTable2 = document.getElementById('handleTable2');

function showHandle( res , url , payAllPerm , paySlicePerm , restorePerm , editHandlePerm , deleteHandlePerm , which){
var count = res.length;
var str = '';
var t1 , t2 , title1 , all , slice , restore , edit , deleteHandle;
for(i = 0 ; i < count ; i++){
    if(Number(res[i].volume_handle) < Number(res[i].handle_pay)){t1 = 'text-danger'; }else{t1 = '';}
    if(Number(res[i].handle_rest) < Number(0)){t2 = 'text-danger';}else{t2 = '';}
    if(payAllPerm && Number(res[i].handle_rest) > Number(0)){all = '<li title="پرداخت کامل" data-toggle="tooltip" class="text-success"><a data-toggle="modal" href="#modal_pay_all"><i onclick="pay_all('+res[i].id+' ,'+res[i].handle_rest+')" class="icon-checkmark4"></i></a></li>';}else{all = '';}
    if(paySlicePerm && Number(res[i].handle_rest) > Number(0)){slice = '<li title="پرداخت جزئی" data-toggle="tooltip" class="text-primary"><a data-toggle="modal" href="#modal_pay_slice"><i onclick="pay_slice('+res[i].id+' ,'+res[i].handle_rest+')" class="icon-stack-empty"></i></li>';}else{slice = '';}
    if(restorePerm){restore = '<li title="بازگشت پرداخت " data-toggle="tooltip" class="text-warning-800"><a data-toggle="modal" href="#modal_restore"><i onclick="history('+res[i].id+')" class="icon-file-minus"></i></li>';}else{restore = '';}
    if(editHandlePerm){edit = '<li title="ویرایش هماهنگی" data-toggle="tooltip" class="text-primary"><a data-toggle="modal" href="#modal_edit_handle"><i class="icon-pencil6" onclick="edit_handle('+res[i].id+' ,'+res[i].volume_handle+')" ></i></a></li>';}else{edit = '';}
    if(deleteHandlePerm){deleteHandle = '<li title="حذف هماهنگی" data-toggle="tooltip" class="text-danger"><a data-toggle="modal" href="#modal_delete_handle"><i onClick="deleteHandle('+res[i].id+' ,'+res[i].handle_pay+')" class="icon-cross2"></i></a></li>';}else{deleteHandle = '';}
    title1 = ' پرداخت شده - هماهنگی &#xA;' + numeral(res[i].handle_pay).format('0,0') + ' - ' + numeral(res[i].volume_handle).format('0,0');  
    str += '<tr><td>'+i+'</td><td><a href="'+url+'deal/profile/'+res[i].cust_id+'" target="_blank" class="enterCustomer">'+res[i].fullname+'</a></td><td class="'+t1+'">'+numeral(res[i].volume_handle).format('0,0')+'</td><td class="'+t1+'">'+numeral(res[i].handle_pay).format('0,0')+'</td><td class="'+t2+'"><span title="'+title1+'" data-toggle="tooltip">'+numeral(res[i].handle_rest).format('0,0')+'</span></td><td>'+res[i].bank_id+'</td><td>'+res[i].explain+'</td><td>'+res[i].date_handle+'</br>'+res[i].time_handle+'</td><td class="text-center"><ul class="icons-list">'+all+slice+restore+edit+deleteHandle+'</ul></td></tr>';
}
if(which == 1){
    handleTable.innerHTML = str;
}else{
    handleTable2.innerHTML = str;
}
$(function () {
    $('[data-toggle="tooltip"]').tooltip();
})
}

//show handle

//scroll 
var a = document.getElementById('div_handle');
var b = document.getElementById('div_bank');
var c = document.getElementById('archive_handle');
var p = document.getElementById('alert_status').innerHTML;
window.onload = function(){
    if(p == 0){

    }else if(p == 1){
        window.setTimeout(function() {
            window.scrollTo(0 , a.offsetTop);
        } , 1500 )     
    }else if(p == 2){
        window.setTimeout(function() {
            window.scrollTo(0 , b.offsetTop);
        } , 1500 )
    }else if(p == 3){
        window.setTimeout(function() {
            window.scrollTo(0 , c.offsetTop);
        } , 1500 )
    }
}
//scroll
