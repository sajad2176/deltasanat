<div class="breadcrumb-line breadcrumb-line-component mb-20">
	<ul class="breadcrumb">
		<li><a href="index.html"><i class="icon-home2 position-left"></i> Home</a>
		</li>
		<li><a href="alpaca_basic.html">Alpaca</a>
		</li>
		<li class="active">Basic examples</li>
	</ul>
</div>

<!-- sheet -->
<div class="panel panel-flat">
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<fieldset>
					<legend class="text-semibold"><i class="icon-cart5 position-left"></i> خرید</legend>

					<table class="table datatable-selection-single table-hover table-responsive-lg table-striped  ">
						<thead>
							<tr>
								<th>ردیف</th>
								<th>نام مشتری</th>
								<th>نوع معامله</th>
								<th>تعداد ارز</th>
								
							</tr>
						</thead>
						<tbody>
							<tr></tr>
						</tbody>
						<tbody>
							<tr >
								<td>علی شیرازی</td>
								<td>علی شیرازی</td>
								<td>علی شیرازی</td>
                                <td>علی شیرازی</td>                            
							</tr>
						</tbody>
					</table>

				</fieldset>
			</div>
			<div class="col-md-6">
				<fieldset>
					<legend class="text-semibold"><i class="icon-coins position-left"></i> فروش</legend>
					<table class="table datatable-selection-single table-hover table-responsive-lg ">
						<thead>
							<tr>
								<th>ردیف</th>
								<th>نام مشتری</th>
								<th>نوع معامله</th>
								<th>تعداد ارز</th>
							</tr>
						</thead>
						<tbody>
							<tr></tr>
						</tbody>
						<tbody>
							<tr>
								<td>علی شیرازی</td>
								<td>علی شیرازی</td>
								<td>علی شیرازی</td>
								<td>علی شیرازی</td>
							</tr>
						</tbody>
					</table>
				</fieldset>
			</div>
		</div>
	</div>
</div>
<!-- /sheet -->
<!-- handle -->
<div class="panel panel-flat">
		<div class="panel-body">
		<form action="<?php echo base_url('deal/handle_profile/').$this->uri->segment(3);?>" method="post">
			<legend class="text-semibold"><i class="icon-address-book position-left"></i> افزودن هماهنگی</legend>
			<div class="row field_wrapper4">
				<div>
					<div class="col-md-3">
						<div class="form-group">
							<label>نام مشتری :</label>
							<input type="text" name="customer[]" onFocus ="search_customer(this)" placeholder="نام مشتری خود را وارد کنید"  autocomplete="off" class="form-control" required>
							<p class="text-primary" style="display:none; position:absolute;font-size:12px;"></p>
						</div>
					</div>
						<div class="col-md-3">
						<div class="form-group">
							<label>انتخاب معامله:</label>
							<input type="text" name="deal_id[]" onkeyup="select_deal(this)" autocomplete="off" placeholder="لطفا شناسه معامله را وارد کنید" class="form-control" required>
						</div>
                    </div>
                    <div class="col-md-3">
						<div class="form-group">
							<label>مبلغ هماهنگی :</label>
							<input type="text" placeholder="111,000,000"  onkeyup="amhandle(this)" autocomplete="off" class="form-control" required>
							<input type = "hidden" name='volume_handle[]'>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>انتخاب حساب :</label>
                            <select class="form-control" name="bank_id[]" required>
                         <?php if(sizeof($select2) == 0){ ?>
							<option value="0">شماره حسابی ثبت نشده است</option>
						 <?php } else { foreach($select2 as $selects){
							 $a = $selects->deal_id + 100;
							 $aa = $selects->id + 1000;
							 ?>
						
							<option value="<?php echo $selects->id;?>"><?php echo ' شناسه بانک  : '. $aa .' | شناسه معامله  :'.$a; ?></option>
						 <?php } }?>
											</select>
						</div>
					</div>
					
			
				</div>
			</div>
			<div class="text-right">
				<button type="submit" name="sub" class="btn btn-primary mt-25">ثبت هماهنگی <i class="icon-arrow-left13 position-right"></i></button>
			</div>
			</form>
		</div>
    </div>
    <!-- /handle -->