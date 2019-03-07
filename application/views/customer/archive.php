                    <div class="breadcrumb-line breadcrumb-line-component mb-20">
						<ul class="breadcrumb">
							<li><a href="index.html"><i class="icon-home2 position-left"></i> داشبورد</a></li>
							<li><a href="datatable_api.html">مشتریان</a></li>
							<li class="active">آرشیو مشتریان</li>
						</ul>

                    </div>
                    
                    <div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">آرشیو مشتریان</h5>

                        </div>
                        <div class="datatable-header"><div id="DataTables_Table_3_filter" class="dataTables_filter"><label><span>جستجو:</span> <input type="search" class="" placeholder="جستجو کنید ..." aria-controls="DataTables_Table_3"></label></div><div class="dataTables_length" id="DataTables_Table_3_length"><label><span>نمایش:</span> <select name="DataTables_Table_3_length" aria-controls="DataTables_Table_3" class="select2-hidden-accessible" tabindex="-1" aria-hidden="true"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select><span class="select2 select2-container select2-container--default" dir="rtl" style="width: auto;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-DataTables_Table_3_length-21-container"><span class="select2-selection__rendered" id="select2-DataTables_Table_3_length-21-container" title="10">10</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span></label></div></div>
				    	<table class="table datatable-selection-single table-striped">
							<thead>
								<tr>
                                    <th>ردیف</th>
					                <th>نام و نام خانوادگی</th>
					                <th>تعداد معاملات انجام شده</th>
					                <th>مجموع معامله</th>
					                <th class="text-center">ابزارک</th>
					            </tr>
							</thead>
							<tbody>
							<?php 
							$num = $this->uri->segment(3) + 1;
							foreach($customer as $rows){
							?>
								<tr>
					                <td><?php echo $num;?></td>
					                <td><?php echo $rows->fullname; ?></td>
					                <td>10</td>
					                <td><span class="label label-info">$320,800</span></td>
									<td class="text-center">
										<ul class="icons-list">
										<li class="text-primary-600"><a href="<?php echo base_url('customer/edit/').$rows->id;?>"><i class="icon-pencil7"></i></a></li>
                                        <li class="text-teal-600"><a href="<?php echo base_url('customer/edit/').$rows->id;?>"><i class="icon-cog7"></i></a></li>
                                        <li class="text-danger-600"><a href="#"><i class="icon-trash"></i></a></li>

										</ul>
									</td>
					            </tr>
							<?php
						$num++;
						} ?>
					            <!-- <tr>
					                <td>Garrett Winters</td>
					                <td>Accountant</td>
					                <td>63</td>
					                <td><a href="#">2011/07/25</a></td>
					                <td><span class="label label-danger">$170,750</span></td>
									<td class="text-center">
										<ul class="icons-list">
											<li class="dropdown">
												<a href="#" class="dropdown-toggle" data-toggle="dropdown">
													<i class="icon-menu9"></i>
												</a>

												<ul class="dropdown-menu dropdown-menu-right">
													<li><a href="#"><i class="icon-file-pdf"></i> Export to .pdf</a></li>
													<li><a href="#"><i class="icon-file-excel"></i> Export to .csv</a></li>
													<li><a href="#"><i class="icon-file-word"></i> Export to .doc</a></li>
												</ul>
											</li>
										</ul>
									</td>
					            </tr> -->
								<tr>
								<td colspan="3" class="pt-20 pb-20">
								Showing 1 to 10 of 15 entries
								</td>
								<td colspan="2" class="text-left pt-20 pb-20">
								<?php echo $page; ?>
								</td>
								</tr>
								<!-- <div class="row">
					<div class="dataTables_info col-md-6"></div>
					<div class="col-md-6"><?php// echo $page;?></div>
					</div> -->
							</tbody>
							
                        </table>
						
						

                    </div>




                    
