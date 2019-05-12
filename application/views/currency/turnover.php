					<!-- Column selectors -->
					<p style = "display=none;" id="check"><?php echo $check;?></p>
					<div class="panel panel-flat">
					<div class="panel-body">
						<div class="panel-heading">
							<h5 class="panel-title">گردش حساب</h5>
							<br>
							<div class="row">
				<form action="<?php echo base_url('settings/turnover'); ?>" method="post">

					<div class="col-md-12">
						<div class="col-md-3">
							<div class="form-group">
								<label>نام صاحب حساب : </label>
								<input class="form-control" name= 'owner' type="search" value="<?php echo $owner; ?>" placeholder=" نام صاحب حساب را وارد کنید">

							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>نام واریز کننده : </label>
								<input class="form-control" name= 'provider' type="search" value="<?php echo $provider; ?>" placeholder="نام واریز کننده را وارد کنید">

							</div>
						</div>
						<div class="col-md-4">
							<div class="col-md-6">
								<div class="form-group">
									<label for="j_created_date">از تاریخ :</label>
									<input type="text" class="form-control" name="start_date" id="j_created_date" readonly data-mddatetimepicker="true" data-enabletimepicker="true" data-placement="bottom" value="<?php echo $start_date; ?>" placeholder="Jalali Created Date">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="j_created_date">تا تاریخ :</label>
									<input type="text" class="form-control" name="end_date" id="j_created_date" readonly data-mddatetimepicker="true" data-enabletimepicker="true" data-placement="bottom" value="<?php echo $end_date;?>" placeholder="Jalali Created Date">
								</div>
							</div>
						</div>
						<div class="col-md-1">
							<button class="btn btn-success mt-25" name="sub" type="submit" >اعمال فیلتر</button>
						</div>
						<div class="col-md-1">
							<button class="btn btn-primary mt-25" onclick="window.print();" type="button"> Print </button>
						</div>
						</div>
						</div>
						</div>
						<table class="table datatable-button-html5-columns">
							<thead>
								<tr>
									<th>نام صاحب حساب</th>
									<th>نام واریز کننده</th>
									<th>مشخصات حساب</th>
									<th>توضیحات حساب</th>
									<th>مانده حساب</th>
                                    <th>تاریخ</th>
                                    <th>زمان</th>
									<th>مبلغ </th>
									<th>ویرایش </th>
								</tr>
							</thead>
							<tbody>
								<?php if(empty($turnover)){?>
									<tr><td class="text-center p-20" colspan="8">موردی یافت نشد</td></tr>
								<?php }else{?>
								<?php foreach($turnover as $rows){ ?>
								<tr>
									<td><?php echo $rows->owner;?></td>
									<td><?php echo $rows->fullname;?></td>
                                    <td><?php echo $rows->shaba."</br>".$rows->name; ?></td>
									<td><?php echo $rows->explain;?></td>
									<td><?php echo number_format($rows->rest);?></td>
                                    <td><?php echo $rows->date;?></td>
									<td><?php echo $rows->time;?></td>
									<td class="lright <?php if($rows->amount < 0 ){echo 'text-danger';}?>" ><?php echo number_format($rows->amount);?></td>
									<td>s</td>
								</tr>
								<?php } }?>
							</tbody>
						</table>
						<div class="p-20"><?php if(isset($page)){ echo $page;}?></div>
						</div>
					</div>
					<!-- /column selectors -->
					<script>
					window.onload = function(){
		var remove_class = document.getElementById('DataTables_Table_0');
        remove_class.classList.remove('dataTable');				
		var a = document.getElementsByClassName('datatable-footer');
        var b = document.getElementsByClassName('dataTables_filter');
		var c = document.getElementsByClassName('dataTables_length');
	    var check = document.getElementById('check');
        if(check.innerHTML == 1){	
        a[0].setAttribute( 'style', 'display: block !important' );
        b[0].setAttribute( 'style', 'display: block !important' );
        c[0].setAttribute( 'style', 'display: block !important' );
        }else{
		a[0].setAttribute( 'style', 'display: none !important' );
        b[0].setAttribute( 'style', 'display: none !important' );
        c[0].setAttribute( 'style', 'display: none !important' );
        }				
					}
					</script>