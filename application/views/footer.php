</div>
</div>
<div id="modal_logout" class="modal_logout">
  <div class="modal-content animate_logout">
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
  <script>
		
		$(document).ready(function(){
    var maxField = 3;
    var addButton = $('.add_button');
    var wrapper = $('.field_wrapper');
    var fieldHTML = '<div><div class="col-md-4"><div class="form-group"><label>اطلاعات تماس:</label><input type="text" name="tel_title[]" placeholder="عنوان" class="form-control" required></div></div><div class="col-md-8"><div class="form-group mt-25 input-group"><input type="text" name="tel[]" placeholder="شماره تماس" class="form-control"><span class="input-group-btn remove_button"><button href="javascript:void(0);"  type="button" class="btn btn-danger"><span class="icon-minus2"></span></button></span></div></div></div>';
		
    var x = 1;
    
    $(addButton).click(function(){
        if(x < maxField){ 
            x++;
            $(wrapper).append(fieldHTML);
        }
    });
    $(wrapper).on('click', '.remove_button', function(e){
        e.preventDefault();
        $(this).parent('div').parent('div').parent('div').remove();
        x--; 
    });
});
	
	
		$(document).ready(function(){
    var maxField = 3;
    var addButton = $('.add_button2');
    var wrapper = $('.field_wrapper2');
    var fieldHTML = '<div><div class="row"><div class="col-md-6"><div class="form-group"><label>شماره شبا: </label><input type="text" name="number_shaba[]" class="form-control" onkeyup="show_bank(this)" data-mask="aa-99-999-9999999999999999999" placeholder="IR-06-017-0000000123014682799" required></div></div><div class="col-md-6"><div class="form-group"><label>بانک:</label><input type="text" name="name_bank[]" placeholder="ملی ، ملت ..." readonly class="form-control"></div></div></div><div class="row"><div class="col-md-6"><div class="form-group"><label>مبلغ معامله: </label><input type="text" onKeyUp="amount_bank(this)" placeholder="1000000" class="form-control"><input type="hidden" name="amount_bank[]"></div></div><div class="col-md-6"><div class="form-group input-group"><label>توضیحات حساب:</label><input type="text" name="bank_explain[]" class="form-control"><span class="input-group-btn remove_button2 "><button type="button" style="top: 14px;" class="btn btn btn-danger icon-minus2"></button></span></div></div></div></div>';
		
    var x = 1;
    
    $(addButton).click(function(){
        if(x < maxField){ 
            x++;
            $(wrapper).append(fieldHTML);
        }
    });
    $(wrapper).on('click', '.remove_button2', function(e){
        e.preventDefault();
        $(this).parent('div').parent('div').parent('div').parent('div').remove();
        x--; 
    });
});
</script>
	<!-- /page container -->
	<div class="footer text-muted">
						&copy; 2015. <a href="#">Limitless Web App Kit</a> by <a href="http://themeforest.net/user/Kopyov" target="_blank">Eugene Kopyov</a>
					</div>
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
  <!-- <script type="text/javascript" src="<?php echo base_url('files/');?>assets/js/pages/dashboard.js"></script> -->
  <!-- /theme JS files --> 
  <script>


    </script>         
</body>
</html>
