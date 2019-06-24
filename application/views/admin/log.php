<div class="breadcrumb-line breadcrumb-line-component mb-20">
	<ul class="breadcrumb">
		<li><a href="<?php echo base_url('home');?>"><i class="icon-home2 position-left"></i> داشبورد</a>
		</li>
		<li><a href="<?php echo base_url('deal/log/').$this->uri->segment(3); ?>">کاربران</a>
		</li>
		<li class="active">فعالیت کاربر</li>
	</ul>

</div>
<div class="panel panel-flat">
	<div class="panel-body">
		<div class="panel-heading">
			<h5 class="panel-title">فعالیت کاربر</h5>
		</div>

		<div class="datatable-header">
			<div class="row">
				<form action="<?php echo base_url("admin/log/").$this->uri->segment(3);?>" method="get">
				<input type="hidden" name="user_id" value="<?php if($this->input->get('user_id')){echo $this->input->get('user_id');}else{echo $this->uri->segment(3);}?>">
			<div class="col-md-12">
				<div class="form-group col-md-3">
		            <label class="col-form-label font-lg"> نام مشتری : </label>
                    <div id="load-selected" class=" form-control col-sm-9 responsive">
					<select id="pemissions-list" name="cust_id" class="selectpicker " title="نام مشتری خود را وارد کنید" data-live-search="true">
                    <?php foreach($customer as $customers){ ?>
						<option value="<?php echo $customers->id;?>" <?php if($customers->id == $this->input->get('cust_id')){echo 'selected';}?>><?php echo $customers->fullname;?></option>
                    <?php } ?>
					</select>
			        </div>
                </div>
						<div class="col-md-4">
							<div class="col-md-6">
								<div class="form-group">
									<label for="j_created_date">از تاریخ :</label>
									<input type="text" class="form-control" id="j_created_date" readonly data-mddatetimepicker="true" data-placement="bottom" value="<?php if($this->input->get('start_date')){echo $this->input->get('start_date');}else{ echo $date;} ?>" name="start_date">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="j_created_date">تا تاریخ : </label>
									<input type="text" class="form-control" id="j_created_date" readonly data-mddatetimepicker="true"  data-placement="bottom" value="<?php if($this->input->get('end_date')){echo $this->input->get('end_date');}else{ echo $date;} ?>" name="end_date">
								</div>
							</div>
						</div>
						<div class="form-group col-md-3">
			<label class="col-form-label font-lg"> نوع فعالیت : </label>
      <div id="load-selected" class=" form-control col-sm-9 responsive">
							<select id="pemissions-list" name="act_id" class="selectpicker " title="نام فعالیت خود را وارد کنید" data-live-search="true">
                            <?php foreach($activity as $rows){ ?>
							<option value="<?php echo $rows->id;?>" <?php if($rows->id == $this->input->get('act_id')){echo 'selected';}?> ><?php echo $rows->name;?></option>
                             <?php } ?>
							</select>
				</div>
    </div>
	
						<div class="col-md-1">
							<button class="btn btn-success mt-25" name="sub" type="submit" >اعمال فیلتر</button>
						</div>
						
					</div>
				</form>
			</div>
		</div>
	

	<table class="table datatable-selection-single table-hover table-responsive-lg ">
		<thead>
			<tr>
				<th width="4%">ردیف</th>
				<th width="7%" class="text-center">تاریخ</th>
				<th width="6%" class="text-center">زمان</th>
				<th width="15%">نوع فعالیت</th>
				<th width="68%">توضحیات</th>
			</tr>
		</thead>
		<tbody>
                 
	<?php if(empty($logs)){ ?>  <tr><td colspan = '5' class='text-center p-20'>موردی یافت نشد</td></tr> <?php }
			 
			 else{
		$num = $this->input->get('per_page') + 1;
		foreach($logs as $rows){ ?>
			<tr>
				<td>
					<?php echo $num;?>
				</td>
				<td>
				<?php echo $rows->date_log; ?>

				</td>
				<td>
					<?php echo $rows->time_log; ?>
				</td>
				<td>
					<?php echo $rows->name;?>
				</td>
				<td>
					<?php echo $rows->explain;;?>
				</td>
			</tr>
			<?php
			$num++;
			}
			?>
			<tr>
				<td colspan="2" class="pt-20 pb-20">
					نمایش
					<?php echo  $this->uri->segment(4) + 1;?> تا
					<?php echo $num - 1; ?> از
					<?php echo $count;?>
				</td>
				<td colspan="3" class="text-left pt-20 pb-20">
					<?php echo $page; ?>
				</td>
			</tr>
			<?php }?>
		</tbody>

	</table>


</div>
</div>
</div>