// window.onload = function(){
//     setInterval(update , 20000);
// }
			
var unitName = document.getElementsByClassName('unitName');
var unitAmount = document.getElementsByClassName('unitAmount');
var today = document.getElementById('today');
var dealName = document.getElementsByClassName('dealName');
var dealBuy = document.getElementsByClassName('dealBuy');
var dealSell = document.getElementsByClassName('dealSell');
var restRial = document.getElementById('restRial');
var sellNot = document.getElementById('sellNot');
var buyNot = document.getElementById('buyNot');
var aveBuy = document.getElementById('aveBuy');
var aveSell = document.getElementById('aveSell');

    function showResult(res){
      var countUnit = res.remain.length;  
     for(var i = 0 ; i < countUnit ; i++ ){
         unitName[i].innerHTML = res.remain[i].name;
         unitAmount[i].innerHTML = numeral(res.remain[i].amount).format('0,0');
     }
     today.innerHTML = res.today;
     var countdeal = res.deal.length;
     var rest = 0;
     for(var j = 0 ; j < countdeal ; j++){
         dealName[j].innerHTML = res.deal[j].name;
         dealBuy[j].innerHTML = numeral(res.deal[j].buy).format('0,0');
         dealSell[j].innerHTML = numeral(res.deal[j].sell).format('0,0');
         rest += res.deal[j].sell_v - res.deal[j].buy_v;
     }
  
        restRial.innerHTML = numeral(rest).format('0,0');
        sellNot.innerHTML = numeral(res.sell_not).format('0,0');
        buyNot.innerHTML = numeral(res.buy_not).format('0,0');

        aveBuy.innerHTML = numeral(res.ave_buy).format('0,0');
        aveSell.innerHTML = numeral(res.ave_sell).format('0,0');
    }