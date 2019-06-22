// count convert wage volume
var vpay = document.getElementById('vpay');
var alert_msg = document.getElementById('alert');
var count = document.getElementById( 'count' );
var wage = document.getElementById( 'wage' );
var convert = document.getElementById( 'convert' );
var volume = document.getElementById( 'volume_deal' );

count.onkeyup = function () {
    count.value = numeral( count.value ).format( '0,0' );
    var x = numeral( count.value ).value();
    var y = numeral( wage.value ).value();
    var z = numeral( convert.value ).value();
    count.nextElementSibling.value = x;
    volume.innerHTML = numeral( ( x + y ) * z ).format( '0 , 0' ) + ' ریـال  ';
    if(numeral(volume.innerHTML).value() < vpay.innerHTML){
        alert_msg.style.display = 'block';
        alert_msg.innerHTML = ' مبلغ پرداختی برای این معامله ' + numeral(vpay.innerHTML).format('0,0') + " می باشد و این مقدار بیشتر از حجم معامله است که تعیین کردید ";
    }else{
        alert_msg.style.display = 'none';
    }
}
wage.onkeyup = function () {
    wage.value = numeral( wage.value ).format( '0,0' ) ;
    var x = numeral( count.value ).value();
    var y = numeral( wage.value ).value();
    var z = numeral( convert.value ).value();
    wage.nextElementSibling.value = y;
    volume.innerHTML = numeral( ( x + y ) * z ).format( '0 , 0' ) + ' ریـال  ';
    if(numeral(volume.innerHTML).value() < vpay.innerHTML){
        alert_msg.style.display = 'block';
        alert_msg.innerHTML = ' مبلغ پرداختی برای این معامله ' + numeral(vpay.innerHTML).format('0,0') + " می باشد و این مقدار بیشتر از حجم معامله است که تعیین کردید ";
    }else{
        alert_msg.style.display = 'none';
    }
}
convert.onkeyup = function () {
    convert.value = numeral( convert.value ).format( '0,0' );
    var x = numeral( count.value ).value();
    var y = numeral( wage.value ).value();
    var z = numeral( convert.value ).value();
    convert.nextElementSibling.value = z;
    volume.innerHTML = numeral( ( x + y ) * z ).format( '0 , 0' ) + ' ریـال  ';
    if(numeral(volume.innerHTML).value() < vpay.innerHTML){
        alert_msg.style.display = 'block';
        alert_msg.innerHTML = ' مبلغ پرداختی برای این معامله ' + numeral(vpay.innerHTML).format('0,0') + " می باشد و این مقدار بیشتر از حجم معامله است که تعیین کردید ";
    }else{
        alert_msg.style.display = 'none';
    }
}
// count convert wage volume

//little pay script
baseMoney = document.getElementById('base_money').innerHTML;
sendMoney = document.getElementById('send_money');
direct = document.getElementById('direct');
sub = document.getElementById('sub');
var modal_check = document.getElementById('modal_check');
function checkSubmit(){
    send = sendMoney[sendMoney.selectedIndex].value;
    var diff = numeral(volume.innerHTML).value() - vpay.innerHTML;
    diff = Math.abs(diff);
    if(baseMoney == 5 && baseMoney != send && Number(diff) <= Number(50000) && diff != 0 && vpay.innerHTML != 0){
    modal_check.style.display='block';
    document.getElementById('check_span').innerHTML = numeral(diff).format('0,0')
    }else{
        sub.submit();
    }
}
function check(status){
    if(status){
        direct.value = 1;
        sub.submit();
    }else{
        direct.value = 0;
        sub.submit();
    }
}
//little pay script