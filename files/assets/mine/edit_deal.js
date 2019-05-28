// count convert volume
var vpay = document.getElementById('vpay');
var alert_msg = document.getElementById('alert');
var count = document.getElementById( 'count' );
var wage = document.getElementById( 'wage' );
var convert = document.getElementById( 'convert' );
var volume = document.getElementById( 'volume_deal' );

count.onkeyup = function () {
    var x = numeral( count.value ).value();
    var y = numeral( wage.value ).value();
    var z = numeral( convert.value ).value();
    count.nextElementSibling.value = x;
    volume.innerHTML = numeral( ( x + y ) * z ).format( '0 , 0' ) + ' ریـال  ';
    if(numeral(volume.innerHTML).value() < vpay.value){
        alert_msg.style.display = 'block';
        alert_msg.innerHTML = ' مبلغ پرداختی برای این معامله ' + numeral(vpay.value).format('0,0') + " می باشد و این مقدار بیشتر از حجم معامله است که تعیین کردید ";
    }else{
        alert_msg.style.display = 'none';
    }
    count.value = numeral( count.value ).format( '0,0' );
}
wage.onkeyup = function () {
    var x = numeral( count.value ).value();
    var y = numeral( wage.value ).value();
    var z = numeral( convert.value ).value();
    wage.nextElementSibling.value = y;
    volume.innerHTML = numeral( ( x + y ) * z ).format( '0 , 0' ) + ' ریـال  ';
    if(numeral(volume.innerHTML).value() < vpay.value){
        alert_msg.style.display = 'block';
        alert_msg.innerHTML = ' مبلغ پرداختی برای این معامله ' + numeral(vpay.value).format('0,0') + " می باشد و این مقدار بیشتر از حجم معامله است که تعیین کردید ";
    }else{
        alert_msg.style.display = 'none';
    }
    wage.value = numeral( wage.value ).format( '0,0' ) ;
}
convert.onkeyup = function (e) {
    var x = numeral( count.value ).value();
    var y = numeral( wage.value ).value();
    var z = numeral( convert.value ).value();
    convert.nextElementSibling.value = z;
    volume.innerHTML = numeral( ( x + y ) * z ).format( '0 , 0' ) + ' ریـال  ';
    if(numeral(volume.innerHTML).value() < vpay.value){
        alert_msg.style.display = 'block';
        alert_msg.innerHTML = ' مبلغ پرداختی برای این معامله ' + numeral(vpay.value).format('0,0') + " می باشد و این مقدار بیشتر از حجم معامله است که تعیین کردید ";
    }else{
        alert_msg.style.display = 'none';
    }
    convert.value = numeral( convert.value ).format( '0,0' );
}
// count convert volume