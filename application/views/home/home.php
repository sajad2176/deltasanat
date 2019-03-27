<!-- Dashboard content -->
<div  class="row">
	<div class="col-lg-8">
		<div class="row">
			<div class="col-lg-4">
				<!-- Members online -->
				<div class="panel bg-teal-400">
					<div class="panel-body">
						<div class="heading-elements">
							<span class="heading-text badge bg-teal-800">هماهنگی شده</span>
						</div>
						<h3 class="no-margin">3,450</h3> 
						<div class="text-muted text-size-small">489 avg</div>
					</div>

					<div class="container-fluid">
						<div class="chart" id="members-online"></div>
					</div>
				</div>
				<!-- /members online -->
			</div>
			<div class="col-lg-4">
				<!-- Current server load -->
				<div class="panel bg-pink-400">
					<div class="panel-body">
						<div class="heading-elements">
							<span class="heading-text badge bg-success-800">پرداخت شده</span>
						</div>
						<h3 class="no-margin">49.4%</h3> 
						<div class="text-muted text-size-small">34.6% avg</div>
					</div>
					<div class="chart" id="server-load"></div>
				</div>
				<!-- /current server load -->

			</div>

			<div class="col-lg-4">
				<!-- Today's revenue -->
				<div class="panel bg-blue-400">
					<div class="panel-body">
						<div class="heading-elements">
							<span class="heading-text badge bg-blue-800">مانده هماهنگی</span>
						</div>
						<h3 class="no-margin">$18,390</h3> 
						<div class="text-muted text-size-small">$37,578 avg</div>
					</div>
					<div class="chart" id="today-revenue"></div>
				</div>
				<!-- /today's revenue -->
			</div>
		</div>
		<!-- Marketing campaigns -->
		<div class="panel panel-flat">
			<div class="panel-body">
				<div class="panel-heading">
					<h5 class="panel-title">مجموع معاملات</h5>
				</div>
				<img style="width: 100%" src="<?php echo base_url('files/');?>/assets/img/Annotation 2019-03-13 161608.jpg" </div>
			</div>
			<!-- /marketing campaigns -->
			<!-- Quick stats boxes -->
			<!-- /quick stats boxes -->
		</div>
	</div>

	<div class="col-lg-4">
		<!-- Daily sales -->
		<div class="panel panel-flat">
			<div class="panel-body">
				<div style="padding-right: 0px" class="panel-heading">
					<h6 class="panel-title">خرید و فروش ارز ها</h6>
					<div  class="heading-elements">
						<h6 class="  heading-text">تاریخ: <span class="text-bold text-black position-right">12 مرداد 98</span></h6>

					</div>
				</div>
				

				<div class="table-responsive">
					<table class="table text-nowrap">
						<thead>
							<tr>
								<th style="width: 85px">ارز</th>
								<th class="text-success-800" style="padding-right: 80px">خرید</th>
								<th class="text-primary">فروش</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<div class="media-body">
										<div class="media-heading">
											<h5 class="letter-icon-title">دلار</h5>
										</div>
									</div>
								</td>
								<td>
									<h6 class="text-semibold text-success-800 pr-34 no-margin ">
										<?php echo number_format($buy_dollar->cd + $buy_dollar->wd);?>
									</h6>
								</td>
								<td>
									<h6 class="text-semibold text-primary pr-8 no-margin">
										<?php echo number_format($sell_dollar->cd + $sell_dollar->wd); ?>
									</h6>
								</td>
							</tr>
							<tr>
							</tr>
							<tr>
								<td>
								
									<div class="media-body">
										<div class="media-heading">
											<h5  class="letter-icon-title">یوان</h5>
										</div>
									</div>
								</td>
								<td>
									<h6 class=" text-semibold text-success-800 pr-34 no-margin">
										<?php echo number_format($buy_yuan->cd + $buy_yuan->wd); ?>
									</h6>
								</td>
								<td>
									<h6 class="text-semibold text-primary  pr-8 no-margin">
										<?php echo number_format($sell_yuan->cd + $sell_yuan->wd); ?>
									</h6>
								</td>
							</tr>
							<tr>
								<td>
									<div class="media-body">
										<div class="media-heading">
											<h5 class="letter-icon-title">یورو</h5>
										</div>	
									</div>
								</td>
								<td>
									<h6 class=" text-semibold text-success-800 pr-34 no-margin">
										<?php echo number_format($buy_euro->cd + $buy_euro->wd); ?>
									</h6>
								</td>
								<td>
									<h6 class="text-semibold text-primary pr-8 no-margin">
										<?php echo number_format($sell_euro->cd + $sell_euro->wd); ?>
									</h6>
								</td>
							</tr>
							<tr>
								<td>
									<div class="media-body">
										<div class="media-heading">
											<h5 class="letter-icon-title">درهم</h5>
										</div>
									</div>
								</td>
								<td>
									<h6 class="text-semibold text-success-800 pr-34 no-margin">
										<?php echo number_format($buy_derham->cd + $buy_derham->wd); ?>
									</h6>
								</td>
								<td>
									<h6 class="text-semibold text-primary pr-8 no-margin">
										<?php echo number_format($sell_derham->cd + $sell_derham->wd); ?>
									</h6>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<!-- /daily sales -->
		<div class="panel panel-flat">
			<div class="panel-body">
				<!-- Members online -->
			<div style="padding-bottom: 30px " class="panel-heading">
					<h6 class="panel-title">موجودی</h6>
					<div  class="heading-elements">
						<a href="#add_arz_modal" data-toggle="modal" class=" btn btn-success">ارز اولیه </a>
					</div>
				</div>
					<div class="row">
					<?php 
					$array = array('bg-blue-1' , 'bg-blue-2' , 'bg-blue-3' , 'bg-blue-4');
					foreach($remain as $i => $remains){ ?>
					<div class="col-md-6">
						<div class="panel <?php echo $array[$i]; ?>">

							<div class="panel-body">
								<div class="heading-elements">
									<span class="heading-text badge bg-success-800">+53,6%</span>
								</div>
								<h3 class="no-margin">
									<?php echo $remains->amount_unit;?>
								</h3>
								<div class="text-white text-size-larg">
									<?php echo $remains->name;?>
								</div>
							</div>

							<div class="container-fluid">
								<div class="chart" id="members-online"></div>
							</div>
						</div>
					</div>
					<!-- /members online -->
					<?php } ?>

				</div>
			</div>
		</div>

	</div>
</div>
<!-- /dashboard content -->
<!-- add arz modal -->
			<div id="add_arz_modal" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h5 class="modal-title text-center">ارز اولیه</h5>

						</div>
						<hr>
						<form action="<?php ?>" method="post">
							<div class="modal-body">
								<div class="field_wrapper2">
									<div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label>دلار : </label>
													<input type="text" placeholder="999/999/999"  class="form-control" >
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>یورو :</label>
													<input type="text" placeholder="999/999/999" class="form-control" >
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>یوان : </label>
												<input type="text" placeholder="999/999/999" class="form-control">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group input-group">
												<label>درهم :</label>
												<input type="text"  placeholder="999/999/999" class="form-control">
												<span class="input-group-btn "><button type="submit" name="sub" style="top: 13px;" class="btn btn btn-success">ذخیره</button></span>
											</div>
										</div>
									</div>
								</div>
						</form>
						</div>
					</div>
				</div>
				<!-- /add arz modal -->