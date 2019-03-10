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
						<legend class="text-semibold"><i class="icon-law position-left"></i> اطلاعات معامله</legend>
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
<div class="panel panel-flat">
	<div class="panel-body">
		<div class="row field_wrapper">
			<div>
				<legend class="text-semibold"><i class="icon-address-book3 position-left"></i> افزودن هماهنگی</legend>
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
		<legend class="text-semibold"><i class="icon-law position-left"></i> اطلاعات هماهنگی</legend>
		<table class="table datatable-basic">
			<thead>
				<tr>
					<th>ردیف</th>
					<th>Last Name</th>
					<th>Job Title</th>
					<th>DOB</th>
					<th>Status</th>
					<th class="text-center">Actions</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Marth</td>
					<td><a href="#">Enright</a>
					</td>
					<td>
						<div class="progress">
							<div class="progress-bar progress-bar-info" style="width: 60%;">
								60%
							</div>
						</div>
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
									<li class="text-primary-600"><a href="#"><i class="icon-pencil7"></i></a>
									</li>
									<li title="هماهنگی ها" class="text-teal-600"><a href="#"><i class="icon-cog7"></i></a>
									</li>
									<li class="text-danger-600"><a href="#"><i class="icon-trash"></i></a>
									</li>
								</ul>
							</li>
						</ul>
					</td>
				</tr>
				<tr>
	</div>
</div>			