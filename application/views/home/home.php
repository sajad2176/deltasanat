<!-- Dashboard content -->
<div class="panel panel-flat">
  <div class="panel-body"> 
    <div  class="panel-heading">
      <h4 class="panel-title">موجودی</h4>
    </div>
    <div class="row">
      <?php 
					$array = array('bg-blue-1' , 'bg-blue-2' , 'bg-blue-3' , 'bg-blue-4');
					$i = 0;
					foreach($remain as $remains){ ?>
      <div class="col-md-3" title=" ارز اولیه + ( فروش - خرید ) &#xA;<?php echo $remains->amount." + ( ".number_format($remains->sell_unit)." - ".number_format($remains->buy_unit)." ) ";?>" data-toggle="tooltip" >
        <div class="panel <?php echo $array[$i]; if($i%3 == 0){$i = 0;}?>">
          <div class="panel-body">
            <h3 class="no-margin lright unitAmount" > <?php echo number_format($remains->buy_unit - $remains->sell_unit + $remains->amount );?> </h3>
            <h5 class="text-white text-size-larg unitName"> <?php echo $remains->name;?> </h5>
          </div>
        </div>
      </div>
      <?php  $i++;  } ?>
    </div>
  </div>
</div>
<div  class="row">
  <div class="col-md-4"> 
    <!-- Daily sales -->
    <div class="panel panel-flat">
      <div class="panel-body">
        <div style="padding-right: 0px" class="panel-heading">
          <h6 class="panel-title">خرید و فروش ارز ها</h6>
          <div  class="heading-elements">
            <h6 class="heading-text">تاریخ: <span class="text-bold text-black position-right" id="today"><?php echo $today;?></span></h6>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table text-nowrap">
            <thead>
              <tr>
                <th style="width: 85px">ارز</th>
                <th class="text-success-800" style="padding-right: 80px">خرید</th>
                <th class="text-primary">فروش</th>
              </tr>
            </thead>
            <tbody>
              <?php $sell_v = 0; $buy_v = 0; foreach($deal as $deals){ $sell_v += $deals->sell_v; $buy_v += $deals->buy_v;?>
              <tr>
                <td><div class="media-body">
                    <div class="media-heading">
                      <h5 class="letter-icon-title dealName"><?php echo $deals->name?></h5>
                    </div>
                  </div></td>
                <td><h6 class="text-semibold text-success-800 pr-34 no-margin dealBuy"> <?php echo number_format($deals->buy);?> </h6></td>
                <td><h6 class="text-semibold text-primary pr-8 no-margin dealSell"> <?php echo number_format($deals->sell); ?> </h6></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <!-- /daily sales --> 
  </div>
  <div class="col-md-8">
    <div class="panel panel-flat">
      <div class="panel-body">
        <div  class="panel-heading">
          <h4 class="panel-title">هماهنگ شده ها</h4>
        </div>
        <div class="row">
        <!-- rest rial -->
          <div class="col-md-4" title=" تغییر + ( خرید - فروش) &#xA;<?php echo number_format($plus->rest_rial)." + &#xA;( ".number_format($sell_v)." - ".number_format($buy_v)." ) " ?>" data-toggle="tooltip"> 
            <div class="panel bg-teal-400 p-5 pb-10 pr-10">
              <div class="row">
                <h6 class="col-md-10"> مانده ریالی</h6>
                <a data-toggle="modal" href="#restRialModal" class="text-white col-md-1 mt-10"><span class=" icon-plus3" onclick="notHandle(<?php echo $plus->rest_rial;?> , 'rest')"></span></a>
                </div>
              <h3 class="no-margin lright" id="restRial"><?php echo number_format($sell_v - $buy_v + $plus->rest_rial);?></h3>
            </div>
          </div>
          <!-- rest rial -->

          <!-- not handle sell -->
          <div class="col-md-4" title = 'تغییر + (هماهنگی - فروش)&#xA;<?php if(empty($sell_not)){echo number_format($plus->not_sell).' + (0 - 0)';}else{echo number_format($plus->not_sell)." + ( ".number_format($sell_not->handle)." - ".number_format($sell_not->volume)." ) ";}?>' data-toggle="tooltip"> 
            <div class="panel bg-pink-400 p-5 pb-10 pr-10">
			<div class="row">
              <h6 class="col-md-10">مانده هماهنگ نشده فروش</h6>
			  <a data-toggle="modal" href="#notHandleSell" class="text-white col-md-1 mt-10"><span class=" icon-plus3" onclick="notHandle(<?php echo $plus->not_sell;?> , 'sell')"></span></a> </div>
              <h3 class="no-margin lright" id="sellNot"><?php if(empty($sell_not)){echo number_format(0 + $plus->not_sell);}else{echo number_format($sell_not->volume - $sell_not->handle + $plus->not_sell);} ?></h3>
            </div>
          </div>
         <!-- not handle sell -->
         <!-- not handle buy -->
          <div class="col-md-4" title = 'تغییر + (هماهنگی - خرید)&#xA;<?php if(empty($buy_not)){echo number_format($plus->not_buy).' + (0 - 0)';}else{echo number_format($plus->not_buy)." + ( ".number_format($buy_not->handle)." - ".number_format($buy_not->volume)." )";}?>' data-toggle="tooltip"> 
            <div class="panel bg-blue-400 p-5 pb-10 pr-10">
			<div class="row">
              <h6 class="col-md-10"> مانده هماهنگ نشده خرید</h6>
			  <a data-toggle="modal" href="#notHandleBuy" class="text-white col-md-1 mt-10"><span class=" icon-plus3" onclick="notHandle(<?php echo $plus->not_buy;?> , 'buy')" ></span></a> </div>
              <h3 class="no-margin lright" id="buyNot"><?php if(empty($buy_not)){echo number_format(0 + $plus->not_buy);}else{echo number_format($buy_not->volume - $buy_not->handle + $plus->not_buy);} ?></h3>
            </div>
          </div>
		  <!-- not handle buy -->
        </div>
      </div>
    </div>

    <div class="panel panel-flat">
      <div class="panel-body">
        <div class="panel-heading">
          <h4 class="panel-title">میانگین دلاری</h4>
        </div>
        <div class="col-md-6"> 

          <div class="panel bg-dollar p-5">
            <div class="panel-body">
              <div class="heading-elements"> <span class="heading-text badge bg-teal-800">میانگین دلاری خرید </span> </div>
              <h3 class="no-margin lright" id="aveBuy"><?php echo number_format($ave_buy); ?></h3>
            </div>
          </div>
   
        </div>
        <div class="col-md-6"> 

          <div class="panel bg-dollar2 p-5">
            <div class="panel-body">
              <div class="heading-elements"> <span class="heading-text badge bg-teal-400">میانگین دلاری فروش </span> </div>
              <h3 class="no-margin lright" id="aveSell"><?php echo number_format($ave_sell); ?></h3>
            </div>
          </div>

        </div>
      </div>
       <!-- restRialModal  -->
			<div id="restRialModal" class="modal fade">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header bg-success">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title">تغییر در مانده ریالی</h4>
							</div>
							<div class="modal-body">
              <form action="<?php echo base_url('home/change_amount/rest');?>" method="post">
              <div class="row">
              <div class="form-group  col-md-6">
              <label>مقدار فعلی</label>
              <p class="form-control"><?php echo number_format($sell_v - $buy_v); ?></p>
              </div>
							<div class="form-group input-group col-md-6">
						
            <label>مقدار تغیرات</label>
                        <input type="text" id="restInput" placeholder="مقدار مورد نظر را وارد کنید" onkeyup="insertAmount(this)" class="form-control">
                        <input type="hidden" name="change" value="0">
                        <span class="input-group-btn ">
                        <button type="submit" name="sub" class="btn btn-success mt-25">ثبت تغیرات</button>
                        </span> </div></div>
                    </form>         
							</div>
						</div>
					</div>
				</div>
			<!-- restRialModal -->
    </div>
  </div>
  			<!-- notHandleSell-->
        <div id="notHandleSell" class="modal fade">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header bg-success">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title">تغییر در مانده هماهنگ نشده فروش</h4>
							</div>
                
							<div class="modal-body">
              <form action="<?php echo base_url('home/change_amount/sell');?>" method="post">
              <div class="row">
              <div class="form-group  col-md-6">
              <label>مقدار فعلی</label>
              <p class="form-control"><?php if(empty($sell_not)){echo '0';}else{echo number_format($sell_not->volume - $sell_not->handle);}?></p>
              </div>
							<div class="form-group input-group col-md-6">
						
            <label>مقدار تغیرات</label>
                        <input type="text" id="sellInput" onkeyup="insertAmount(this)" placeholder=" مقدار مورد نظر را وارد کنید " class="form-control" required>
                        <input type="hidden" name="change" value = '0'>
                        <span class="input-group-btn ">
                        <button type="submit" name="sub" class="btn btn-success mt-25">ثبت تغیرات</button>
                        </span> </div></div>
              </form>       
							</div>
						</div>
					</div>
				</div>

			<!-- /notHandleSell -->
</div>
			<!-- notHandleBuy modal-->
			<div id="notHandleBuy" class="modal fade">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header bg-success">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title">تغیر در مانده ریالی</h4>
							</div>
          <form action="<?php echo base_url('home/change_amount/buy');?>" method="post" >
							<div class="modal-body">
              <div class="row">
              <div class="form-group  col-md-6">
              <label>مقدار فعلی</label>
              <p  class="form-control"><?php if(empty($buy_not)){echo '0';}else{echo number_format($buy_not->volume - $buy_not->handle);}?></p>
              </div>
							<div class="form-group input-group col-md-6">
						
            <label>مقدار تغیرات</label>
                        <input type="text" id="buyInput" placeholder="مقدار مورد نظر را وارد کنید" onkeyup="insertAmount(this)" class="form-control">
                        <input type="hidden" name="change" value="0">
                        <span class="input-group-btn ">
                        <button type="submit" name="sub" class="btn btn-success mt-25">ثبت تغیرات</button>
                        </span> </div></div>        
							</div>
          </form>


						</div>
					</div>
				</div>
			<!-- notHandleBuy modal -->

<script type="text/javascript" src="<?php echo base_url('files/');?>assets/mine/home.js"></script>
<script>
function update(){
var xhr = new XMLHttpRequest();
xhr.onload = function(){
if((xhr.status >= 200 && xhr.status < 300) || xhr.status == 304){
var result = JSON.parse(xhr.responseText);
    showResult(result);
}else{
    alert('request was unsuccessful : ' + xhr.status);
    location.reload();
}
}
xhr.open('post' , "<?php echo base_url('home/update_dashbord')?>" , true);
xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
xhr.send("request="+true);
}


</script>