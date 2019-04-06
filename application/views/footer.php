</div>
</div>
<div id="modal_logout" class="modal_logout">
  <div class="modal_content animate_logout">
    <div class="dang_modal">
      <span class="dang_body"></span>
      <span class="dang_dot"></span>
    </div>
    <div class="container_logout">
      <h1>خروج</h1>
      <h6>آیا مایل به خروج از پورتال می باشید؟</h6>
    </div>
    <a class="btn btn-secendery"  onclick="document.getElementById('modal_logout').style.display = 'none'">انصراف</a>
    <a  class="btn btn-danger"href="<?php echo base_url('home/logout');?>">بله مطمئنم</a>
  </div>




</div>
<?php if(isset($select) and sizeof($select) != 0){ 
  $str = '';
foreach($select as $selects){
$str .= "<option value = \" $selects->id \">". $selects->number_shaba ." | ". $selects->name_bank ."</option>";
}
}
if(isset($select2) and sizeof($select2) != 0){ 
  $str2 = '';
foreach($select2 as $selects){
  $id = $selects->deal_id + 100;
$str2 .= "<option value = \" $selects->id \">". $selects->number_shaba ." | ". $selects->name_bank." | شناسه معامله : " . $id ."</option>";
$id = 0;
}
}
?>
<script>
var modal = document.getElementById('modal_logout');
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

</script>


		</div>
		<!-- /page content -->

  </div>
  <script type="text/javascript" src="<?php echo base_url('files/');?>assets/js/plugins/loaders/pace.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url('files/');?>assets/js/core/libraries/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url('files/');?>assets/mine/add_input.js"></script>
  <script>


$(document).ready(function(){
    var maxField = 3;
    var addButton = $('.add_button3');
    var wrapper = $('.field_wrapper3');
    var fieldHTML = '<div><div class="col-md-4"><div class="form-group"><label>نام مشتری:</label><input class="form-control" onFocus="search_customer(this)" name="customer[]" type="text" placeholder="نام مشتری خود را وارد کنید" autocomplete="off" required><p class="text-primary" style="display:none; position:absolute;font-size:12px;"></p></div></div><div class="col-md-4"><div class="form-group"><label>انتخاب حساب : </label>	<select class="form-control" name="bank_id[]" required><?php if(isset($str)){ echo $str;}else{echo '<option value = "0">شماره حسابی ثبت نشده است</option>';} ?></select></div></div><div class="col-md-4"><div class="form-group input-group"><label>مبلغ هماهنگی:</label><input type="text" onkeyup="volume_handle(this)" placeholder="111,000,000" class="form-control" required><input type="hidden" name="volume_handle[]"><p class="text-danger" style="position:absolute;top:65px;"></p><span class="input-group-btn remove_button3"><button type="button" class="btn btn-danger  mt-25"><span class="icon-minus2"></span></button></span></div></div></div>';
		
    var x = 1;
    		
    $(addButton).click(function(){
        if(x < maxField){ 
            x++;
            $(wrapper).append(fieldHTML);
        }
    });
    $(wrapper).on('click', '.remove_button3', function(e){
        e.preventDefault();
        $(this).parent('div').parent('div').parent('div').remove();
        x--; 
    });
});
$(document).ready(function(){
    var maxField = 3;
    var addButton = $('.add_button4');
    var wrapper = $('.field_wrapper4');
    var fieldHTML = '<div><div class="col-md-3"><div class="form-group"><label>نام مشتری:</label><input class="form-control" onFocus="search_customer(this)" name="customer[]" type="text" placeholder="نام مشتری خود را وارد کنید" autocomplete="off" required><p class="text-primary" style="display:none; position:absolute;font-size:12px;"></p></div></div><div class="col-md-3"><div class="form-group"><label>انتخاب معامله:</label><input type="text" placeholder="لطفا شناسه معامله را وارد کنید" onkeyup="select_deal(this)" name="deal_id[]" class="form-control" required></div></div><div class="col-md-3"><div class="form-group"><label>انتخاب حساب : </label><select class="form-control" name="bank_id[]" required><?php if(isset($str2)){ echo $str2;}else{echo '<option value = "0">شماره حسابی ثبت نشده است</option>';} ?></select></div></div><div class="col-md-3"><div class="form-group input-group"><label>مبلغ هماهنگی:</label><input type="text" onkeyup="ambank(this)" placeholder="111,000,000" class="form-control" required><input type="hidden" name="volume_handle[]" required><span class="input-group-btn remove_button4"><button type="button" class="btn btn-danger  mt-25"><span class="icon-minus2"></span></button></span></div></div></div>';
		
    var x = 1;
    		
    $(addButton).click(function(){
        if(x < maxField){ 
            x++;
            $(wrapper).append(fieldHTML);
        }
    });
    $(wrapper).on('click', '.remove_button4', function(e){
        e.preventDefault();
        $(this).parent('div').parent('div').parent('div').remove();
        x--; 
    });
});
</script>
	<!-- /page container -->

<!-- Core JS files -->
	<script type="text/javascript" src="<?php echo base_url('files/');?>assets/js/core/libraries/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url('files/');?>assets/js/plugins/loaders/blockui.min.js"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
	

  <script type="text/javascript" src="<?php echo base_url('files/');?>assets/js/plugins/visualization/d3/d3.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url('files/');?>assets/js/plugins/visualization/d3/d3_tooltip.js"></script>
  <script type="text/javascript" src="<?php echo base_url('files/');?>assets/js/plugins/forms/styling/switchery.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url('files/');?>assets/js/plugins/forms/styling/uniform.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url('files/');?>assets/js/plugins/forms/selects/bootstrap_multiselect.js"></script>
  <script type="text/javascript" src="<?php echo base_url('files/');?>assets/js/plugins/ui/moment/moment.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url('files/');?>assets/js/plugins/pickers/daterangepicker.js"></script>
  <script type="text/javascript" src="<?php echo base_url('files/'); ?>assets/js/plugins/forms/selects/select2.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url('files/'); ?>assets/js/core/libraries/jasny_bootstrap.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url('files/'); ?>assets/js/plugins/forms/inputs/autosize.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url('files/'); ?>assets/js/plugins/forms/inputs/formatter.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url('files/'); ?>assets/js/plugins/forms/inputs/typeahead/typeahead.bundle.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url('files/'); ?>assets/js/plugins/forms/inputs/typeahead/handlebars.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url('files/'); ?>assets/js/plugins/forms/inputs/passy.js"></script>
  <script type="text/javascript" src="<?php echo base_url('files/'); ?>assets/js/plugins/forms/inputs/maxlength.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url('files/');?>assets/js/pages/form_layouts.js"></script>
  <script type="text/javascript" src="<?php echo base_url('files/');?>assets/js/core/app.js"></script>
  <script type="text/javascript" src="<?php echo base_url('files/'); ?>assets/js/pages/form_controls_extended.js"></script>
  <script type="text/javascript" src="<?php echo base_url('files/'); ?>assets/bootstrap-PersianDateTimePicker/jalaali.js"></script>
  <script type="text/javascript" src="<?php echo base_url('files/'); ?>assets/bootstrap-PersianDateTimePicker/jquery.Bootstrap-PersianDateTimePicker.js"></script>
  <script>

 		$(function () {
            $('[data-toggle="tooltip"]').tooltip();
        })
    </script>  
</body>
</html>
