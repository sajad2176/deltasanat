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
		<li><a href="<?php echo base_url('settings/add_unit')?>"> تنظیمات</a>
		</li>
		<li class="active">افزودن ارز</li>
	</ul>
</div>
<!-- form arz -->
<div class="panel panel-flat">
	<div class="panel-body">
		<form action="<?php echo base_url('settings/add_unit')?>" method="post" id="form">
			<div class="row">
				<div class="col-md-12">
					<legend class="text-semibold"><i class="icon-coin-dollar position-left"></i><span id="title">افزودن ارز</span></legend>
				</div>
				<div class="col-md-12">
					<fieldset>
						<div class="col-md-4">
							<div class="form-group">
								<label>نام ارز : </label>
								<input type="text" name = 'unit' class="form-control" id="unit_name" placeholder="نام ارز را وارد کنید" required autofocus>
							</div>
						</div>
					</fieldset>
				</div>
			</div>
			<div class="text-right">
				<button type="submit" name="sub" id="confirm" class="btn btn-primary">ثبت ارز <i class="icon-arrow-left13 position-right"></i></button>
			</div>
		</form>
	</div>
</div>
<!-- /form arz -->
<!--table arz-->
<div class="panel panel-flat">
	<div class="panel-body">
		<legend class="text-semibold"><i class=" icon-stats-dots position-left"></i> آرشیو ارز ها</legend>
		<table class="table datatable-selection-single table-hover table-responsive-lg ">
		<thead>
			<tr>
				<th>ردیف</th>
				<th>نام ارز</th>
				<th>ابزار</th>
			</tr>
		</thead>
		<tbody>

			<?php 
			if(sizeof($unit) == 0){ ?>
			<tr><td colspan = '3' class='text-center p-20'>موردی یافت نشد</td></tr>
			<?php }else{
			foreach($unit as $rows){ ?>
			<tr>
				<td>
					<?php echo $rows->id;?>
				</td>
				<td>
				<?php echo $rows->name ?>
				</td>
				<td>
					<ul class="icons-list">
<li title="ویرایش ارز" data-toggle="tooltip" class="text-primary" style="cursor:pointer;"><i onclick="unit(<?php echo $rows->id;?>)" class=" icon-pencil6"></i></li>
					</ul>
				</td>
			</tr>
			<?php } } ?>
		</tbody>
		</table>
	</div>
</div>
<script>
			var confirm = document.getElementById('confirm');
			var title = document.getElementById('title');
			var editform = document.getElementById('form');
			var uname = document.getElementById('unit_name');
	function unit(id) {
		var xhr = new XMLHttpRequest();
		xhr.onload = function () {
			if ( ( xhr.status >= 200 && xhr.status < 300 ) || xhr.status == 304 ) {
		var result = JSON.parse( xhr.responseText );
		confirm.innerHTML = 'ویرایش ارز <i class="icon-arrow-left13 position-right"></i>';
		title.innerHTML = ' ویرایش ارز  ' + result.name;
		uname.value = result.name;
		editform.action = "<?php echo base_url('settings/edit_unit/');?>" + id;
			} else {
				alert( 'request was unsuccessful : ' + xhr.status );
			}
		}
		xhr.open( 'post', "<?php echo base_url('settings/get_unit/')?>", true );
		xhr.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' );
		xhr.send( 'id='+id );
	}
</script>
<!--/table arz-->