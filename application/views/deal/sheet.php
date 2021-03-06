<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if($this->session->has_userdata('msg')){
$msg = $this->session->userdata('msg');?>
<div class="alert bg-<?php echo $msg[1];?> alert-styled-left">
										<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
										<?php echo $msg[0];?>
								    </div>
<?php }?>

<div class="breadcrumb-line breadcrumb-line-component mb-20">
	<ul class="breadcrumb">
		<li><a href="<?php echo base_url('home');?>"><i class="icon-home2 position-left"></i> داشبورد</a>
		</li>
		<li><a href="<?php echo base_url('deal/worksheet')?>">معاملات</a>
		</li>
		<li class="active">کاربرگ معاملات</li>
	</ul>
</div>
<!-- handle -->
<?php if($this->session->has_userdata('add_handle') and $this->session->userdata('add_handle') == TRUE){?>
	<div class="panel panel-flat">
		<div class="panel-body">
		<form action="<?php echo base_url('deal/worksheet/')?>" method="post">
			<legend class="text-semibold"><i class="icon-address-book position-left"></i> افزودن هماهنگی</legend>
			<div class="row">
				<div>
					<div class="col-md-3">
						<div class="form-group">
							<label> مشتری خرید :</label>
							<input type="text" name="customer_buy" onFocus ="search_buy(this);"  placeholder=" لطفا نام مشتری خرید را وارد کنید "  autocomplete="off" class="form-control" required autofocus>
							<p class="text-danger" style="display:none; position:absolute;font-size:12px;"></p>
						</div>
					</div>
						<div class="col-md-3">
						<div class="form-group">
							<label> مشتری فروش :</label>
							<input type="text" name="customer_sell" onFocus ="search_sell(this);" autocomplete="off" placeholder="لطفا نام مشتری فروش را وارد کنید" class="form-control" required>
							<p class="text-danger" style="display:none; position:absolute;font-size:12px;"></p>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>انتخاب حساب :</label>
                            <select class="form-control" name="bank_id" id ="bankSelect" required>
							<option value="0">نام مشتری خرید را وارد کنید</option>
											</select>
						</div>
					</div>
					<div class="col-md-1">
								<div class="form-group">
									<label for="j_created_date"> تاریخ  :</label>
									<input type="text" class="form-control" name="date_handle" id="j_created_date" readonly data-mddatetimepicker="true" data-enabletimepicker="true" data-placement="bottom" value="<?php echo $date; ?>" placeholder="Jalali Created Date">
								</div>
							</div>
					<div class="col-md-2">
						<div class="form-group">
							<label>مبلغ هماهنگی :</label>
							<input type="text" placeholder="111,000,000"  onkeyup="amhandle(this)" autocomplete="off" class="form-control" required>
							<input type = "hidden" name='volume_handle' value="0">
						</div>
					</div>
				</div>
			</div>
			<div class="text-right">
				<button type="submit" name="sub" class="btn btn-primary mt-25">ثبت هماهنگی <i class="icon-arrow-left13 position-right"></i></button>
			</div>
			</form>
		</div>
	</div>
<?php } ?>
<!-- sheet -->
<div class="panel panel-flat">
	<div class="panel-body">
		<div class="row">
		<!---------------------------->
		<!---buy customer worksheet--->
		<!---------------------------->
			<div class="col-md-6">
				<fieldset style="height:630px;">
					<legend class="text-semibold"><i class="icon-cart5 position-left"></i> خرید</legend>

					<table class="table datatable-selection-single table-hover table-responsive-lg table-striped  ">
						<thead>
							<tr>
							    <th width="22%">نام مشتری</th>
								<th width="24%"> مبلغ ریالی</th>
								<th width="24%"> هماهنگ شده</th>
								<th width="24%">هماهنگ نشده</th>
							</tr>
						</thead>
						<tbody id="searchBuy">
						<tr></tr>
						</tbody>
						<tbody id ="baseBuy">
<?php  if(empty($buy)){ ?>
<tr><td colspan="4" class="text-center p-20">موردی یافت نشد</td></tr>
<?php }else{
	foreach($buy as $buys){
		?>
		<tr class="<?php if($buys->rest == 0){echo 'tr_rest';}?>">
			<td><a href="<?php echo base_url('deal/profile/').$buys->customer_id;?>" class="enterCustomer" target="_blank"><?php echo $buys->fullname;?></a></td>
			<td><?php echo number_format($buys->volume);?></td>
			<td><?php echo number_format($buys->handle);?></td>
            <td>
			<span title="<?php echo number_format($buys->handle)." - ".number_format($buys->volume);?>" data-toggle="tooltip">
			<?php echo number_format($buys->volume - $buys->handle);?>
			</span>
			</td>                            
		</tr>

<?php }} ?>
						</tbody>
					</table>
				</fieldset>
<div class="float-left">
	<ul class="pagination">
<?php
if($countBuy > 10){
$base = floor($countBuy / 10);
if($countBuy % 10 != 0 ){
	$count = $base + 1;
}else{
	$count = $base;
}
$offset = 0; for($i = 0 ; $i < $count ; $i++){ ?>
<li class="buy <?php if($i == 0){echo 'active';} ?>"><a onclick = "buyPagin(<?php echo $offset; ?> , this)"><?php echo $i + 1;?></a></li>
<?php $offset += 10; }
}
?>


	  </ul>
	</div>
</div>
		<!---------------------------->
		<!---buy customer worksheet--->
		<!---------------------------->

		<!---------------------------->
		<!--sell customer worksheet--->
		<!---------------------------->
			<div class="col-md-6">
				<fieldset style="height:630px;">
					<legend class="text-semibold"><i class="icon-coins position-left"></i> فروش</legend>
					<table class="table datatable-selection-single table-hover table-responsive-lg ">
						<thead>

							<tr>
								<th width="22%">نام مشتری</th>
								<th width="26%"> مبلغ ریالی</th>
								<th width="26%"> هماهنگ شده</th>
								<th width="26%">هماهنگ  نشده</th>
							</tr>
						</thead>
						<tbody id="searchSell">
						<tr></tr>
						</tbody>
						<tbody id="baseSell">
<?php 
if(empty($sell)){ ?>
<tr><td colspan="4" class="text-center p-20">موردی یافت نشد</td></tr>
<?php }else{
	foreach($sell as $sells){ ?>
			<tr class="<?php if($sells->rest == 0){echo 'tr_rest';} ?>">
				<td><a href="<?php echo base_url('deal/profile/').$sells->customer_id;?>" target="_blank"><?php echo $sells->fullname;?></a></td>
				<td><?php echo number_format($sells->volume);?></td>
				<td><?php echo number_format($sells->handle);?></td>
				<td>
				<span title="<?php echo number_format($sells->handle)." - ".number_format($sells->volume);?>" data-toggle="tooltip">
			    <?php echo number_format($sells->volume - $sells->handle);?>
			   </span>       
				</td>
			</tr>
<?php } } ?>
						</tbody>
					</table>
				</fieldset>
				<div class="float-left">
									<ul class="pagination">
<?php
if($countSell > 10){
$base = floor($countSell / 10);
if($countSell % 10 != 0 ){
	$count = $base + 1;
}else{
	$count = $base;
}
$offset = 0; for($i = 0 ; $i < $count ; $i++){ ?>
<li class="sell <?php if($i == 0){echo 'active';} ?>"><a onclick = "sellPagin(<?php echo $offset; ?> , this)"><?php echo $i + 1;?></a></li>
<?php $offset += 10; }
}
?>
					</ul>
				</div>
			</div>
		<!---------------------------->
		<!--sell customer worksheet--->
		<!---------------------------->
		</div>
	</div>
</div>
<!-- /sheet -->

<!-- search customer -->
<?php
		$str = ''; $str2 = ''; $str3 = '';
		$count = sizeof($search);
		for($i = 1 ; $i <= $count ; $i++){
			$name = $search[$i]['fullname'];
			$buy  = $search[$i]['buy'];
			$sell = $search[$i]['sell'];
			$str .= "\"$name\",";
		   $str2 .= "\"$buy\"," ;
		   $str3 .= "\"$sell\",";
		}
		$str = trim($str , ','); $str2 = trim($str2 , ','); $str3 = trim($str3 , ','); 
?>
<!-- search customer -->

<script type="text/javascript" src="<?php echo base_url('files/');?>assets/mine/sheet.js"></script>

<script>

//pagin buy table
function buyPagin(offset , li){
   var classBuy = document.getElementsByClassName('buy');
   for(i = 0 ; i < classBuy.length ; i++){
   classBuy[i].classList.remove('active');
   }
   li.parentElement.classList.add('active');

	var xhr = new XMLHttpRequest();
		xhr.onload = function () {
			if ( ( xhr.status >= 200 && xhr.status < 300 ) || xhr.status == 304 ) {
                // alert(xhr.responseText);
				var result = JSON.parse( xhr.responseText );
				var url = "<?php echo base_url();?>";
				showBuy( result , url);
			} else {
				alert( 'request was unsuccessful : ' + xhr.status );
			}
		}
		xhr.open( 'post', "<?php echo base_url('deal/page_buy/')?>", true );
		xhr.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' );
		xhr.send( 'offset=' + offset );
}
//pagin buy table

//pagin sell table
function sellPagin(offset , li){
	var classSell = document.getElementsByClassName('sell');
for(i = 0 ; i < classSell.length ; i++){
classSell[i].classList.remove('active');
}
li.parentElement.classList.add('active');

	var xhr = new XMLHttpRequest();
		xhr.onload = function () {
			if ( ( xhr.status >= 200 && xhr.status < 300 ) || xhr.status == 304 ) {
               // alert(xhr.responseText);
				var result = JSON.parse( xhr.responseText );
				var url = "<?php echo base_url();?>";
				showSell( result , url);
			} else {
				alert( 'request was unsuccessful : ' + xhr.status );
			}
		}
		xhr.open( 'post', "<?php echo base_url('deal/page_sell/')?>", true );
		xhr.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' );
		xhr.send( 'offset=' + offset );
}
//pagin sell table

// search customer
var array  = [ <?php echo $str;?> ];
var array2 = [<?php echo $str2;?> ];
var array3 = [<?php echo $str3;?> ];
function search_buy( input ) {
autocomplete( input, array , array2 , array3 , 'buy');
}
function search_sell( input ) {
autocomplete( input, array , array2 , array3 , 'sell');
}
//search customer		




// show customer
function show(val , type){
	var xhr = new XMLHttpRequest();
		xhr.onload = function () {
			if ( ( xhr.status >= 200 && xhr.status < 300 ) || xhr.status == 304 ) {
      // alert(xhr.responseText);
				var result = JSON.parse(xhr.responseText);
				var url = "<?php echo base_url();?>";
				showTable( result , type , url);
			} else {
				alert( 'request was unsuccessful : ' + xhr.status );
			}
		}
		xhr.open( 'post', "<?php echo base_url('deal/get_customer/')?>", true );
		xhr.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' );
		xhr.send( 'name='+val+'&type='+type );
}
// show customer
</script>
