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
		<li><a href="<?php echo base_url('deal/archive') ?>">معاملات</a>
		</li>
		<li class="active">آرشیو معاملات</li>
	</ul>

</div>
<div class="panel panel-flat">
	<div class="panel-body">
		<div class="panel-heading">
			<h5 class="panel-title">آرشیو معاملات</h5>

		</div>

		<div class="datatable-header">
			<div class="row">
				<form action="<?php echo base_url('deal/archive'); ?>" method="post">

					<div class="col-md-12">
						<div class="col-md-3">
							<div class="form-group">
								<label>جستجو : </label>
								<input class="form-control" type="search" onkeyup="search_cust(this)" placeholder="نام مشتری خود را جستجو کنید">

							</div>
						</div>
						<div class="col-md-4">
							<div class="col-md-6">
								<div class="form-group">
									<label for="j_created_date">از تاریخ :</label>
									<input type="text" class="form-control" name="start_date" id="j_created_date" readonly data-mddatetimepicker="true" data-enabletimepicker="true" data-placement="bottom" value="<?php echo $date; ?>" name="j_created_date" placeholder="Jalali Created Date">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="j_created_date">تا تاریخ :</label>
									<input type="text" class="form-control" name="end_date" id="j_created_date" readonly data-mddatetimepicker="true" data-enabletimepicker="true" data-placement="bottom" value="<?php echo $date;?>" name="j_created_date" placeholder="Jalali Created Date">
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>نوع معامله : </label>
								<select class="form-control" name="deal_type" required>
									<option value="0" <?php if($t == 0){echo 'selected';}?> >همه</option>
									<option value="1" <?php if($t == 1){echo 'selected';}?> >خرید</option>
									<option value="2" <?php if($t == 2){echo 'selected';}?> >فروش</option>
									
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>ارز معامله : </label>
								<select class="form-control" name="money_id" required>
								    <option value="0" <?php if($m == 0){echo 'selected';}?>>همه</option>
									<option  value="1" <?php if($m == 1){echo 'selected';}?> >دلار</option>
									<option value="2" <?php if($m == 2){echo 'selected';}?> >یورو</option>
									<option value="3"  <?php if($m == 3){echo 'selected';}?> >یوان</option>
									<option value="4"  <?php if($m == 4){echo 'selected';}?> >درهم</option>
								</select>
							</div>
						</div>
						<div class="col-md-1">
							<button class="btn btn-success mt-25" name="sub" type="submit" >اعمال فیلتر</button>
						</div>
						
					</div>
				</form>
			</div>
		</div>
	

	<table class="table datatable-selection-single table-hover table-responsive-lg ">
		<thead>
			<tr>
				<th>شناسه معامله</th>
				<th>نام مشتری</th>
				<th>نوع معامله</th>
				<th>تعداد ارز</th>
				<th>نرخ تبدیل</th>
				<th>حجم معامله</th>
				<th>حجم پرداخت شده</th>
				<th>حجم باقی مانده</th>
				<th>تاریخ ثبت</th>
				<th>آخرین ویرایش</th>
				<th class="text-center">ابزار</th>
			</tr>
		</thead>
		<tbody id="search_cust" tyle="display: none;">
			<tr></tr>
		</tbody>
		<tbody>

			<?php 
			if(sizeof($deal) == 0){ ?>
			<tr><td colspan = '11' class='text-center p-20'>موردی یافت نشد</td></tr>
			<?php }else{
			$num = $this->uri->segment(3) + 1;
			foreach($deal as $rows){ ?>
			<tr>
				<td>
					<?php echo $rows->id + 100;?>
				</td>
				<td>
					<a href="<?php echo base_url('deal/handle_profile/').$rows->cust_id ?>">
						<?php echo $rows->fullname; ?>
					</a>
				</td>
				<td>
					<?php if($rows->type_deal == 1){echo 'خرید';}else{echo 'فروش';}?>
				</td>
				<td>
					<?php echo number_format($rows->count_money)." ".$rows->name;?>
				</td>
				<td>
					<?php echo number_format($rows->convert_money); ?>
				</td>
				<td class="<?php if($rows->volume_deal < $rows->volume_pay){echo 'text-danger';}?>">
					<?php echo number_format($rows->volume_deal);?>
				</td>
				<td class="<?php if($rows->volume_deal < $rows->volume_pay){echo 'text-danger';}?>">
					<?php echo number_format($rows->volume_pay);?>
				</td>
				<td class="<?php if($rows->volume_rest < 0){echo 'text-danger';}?>">
					<?php echo number_format($rows->volume_rest);?>
				</td>
				<td>
					<?php echo $rows->date_deal."</br>".$rows->time_deal; ?>
				</td>
				<td>
					<?php echo $rows->date_modified;?>
				</td>
				<td class="text-center">
					<ul class="icons-list">
<?php if($this->session->has_userdata('see_handle') and $this->session->userdata('see_handle') == TRUE ){?><li title="هماهنگی ها" data-toggle="tooltip" class="text-success"><a href="<?php echo base_url('deal/handle/').$rows->id;?>"><i class="icon-notebook"></i></a></li><?php } ?>
<?php if($this->session->has_userdata('edit_deal') and $this->session->userdata('edit_deal') == TRUE){?><li title="ویرایش معامله" data-toggle="tooltip" class="text-primary"><a href="<?php echo base_url('deal/edit/').$rows->id;?>"><i class=" icon-pencil6"></i></a></li><?php } ?>
<?php if($this->session->has_userdata('see_photo') and $this->session->userdata('see_photo') == TRUE){?><li title="مشاهده قبض" data-toggle="tooltip" class="text-indigo-600"><a href="<?php echo base_url('deal/photo/').$rows->id;?>"><i class="icon-stack-picture"></i></a></li><?php } ?>
<?php if($this->session->has_userdata('delete_deal') and $this->session->userdata('delete_deal') == TRUE){?><li title="حذف معامله"  data-toggle="tooltip" class="text-danger" ><a data-toggle="modal" href="#modal_theme_danger"><i  class="icon-trash" onclick = "deleteDeal(<?php echo $rows->id;?> , <?php echo $rows->volume_pay; ?>)" ></i></a></li><?php } ?>
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
			<?php }?>
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
		  titleDelete.innerHTML = "با حذف معامله تمام اطلاعات مربوط به معامله ، هماهنگی ها ،اطلاعات بانکی حذف خواهد شد.</br> آیا می خواهید ادامه دهید؟";
		  closeDelete.style.display = 'inline-block';
		  confirmDelete.style.display = 'inline-block';
		  confirmDelete.setAttribute('href' , "<?php echo base_url('deal/delete_deal/')?>" + id);
	  }
	}
	function search_cust( input ) {
		var search_cust = document.getElementById( 'search_cust' );
		var text = input.value;
		if ( text == '' ) {
			search_cust.style.display = 'none';
			search_cust.nextElementSibling.style.display = 'contents';
			return;
		}
		var xhr = new XMLHttpRequest();
		xhr.onload = function () {
			if ( ( xhr.status >= 200 && xhr.status < 300 ) || xhr.status == 304 ) {

				var result = JSON.parse( xhr.responseText );
				showCustResult( result, input, search_cust );
			} else {
				alert( 'request was unsuccessful : ' + xhr.status );
			}
		}
		xhr.open( 'post', "<?php echo base_url('deal/search/')?>", true );
		xhr.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' );
		xhr.send( 'text_search=' + text );
	}

	function showCustResult( result, input, tbody ) {
		tbody.style.display = 'contents';
		tbody.nextElementSibling.style.display = 'none';
		if ( result.length == 0 ) {
			tbody.innerHTML = "<tr><td colspan = '11' class='text-center p-20'>موردی یافت نشد</td></tr>";
			return;
		} else {
			var div = document.createElement( 'tbody' );
			var len = result.length;
			for ( var i = 0; i < len ; i++ ) {
				var tr = div.appendChild( document.createElement( 'tr' ) );
				var td_row = tr.appendChild( document.createElement( 'td' ) );
				td_row.innerHTML = Number(result[i].id) + Number(100);
				
				var td_fullname = tr.appendChild( document.createElement( 'td' ) );
				var a_customer = td_fullname.appendChild(document.createElement('a'));
				a_customer.setAttribute('href' , "<?php echo base_url('deal/handle_profile/');?>" + result[i].cust_id);
			  a_customer.innerHTML = result[i].fullname;
				
				var td_type = tr.appendChild( document.createElement('td'));
				if(result[i].type_deal == 1){var type = 'خرید';}else{var type = 'فروش';}
				td_type.innerHTML = type;
				
				var td_count = tr.appendChild( document.createElement( 'td' ) );
				td_count.innerHTML = numeral(result[i].count_money).format('0,0') + ' ' + result[i].name;
                
				var td_convert = tr.appendChild(document.createElement('td'));
				td_convert.innerHTML = numeral(result[i].convert_money).format('0,0');
				
				var td_volume = tr.appendChild(document.createElement('td'));
				td_volume.innerHTML = numeral(result[i].volume_deal).format('0,0');
				
				var td_pay = tr.appendChild(document.createElement('td'));
				td_pay.innerHTML = numeral(result[i].volume_pay).format('0,0');
				
				var td_rest = tr.appendChild(document.createElement('td'));
				td_rest.innerHTML = numeral(result[i].volume_rest).format('0,0');

				var td_date = tr.appendChild(document.createElement('td'));
				td_date.innerHTML = result[i].date_deal + '</br>' + result[i].time_deal;

				var td_modify = tr.appendChild(document.createElement('td'));
				if(result[i].date_modified == ''){
					td_modify.innerHTML = 'ثبت نشده است';
				}else{
					td_modify.innerHTML = result[i].date_modified;
				}


				var td_tool = tr.appendChild( document.createElement( 'td' ) );
				td_tool.setAttribute( 'class', 'text-center' );

				var ul_tool = td_tool.appendChild( document.createElement( 'ul' ) );
				ul_tool.setAttribute( 'class', 'icons-list' );

				var li_handle = ul_tool.appendChild( document.createElement( 'li' ) );
				li_handle.setAttribute( 'class', "text-success" );
				var a_handle = li_handle.appendChild( document.createElement( 'a' ) )
				a_handle.setAttribute( 'href', "<?php echo base_url('deal/handle/')?>" + result[ i ].id );
				var i_handle = a_handle.appendChild( document.createElement( 'i' ) );
				i_handle.setAttribute( 'class', 'icon-notebook' );

				var li_detail = ul_tool.appendChild( document.createElement( 'li' ) );
				li_detail.setAttribute( 'class', "text-primary" );
				var a_detail = li_detail.appendChild( document.createElement( 'a' ) )
				a_detail.setAttribute( 'href', "<?php echo base_url('deal/edit/')?>" + result[ i ].id );
				var i_detail = a_detail.appendChild( document.createElement( 'i' ) );
				i_detail.setAttribute( 'class', 'icon-pencil6' );
				
				var li_photo = ul_tool.appendChild( document.createElement( 'li' ) );
				li_photo.setAttribute( 'class', "text-indigo-600" );
				var a_photo = li_photo.appendChild( document.createElement( 'a' ) )
				a_photo.setAttribute( 'href', "<?php echo base_url('deal/photo/')?>" + result[ i ].id );
				var i_photo = a_photo.appendChild( document.createElement( 'i' ) );
				i_photo.setAttribute( 'class', 'icon-stack-picture' );

				var li_delete = ul_tool.appendChild( document.createElement( 'li' ) );
				li_delete.setAttribute( 'class', "text-danger-600" );
				var a_delete = li_delete.appendChild( document.createElement( 'a' ) )
				a_delete.setAttribute( 'href', "<?php echo base_url('deal/delete/')?>" + result[ i ].id );
				var i_delete = a_delete.appendChild( document.createElement( 'i' ) );
				i_delete.setAttribute( 'class', 'icon-trash' );
			}
			tbody.replaceChild( div, tbody.firstChild );
		}
	}
</script>