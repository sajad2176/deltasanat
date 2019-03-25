<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="breadcrumb-line breadcrumb-line-component mb-20">
	<ul class="breadcrumb">
		<li><a href="<?php echo base_url('home')?>"><i class="icon-home2 position-left"></i> داشبورد</a>
		</li>
		<li><a href="<?php echo base_url('admin/archive/') ?>">کاربران</a>
		</li>
		<li class="active">آرشیو کاربران</li>
	</ul>
</div>
<div class="panel panel-flat">
	<div class="panel-body">
		<legend class="text-semibold"><i class="icon-users4 position-left"></i> آرشیو کاربران</legend>
		<table class="table datatable-basic">
			<thead>
				<tr>
					<th width="5%">ردیف</th>
					<th width="21%">نام و نام خانوادگی</th>
					<th width="21%">نام کاربری </th>
					<th width="22%">زمان آخرین ورود</th>
					<th width="11%">وضعیت</th>
					<th width="15%" class="text-center">ابزار</th>
				</tr>
			</thead>
			<tbody>
			<?php if(sizeof($user) == 0){?>
			<tr><td colspan="6" class="text-center p-20">موردی یافت نشد</td></tr>
			<?php }else{
				$row = $this->uri->segment(3) + 1;
				foreach($user as $rows){?>	
				
				<tr>
					<td><?php echo $row;?></td>
					<td><?php echo $rows->firstname."  ".$rows->lastname;?></td>
					<td><?php echo $rows->username;?></td>
					<td><?php if($rows->date_login == '0000-00-00'){echo 'ثبت نشده است';}else{echo $rows->date_login."  ".$rows->time_login;}?></td>
					<?php if($rows->active == 1){$class='success';$txt = 'فعال';$url = '0';}else{$class='danger';$txt = 'غیر فعال';$url = '1';} ?>
	<td><a href="<?php echo base_url('admin/active/').$rows->id."/".$url; ?>"><span class="label label-<?php echo $class;?>"><?php echo $txt;?></span></a></td>
					<td class="text-center">
					   <ul class="icons-list">
		<li title="ویرایش کاربر <?php echo $rows->username;?>" class="text-success" data-toggle="tooltip"><a href="<?php echo base_url('admin/edit/').$rows->id;?>"><i class=" icon-pencil3"></i></li>
		<li title="تاریخچه کاربر <?php echo $rows->username;?>" class="text-primary" data-toggle="tooltip"><a href="<?php echo base_url('admin/log/').$rows->id;?>"><i class=" icon-eye4"></i></li>
					  </ul>
					</td>
				</tr>
				<tr>
			<?php $row++; }?>
			<tr>
					<td colspan="3" class="pt-20 pb-20">
						نمایش
						<?php echo  $this->uri->segment(3) +1 ;?> تا
						<?php echo  $row - 1; ?> از
						<?php echo $count;?>
					</td>
					<td colspan="3" class="text-left pt-20 pb-20">
						<?php echo $page; ?>
					</td>
				</tr>
				<?php }?>
		</table>


	</div>

</div>
<!-- danger modal -->
<div id="modal_theme_danger" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-danger">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">حذف هماهنگی</h4>
			</div>

			<div class="modal-body">

				<h5 class="text-center">آیا میخواهید کاربر حذف شود ؟</h5>


			</div>

			<div class="modal-footer text-center">
				<button type="button" class="btn btn-danger" data-dismiss="modal">خیر</button>
				<button type="button" class="btn btn-success">بله </button>
			</div>
		</div>
	</div>
</div>
<!-- /danger modal -->
</div>