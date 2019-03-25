<div class="breadcrumb-line breadcrumb-line-component mb-20">
	<ul class="breadcrumb">
		<li><a href="<?php echo base_url('home')?>"><i class="icon-home2 position-left"></i> خانه</a>
		</li>
		<li><a href="<?php echo base_url('deal/photo/').$this->uri->segment(3);?>">معاملات</a>
		</li>
		<li class="active">قبض ها</li>
	</ul>
</div>
<!-- Grid -->
<div class="container-detached">
						<div class="content-detached">
<div class="row">
<?php if(sizeof($photo) == 0){?>
<div class ="col-lg-12">
<div class="panel text-center" style = "padding:50px;"><h4>موردی یافت نشد</h4></div>
</div>
<?php } else { foreach($photo as $rows){?>
	<div class="col-lg-3 col-sm-6">

		<div class="panel" style="height:270px;">
			<div class="panel-body">
				<div class="thumb thumb-fixed" style="height:200px;">
					<a href="<?php echo base_url('uploads/deal/').$rows->pic_name;?>" data-popup="lightbox">
			     <img src="<?php echo base_url('uploads/deal/').$rows->pic_name;?>" style="height:100%;">
													<span class="zoom-image"><i class="icon-zoomin3"></i></span>
												</a>
				
				</div>
			</div>

			<div class="panel-body panel-body-accent text-center">
				<h6 class="text-semibold no-margin"><a  class="text-default"><?php echo $rows->date_upload;?></a></h6>

			</div>
		</div>
	</div>
<?php } }?>
</div>
</div>
</div>