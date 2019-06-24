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
		<li><a href="<?php echo base_url('rest_unit');?>">تنظیمات</a>
		</li>
		<li class="active">مانده حساب ریالی</li>
	</ul>
</div>
<!-- form arz -->
<div class="panel panel-flat">
	<div class="panel-body">
		<form action="<?php echo base_url('settings/rest_unit')?>" method="post">
			<div class="row">
				<div class="col-md-12">
					<legend class="text-semibold"><i class=" icon-price-tag3 position-left"></i> مانده حساب ریالی</legend>
				</div>
				<div class="col-md-12">
					<fieldset>
						<div class="col-md-3">
							<div class="form-group">
								<label>نام مشتری : </label>
								<input name="fullname" onFocus="search_customer(this)" autocomplete="off" type="text" class="form-control" placeholder="نام مشتری خود را وارد کنید" required>
								<p class="text-primary" style="display:none;"></p>
							</div>
						</div>
			
						<div class="col-md-3">
							<div class="form-group">
								<label>مقدار حساب : </label>
								<input type="text" class="form-control" id="count" autocomplete="off" placeholder="100,000" required>
								<input name="count" type="hidden" class="form-control">
							</div>
						</div>
                        <div class="col-md-2">
								<div class="form-group">
									<label for="j_created_date"> تاریخ ثبت :</label>
									<input type="text" class="form-control" name="date_deal" id="j_created_date" readonly data-mddatetimepicker="true" data-placement="bottom" value="<?php echo $date;?>" placeholder="Jalali Created Date" required>
								</div>
								</div>
                        <div class="col-md-3">
                        <label class="display-block text-semibold">نوع حساب :</label>
						<div class="form-group mt-20">
										
										<label class="radio-inline">
											<input type="radio" name="type" value='2' class="styled" checked="checked">
											بدهکار
										</label>

										<label class="radio-inline">
											<input type="radio" name="type" value="1" class="styled" >
											طلب کار
										</label>
									</div>
						</div>
					</fieldset>
				</div>
			</div>
			<div class="text-right">
				<button type="submit" name="sub" class="btn btn-primary">ثبت مانده حساب <i class="icon-arrow-left13 position-right"></i></button>
			</div>
		</form>
	</div>
</div>
<!-- /form arz -->
<!--table arz-->
<div class="panel panel-flat">
	<div class="panel-body">
		<legend class="text-semibold"><i class=" icon-books position-left"></i> آرشیو </legend>
		<table class="table datatable-selection-single table-hover table-responsive-lg ">
		<thead>
			<tr>
			<th width="5%">شناسه</th>
				<th width="10%">نام مشتری</th>
				<th width="5%">نوع</th>
				<th width="10%">تعداد ارز</th>
				<th width="8%">نرخ تبدیل</th>
				<th width="15%">حجم معامله</th>
				<th width="15%">حجم پرداخت شده</th>
				<th width="15%">حجم باقی مانده</th>
				<th width="7%"> تاریخ ثبت</th>
				<th width="10%" class="text-center">ابزار</th>
			</tr>
		</thead>
		<tbody>

			<?php 
			if(empty($deal)){ ?>
			<tr><td colspan = '10' class='text-center p-20'>موردی یافت نشد</td></tr>
			<?php }else{
			$num = $this->uri->segment(3) + 1;
			foreach($deal as $rows){ ?>
			<tr class='<?php if($rows->temp == 1){echo 'tr_temp';}?>'>
				<td>
					<?php echo $rows->id;?>
				</td>
				<td>
					<a href="<?php echo base_url('deal/profile/').$rows->cust_id ?>" target="_blank" class="enterCustomer">
						<?php echo $rows->fullname; ?>
					</a>
				</td>
				<td>
					<?php if($rows->type == 1){echo 'خرید';}else{echo 'فروش';}?>
				</td>
				<td>
					<?php echo number_format($rows->count_money)."</br>".$rows->name;?>
				</td>
				<td>
					<?php echo number_format($rows->convert); ?>
				</td>
				<td class="lright <?php if($rows->volume < $rows->pay){echo 'text-danger';}?>">
				<span title="<?php echo " ( ".number_format($rows->count_money).' + '.$rows->wage." ) × ".number_format($rows->convert) ;?>" data-toggle="tooltip">
				<?php echo number_format($rows->volume);?>
				</span>
				</td>
				<td class="<?php if($rows->volume < $rows->pay){echo 'text-danger';}?>">
					<?php echo number_format($rows->pay);?>
				</td>
				<td class="lright <?php if($rows->rest < 0){echo 'text-danger';}?>">
				<span title="<?php echo number_format($rows->volume)." - ".number_format($rows->pay)?>" data-toggle="tooltip">
				<?php echo number_format($rows->rest);?>
				</span>
				</td>
				<td>
					<?php echo $rows->date_deal."</br>".$rows->time_deal; ?>
				</td>
				<td class="text-center">
					<ul class="icons-list">
<?php if($this->session->has_userdata('edit_deal') and $this->session->userdata('edit_deal') == TRUE){?><li title="ویرایش معامله" data-toggle="tooltip" class="text-primary"><a href="<?php echo base_url('deal/edit/').$rows->id;?>"><i class=" icon-pencil6"></i></a></li><?php } ?>
<?php if($this->session->has_userdata('see_photo') and $this->session->userdata('see_photo') == TRUE){?><li title="مشاهده قبض" data-toggle="tooltip" class="text-indigo-600"><a href="<?php echo base_url('deal/photo/').$rows->id;?>"><i class="icon-stack-picture"></i></a></li><?php } ?>
<?php if($this->session->has_userdata('delete_deal') and $this->session->userdata('delete_deal') == TRUE){?><li title="حذف معامله"  data-toggle="tooltip" class="text-danger" ><a data-toggle="modal" href="#modal_theme_danger"><i  class="icon-trash" onclick = "deleteDeal(<?php echo $rows->id;?> , <?php echo $rows->pay; ?>)" ></i></a></li><?php } ?>
					</ul>
				</td>
			</tr>
			<?php
			$num++;
			}
			?>
			<tr>
				<td colspan="6" class="pt-20 pb-20">
					نمایش
					<?php echo  $this->uri->segment(3) + 1;?> تا
					<?php echo $num - 1; ?> از
					<?php echo $count;?>
				</td>
				<td colspan="5" class="text-left pt-20 pb-20">
					<?php echo $page; ?>
				</td>
			</tr>
			<?php } ?>
		</tbody>
		</table>
	</div>
</div>
<div id="modal_theme_danger" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header bg-danger">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">حذف معامله</h4>
						</div>

						<div class="modal-body">

							<h6 class="text-center" id="titleDelete"></h6>


						</div>

						<div class="modal-footer text-center">
							<button type="button" class="btn btn-danger" data-dismiss="modal" id='closeDelete'>بستن</button>
							<a class="btn btn-success" id="confirmDelete">بله </a>
						</div>
					</div>
				</div>
			</div>
</div>
<?php $str = '';foreach($customer as $row){$str .= "\"$row->fullname\",";}$str = trim($str , ",");?>
<!--/table arz-->
<script>
	var titleDelete = document.getElementById('titleDelete');
	var closeDelete = document.getElementById('closeDelete');
	var confirmDelete = document.getElementById('confirmDelete');
	function deleteDeal(id , rest){
      if(rest != 0){
		  titleDelete.innerHTML = " حجم پرداختی این معامله صفر نمی باشد . اگر مایل به حذف معامله می باشید جهت جلوگیری از ناسازگاری در سامانه ابتدا مبالغ پرداختی را بازگردانید. ";
		  closeDelete.style.display = 'none';
		  confirmDelete.style.display = 'none';
		  return;
	  }else{
		  titleDelete.innerHTML = " آیا می خواهید ادامه دهید؟";
		  closeDelete.style.display = 'inline-block';
		  confirmDelete.style.display = 'inline-block';
		  confirmDelete.setAttribute('href' , "<?php echo base_url('deal/delete_deal/')?>" + id + '/rest');
	  }
	}

	var customer = [<?php echo $str; ?>];
	function search_customer( input ) {
		autocomplete( input, customer );
	}

	function autocomplete( inp, arr ) {
		var currentFocus;
		inp.addEventListener( "input", function ( e ) {
			var a, b, i, val = this.value;
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
					let str = arr[ i ].slice( 0, arr[ i ].indexOf( search ) )+'<strong style="color:#46a64c;">'+match+'</strong>'+arr[ i ].slice( arr[ i ].length - lastIndx, arr[ i ].length );

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
				inp.nextElementSibling.innerHTML = 'بعد از اتمام معامله مشتری ' + val + ' به لیست مشتریان افزوده خواهد شد ';
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
					if ( x ){ x[ currentFocus ].click();}
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
	
	var count = document.getElementById( 'count' );
	count.onkeyup = function(){
		count.value = numeral( count.value ).format( '0,0' );
		count.nextElementSibling.value = numeral( count.value ).value();;
	}
</script>