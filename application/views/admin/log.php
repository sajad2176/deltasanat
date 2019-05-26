<div class="breadcrumb-line breadcrumb-line-component mb-20">
	<ul class="breadcrumb">
		<li><a href="<?php echo base_url('home');?>"><i class="icon-home2 position-left"></i> داشبورد</a>
		</li>
		<li><a href="<?php echo base_url('deal/log/').$this->uri->segment(3); ?>">کاربران</a>
		</li>
		<li class="active">فعالیت کاربر</li>
	</ul>

</div>
<div class="panel panel-flat">
	<div class="panel-body">
		<div class="panel-heading">
			<h5 class="panel-title">فعالیت کاربر</h5>

		</div>

		<div class="datatable-header">
			<div class="row">
				<form action="<?php echo base_url("admin/log/").$this->uri->segment(3);?>" method="post">

					<div class="col-md-12">
						<div class="col-md-6">
							<div class="col-md-6">
								<div class="form-group">
									<label for="j_created_date">از تاریخ :</label>
									<input type="text" class="form-control" id="j_created_date" readonly data-mddatetimepicker="true" data-enabletimepicker="true" data-placement="bottom" value="<?php echo $date; ?>" name="start_date" placeholder="Jalali Created Date">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="j_created_date">تا تاریخ : </label>
									<input type="text" class="form-control" id="j_created_date" readonly data-mddatetimepicker="true" data-enabletimepicker="true" data-placement="bottom" value="<?php echo $date;?>" name="end_date" placeholder="Jalali Created Date">
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label> انتخاب دسته : </label>
								<select class="form-control" id="select_cat">
									<option value="0">همه</option>
									<option value="1">کاربران</option>
									<option value="2">مشتریان</option>
									<option value="3">معاملات</option>
									<option value="4">تنظیمات</option>
									
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label> انتخاب فعالیت : </label>
								<select class="form-control" name="select_act" required id="select_act">
									<option value="all">همه</option>
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
				<th width="4%">ردیف</th>
				<th width="7%" class="text-center">تاریخ</th>
				<th width="6%" class="text-center">زمان</th>
				<th width="15%">نوع فعالیت</th>
				<th width="68%">توضحیات</th>
			</tr>
		</thead>
		<tbody>
                 
	<?php if(empty($logs)){ ?>  <tr><td colspan = '5' class='text-center p-20'>موردی یافت نشد</td></tr> <?php }
			 
			 else{
		$num = $this->uri->segment(4) + 1;
		foreach($logs as $rows){ ?>
			<tr>
				<td>
					<?php echo $num;?>
				</td>
				<td>
				<?php echo $rows->date_log; ?>

				</td>
				<td>
					<?php echo $rows->time_log; ?>
				</td>
				<td>
					<?php echo $rows->name;?>
				</td>
				<td>
					<?php echo $rows->explain;;?>
				</td>
			</tr>
			<?php
			$num++;
			}
			?>
			<tr>
				<td colspan="2" class="pt-20 pb-20">
					نمایش
					<?php echo  $this->uri->segment(4) + 1;?> تا
					<?php echo $num - 1; ?> از
					<?php echo $count;?>
				</td>
				<td colspan="3" class="text-left pt-20 pb-20">
					<?php if(isset($page)){echo $page; } ?>
				</td>
			</tr>
			<?php }?>
		</tbody>

	</table>


</div>
</div>
</div>
<script>
var select_cat = document.getElementById('select_cat');
var select_act = document.getElementById('select_act');
select_cat.onchange = function(){
	var value = select_cat.selectedIndex;
	if(value == 0){
		select_act.innerHTML = '<option value="all">همه</option>';
	}
	else if(value == 1){
	    select_act.innerHTML = '<option value="1">ورود به سامانه</option><option value="2">خروج از سامانه</option><option value="3">افزودن کاربر</option><option value="4">ویرایش کاربر</option><option value="5">تغییر وضعیت کاربر</option>';
	}else if(value == 2){
		select_act.innerHTML = '<option value="6">افزودن مشتری</option><option value="7">ویرایش مشتری</option>';
	}else if(value == 3){
		select_act.innerHTML = '<option value="9">افزودن خرید</option><option value="10">افزودن فروش</option><option value="11">ویرایش معامله</option><option value="24">ارسال قبض</option><option value="25">حذف قبض</option><option value="20">حذف معامله </option><option value="12">افزودن هماهنگی</option><option value="13"> پرداخت کامل</option><option value="14">پرداخت جزیی</option><option value="15">بازگشت پرداخت</option><option value="21"> ویرایش هماهنگی </option><option value="16"> حذف هماهنگی </option><option value="17">افزودن اطلاعات حساب </option><option value="18">ویرایش اطلاعات حساب</option><option value="19"> تغییر وضعیت اطلاعات حساب</option>';
	}else if(value == 4){
		select_act.innerHTML = '<option value="22"> تبدیل ارز</option><option value="23"> ارز اولیه</option>';
	}
}

</script>