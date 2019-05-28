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
		<li><a href="<?php echo base_url('home')?>"><i class="icon-home2 position-left"></i> داشبورد</a>
		</li>
		<li><a href="<?php echo base_url('settings/primitive_unit')?>">تنظیمات </a>
		</li>
		<li class="active">ارز اولیه </li>
	</ul>
</div>
<!-- form arz -->
<div class="panel panel-flat">
	<div class="panel-body">
		<form action="<?php echo base_url('settings/primitive_unit');?>" method="post">
			<div class="row">
				<div class="col-md-12">
					<legend class="text-semibold"><i class="icon-coin-dollar position-left"></i> ارز اولیه:</legend>
				</div>
				<div class="col-md-12">
					<fieldset>
					
					<?php
					foreach($unit as  $row){ ?>
						<div class="col-md-2">
							<div class="form-group">
								<label><?php echo $row->name;?> : </label>
								<input type="text" value="<?php echo number_format($row->amount); ?>" onkeyup = "amount(this)" class="form-control" required>
								<input type="hidden" name="amount[]" value="<?php echo $row->amount;?>">
								<input type="hidden" name="id[]" value='<?php echo $row->id;?>'>
								<input type="hidden" name="name[]" value="<?php echo $row->name; ?>" >
								<input type="hidden" name="base[]" value="<?php echo $row->amount;?>">
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
					<th width="10%">ردیف</th>
					<th width="15%">تاریخ</th>
					<th width="15%">زمان</th>
					<th width="15%">توسط</th>
					<th width="45%">توضیحات</th>
				</tr>
			</thead>
			<tbody>
			<?php if(empty($change)){?>
              <tr><td colspan="5" class="text-center p-20"  >موردی یافت نشد</td></tr>
			<?php }else{
				$key = $this->uri->segment(3);
				foreach($change as $rows){?>
				<tr>
					<td><?php echo $key + 1;?></td>
					<td><?php echo $rows->date_log;?></td>
					<td><?php echo $rows->time_log;?></td>
					<td><?php echo $rows->name;?></td>
					<td><?php echo $rows->explain;?></td>
				</tr>
			<?php } ?>
			<tr><td><?php echo $page;?></td></tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
</div>
<!--/table arz-->
<script>
function amount(input){
    input.value = numeral( input.value ).format( '0,0' );
    input.nextElementSibling.value = numeral( input.value ).value();
}
</script>