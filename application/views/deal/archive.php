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
									<input type="text" class="form-control" name="start_date" id="j_created_date" readonly data-mddatetimepicker="true" data-enabletimepicker="true" data-placement="bottom" value="<?php echo $date; ?>" placeholder="Jalali Created Date">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="j_created_date">تا تاریخ :</label>
									<input type="text" class="form-control" name="end_date" id="j_created_date" readonly data-mddatetimepicker="true" data-enabletimepicker="true" data-placement="bottom" value="<?php echo $date;?>" placeholder="Jalali Created Date">
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>نوع معامله : </label>
								<select class="form-control" name="type" required>
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
										<?php foreach($unit as $units){ ?>
											<option  value="<?php echo $units->id;?>" <?php if($units->id == $m){echo 'selected';}?> ><?php echo $units->name;?></option>
										<?php } ?>	
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
	

	<table class="table datatable-selection-single table-responsive-lg ">
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
		<tbody id="search" tyle="display: none;">
			<tr></tr>
		</tbody>
		<tbody id="base">

			<?php 
			if(empty($deal)){ ?>
			<tr><td colspan = '11' class='text-center p-20'>موردی یافت نشد</td></tr>
			<?php }else{
			$num = $this->uri->segment(3) + 1;
			foreach($deal as $rows){ ?>
			<tr class="<?php if($rows->state == 0){echo 'state_bg';}else{echo '';} ?>" >
				<td>
					<?php echo $rows->id + 100;?>
				</td>
				<td>
					<a href="<?php echo base_url('deal/handle_profile/').$rows->cust_id ?>" target="_blank">
						<?php echo $rows->fullname; ?>
					</a>
				</td>
				<td>
					<?php if($rows->type == 1){echo 'خرید';}else{echo 'فروش';}?>
				</td>
				<td>
					<?php echo number_format($rows->count_money)." ".$rows->name;?>
				</td>
				<td>
					<?php echo number_format($rows->convert); ?>
				</td>
				<td class="<?php if($rows->volume < $rows->pay){echo 'text-danger';}?>">
					<?php echo number_format($rows->volume);?>
				</td>
				<td class="<?php if($rows->volume < $rows->pay){echo 'text-danger';}?>">
					<?php echo number_format($rows->pay);?>
				</td>
				<td class="<?php if($rows->rest < 0){echo 'text-danger';}?>">
					<?php echo number_format($rows->rest);?>
				</td>
				<td>
					<?php echo $rows->date_deal."</br>".$rows->time_deal; ?>
				</td>
				<td>
					<?php echo $rows->date_modified;?>
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
					<?php if(isset($page)){echo $page;} ?>
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
		  titleDelete.innerHTML = " آیا می خواهید ادامه دهید؟";
		  closeDelete.style.display = 'inline-block';
		  confirmDelete.style.display = 'inline-block';
		  confirmDelete.setAttribute('href' , "<?php echo base_url('deal/delete_deal/')?>" + id);
	  }
	}
	//search
	var search = document.getElementById( 'search' );
	var base = document.getElementById( 'base' );
	function search_cust( input ) {
		var text = input.value;
		text = text.trim();
		if ( text == '' ) {
			search.style.display = 'none';
			base.style.display = 'contents';
			return;
		}
		var xhr = new XMLHttpRequest();
		xhr.onload = function () {
			if ( ( xhr.status >= 200 && xhr.status < 300 ) || xhr.status == 304 ) {
        var url = "<?php echo base_url();?>";
				var result = JSON.parse( xhr.responseText );
				showCustResult( result, url );
			} else {
				alert( 'request was unsuccessful : ' + xhr.status );
			}
		}
		xhr.open( 'post', "<?php echo base_url('deal/search/')?>", true );
		xhr.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' );
		xhr.send( 'text_search=' + text );
	}
</script>
<script type="text/javascript" src="<?php echo base_url('files/');?>assets/mine/deal_archive.js"></script>