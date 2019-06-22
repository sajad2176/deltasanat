<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if($this->session->has_userdata('msg')){
$msg = $this->session->userdata('msg');?>
<div class="alert bg-<?php echo $msg[1];?> alert-styled-left">
										<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
										<?php echo $msg[0];?>
								    </div>
<?php }?>
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
<div class="panel panel-flat">
	<div class="panel-body">
		<div class="panel-heading">
			<h5 class="panel-title" id = "title">ارسال قبض</h5>
		</div>

		<div class="datatable-header">
			<div class="row">
				<form action="<?php echo base_url('deal/photo/'.$this->uri->segment(3)); ?>" method="post" enctype="multipart/form-data" id='form'>
					<div class="col-md-12">
						<div class="col-md-3">
							<div class="form-group">
								<label>تاریخ : </label>
								<input type="text" class="form-control" name="date" id="j_created_date" readonly data-mddatetimepicker="true" data-enabletimepicker="true" data-placement="bottom" value="<?php echo $date; ?>" placeholder="Jalali Created Date">
							</div>
						</div>
			
							<div class="col-md-4">
								<div class="form-group">
									<label>ارسال عکس (برای انتخاب چند عکس لطفا دکمه ctrl را نگه دارید)</label>
									<input type="file" class="file-styled" name="deal_pic[]" multiple="multiple">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label> توضحیات  :</label>
									<input class="form-control" id="explain" name="explain" type="text" placeholder="توضحیات">
								</div>
							</div>



						<div class="col-md-2">
							<button class="btn btn-success mt-25" id="submit" name="sub" type="submit" >ارسال</button>
						</div>
						
					</div>
				</form>
			</div>
		</div>
		</div>
		</div>
<div class="container-detached">
	<div class="content-detached">
<div class="row">
<?php if(sizeof($photo) == 0){ ?>
<div class ="col-lg-12">
<div class="panel text-center" style = "padding:50px;"><h4>موردی یافت نشد</h4></div>
</div>
<?php } else { foreach($photo as $rows){ ?>
	<div class="col-lg-3 col-sm-6">

		<div class="panel">
			<div class="panel-body">
				<div class="thumb thumb-fixed" style="height:200px;">
					<a href="<?php echo base_url('uploads/deal/').$rows->pic_name;?>" data-popup="lightbox">
			     <img src="<?php echo base_url('uploads/deal/').$rows->pic_name;?>" style="height:100%;">
													<span class="zoom-image"><i class="icon-zoomin3"></i></span>
												</a>
				</div>
			</div>
			<div class="panel-body panel-body-accent text-center">
			<div id="deletePhoto" title="حذف قبض" onclick='deletePhoto(<?php echo $rows->id;?> , "<?php echo $rows->pic_name;?>");'  data-toggle="tooltip"><a class="text-danger" data-toggle="modal" href="#modal_theme_danger"><i  class="icon-trash" ></i></a></div>
			<div id="editPhoto" onclick="editPhoto(<?php echo $rows->id; ?>);" title="ویرایش قبض"  data-toggle="tooltip"><a class="text-primary"><i  class="icon-pencil6" ></i></a></div>
				<h6 class="text-semibold no-margin"><a  class="text-default"><?php echo $rows->date_upload;?></a></h6>
				<h6 class="text-semibold no-margin"><a  class="text-default" style='display:inline-block;height:14px;'><?php echo $rows->explain;?></a></h6>

			</div>
		</div>
	</div>
	
<?php } ?>
</div>
<div class="text-left row bg-white" style = "padding:20px;"><div class="col-md-12"><?php echo $page; ?></div></div>
<?php }?>

</div>
<!-- delete photo modal -->
<div id="modal_theme_danger" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header bg-danger">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">حذف قبض</h4>
						</div>

						<div class="modal-body">

							<h6 class="text-center">آیا مایل به حذف قبض می باشید؟</h6>


						</div>

						<div class="modal-footer text-center">
							<button type="button" class="btn btn-danger" data-dismiss="modal">بستن</button>
							<a class="btn btn-success" id="confirm">بله </a>
						</div>
					</div>
				</div>
			</div>
<!-- delete photo modal -->
</div>
<script>

			// delete photo script
			var confirm = document.getElementById('confirm');
			function deletePhoto(id , picname){
				confirm.removeAttribute('href');
				confirm.setAttribute('href' , "<?php echo base_url('deal/delete_photo/'.$this->uri->segment(3)."/")?>"+id+'/'+picname);
			}
			// delete photo script 
 
			// edit photo script
			var date = document.getElementById('j_created_date');
			var explain = document.getElementById('explain');
			var submit = document.getElementById('submit');
			var title = document.getElementById('title');
			var editform = document.getElementById('form');

	       function editPhoto(id){

		var xhr = new XMLHttpRequest();
		xhr.onload = function () {
			if ( ( xhr.status >= 200 && xhr.status < 300 ) || xhr.status == 304 ) {
         result = JSON.parse(xhr.responseText);
		 date.value = result.date_upload;
		 explain.value = result.explain;
        submit.innerHTML = 'ویرایش قبض';
		title.innerHTML = 'ویرایش قبض';
		editform.action = "<?php echo base_url('deal/edit_photo/').$this->uri->segment(3)."/";?>" + id;
			} else {
				alert( 'request was unsuccessful : ' + xhr.status );
			}
		}
		xhr.open( 'post', "<?php echo base_url('deal/get_photo/')?>", true );
		xhr.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' );
		xhr.send('id='+id);

		}
			// edit photo script

</script>