					<!-- Column selectors -->
					<p style = "display:none;" id="check"><?php if($status == 1){echo 1;}else{echo $this->input->get('check');}?></p>
					<div class="panel panel-flat">
					<div class="panel-body">
						<div class="panel-heading">
							<h5 class="panel-title">گردش حساب</h5>
							<br>
							<div class="row">
				<form action="<?php echo base_url('settings/turnover'); ?>" method="get">

					<div class="col-md-12">
						<div class="col-md-3">
							<div class="form-group">
								<label>نام صاحب حساب : </label>
								<input class="form-control" name='owner' type="search" value="<?php if($status == 1){echo $owner;}else{echo $this->input->get('owner');}?>" placeholder=" نام صاحب حساب را وارد کنید">

							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>نام واریز کننده : </label>
								<input class="form-control" name='provider' type="search" value="<?php echo $this->input->get('provider'); ?>" placeholder="نام واریز کننده را وارد کنید">

							</div>
						</div>
						<div class="col-md-4">
						<div class="col-md-6">
								<div class="form-group">
									<label for="j_created_date">از تاریخ :</label>
									<input type="text" class="form-control" name="start_date" id="j_created_date" readonly data-mddatetimepicker="true" data-enabletimepicker="true" data-placement="bottom" value="<?php if($this->input->get('start_date')){echo $this->input->get('start_date');}else{echo $date;} ?>" placeholder="Jalali Created Date">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="j_created_date">تا تاریخ :</label>
									<input type="text" class="form-control" name="end_date" id="j_created_date" readonly data-mddatetimepicker="true" data-enabletimepicker="true" data-placement="bottom" value="<?php if($this->input->get('end_date')){echo $this->input->get('end_date');}else{echo $date;} ?>" placeholder="Jalali Created Date">
								</div>
							</div>
						</div>
						<input type="hidden" name="check" value='1'>
						<div class="col-md-1">
							<button class="btn btn-success mt-25" type="submit" >اعمال فیلتر</button>
						</div>
						</form>
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
									<th>ابزار</th>
								</tr>
							</thead>
							<tbody>
								<?php if(empty($turnover)){?>
									<tr><td class="text-center p-20" colspan="8">موردی یافت نشد</td></tr>
								<?php }else{?>
								<?php foreach($turnover as $key => $rows){ ?>
								<tr>
									<td><?php echo $rows->owner;?></td>
									<td><?php echo $rows->fullname;?></td>
                                    <td><?php echo $rows->shaba."</br>".$rows->name; ?></td>
									<td><?php echo $rows->explain;?></td>
									<td><?php echo number_format($rows->rest);?></td>
                                    <td id="date<?php echo $key;?>"><?php echo $rows->date;?></td>
									<td id="time<?php echo $key;?>"><?php echo $rows->time;?></td>
									<td class="lright <?php if($rows->amount < 0 ){echo 'text-danger';}?>" ><?php echo number_format($rows->amount);?></td>
									<td><ul class="icons-list"><li class="text-primary"><a data-toggle="modal" href="#edit_bank_modal"><i onclick = 'edit_bank(<?php echo $rows->id; ?>, <?php echo $key; ?>)' class="icon-credit-card"></i></li></ul></td>
								</tr>
								<?php } }?>
							</tbody>
						</table>
						<div class="row">
						<div class="col-md-12 p-20">
						<div class="col-md-5"><?php if(isset($page)){ ?> نمایش <?php echo $this->input->get('per_page') + 1 ?> تا <?php echo $this->input->get('per_page') + $key + 1;?> از <?php echo $total;?> <?php }?></div>
						<div class="col-md-7"><?php if(isset($page)){ echo $page;}?></div>
						</div>
						</div>
						<div id="edit_bank_modal" class="modal fade">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h5 class="modal-title text-center">تاریخ واریزی را ویرایش کنید</h5>

							</div>
							<hr>
							<form method="post">
								<div class="modal-body">
									<div class="form-group input-group">
										<label> انتخاب تاریخ :</label>
										<input type="text" class="form-control" name="start_date" id="j_created_date" readonly data-mddatetimepicker="true" data-enabletimepicker="true" data-placement="bottom" value="<?php echo $date; ?>" placeholder="Jalali Created Date">
										<p id="id_bank" style="display:none;"></p>
										<p id="id_key" style="display:none;"></p>
										<p style="position: absolute; top: 65px;"></p>
										<span class="input-group-btn">
							<a class="btn btn-success mt-25" onclick='change_date()'>ذخیره</a>
											</span>
									</div>
							</form>
							</div>
						</div>
					</div>
				<!-- /minor form modal -->

			</div>
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
	var id_bank = document.getElementById('id_bank');
	var dateBank = id_bank.previousElementSibling;
	var id_key = document.getElementById('id_key');
	function edit_bank(id , key){
		var date = document.getElementById('date'+key).innerHTML;
		var time = document.getElementById('time'+key).innerHTML;
		dateBank.value = date.replace(/-/g , '/')+' '+ time;
		id_bank.innerHTML = id;
		id_key.innerHTML = key; 
	}
	function change_date(){
		id = id_bank.innerHTML;
		date = dateBank.value;
		var xhr = new XMLHttpRequest();
		xhr.onload = function () {
			if ( ( xhr.status >= 200 && xhr.status < 300 ) || xhr.status == 304 ) {
				res = xhr.responseText;
                 if( res != 0){
					 key = id_key.innerHTML;
					document.getElementById('date'+key).innerHTML = res.substr(0 , 10);
					document.getElementById('time'+key).innerHTML = res.substr(11 , 20);
					dateBank.value = res.substr(0 , 10)+" "+res.substr(11 , 20);
					id_key.nextElementSibling.innerHTML = 'تاریخ با موفقیت ویرایش شد'; 
					id_key.nextElementSibling.classList.add("text-success");
				 }else{
					id_key.nextElementSibling.innerHTML = 'مشکلی در ثبت اطلاعات رخ داده است'; 
					id_key.nextElementSibling.classList.add("text-danger");
				 }
			} else {
				alert( 'request was unsuccessful : ' + xhr.status );
			}
		}
		xhr.open( 'post', "<?php echo base_url('settings/change/')?>", true );
		xhr.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' );
		xhr.send( 'id=' + id+"&date="+date );
	}
					</script>