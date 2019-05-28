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
		<li><a href="<?php echo base_url('settings/set_unit')?>">تبدیل ارز</a>
		</li>
		<li class="active">تبدیل ارز</li>
	</ul>
</div>
<!-- form arz -->
<div class="panel panel-flat">
	<div class="panel-body">
		<form action="<?php echo base_url('settings/set_unit')?>" method="post">
			<div class="row">
				<div class="col-md-12">
					<legend class="text-semibold"><i class="icon-coin-dollar position-left"></i> نرخ تبدیل دلار به :</legend>
				</div>
				<div class="col-md-12">
					<fieldset>
					<?php foreach($unit as $row){  ?>
						<div class="col-md-2 <?php if($row->id == 1){echo 'd-none';} ?>">
							<div class="form-group">
								<label><?php echo $row->name;?> : </label>
								<input type="text" name = 'rate[]' value = '1' class="form-control" placeholder="0.8" required autofocus>
								<input type="hidden" name="unit_id[]" value="<?php echo $row->id;?>">
								<input type="hidden" name="name[]" value="<?php echo $row->name;?>">
							</div>
						</div>
					<?php } ?>
					</fieldset>
				</div>
			</div>
			<div class="text-right">
				<button type="submit" name="sub" class="btn btn-primary">ثبت نرخ ارز <i class="icon-arrow-left13 position-right"></i></button>
			</div>
		</form>
	</div>
</div>
<!-- /form arz -->
<!--table arz-->
<div class="panel panel-flat">
	<div class="panel-body">
		<legend class="text-semibold"><i class=" icon-stats-dots position-left"></i> آرشیو تغیرات</legend>
		<table class="table datatable-selection-single table-hover table-responsive-lg ">
		<thead>
			<tr>
				<th>ردیف</th>
				<th>تاریخ ثبت</th>
				<th> زمان ثبت</th>
				<th width="50%"> توضیحات</th>
			</tr>
		</thead>
		<tbody>

			<?php 
			if(sizeof($rate) == 0){ ?>
			<tr><td colspan = '4' class='text-center p-20'>موردی یافت نشد</td></tr>
			<?php }else{
			$num = $this->uri->segment(3) + 1;
			foreach($rate as $rows){ ?>
			<tr>
				<td>
					<?php echo $num;?>
				</td>
				<td>
				<?php echo $rows->date_log ?>
				</td>
				<td>
					<?php echo $rows->time_log;?>
				</td>
				<td>
					<?php echo $rows->explain;?>
				</td>
			</tr>
			<?php
			$num++;
			}
			?>
			<tr>
				<td colspan="2" class="pt-20 pb-20">
					نمایش
					<?php echo  $this->uri->segment(3) + 1;?> تا
					<?php echo $num - 1; ?> از
					<?php echo $count;?>
				</td>
				<td colspan="3" class="text-left pt-20 pb-20">
					<?php echo $page; ?>
				</td>
			</tr>
			<?php }?>
		</tbody>
		</table>
	</div>
</div>
<!--/table arz-->