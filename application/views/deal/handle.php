<div class="breadcrumb-line breadcrumb-line-component mb-20">
	<ul class="breadcrumb">
		<li><a href="index.html"><i class="icon-home2 position-left"></i> داشبورد</a>
		</li>
		<li><a href="datatable_api.html">معاملات</a>
		</li>
		<li class="active">آرشیو معاملات</li>
	</ul>

</div>

<form action="#">
	<div class="panel panel-flat">
		<div class="panel-body">
			<div class="row">
				<div class="">
					<fieldset>
						<legend class="text-semibold"><i class="icon-share4 position-left"></i> اطلاعات معامله</legend>
						<div class="row">
							<div class="col-sm-3">
								<div class="form-group">
									<label>نام مشتری: </label>
									<input readonly type="text" class="form-control">
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label>نوع معامله: </label>
									<input readonly type="text" class="form-control">
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label>تعداد ارز: </label>
									<input readonly type="text" class="form-control">
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label>نرخ تبدیل: </label>
									<input readonly type="text" class="form-control">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-3">
								<div class="form-group">
									<label>حجم معادله: </label>
									<input readonly type="text" class="form-control">
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label>حجم پرداخت شده: </label>
									<input readonly type="text" class="form-control">
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label>حجم قابل پرداخت: </label>
									<input readonly type="text" class="form-control">
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label>حجم هماهنگ شده: </label>
									<input readonly type="text" class="form-control">
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>توضیحات معامله: </label>
							<input readonly type="text" class="form-control">
						</div>

					</fieldset>
				</div>
			</div>
		</div>
	</div>
</form>
<div>
	<div class="panel panel-flat">
		<div class="panel-body">
			<a class="btn btn-success float-btn-left" href="#add_bank_modal" data-toggle="modal">افزودن بانک</a>
			<legend class="text-semibold"><i class="icon-credit-card position-left"></i> اطلاعات بانکی </legend>
			<table class="table datatable-basic">
				<thead>
					<tr>
						<th width="10%">ردیف</th>
						<th width="20%">Last Name</th>
						<th width="20%">Job Title</th>
						<th width="20%">DOB</th>
						<th width="20%">Status</th>
						<th width="10%" class="text-center">Actions</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Marth</td>
						<td>asdasdas
						</td>
						<td>
							asdasdasd
						</td>
						<td>22 Jun 1972</td>
						<td><span class="label label-success">Active</span>
						</td>
						<td class="text-center">
							<ul class="icons-list">
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									<i class=""></i>
								</a>
								
									<ul class="icons-list">
										<li class="text-success"><a data-toggle="modal" href="#"><i class="icon-checkmark4"></i></a>
										</li>
										<li class="text-primary"><a data-toggle="modal" href="#edit_bank_modal"><i class="icon-stack-empty"></i></>
									</li>
									<li class="text-danger"><a data-toggle="modal" href="#"><i class="icon-cross2"></i></a>
										</li>
									</ul>
								</li>
							</ul>
						</td>
					</tr>
					<tr>
			</table>
			<!-- add bank modal -->
			<div id="add_bank_modal" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h5 class="modal-title text-center">افزودن بانک</h5>

						</div>
						<hr>
						<form action="#">
							<div class="modal-body">
								<div class="field_wrapper2">
									<div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label>شماره شبا: </label>
													<input onkeyup="show_bank(this)" data-mask="aa-99-999-9999999999999999999" type="text" placeholder="IR-06-017-0000000123014682799" name="number_shaba[]" class="form-control">
												</div>
											</div>



											<div class="col-md-6">
												<div class="form-group">
													<label>بانک:</label>
													<input type="text" name="name_bank[]" placeholder="ملت،ملی،.." class="form-control" readonly>
												</div>
											</div>
										</div>


									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>مبلغ معامله: </label>
												<input type="text" onKeyUp="amount_bank(this)" placeholder="100000" class="form-control">
												<input type="hidden" name="amount_bank[]">
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group input-group">
												<label>توضیحات حساب:</label>
												<input type="text" name="bank_explain[]" placeholder="توضیحات خود را وارد کنید" class="form-control">
												<span class="input-group-btn "><button type="submit" style="top: 13px;" class="btn btn btn-success">ذخیره</button></span>
											</div>
										</div>
									</div>
								</div>
						</form>
						</div>
					</div>
				</div>
				<!-- /add bank modal -->

			</div>
		</div>
		
			
			
		</div>
	</div>
	<div class="panel panel-flat">
		<div class="panel-body">
			<div class="row field_wrapper">
				<div>
					<legend class="text-semibold"><i class="icon-address-book position-left"></i> افزودن هماهنگی</legend>
					<div class="col-md-4">
						<div class="form-group">
							<label>نام مشتری:</label>
							<input type="text" placeholder="علی شیرازی" class="form-control">
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>نام بانک:</label>
							<input class="form-control" onFocus="search_customer(this)" name="customer[]" type="text" placeholder="نام بانک خود را وارد کنید" autocomplete="off" required>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group input-group">
							<label>مبلغ هماهنگی:</label>
							<input type="text" placeholder="111,000,000" class="form-control">
							<span class="input-group-btn">
							<button type="button" class="btn btn-success add_button3 mt-25">
								<span class="icon-plus3"></span>
						







							</button>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="text-right">
				<button type="submit" class="btn btn-primary">ثبت هماهنگی <i class="icon-arrow-left13 position-right"></i></button>
			</div>
		</div>
	</div>
	<div class="panel panel-flat">
		<div class="panel-body">
			<legend class="text-semibold"><i class="icon-notebook position-left"></i> اطلاعات هماهنگی</legend>
			<table class="table datatable-basic">
				<thead>
					<tr>
						<th width="10%">ردیف</th>
						<th width="20%">Last Name</th>
						<th width="20%">Job Title</th>
						<th width="20%">DOB</th>
						<th width="20%">Status</th>
						<th width="10%" class="text-center">Actions</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Marth</td>
						<td>asdasdas
						</td>
						<td>
							asdasdasd
						</td>
						<td>22 Jun 1972</td>
						<td><span class="label label-success">Active</span>
						</td>
						<td class="text-center">
							<ul class="icons-list">
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									<i class=""></i>
								</a>
								







									<ul class="icons-list">
										<li class="text-success"><a data-toggle="modal" href="#modal_theme_success"><i class="icon-checkmark4"></i></a>
										</li>
										<li class="text-primary"><a data-toggle="modal" href="#modal_form_minor"><i class="icon-stack-empty"></i></>
									</li>
									<li class="text-danger"><a data-toggle="modal" href="#modal_theme_danger"><i class="icon-cross2"></i></a>
										</li>
									</ul>
								</li>
							</ul>
						</td>
					</tr>
					<tr>
			</table>
			<!-- minor form modal -->
			<div id="modal_form_minor" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h5 class="modal-title text-center">بخشی از مطلب هماهنگ شده را به صورت جزئی پرداخت کنید</h5>

						</div>
						<hr>
						<form action="#">
							<div class="modal-body">
								<div class="form-group input-group">
									<label>مبلغ هماهنگی:</label>
									<input type="text" placeholder="111,000,000" class="form-control">
									<span class="input-group-btn">
							<button type="submit" class="btn btn-success mt-25">ذخیره</button>
											</span>
								




								</div>
						</form>
						</div>
					</div>
				</div>
				<!-- /minor form modal -->

			</div>
			<!-- Success modal -->
			<div id="modal_theme_success" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header bg-success">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">پرداخت کامل</h4>
						</div>

						<div class="modal-body">

							<h5 class="text-center">آیا میخواهید تمام مبلغ هماهنگی پرداخت شود ؟</h5>


						</div>

						<div class="modal-footer text-center">
							<button type="button" class="btn btn-danger" data-dismiss="modal">خیر</button>
							<button type="button" class="btn btn-success">بله </button>
						</div>
					</div>
				</div>
			</div>
			<!-- /success modal -->
		</div>
		<!-- Success modal -->
		<div id="modal_theme_danger" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header bg-danger">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">حذف هماهنگی</h4>
					</div>

					<div class="modal-body">

						<h5 class="text-center">آیا میخواهید هماهنگی حذف شود ؟</h5>


					</div>

					<div class="modal-footer text-center">
						<button type="button" class="btn btn-danger" data-dismiss="modal">خیر</button>
						<button type="button" class="btn btn-success">بله </button>
					</div>
				</div>
			</div>
		</div>
		<!-- /success modal -->
		</div>
			<!-- edit bank modal -->
			<div id="edit_bank_modal" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h5 class="modal-title text-center">ویرایش بانک</h5>

						</div>
						<hr>
						<form action="#">
							<div class="modal-body">
								<div class="field_wrapper2">
									<div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label>شماره شبا: </label>
													<input onkeyup="show_bank(this)" data-mask="aa-99-999-9999999999999999999" type="text" placeholder="IR-06-017-0000000123014682799" name="number_shaba[]" class="form-control">
												</div>
											</div>



											<div class="col-md-6">
												<div class="form-group">
													<label>بانک:</label>
													<input type="text" name="name_bank[]" placeholder="ملت،ملی،.." class="form-control" readonly>
												</div>
											</div>
										</div>


									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>مبلغ معامله: </label>
												<input type="text" onKeyUp="amount_bank(this)" placeholder="100000" class="form-control">
												<input type="hidden" name="amount_bank[]">
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group input-group">
												<label>توضیحات حساب:</label>
												<input type="text" name="bank_explain[]" placeholder="توضیحات خود را وارد کنید" class="form-control">
												<span class="input-group-btn "><button type="submit" style="top: 13px;" class="btn btn btn-success">ذخیره</button></span>
											</div>
										</div>
									</div>
								</div>
						</form>
						</div>
					</div>
				</div>
				<!-- /edit bank modal -->