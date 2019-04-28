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
								<!-- <div class="heading-elements">
									<span class="heading-text badge bg-success-800">+53,6%</span>
								</div> -->
								<h3 class="no-margin" id="<?php echo $set_id[$i];?>">
									<?php echo number_format($remains->amount);?>
								</h3>
								<h5 class="text-white text-size-larg">
									<?php echo $remains->name;?>
								</h5>
							</div>
						</div>
					</div>
					<!-- /members online -->
					<?php } ?>

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
					<div class="pb-10 pl-10">

					<h5 class=""> مانده ریالی</h5>

						<h3 class="no-margin" id="restRial"><?php echo number_format($rest_rial);?></h3> 
						
					</div>
				</div>
				<!-- /members online -->
			</div>
			<div class="col-md-4">
				<!-- Current server load -->
				<div class="panel bg-pink-400 p-5">
					<div class="pb-10 pl-10">

							<h5 class="">مانده همانگ نشده فروش </h5>

						<h3 class="no-margin" id="sellNot"><?php echo number_format($sell_not);?></h3> 
						
					</div>
				</div>
				<!-- /current server load -->

			</div>

			<div class="col-md-4">
				<!-- Today's revenue -->
				<div class="panel bg-blue-400 p-5">
					<div class="pb-10 pl-10">
					
							<h5 class="">مانده همانگ نشده خرید </h5>
				
						<h3 class="no-margin" id="buyNot"><?php echo number_format($buy_not); ?></h3> 
					</div>
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
							<span class="heading-text badge bg-teal-800">میانگین دلاری خرید </span>
						</div>
						<h3 class="no-margin" id="aveBuy"><?php echo number_format($ave_buy); ?></h3> 
						
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
							<span class="heading-text badge bg-teal-400">میانگین دلاری فروش </span>
						</div>
						<h3 class="no-margin" id="aveSell"><?php echo number_format($ave_sell); ?></h3> 
						
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

<script type="text/javascript" src="<?php echo base_url('files/');?>assets/mine/home.js"></script>
<script>
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
</script>