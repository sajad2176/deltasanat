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
                            <select class="form-control" name="bank_id" id ="money_id" required>
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
			<div class="col-md-6">
				<fieldset style="height:560px;">
					<legend class="text-semibold"><i class="icon-cart5 position-left"></i> خرید</legend>

					<table class="table datatable-selection-single table-hover table-responsive-lg table-striped  ">
						<thead>
							<tr>
							<th width="25%">نام مشتری</th>
								<th width="25%"> مبلغ ریالی</th>
								<th width="25%"> هماهنگ شده</th>
								<th width="25%">هماهنگ نشده</th>
								
							</tr>
						</thead>
						<tbody id="tbody_buy">
						<tr></tr>
						</tbody>
						<tbody id ="buy_table">
<?php 
if(empty($buy)){ ?>
<tr><td colspan="4" class="text-center p-20">موردی یافت نشد</td></tr>
<?php }else{
	foreach($buy as $buys){
		if($buys->rest == 0){
			$rest_class = 'tr_rest';
		}else{
			$rest_class = '';
		}
		?>
							<tr class="<?php echo $rest_class;?>">
								<td><a href="<?php echo base_url('deal/handle_profile/').$buys->customer_id;?>" target="_blank"><?php echo $buys->fullname;?></a></td>
								<td><?php echo number_format($buys->volume);?></td>
								<td><?php echo number_format($buys->handle);?></td>
                <td><?php echo number_format($buys->volume - $buys->handle);?></td>                            
							</tr>
<?php }} ?>
						</tbody>
					</table>
				</fieldset>
				<div class="float-left">
									<ul class="pagination">
<?php
if($rows_buy > 10){
$base = floor($rows_buy / 10);
if($rows_buy % 10 != 0 ){
	$count = $base + 1;
}else{
	$count = $base;
}
$offset = 0; for($i = 0 ; $i < $count ; $i++){ if($i == 0){$active = 'active';}else{$active = '';}?>
<li class="buy <?php echo $active;?>"><a onclick = "buy(<?php echo $offset; ?> , this)"><?php echo $i + 1;?></a></li>
<?php $offset += 10; }
}
?>


									</ul>
								</div>
			</div>
			<div class="col-md-6">
				<fieldset style="height:560px;">
					<legend class="text-semibold"><i class="icon-coins position-left"></i> فروش</legend>
					<table class="table datatable-selection-single table-hover table-responsive-lg ">
						<thead>

							<tr>
								<th width="25%">نام مشتری</th>
								<th width="25%"> مبلغ ریالی</th>
								<th width="25%"> هماهنگ شده</th>
								<th width="25%">هماهنگ  نشده</th>
							</tr>
						</thead>
						<tbody id="tbody_sell">
						<tr></tr>
						</tbody>
						<tbody id="sell_table">
<?php 
if(empty($sell)){ ?>
<tr><td colspan="4" class="text-center p-20">موردی یافت نشد</td></tr>
<?php }else{
	foreach($sell as $sells){
		if($sells->rest == 0){
			$rest_class = 'tr_rest';
		}else{
			$rest_class = '';
		}
		?>
							<tr class="<?php echo $rest_class;?>">
								<td><a href="<?php echo base_url('deal/handle_profile/').$sells->customer_id;?>" target="_blank"><?php echo $sells->fullname;?></a></td>
								<td><?php echo number_format($sells->volume);?></td>
								<td><?php echo number_format($sells->handle);?></td>
                <td><?php echo number_format($sells->volume - $sells->handle);?></td>                            
							</tr>
<?php } } ?>
						</tbody>
					</table>
				</fieldset>
				<div class="float-left">
									<ul class="pagination">
<?php
if($rows_sell > 10){
$base = floor($rows_sell / 10);
if($rows_sell % 10 != 0 ){
	$count = $base + 1;
}else{
	$count = $base;
}
$offset = 0; for($i = 0 ; $i < $count ; $i++){ if($i == 0){$active = 'active';}else{$active = '';}?>
<li class="sell <?php echo $active;?>"><a onclick = "sell(<?php echo $offset; ?> , this)"><?php echo $i + 1;?></a></li>
<?php $offset += 10; }
}
?>


									</ul>
								</div>
			</div>
		</div>
	</div>
</div>
<!-- /sheet -->

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

<script type="text/javascript" src="<?php echo base_url('files/');?>assets/mine/sheet.js"></script>
<script>
var array  = [ <?php echo $str;?> ];
var array2 = [<?php echo $str2;?> ];
var array3 = [<?php echo $str3;?> ];
function search_buy( input ) {
autocomplete( input, array , array2 , array3 , 'buy');
}
function search_sell( input ) {
autocomplete( input, array , array2 , array3 , 'sell');
}		


//buy table
function buy(offset , li){
var classBuy = document.getElementsByClassName('buy');
for(i = 0 ; i < classBuy.length ; i++){
classBuy[i].classList.remove('active');
}
li.parentElement.classList.add('active');
Btable.style.display = 'contents';
tbody_buy.style.display = 'none';
	var xhr = new XMLHttpRequest();
		xhr.onload = function () {
			if ( ( xhr.status >= 200 && xhr.status < 300 ) || xhr.status == 304 ) {
// alert(xhr.responseText);
				var result = JSON.parse( xhr.responseText );
				var url = "<?php echo base_url();?>";
				buyTable( result , url);
			} else {
				alert( 'request was unsuccessful : ' + xhr.status );
			}
		}
		xhr.open( 'post', "<?php echo base_url('deal/page_buy/')?>", true );
		xhr.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' );
		xhr.send( 'offset=' + offset );
}
//buy table
//sell table
function sell(offset , li){
	var classSell = document.getElementsByClassName('sell');
for(i = 0 ; i < classSell.length ; i++){
classSell[i].classList.remove('active');
}
li.parentElement.classList.add('active');
stable.style.display = 'contents';
tbody_sell.style.display = 'none';
	var xhr = new XMLHttpRequest();
		xhr.onload = function () {
			if ( ( xhr.status >= 200 && xhr.status < 300 ) || xhr.status == 304 ) {
// alert(xhr.responseText);
				var result = JSON.parse( xhr.responseText );
				var url = "<?php echo base_url();?>";
				sellTable( result , url);
			} else {
				alert( 'request was unsuccessful : ' + xhr.status );
			}
		}
		xhr.open( 'post', "<?php echo base_url('deal/page_sell/')?>", true );
		xhr.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' );
		xhr.send( 'offset=' + offset );
}
//sell table

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
</script>
