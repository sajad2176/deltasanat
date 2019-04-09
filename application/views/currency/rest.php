<div class="breadcrumb-line breadcrumb-line-component mb-20">
	<ul class="breadcrumb">
		<li><a href="index.html"><i class="icon-home2 position-left"></i> Home</a>
		</li>
		<li><a href="alpaca_basic.html">Alpaca</a>
		</li>
		<li class="active">Basic examples</li>
	</ul>
</div>
<!-- form arz -->
<div class="panel panel-flat">
	<div class="panel-body">
		<form action="#">
			<div class="row">
				<div class="col-md-12">
					<legend class="text-semibold"><i class=" icon-price-tag3 position-left"></i> مانده حساب ریالی</legend>
				</div>
				<div class="col-md-9">
					<fieldset>
						<div class="col-md-3">
							<div class="form-group">
								<label>نام مشتری: </label>
								<input type="text" class="form-control" placeholder="1.2">
							</div>
						</div>
			
						<div class="col-md-3">
							<div class="form-group">
								<label>مقدار حساب: </label>
								<input type="text" class="form-control" placeholder="3.4">
							</div>
						</div>
                        <div class="col-md-3">
								<div class="form-group">
									<label for="j_created_date"> تاریخ ثبت :</label>
									<input type="text" class="form-control" name="date_deal" id="j_created_date" readonly data-mddatetimepicker="true" data-enabletimepicker="true" data-placement="bottom" value="" placeholder="Jalali Created Date">
								</div>
								</div>
                        <div class="col-md-3">
                        <label class="display-block text-semibold">نوع حساب:</label>
						<div class="form-group mt-20">
										
										<label class="radio-inline">
											<input type="radio" name="radio-inline-left" class="styled" checked="checked">
											بدهکار
										</label>

										<label class="radio-inline">
											<input type="radio" name="radio-inline-left" class="styled">
											طلب کار
										</label>
									</div>
						</div>
					</fieldset>
				</div>
			</div>
			<div class="text-right">
				<button type="submit" class="btn btn-primary">ثبت مانده حساب <i class="icon-arrow-left13 position-right"></i></button>
			</div>
		</form>
	</div>
</div>
<!-- /form arz -->
<!--table arz-->
<div class="panel panel-flat">
	<div class="panel-body">
		<legend class="text-semibold"><i class=" icon-books position-left"></i> آرشیو </legend>
		<table class="table datatable-selection-single table-hover table-responsive-lg ">
			<thead>
				<tr>
					<th>ردیف</th>
					<th>نام مشتری</th>
					<th>نوع معامله</th>
				</tr>
			</thead>
			<tbody>
				<tr class="base_cust">
					<td>علی شیرازی</td>
					<td>علی شیرازی</td>
					<td>علی شیرازی</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<!--/table arz-->