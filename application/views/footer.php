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
  <script type="text/javascript" src="<?php echo base_url('files/');?>assets/js/plugins/tables/datatables/datatables.min.js"></script>

	<script type="text/javascript" src="<?php echo base_url('files/');?>assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url('files/');?>assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url('files/');?>assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url('files/');?>assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url('files/');?>assets/js/pages/datatables_extension_buttons_html5.js"></script>
    <!-- <script type="text/javascript" src="<?php //echo base_url('files/');?>assets/mine/close_modal.js"></script> -->
    <script>
$('#modal_form_minor').on('shown.bs.modal', function () {
    $('#autofocuss').focus();
})  
    </script>
</body>
</html>
