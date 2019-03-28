function show_bank( input ) {
    var txt = input.value;
    var name_bank = input.parentElement.parentElement.nextElementSibling.firstElementChild.lastElementChild;
    if ( txt[ 3 ] != '_' && txt[ 4 ] != '_' && txt[ 5 ] != '_'  ) {
        name_bank.setAttribute("readonly" , 'readonley');
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
            name_bank.removeAttribute("readonly"); 
        }
    } else {
        name_bank.removeAttribute("readonly"); 
        name_bank.value = '';
    }

}
var vpay = document.getElementById('vpay');
var alert_msg = document.getElementById('alert');
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
    if(numeral(volume.innerHTML).value() < vpay.value){
        alert_msg.style.display = 'block';
        alert_msg.innerHTML = 'مبلغ معامله کمتر از مبلغ پرداخت شده است';
    }else{
        alert_msg.style.display = 'none';
    }
}
wage.onkeyup = function () {
    var name = money_name.options[money_name.selectedIndex].innerHTML;
    wage.value = numeral( wage.value ).format( '0,0' ) + ' ' + name;
    var x = numeral( count.value ).value();
    var y = numeral( wage.value ).value();
    var z = numeral( convert.value ).value();
    wage.nextElementSibling.value = y;
    volume.innerHTML = numeral( ( x + y ) * z ).format( '0 , 0' ) + ' ریـال  ';
    if(numeral(volume.innerHTML).value() < vpay.value){
        alert_msg.style.display = 'block';
        alert_msg.innerHTML = 'مبلغ معامله کمتر از مبلغ پرداخت شده است';
    }else{
        alert_msg.style.display = 'none';
    }
}
convert.onkeyup = function () {
    convert.value = numeral( convert.value ).format( '0,0' ) +  ' ریـال ';
    var x = numeral( count.value ).value();
    var y = numeral( wage.value ).value();
    var z = numeral( convert.value ).value();
    convert.nextElementSibling.value = z;
    volume.innerHTML = numeral( ( x + y ) * z ).format( '0 , 0' ) + ' ریـال  ';
    if(numeral(volume.innerHTML).value() < vpay.value){
        alert_msg.style.display = 'block';
        alert_msg.innerHTML = 'مبلغ معامله کمتر از مبلغ پرداخت شده است';
    }else{
        alert_msg.style.display = 'none';
    }
}

function amount_bank( input ) {
    var volume = document.getElementById( 'volume_deal');
    input.value = numeral( input.value ).format( '0,0' ) + " ریـال ";
    input.nextElementSibling.value = numeral( input.value ).value();
    if(input.previousElementSibling.value > numeral(input.value).value()){
        input.nextElementSibling.nextElementSibling.style.display = 'block';
        input.nextElementSibling.nextElementSibling.innerHTML = ' مبلغ تعیین شده از مبلغی که به این حساب واریز شده  کمتر است  ';
    }
   else if(numeral(volume.innerHTML).value() < numeral(input.value).value()){
     input.nextElementSibling.nextElementSibling.style.display = 'block';
     input.nextElementSibling.nextElementSibling.innerHTML = ' مبلغ وارد شده بیشتر از حجم معامله است';
    }else{
        input.nextElementSibling.nextElementSibling.style.display = 'none';
    }
}