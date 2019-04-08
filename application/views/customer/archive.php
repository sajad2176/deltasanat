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
					<th width="10%">ردیف</th>
					<th width="15%">نام و نام خانوادگی</th>
					<th width="20%">تعداد معاملات  </th>
					<th width="20%">مجموع معامله</th>
					<th width="20%">مجموع پرداخت نشده</th>
					<th width= "15%" class="text-center">ابزار</th>
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
						<?php if($this->session->has_userdata('edit_customer') and $this->session->userdata('edit_customer') == TRUE){?>
							<li class="text-primary-600"><a href="<?php echo base_url('customer/edit/').$rows->id;?>" title="ویرایش" data-toggle="tooltip"><i class="icon-pencil7"></i></a></li>
						<?php } ?>
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
</div>
<script>
function search_cust( input ) {
	var search_cust = document.getElementById( 'search_cust' );
	var text = input.value;
		if ( text == '' ) {
			search_cust.style.display = 'none';
			search_cust.nextElementSibling.style.display = 'contents';
			return;
		}else{
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
		var result = JSON.parse( xhttp.responseText );
	    showCustResult( result, input, search_cust );
    }
  };
  xhttp.open("POST", "<?php echo base_url('customer/search/')?>" , true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send('text_search='+text);
	}
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

				var td_count = tr.appendChild( document.createElement( 'td' ) );
				td_count.innerHTML = result[i].deal_count;

				var td_sum = tr.appendChild( document.createElement( 'td' ) );
				td_sum.innerHTML = numeral(result[i].vd).format('0,0');

				var td_rest = tr.appendChild( document.createElement( 'td' ) );
				td_rest.innerHTML = numeral(result[i].vr).format('0,0');

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

			}
			tbody.replaceChild( div, tbody.firstChild );
		}
	}
</script>