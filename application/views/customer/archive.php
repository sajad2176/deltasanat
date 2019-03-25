<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="breadcrumb-line breadcrumb-line-component mb-20">
	<ul class="breadcrumb">
		<li><a href="<?php echo base_url('home');?>"><i class="icon-home2 position-left"></i> داشبورد</a>
		</li>
		<li><a href="<?php echo base_url('customer/archive')?>">مشتریان</a>
		</li>
		<li class="active">آرشیو مشتریان</li>
	</ul>

</div>
<div class="panel panel-flat">
	<div class="panel-body">
		<div class="panel-heading">
			<h5 class="panel-title">آرشیو مشتریان</h5>

		</div>

		<div class="datatable-header">
			<div class="row">
				<div class="col-md-7 text-right">
					<div class="dataTables_filter"><label><span>جستجو : </span> <input type="search" onkeyup="search_cust(this)" placeholder="نام مشتری خود را جتسجو کنید"></label>
					</div>
				</div>
			</div>
		</div>
		<table class="table datatable-selection-single table-hover ">
			<thead>
				<tr>
					<th>ردیف</th>
					<th>نام و نام خانوادگی</th>
					<th>تعداد معاملات  </th>
					<th>مجموع معامله</th>
					<th>مجموع پرداخت نشده</th>
					<th class="text-center">ابزار</th>
				</tr>
			</thead>
			<tbody id="search_cust" style="display: none;">
				<tr></tr>
			</tbody>
			<tbody>
				<?php 
						if(sizeof($customer) == 0){ ?>
							<tr><td colspan="6" class="text-center p-20">موردی یافت نشد</td></tr>
						<?php }else{
							$num = $this->uri->segment(3) + 1;
							foreach($customer as $rows){ ?>
				<tr class="base_cust">
					<td>
						<?php echo $num;?>
					</td>
					<td>
						<a href="<?php echo base_url('deal/handle_profile/').$rows->id;?>"><?php echo $rows->fullname; ?></a>
					</td>
					<td><?php echo $rows->deal_count;?></td>
					<td><?php echo number_format($rows->vd); ?></td>
					<td><?php echo number_format($rows->vr); ?></td>
					<td class="text-center">
						<ul class="icons-list">
							<li class="text-primary-600"><a href="<?php echo base_url('customer/edit/').$rows->id;?>" title="ویرایش" data-toggle="tooltip"><i class="icon-pencil7"></i></a>
							</li>
							<li class="text-danger"><a data-toggle="modal" title="حذف مشتری" href="#modal_theme_danger"><i class="icon-cross2" onclick="delete_cust(<?php echo $rows->id;?>)" ></i></a>
							</li>
						</ul>
					</td>
				</tr>
				<?php
				$num++;
				} 
				?>
				<tr>
					<td colspan="3" class="pt-20 pb-20">
						نمایش
						<?php echo  $this->uri->segment(3) + 1;?> تا
						<?php echo $num - 1; ?> از
						<?php echo $count;?>
					</td>
					<td colspan="3" class="text-left pt-20 pb-20">
						<?php echo $page; ?>
					</td>
				</tr>
				<?php } ?>
			</tbody>

		</table>

	</div>
	<div id="modal_theme_danger" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header bg-danger">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">حذف مشتری</h4>
						</div>

						<div class="modal-body">

		                  <h4 class="text-center"> تمام اطلاعات مربوط به مشتری از جمله معاملات و هماهنگی ها و ... حذف می شوند</h4>
						  </br>
						  <h5 class="text-center">آیا می خواهید ادامه دهید؟</h5>


						</div>

						<div class="modal-footer text-center">
							<button type="button" class="btn btn-danger" data-dismiss="modal">خیر</button>
							<a id="delete_customer" class="btn btn-success">بله </a>
						</div>
					</div>
				</div>
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
		if ( result.length == 0) {
			tbody.innerHTML = '<tr><td colspan="6" class="text-center p-20">موردی یافت نشد</td></tr>';
			return;
		} else {
			var div = document.createElement( 'tbody' );
			for ( var i = 0; i < result.length; i++ ) {
				var tr = div.appendChild( document.createElement( 'tr' ) );
				var td_row = tr.appendChild( document.createElement( 'td' ) );
				td_row.innerHTML = i + 1;
				var td_fullname = tr.appendChild( document.createElement( 'td' ) );
				var a_customer = td_fullname.appendChild(document.createElement('a'));
				a_customer.setAttribute('href' , "<?php echo base_url('deal/handle_profile/')?>"+result[i].id);
				a_customer.innerHTML = result[ i ].fullname;
				var td_tel = tr.appendChild( document.createElement( 'td' ) );
				td_tel.innerHTML = result[i].deal_count;
				var td_tel1 = tr.appendChild( document.createElement( 'td' ) );
				td_tel1.innerHTML = numeral(result[i].vd).format('0,0');
				var td_tel2 = tr.appendChild( document.createElement( 'td' ) );
				td_tel2.innerHTML = numeral(result[i].vr).format('0,0');

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

				var li_delete = ul_tool.appendChild( document.createElement( 'li' ) );
				li_delete.setAttribute( 'class', "text-danger" );
				var a_delete = li_delete.appendChild( document.createElement( 'a' ) );
				a_delete.setAttribute( 'data-toggle', "modal" );
				a_delete.setAttribute( 'href', "#modal_theme_danger" );
				var i_delete = a_delete.appendChild( document.createElement( 'i' ) );
				i_delete.setAttribute( 'class', 'icon-cross2' );
				i_delete.setAttribute( 'onclick', delete_cust(result[i].id) );
			}
			tbody.replaceChild( div, tbody.firstChild );
		}
	}
	function delete_cust(id){
		var del = document.getElementById( 'delete_customer' );
		del.setAttribute( 'href', "<?php echo base_url('customer/delete/')?>" + id );
	}
</script>