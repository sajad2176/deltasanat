window.onload = function(){
    setInterval(update , 15000);
}
var buyDollar = document.getElementById('buyDollar');
var buyEuro = document.getElementById('buyEuro');
var buyYuan = document.getElementById('buyYuan');
var buyDerham = document.getElementById('buyDerham');
var sellDollar = document.getElementById('sellDollar');
var sellEuro = document.getElementById('sellEuro');
var sellYuan = document.getElementById('sellYuan');
var sellDerham = document.getElementById('sellDerham');	
var today = document.getElementById('today');
// var sumHandle = document.getElementById('sumHandle');
// var sumPay = document.getElementById('sumPay');
var restRial = document.getElementById('restRial');
var dollar = document.getElementById('dollar');			
var euro = document.getElementById('euro');			
var yuan = document.getElementById('yuan');			
var derham = document.getElementById('derham');
var aveBuy = document.getElementById('aveBuy');
var aveSell = document.getElementById('aveSell');			

    function showResult(res){
        // sumHandle.innerHTML = numeral(res.sum_handle.vh).format('0,0');
        // sumPay.innerHTML = numeral(res.sum_pay.vp).format('0,0');
        restRial.innerHTML = numeral(res.rest_rial).format('0,0');
        today.innerHTML = res.today;
        buyDollar.innerHTML = numeral(res.buy_dollar).format('0,0');
        buyEuro.innerHTML = numeral(res.buy_euro).format('0,0');
        buyYuan.innerHTML = numeral(res.buy_yuan).format('0,0');
        buyDerham.innerHTML = numeral(res.buy_derham).format('0,0');
        sellDollar.innerHTML = numeral(res.sell_dollar).format('0,0');
        sellEuro.innerHTML = numeral(res.sell_euro).format('0,0');
        sellYuan.innerHTML = numeral(res.sell_yuan).format('0,0');
        sellDerham.innerHTML = numeral(res.sell_derham).format('0,0');
        dollar.innerHTML = numeral(res.remain[0].amount_unit).format('0,0');
        euro.innerHTML = numeral(res.remain[1].amount_unit).format('0,0');
        yuan.innerHTML = numeral(res.remain[2].amount_unit).format('0,0');
        derham.innerHTML = numeral(res.remain[3].amount_unit).format('0,0');
        aveBuy.innerHTML = numeral(res.ave_buy).format('0,0');
        aveSell.innerHTML = numeral(res.ave_sell).format('0,0');
    }