<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if($this->session->has_userdata('msg')){
$msg = $this->session->userdata('msg');?>
<div class="alert bg-<?php echo $msg[1];?> alert-styled-left">
										<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
										<?php echo $msg[0];?>
								    </div>
<?php } ?>
<div class="breadcrumb-line breadcrumb-line-component mb-20">
	<ul class="breadcrumb">
		<li><a href="<?php echo base_url('home');?>"><i class="icon-home2 position-left"></i> داشبورد</a>
		</li>
		<li><a href="<?php echo base_url('deal/disable_bank/').$this->uri->segment(3);?>"> آرشیو معاملات</a>
		</li>
		<li class="active">بانک های غیر فعال</li>
	</ul>
</div>
<!--table arz-->
<div class="panel panel-flat" id="div_bank">
		<div class="panel-body">	
		<legend class="text-semibold"><i class="icon-credit-card position-left"></i> اطلاعات بانکی </legend>
			<table class="table datatable-basic table-responsive-lg">
				<thead>
					<tr>
						<th width="5%" >شناسه</th>
						<th width="20%"> اطلاعات حساب</th>
						<th width="13%">حجم تعیین شده</th>
						<th width="13%"> پرداخت شده</th>
						<th width="13%"> باقی مانده</th>
						<th width="13%"> هماهنگ نشده</th>
						<th width="13%">توضیحات</th>
						<th width="5%" class="text-center">وضعیت</th>
						<th width="5%" class="text-center">ابزار</th>
					</tr>
				</thead>
				<tbody>
				
				<?php
				if(empty($bank)){ ?>
                  <tr><td colspan="9" class="text-center p-20">موردی یافت نشد</td></tr>
				<?php }else{
				foreach($bank as $key => $banks){
					?>
					<tr>
						<td><?php echo $banks->id;?></td>
						<td><?php echo $banks->shaba."</br>".$banks->name; ?></td>
						<td  class="<?php if($banks->amount < $banks->pay ){echo 'text-danger';}?>"><?php echo number_format($banks->amount); ?></td>
						<td  class="<?php if($banks->amount < $banks->pay ){echo 'text-danger';}?>"><?php echo number_format($banks->pay); ?></td>
						<td  class="<?php if($banks->rest < 0){echo 'text-danger';}?>"><?php echo number_format($banks->rest); ?></td>
						<td  class="<?php if($banks->rest_handle < 0){echo 'text-danger';}?>"><?php echo number_format($banks->rest_handle); ?></td>
						<td><?php echo $banks->explain; ?></td>
				        <td class="text-center"><?php if($this->session->has_userdata('active_bank')){ ?>
						<a href="<?php echo base_url('deal/active/').$this->uri->segment(3)."/".$banks->id."/1/disable"; ?>"><span class="label label-danger">غیر فعال</span></a>
						<?php } ?>
						</td>
						<td class="text-center">
						 <ul class="icons-list">
				<?php if($this->session->has_userdata('edit_bank')){?><li title="ویرایش بانک" data-toggle="tooltip" class="text-primary"><a data-toggle="modal" href="#edit_bank_modal"><i onclick = "getBank(<?php echo $banks->id;?>)" class="icon-credit-card"></i></li><?php } ?>
				<li title="گردش حساب" data-toggle="tooltip" class="text-pink"><a href="<?php echo base_url('settings/bank/').$banks->id;?>" target="_blank"><i class="icon-spinner10"></i></li>
						</ul>
						</td>
					</tr>						
						<?php } ?>
						<tr>
				<td colspan="6" class="pt-20 pb-20">
				</td>
				<td colspan="5" class="text-left pt-20 pb-20">
					<?php if(isset($page)){echo $page;} ?>
				</td>
			</tr>
						</tbody>
			</table>

			<?php  }  ?>
			</div>
			<!-- edit bank modal -->
		<div id="edit_bank_modal" class="modal fade">
			<div class="modal-dialog" style="width:750px;">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title text-center">ویرایش بانک</h5>
					</div>
					<hr>
					<form action="" method="post" id='actionEditBank'>
						<div class="modal-body">
							<div class="field_wrapper2">
								<div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>شماره شبا : </label>
												<input onkeyup="show_bank(this)" id="numberShabaEdit" value="" data-mask="99-999-9999999999999999999" type="text" placeholder="06-017-0000000123014682799" name="shaba" class="form-control">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>بانک :</label>
												<span class="text-primary" style="font-size:12px; display:none;">(طبق شماره شبا وارد شده بانکی پیدا نشد. نام بانک را وارد کنید)</span>
												<input type="text" name="name" id="nameBankEdit" value="" placeholder="ملت،ملی،.." class="form-control">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label>مبلغ معامله : </label>
											<input type="hidden" id="amountPay" value =''>
											<input type="text" onkeyup="ambank(this)" placeholder="100,000" value="" class="form-control">
											<input type="hidden" value='' id="amountBank" name="amount">
											<p class="text-danger" style="display:none;"></p>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group input-group">
											<label>توضیحات حساب :</label>
											<input type="text" id="explainBank" name="bank_explain" value='' placeholder="توضیحات خود را وارد کنید" class="form-control">
											<span class="input-group-btn "><button type="submit" name="sub" style="top: 13px;" class="btn btn btn-success">ذخیره</button></span>
										</div>
									</div>
								</div>
							</div>
					   </form>
					</div>
				</div>
			</div>
		</div>
		<!-- edit bank modal  -->
		</div>
<script>
//get bank---------------------
function getBank(id){
	var xhr = new XMLHttpRequest();
		xhr.onload = function(){
			if((xhr.status >= 200 && xhr.status < 300) || xhr.status == 304){
				    var url = "<?php echo base_url('deal/edit_bank') ?>";
					var result = JSON.parse(xhr.responseText);
				    showBank(result , url);
				}else{
					alert('request was unsuccessful : ' + xhr.status);
				}
		}
		xhr.open('post' , "<?php echo base_url('deal/show_bank/')?>" , true);
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhr.send('bank_id=' + id);
}
//get bank---------------------
// check amount bank
function ambank( input ) {
    input.value = numeral( input.value ).format( '0,0' );
    input.nextElementSibling.value = numeral( input.value ).value();
    if(input.previousElementSibling.value > numeral(input.value).value()){
        input.nextElementSibling.nextElementSibling.style.display = 'block';
        input.nextElementSibling.nextElementSibling.innerHTML = ' مبلغ تعیین شده از مبلغی که به این حساب واریز شده  کمتر است  ';
        }else{
            input.nextElementSibling.nextElementSibling.style.display = 'none';
        }
}
// check amount bank
//edit bank
var actionEditBank = document.getElementById( 'actionEditBank' );
var numberShabaEdit = document.getElementById( 'numberShabaEdit' );
var nameBankEdit = document.getElementById( 'nameBankEdit' );
var amountPay = document.getElementById( 'amountPay' );
var amountBank = document.getElementById( 'amountBank' );
var explainBank = document.getElementById( 'explainBank' );

function showBank(result , url){
    actionEditBank.action = url + "/" + result.id+"/disable";
    numberShabaEdit.value = result.shaba;
    nameBankEdit.value = result.name;
    amountPay.value = result.pay;
    amountBank.value = result.amount;
    amountBank.previousElementSibling.value = numeral(result.amount).format('0,0') ;
	explainBank.value = result.explain;    
}
//edit bank
//name bank
function show_bank( input ) {
    var txt = input.value;
    var name_bank = input.parentElement.parentElement.nextElementSibling.firstElementChild.lastElementChild;
    if ( txt[ 3 ] != '_' && txt[ 4 ] != '_' && txt[ 5 ] != '_' ) {
        name_bank.previousElementSibling.style.display = 'none';
        var bank = txt.slice( 3, 6 );
        if ( bank == '055' ) {
            name_bank.value = 'بانک اقتصاد نوین';
        } else if ( bank == '054' ) {
            name_bank.value = 'بانک پارسیان';
        } else if ( bank == '057' ) {
            name_bank.value = 'بانک پاسارگاد';
        } else if ( bank == '021' ) {
            name_bank.value = 'پست بانک ایران';
        } else if ( bank == '018' ) {
            name_bank.value = 'بانک تجارت';
        } else if ( bank == '051' ) {
            name_bank.value = 'موسسه اعتباری توسعه';
        } else if ( bank == '020' ) {
            name_bank.value = 'بانک توسعه صادرات';
        } else if ( bank == '013' ) {
            name_bank.value = 'بانک رفاه';
        } else if ( bank == '056' ) {
            name_bank.value = 'بانک سامان';
        } else if ( bank == '015' ) {
            name_bank.value = 'بانک سپه';
        } else if ( bank == '058' ) {
            name_bank.value = 'بانک سرمایه';
        } else if ( bank == '019' ) {
            name_bank.value = 'بانک صادرات ایران';
        } else if ( bank == '011' ) {
            name_bank.value = 'بانک صنعت و معدن';
        } else if ( bank == '053' ) {
            name_bank.value = 'بانک کارآفرین';
        } else if ( bank == '016' ) {
            name_bank.value = 'بانک کشاورزی';
        } else if ( bank == '010' ) {
            name_bank.value = 'بانک مرکزی جمهوری اسلامی ایران';
        } else if ( bank == '014' ) {
            name_bank.value = 'بانک مسکن';
        } else if ( bank == '012' ) {
            name_bank.value = 'بانک ملت';
        } else if ( bank == '017' ) {
            name_bank.value = 'بانک ملی ایران';
        } else if ( bank == '022' ) {
            name_bank.value = 'بانک توسعه تعاون';
        } else if ( bank == '059' ) {
            name_bank.value = 'بانک سینا';
        } else if ( bank == '060' ) {
            name_bank.value = 'قرض الحسنه مهر';
        } else if ( bank == '061' ) {
            name_bank.value = 'بانک شهر';
        } else if ( bank == '062' ) {
            name_bank.value = 'بانک تات';
        } else if ( bank == '063' ) {
            name_bank.value = 'بانک انصار';
        } else if ( bank == '064' ) {
            name_bank.value = 'بانک گردشگری';
        } else if ( bank == '065' ) {
            name_bank.value = 'بانک حکمت ایرانیان';
        } else if ( bank == '066' ) {
            name_bank.value = 'بانک دی';
        } else if ( bank == '069' ) {
            name_bank.value = 'بانک ایران زمین';
        } else {
            name_bank.previousElementSibling.style.display = 'inline';
            name_bank.value = '';
        }
    }
}
//name bank
</script>
<!--/table arz-->