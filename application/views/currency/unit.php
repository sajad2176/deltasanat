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
				<div class="col-md-6">
					<fieldset>
						<div class="col-md-4">
							<div class="form-group">
								<label>یورو : </label>
								<input type="text" name = 'euro' value = '1' class="form-control" placeholder="0.8" required autofocus>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>یوان : </label>
								<input type="text" name = 'yuan' value = '1' class="form-control" placeholder="7" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>درهم : </label>
								<input type="text" name = 'derham' value = '1' class="form-control" placeholder="3.4" required>
							</div>
						</div>
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
				<th>ریت یورو</th>
				<th>ریت یوان</th>
				<th>ریت درهم</th>
			</tr>
		</thead>
		<tbody>

			<?php 
			if(sizeof($rate) == 0){ ?>
			<tr><td colspan = '5' class='text-center p-20'>موردی یافت نشد</td></tr>
			<?php }else{
			$num = $this->uri->segment(3) + 1;
			foreach($rate as $rows){ ?>
			<tr>
				<td>
					<?php echo $num;?>
				</td>
				<td>
				<?php echo $rows->date_rate."</br>".$rows->time_rate; ?>
				</td>
				<td>
					<?php echo $rows->rate_euro;?>
				</td>
				<td>
					<?php echo $rows->rate_yuan;?>
				</td>
				<td>
					<?php echo $rows->rate_derham; ?>
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