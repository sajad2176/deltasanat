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