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
				<form action="">

					<div class="col-md-11">
						<div class="col-md-3">
							<div class="form-group">
								<label>جستجو : </label>
								<input class="form-control" type="search" onkeyup="search_cust(this)" placeholder="عنوان خود را جستجو کنید ...">

							</div>
						</div>
						<div class="col-md-5">
							<div class="col-md-6">
								<div class="form-group">
									<label for="j_created_date">تاریخ ایجاد مطلب</label>
									<input type="text" class="form-control" id="j_created_date" readonly data-mddatetimepicker="true" data-enabletimepicker="true" data-placement="bottom" value="<?php echo $date; ?>" name="j_created_date" placeholder="Jalali Created Date">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="j_created_date">تاریخ ایجاد مطلب</label>
									<input type="text" class="form-control" id="j_created_date" readonly data-mddatetimepicker="true" data-enabletimepicker="true" data-placement="bottom" value="<?php echo $date;?>" name="j_created_date" placeholder="Jalali Created Date">
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>نوع معامله: </label>
								<select class="form-control" name="money_id" required>
									<option value="1">همه</option>
									<option value="2">خرید</option>
									<option value="3">فروش</option>
									
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>ارز معامله: </label>
								<select class="form-control" name="money_id" required>
									<option value="1">دلار</option>
									<option value="2">یورو</option>
									<option value="3">یوان</option>
									<option value="4">درهم</option>
								</select>
							</div>
						</div>
					</div>
				</form>
				<div class=" col-md-1 mt-25 text-left">
					<label>نمایش:</label>
					<ul class="icons-list display-inline-block">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle border p-10 text-black " data-toggle="dropdown">
							<span class="text-muted"><span class="icon-arrow-down5"></span><span><?php if($this->uri->segment(3) == 'show'){echo $this->uri->segment(4);}else{echo '10';} ?></span></span>
						</a>
						


							<ul class="dropdown-menu dorpdown-custom dropdown-menu-right">
								<li><a class="dropdown-item" href="<?php echo base_url('deal/archive/show/10')?>">10</a>
								</li>
								<li><a class="dropdown-item" href="<?php echo base_url('deal/archive/show/25')?>">25</a>
								</li>
								<li><a class="dropdown-item" href="<?php echo base_url('deal/archive/show/50')?>">50</a>
								</li>
								<li><a class="dropdown-item" href="<?php echo base_url('deal/archive/show/100')?>">100</a>
								</li>
								<li><a class="dropdown-item" href="<?php echo base_url('deal/archive/show/all')?>">نمایش همه</a>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<table class="table datatable-selection-single table-hover table-responsive-lg ">
		<thead>
			<tr>
				<th>ردیف</th>
				<th>نام مشتری</th>
				<th>نوع معامله</th>
				<th>تعداد ارز</th>
				<th>نرخ تبدیل</th>
				<th>حجم معامله</th>
				<th>حجم پرداخت شده</th>
				<th>حجم باقی مانده</th>
				<th>تاریخ ثبت</th>
				<th>آخرین ویرایش</th>
				<th class="text-center">ابزارک</th>
			</tr>
		</thead>
		<tbody id="search_cust" tyle="display: none;">
			<tr></tr>
		</tbody>
		<tbody>

			<?php 
							if($this->uri->segment(3) == 'show'){
								$show = $this->uri->segment(5);
								$num = $this->uri->segment(5) + 1;
							}else{
								$show = $this->uri->segment(3);
								$num = $this->uri->segment(3) + 1;
							}
							if(sizeof($deal) == 0){ echo "<tr><td colspan = '11' class='text-center p-20'>موردی یافت نشد</td></tr>"; }else{
							foreach($deal as $rows){ ?>
			<tr class="base_cust">
				<td>
					<?php echo $num;?>
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
				<td>
					<?php echo number_format($rows->volume_deal);?>
				</td>
				<td>
					<?php echo number_format($rows->volume_pay);?>
				</td>
				<td>
					<?php echo number_format($rows->volume_rest);?>
				</td>
				<td>
					<?php echo $rows->date_deal."</br>".$rows->time_deal; ?>
				</td>
				<td>
					<?php if($rows->date_modified == ''){echo '-';}else{echo $rows->date_modified;}?>
				</td>
				<td class="text-center">
					<ul class="icons-list">
						<li title="هماهنگی ها" class="text-success"><a href="<?php echo base_url('deal/handle/').$rows->id;?>"><i class="icon-notebook"></i></a>
						</li>
						<li class="text-primary"><a href="<?php echo base_url('customer/edit/').$rows->id;?>"><i class=" icon-pencil6"></i></a>
						</li>
						<li class="text-danger"><a href="#"><i class="icon-trash"></i></a>
						</li>
					</ul>
				</td>
			</tr>
			<?php
			$num++;
			}
			}
			?>
			<?php if(sizeof($deal) != 0){ ?>
			<tr>
				<td colspan="6" class="pt-20 pb-20">
					نمایش
					<?php echo  $show + 1;?> تا
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
<script>
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
		xhr.open( 'post', "<?php echo base_url('customer/search/')?>", true );
		xhr.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' );
		xhr.send( 'text_search=' + text );
	}

	function showCustResult( result, input, tbody ) {
		tbody.style.display = 'contents';
		tbody.nextElementSibling.style.display = 'none';
		if ( result.length == 0 ) {
			tbody.innerHTML = "<tr><td colspan = '11' class='text-center p-20'>موردی یافت نشد</td></tr>";
		} else {
			var div = document.createElement( 'tbody' );
			for ( var i = 0; i < result.length; i++ ) {
				var tr = div.appendChild( document.createElement( 'tr' ) );
				var td_row = tr.appendChild( document.createElement( 'td' ) );
				td_row.innerHTML = i + 1;
				var td_fullname = tr.appendChild( document.createElement( 'td' ) );
				td_fullname.innerHTML = result[ i ].fullname;
				var td_tel = tr.appendChild( document.createElement( 'td' ) );
				td_tel.innerHTML = '10';
				var td_tel1 = tr.appendChild( document.createElement( 'td' ) );
				td_tel1.innerHTML = '10';

				var td_tool = tr.appendChild( document.createElement( 'td' ) );
				td_tool.setAttribute( 'class', 'text-center' );

				var ul_tool = td_tool.appendChild( document.createElement( 'ul' ) );
				ul_tool.setAttribute( 'class', 'icons-list' );

				var li_edit = ul_tool.appendChild( document.createElement( 'li' ) );
				li_edit.setAttribute( 'class', "text-primary-600" );
				var a_edit = li_edit.appendChild( document.createElement( 'a' ) )
				a_edit.setAttribute( 'href', "<?php echo base_url('customer/edit/')?>" + result[ i ].id );
				var i_edit = a_edit.appendChild( document.createElement( 'i' ) );
				i_edit.setAttribute( 'class', 'icon-pencil7' );

				var li_detail = ul_tool.appendChild( document.createElement( 'li' ) );
				li_detail.setAttribute( 'class', "text-teal-600" );
				var a_detail = li_detail.appendChild( document.createElement( 'a' ) )
				a_detail.setAttribute( 'href', "<?php echo base_url('customer/detail/')?>" + result[ i ].id );
				var i_detail = a_detail.appendChild( document.createElement( 'i' ) );
				i_detail.setAttribute( 'class', 'icon-cog7' );

				var li_delete = ul_tool.appendChild( document.createElement( 'li' ) );
				li_delete.setAttribute( 'class', "text-danger-600" );
				var a_delete = li_delete.appendChild( document.createElement( 'a' ) )
				a_delete.setAttribute( 'href', "<?php echo base_url('customer/detail/')?>" + result[ i ].id );
				var i_delete = a_delete.appendChild( document.createElement( 'i' ) );
				i_delete.setAttribute( 'class', 'icon-trash' );
			}
			tbody.replaceChild( div, tbody.firstChild );
		}
	}
</script>