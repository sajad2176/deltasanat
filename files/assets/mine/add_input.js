		// customer
		$(document).ready(function(){
            var maxField = 3;
            var addButton = $('.add_button');
            var wrapper = $('.field_wrapper');
            var fieldHTML = '<div><div class="col-md-4"><div class="form-group"><label>اطلاعات تماس:</label><input type="text" name="tel_title[]" placeholder="عنوان" class="form-control" required></div></div><div class="col-md-8"><div class="form-group mt-25 input-group"><input type="text" name="tel[]" placeholder="شماره تماس" class="form-control" required><span class="input-group-btn remove_button"><button href="javascript:void(0);"  type="button" class="btn btn-danger"><span class="icon-minus2"></span></button></span></div></div></div>';
                
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
        // customer
        // buy and sell
        $(document).ready(function(){
            var maxField = 3;
            var addButton = $('.add_button2');
            var wrapper = $('.field_wrapper2');
            var fieldHTML = '<div><div class="row"><div class="col-md-6"><div class="form-group"><label>شماره شبا: </label><input type="text" name="shaba[]" class="form-control" onkeyup="show_bank(this)" data-mask="99-999-9999999999999999999" placeholder="06-017-0000000123014682799"></div></div><div class="col-md-6"><div class="form-group"><label>بانک : </label><span class="text-primary" style="font-size:12px; display:none;"> (طبق شماره شبا وارد شده بانکی پیدا نشد. نام بانک را وارد کنید) </span><input type="text" name="name[]" placeholder="ملی ، ملت ..." class="form-control"></div></div></div><div class="row"><div class="col-md-6"><div class="form-group"><label>تعیین حجم  : </label><input type="text" onKeyUp="amount_bank(this)" placeholder="100,000" class="form-control"><input type="hidden" name="amount[]"><p class="text-danger" style ="display: none;">مبلغ وارد شده بیشتر از حجم معامله است</p></div></div><div class="col-md-6"><div class="form-group input-group"><label>توضیحات حساب :</label><input type="text" name="bank_explain[]" class="form-control" placeholder="توضحیات خود را وارد کنید"><span class="input-group-btn remove_button2 "><button type="button" style="top: 14px;" class="btn btn btn-danger icon-minus2"></button></span></div></div></div></div>';
                
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
        // buy and sell
        //edit
        $(document).ready(function(){
            var maxField = 3;
            var addButton = $('.add_edit');
            var wrapper = $('.field_edit');
            var fieldHTML = '<div><div class="row"><div class="col-md-6"><div class="form-group"><label>شماره شبا: </label><input type="text" name="send_shaba[]" class="form-control" onkeyup="show_bank(this)" data-mask="99-999-9999999999999999999" placeholder="06-017-0000000123014682799"></div></div><div class="col-md-6"><div class="form-group"><label>بانک : </label><span class="text-primary" style="font-size:12px; display:none;"> (طبق شماره شبا وارد شده بانکی پیدا نشد. نام بانک را وارد کنید) </span><input type="text" name="send_bank[]" placeholder="ملی ، ملت ..." class="form-control"></div></div></div><div class="row"><div class="col-md-6"><div class="form-group"><label>تعیین حجم  : </label><input type="hidden" value="0"><input type="text" onKeyUp="amount_bank(this)" placeholder="100,000" class="form-control"><input type="hidden" name="send_amount[]"><p class="text-danger" style ="display: none;">مبلغ وارد شده بیشتر از حجم معامله است</p></div></div><div class="col-md-6"><div class="form-group input-group"><label>توضیحات حساب :</label><input type="text" name="send_explain[]" class="form-control" placeholder="توضحیات خود را وارد کنید"><span class="input-group-btn remove_edit "><button type="button" style="top: 14px;" class="btn btn btn-danger icon-minus2"></button></span></div></div></div></div>';
                
            var x = 1;
            
            $(addButton).click(function(){
                if(x < maxField){ 
                    x++;
                    $(wrapper).append(fieldHTML);
                }
            });
            $(wrapper).on('click', '.remove_edit', function(e){
                e.preventDefault();
                $(this).parent('div').parent('div').parent('div').parent('div').remove();
                x--; 
            });
        });
        //edit