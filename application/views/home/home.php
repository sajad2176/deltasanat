<!-- Dashboard content -->
		<div class="panel panel-flat">
			<div class="panel-body">
				<!-- Members online -->
			<div  class="panel-heading">
					<h4 class="panel-title">موجودی</h4>
					
				</div>
					<div class="row">
					<?php 
					$array = array('bg-blue-1' , 'bg-blue-2' , 'bg-blue-3' , 'bg-blue-4');
					$set_id = array('dollar' , 'euro' , 'yuan' , 'derham');
					foreach($remain as $i => $remains){ ?>
					<div class="col-md-3">
						<div class="panel <?php echo $array[$i]; ?>">

							<div class="panel-body">
								<div class="heading-elements">
									<span class="heading-text badge bg-success-800">+53,6%</span>
								</div>
								<h3 class="no-margin" id="<?php echo $set_id[$i];?>">
									<?php echo number_format($remains->amount_unit);?>
								</h3>
								<h5 class="text-white text-size-larg">
									<?php echo $remains->name;?>
								</h5>
							</div>
						</div>
					</div>
					<!-- /members online -->
					<?php
				if($i == 3){
					break;
				}
				} ?>

				</div>
			</div>
		</div>
<div  class="row">
	<div class="col-md-8">
		<div class="panel panel-flat">
			<div class="panel-body">
			<div  class="panel-heading">
					<h4 class="panel-title">هماهنگ شده ها</h4>
					
				</div>
		<div class="row">
			<div class="col-md-4">
				<!-- Members online -->
				<div class="panel bg-teal-400 p-5">
					<div class="panel-body">
						<div class="heading-elements">
							<span class="heading-text badge bg-teal-800"> مانده ریالی</span>
						</div>
						<h3 class="no-margin" id="sumHandle"><?php echo number_format($sum_handle->vh);?></h3> 
						
					</div>

					<div class="container-fluid">
						<div class="chart" id="members-online"></div>
					</div>
				</div>
				<!-- /members online -->
			</div>
			<div class="col-md-4">
				<!-- Current server load -->
				<div class="panel bg-pink-400 p-5">
					<div class="panel-body">
						<div class="heading-elements">
							<span class="heading-text badge bg-success-800">مانده همانگ نشده فروش </span>
						</div>
						<h3 class="no-margin" id="sumPay"><?php echo number_format($sum_pay->vp); ?></h3> 
						
					</div>
					<div class="chart" id="server-load"></div>
				</div>
				<!-- /current server load -->

			</div>

			<div class="col-md-4">
				<!-- Today's revenue -->
				<div class="panel bg-blue-400 p-5">
					<div class="panel-body">
						<div class="heading-elements">
							<span class="heading-text badge bg-blue-800">مانده همانگ نشده خرید </span>
						</div>
						<h3 class="no-margin" id="restHandle"><?php echo number_format($sum_handle->hr); ?></h3> 
						
					</div>
					<div class="chart" id="today-revenue"></div>
				</div>
				<!-- /today's revenue -->
			</div>
			
		</div>
					</div>
		</div>
		<!-- Marketing campaigns -->
		<div class="panel panel-flat">
			<div class="panel-body">
				<div class="panel-heading">
					<h4 class="panel-title">میانگین دلاری</h4>
				</div>
							<div class="col-md-6">
				<!-- Today's revenue -->
				<div class="panel bg-dollar p-5">
					<div class="panel-body">
						<div class="heading-elements">
							<span class="heading-text badge bg-teal-800">مانده همانگ نشده خرید </span>
						</div>
						<h3 class="no-margin" id="restHandle"><?php echo number_format($sum_handle->hr); ?></h3> 
						
					</div>
					<div class="chart" id="today-revenue"></div>
				</div>
				<!-- /today's revenue -->
			</div>
							<div class="col-md-6">
				<!-- Today's revenue -->
				<div class="panel bg-dollar2 p-5">
					<div class="panel-body">
						<div class="heading-elements">
							<span class="heading-text badge bg-teal-400">مانده همانگ نشده خرید </span>
						</div>
						<h3 class="no-margin" id="restHandle"><?php echo number_format($sum_handle->hr); ?></h3> 
						
					</div>
					<div class="chart" id="today-revenue"></div>
				</div>
				<!-- /today's revenue -->
			</div>
			</div>
		</div>
	</div>

	<div class="col-md-4">
		<!-- Daily sales -->
		<div class="panel panel-flat">
			<div class="panel-body">
				<div style="padding-right: 0px" class="panel-heading">
					<h6 class="panel-title">خرید و فروش ارز ها</h6>
					<div  class="heading-elements">
						<h6 class="  heading-text">تاریخ: <span class="text-bold text-black position-right" id="today"><?php echo $today;?></span></h6>

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
									<h6 class="text-semibold text-success-800 pr-34 no-margin " id="buyDollar">
										<?php echo number_format($buy_dollar);?>
									</h6>
								</td>
								<td>
									<h6 class="text-semibold text-primary pr-8 no-margin" id="sellDollar">
										<?php echo number_format($sell_dollar); ?>
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
									<h6 class=" text-semibold text-success-800 pr-34 no-margin" id="buyYuan">
										<?php echo number_format($buy_yuan); ?>
									</h6>
								</td>
								<td>
									<h6 class="text-semibold text-primary  pr-8 no-margin" id="sellYuan">
										<?php echo number_format($sell_yuan); ?>
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
									<h6 class=" text-semibold text-success-800 pr-34 no-margin" id="buyEuro">
										<?php echo number_format($buy_euro); ?>
									</h6>
								</td>
								<td>
									<h6 class="text-semibold text-primary pr-8 no-margin" id="sellEuro">
										<?php echo number_format($sell_euro); ?>
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
									<h6 class="text-semibold text-success-800 pr-34 no-margin" id="buyDerham">
										<?php echo number_format($buy_derham); ?>
									</h6>
								</td>
								<td>
									<h6 class="text-semibold text-primary pr-8 no-margin" id="sellDerham">
										<?php echo number_format($sell_derham); ?>
									</h6>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<!-- /daily sales -->
	</div>
</div>
<!-- /dashboard content -->

				<script>
				window.onload = function(){
					setInterval(update , 15000);
				}
		var buyDollar = document.getElementById('buyDollar');
		var buyEuro = document.getElementById('buyEuro');
		var buyYuan = document.getElementById('buyYuan');
		var buyDerham = document.getElementById('buyDerham');
		var sellDollar = document.getElementById('sellDollar');
		var sellEuro = document.getElementById('sellEuro');
		var sellYuan = document.getElementById('sellYuan');
		var sellDerham = document.getElementById('sellDerham');	
		var today = document.getElementById('today');
		var sumHandle = document.getElementById('sumHandle');
		var sumPay = document.getElementById('sumPay');
		var restHandle = document.getElementById('restHandle');
		var dollar = document.getElementById('dollar');			
		var euro = document.getElementById('euro');			
		var yuan = document.getElementById('yuan');			
		var derham = document.getElementById('derham');			
				function update(){
		var xhr = new XMLHttpRequest();
		xhr.onload = function(){
			if((xhr.status >= 200 && xhr.status < 300) || xhr.status == 304){
				var result = JSON.parse(xhr.responseText);
				    showResult(result);
				}else{
					alert('request was unsuccessful : ' + xhr.status);
					location.reload();
				}
		}
		xhr.open('post' , "<?php echo base_url('home/update_dashbord')?>" , true);
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhr.send("request="+true);
				}
					function showResult(res){
						sumHandle.innerHTML = numeral(res.sum_handle.vh).format('0,0');
						sumPay.innerHTML = numeral(res.sum_pay.vp).format('0,0');
						restHandle.innerHTML = numeral(res.sum_handle.hr).format('0,0');
						today.innerHTML = res.today;
						buyDollar.innerHTML = numeral(res.buy_dollar).format('0,0');
						buyEuro.innerHTML = numeral(res.buy_euro).format('0,0');
						buyYuan.innerHTML = numeral(res.buy_yuan).format('0,0');
						buyDerham.innerHTML = numeral(res.buy_derham).format('0,0');
						sellDollar.innerHTML = numeral(res.sell_dollar).format('0,0');
						sellEuro.innerHTML = numeral(res.sell_euro).format('0,0');
						sellYuan.innerHTML = numeral(res.sell_yuan).format('0,0');
						sellDerham.innerHTML = numeral(res.sell_derham).format('0,0');
						dollar.innerHTML = numeral(res.remain[0].amount_unit).format('0,0');
						euro.innerHTML = numeral(res.remain[1].amount_unit).format('0,0');
						yuan.innerHTML = numeral(res.remain[2].amount_unit).format('0,0');
						derham.innerHTML = numeral(res.remain[3].amount_unit).format('0,0');
					}
				
				</script>