<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if($this->session->has_userdata('msg')){
$msg = $this->session->userdata('msg');?>
<div class="alert bg-<?php echo $msg[1];?> alert-styled-left">
										<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
										<?php echo $msg[0];?>
								    </div>
<?php } ?>

<div class="breadcrumb-line breadcrumb-line-component mb-20">
	<ul class="breadcrumb">
		<li><a href="<?php echo base_url('home');?>"><i class="icon-home2 position-left"></i> داشبورد</a>
		</li>
		<li><a href="<?php echo base_url('deal/archive') ?>">معاملات</a>
		</li>
		<li class="active">آرشیو معاملات</li>
	</ul>

</div>
<?php 
//perm
if($this->session->has_userdata('pay_little')){$littlePerm = 1;}else{$littlePerm = 0;}
if($this->session->has_userdata('edit_deal')){$editPerm = 1;}else{$editPerm = 0;}
if($this->session->has_userdata('see_photo')){$photoPerm = 1;}else{$photoPerm = 0;}
if($this->session->has_userdata('delete_deal')){$deletePerm = 1;}else{$deletePerm = 0;} 
//perm
?>
<div class="panel panel-flat">
	<div class="panel-body">
		<div class="panel-heading">
			<h5 class="panel-title">آرشیو معاملات</h5>

		</div>

		<div class="datatable-header">
			<div class="row">
				<form action="<?php echo base_url('deal/archive'); ?>" method="get">

					<div class="col-md-12">
						<div class="col-md-3">
							<div class="form-group">
								<label>جستجو : </label>
								<input class="form-control" name="fullname" value="<?php echo $this->input->get('fullname');?>" type="search" onkeyup="search_cust(<?php echo $deletePerm; ?> , <?php echo $photoPerm;?> , <?php echo $editPerm; ?> , this)" placeholder="نام مشتری خود را جستجو کنید">

							</div>
						</div>
						<div class="col-md-4">
							<div class="col-md-6">
								<div class="form-group">
									<label for="j_created_date">از تاریخ :</label>
									<input type="text" class="form-control" name="start_date" id="j_created_date" readonly data-mddatetimepicker="true" data-placement="bottom" value="<?php  if($this->input->get('start_date')){echo $this->input->get('start_date');}else{echo $date;} ?>">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="j_created_date">تا تاریخ :</label>
									<input type="text" class="form-control" name="end_date" id="j_created_date" readonly data-mddatetimepicker="true" data-placement="bottom" value="<?php if($this->input->get('end_date')){echo $this->input->get('end_date');}else{echo $date;} ?>">
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>نوع معامله : </label>
								<select class="form-control" name="type" required>
									<option value="0" <?php if($this->input->get('type') == 0){echo 'selected';}?> >همه</option>
									<option value="1" <?php if($this->input->get('type') == 1){echo 'selected';}?> >خرید</option>
									<option value="2" <?php if($this->input->get('type') == 2){echo 'selected';}?> >فروش</option>
									
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>ارز معامله : </label>
								<select class="form-control" name="money_id" required>
								    <option value="0" <?php if($this->input->get('money_id') == 0){echo 'selected';}?>>همه</option>
										<?php foreach($unit as $units){ ?>
											<option  value="<?php echo $units->id;?>" <?php if($units->id == $this->input->get('money_id')){echo 'selected';}?> ><?php echo $units->name;?></option>
										<?php } ?>	
								</select>
							</div>
						</div>
						<div class="col-md-1">
							<button class="btn btn-success mt-25" type="submit" >اعمال فیلتر</button>
						</div>
						
					</div>
				</form>
			</div>
		</div>
	

	<table class="table datatable-selection-single table-responsive-lg ">
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
		<tbody id="search" tyle="display: none;">
			<tr></tr>
		</tbody>
		<tbody id="base">
			<?php 
			if(empty($deal)){ ?>
			<tr><td colspan = '10' class='text-center p-20'>موردی یافت نشد</td></tr>
			<?php }else{
			foreach($deal as $key => $rows){ $check = abs($rows->volume - $rows->pay); ?>
			<tr class="<?php if($rows->state == 0){echo 'state_bg';}?>" >
				<td>
					<?php echo $rows->id;?>
				</td>
				<td>
					<a href="<?php echo base_url('deal/profile/').$rows->customer_id ?>" target="_blank" class="enterCustomer">
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
				<td class="lright <?php if($rows->volume < $rows->pay){echo 'text-danger';}?>">
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
					<li class="dropdown" title="تنظیمات" data-toggle="tooltip">
													<a class="dropdown-toggle" data-toggle="dropdown">
														<i class="icon-cog7"></i>
													</a>

													<ul class="dropdown-menu dropdown-menu-right">
														<li onclick="settings(this)"><a>نمایش در داشبورد</a></li>
														<li onclick="settings(this)"><a>عدم نمایش در داشبورد</a></li>
													</ul>
												</li>
<?php if($littlePerm && $rows->pay != 0 && $rows->rest != 0 && $check != 0 && $check <= 50000){ ?><li title="پرداخت خرد" data-toggle="tooltip" class="text-blue-800"><a onclick="payLittle(<?php echo $rows->id; ?> , <?php echo $check; ?>)"><i class="icon-stack-up"></i></a></li><?php } ?>												
<?php if($editPerm && $rows->state == 1){ ?><li title="ویرایش معامله" data-toggle="tooltip" class="text-primary"><a href="<?php echo base_url('deal/edit/').$rows->id;?>"><i class=" icon-pencil6"></i></a></li><?php } ?>												
<?php if($photoPerm){?><li title="مشاهده قبض" data-toggle="tooltip" class="text-indigo-600"><a href="<?php echo base_url('deal/photo/').$rows->id;?>"><i class="icon-stack-picture"></i></a></li><?php } ?>
<?php if($deletePerm && $rows->state == 1){ ?><li title="حذف معامله"  data-toggle="tooltip" class="text-danger" ><a data-toggle="modal" href="#modal_theme_danger"><i  class="icon-trash" onclick = "deleteDeal(<?php echo $rows->id;?> , <?php echo $rows->pay; ?>)" ></i></a></li><?php } ?>
					</ul>
				</td>
			</tr>
			<?php } ?>
			<tr>
				<td colspan="6" class="pt-20 pb-20">
					نمایش
					<?php echo  $this->input->get('per_page') + 1;?> تا
					<?php echo $this->input->get('per_page') + $key + 1; ?> از
					<?php echo $count;?>
				</td>
				<td colspan="5" class="text-left pt-20 pb-20">
					<?php if(isset($page)){echo $page;} ?>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>


</div>
</div>

<!-- delete modal -->
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
<!-- delete modal -->

<!-- pay little modal -->
<div id="modal_check" class="modal_logout">
  <div class="modal_content animate_logout">
    <div class="dang_modal">
      <span class="dang_body"></span>
      <span class="dang_dot"></span>
    </div>
    <div class="container_logout">
      <h1>پرداخت خرد</h1>
      <h6>مبلغی به اندازه <span id="check_span"></span> ریال از این معامله باقی مانده است .</h6>
	  <h6> آیا مایل به صفر شدن این مبلغ می باشید؟ </h6>
    </div>
	<a  class="btn btn-secendery" onclick="document.getElementById('modal_check').style.display = 'none'">انصراف</a>
    <a  class="btn btn-danger" href="" id="confirmLittle">بله</a>
  </div>
</div>	
<!-- pay little modal -->

</div>

<script>

   // delete deal script
	var titleDelete = document.getElementById('titleDelete');
	var closeDelete = document.getElementById('closeDelete');
	var confirmDelete = document.getElementById('confirmDelete');
	function deleteDeal(id , pay){
      if(pay != 0){
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
	// delete deal script

	//pay little
	var modalLittle =  document.getElementById('modal_check');
	function payLittle(deal_id , amount){
	var check_span =  document.getElementById('check_span');
	var confirmLittle =  document.getElementById('confirmLittle');
	check_span.innerHTML = numeral(amount).format('0,0');	
	modalLittle.style.display = 'block';
	confirmLittle.setAttribute('href' , "<?php echo base_url('deal/pay_little/')?>" + deal_id );
	}
	//pay little

    //search
	var search = document.getElementById( 'search' );
	var base = document.getElementById( 'base' );
	function search_cust( deletePerm , photoPerm , editPerm  , input ) {
		var text = input.value;
		text = text.trim();
		if ( text == '' ) {
			search.style.display = 'none';
			base.style.display = 'contents';
			return;
		}
		var littlePerm = <?php echo $littlePerm;?>;
		var xhr = new XMLHttpRequest();
		xhr.onload = function () {
			if ( ( xhr.status >= 200 && xhr.status < 300 ) || xhr.status == 304 ) {
        var url = "<?php echo base_url();?>";
				var result = JSON.parse( xhr.responseText );
				showCustResult( result, url , deletePerm , photoPerm , editPerm , littlePerm );
			} else {
				alert( 'request was unsuccessful : ' + xhr.status );
			}
		}
		xhr.open( 'post', "<?php echo base_url('deal/search/')?>", true );
		xhr.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' );
		xhr.send( 'text_search=' + text );
	}
	//search
	
</script>
<script type="text/javascript" src="<?php echo base_url('files/');?>assets/mine/deal_archive.js"></script>