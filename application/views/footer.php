</div>
</div>
<div id="modal_logout" class="modal_logout">
  <div class="modal-content animate_logout">
    <div class="dang_modal">
      <span class="dang_body"></span>
      <span class="dang_dot"></span>
    </div>
    <div class="container_logout">
      <h5>خروج</h5>
      <h6>آیا مایل به خروج از پورتال می باشید؟</h6>
    </div>
    <a onclick="document.getElementById('modal_logout').style.display = 'none'">انصراف</a>
    <a href="<?php echo base_url('home/logout');?>">بله ، مطعنم</a>
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
	<!-- /page container -->
	<div class="footer text-muted">
						&copy; 2015. <a href="#">Limitless Web App Kit</a> by <a href="http://themeforest.net/user/Kopyov" target="_blank">Eugene Kopyov</a>
					</div>
</body>
</html>
