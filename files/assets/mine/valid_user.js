var pass = document.getElementById('pass');
var repeat = document.getElementById('repeat');
var sub = document.getElementById('sub');
pass.addEventListener("keyup" , check);
repeat.addEventListener("keyup" , check);
function check(){
		if(pass.value == '' && repeat.value == ''){
	    pass.style.boxShadow = 'none';
		repeat.style.boxShadow = 'none';
		repeat.nextElementSibling.style.display = 'none'
		}
	else if(pass.value == repeat.value){
		pass.style.boxShadow = '0 0 3px #4caf50';
		repeat.style.boxShadow = '0 0 3px #4caf50';
		repeat.nextElementSibling.style.display = 'none'
	}
	else{
		if(pass.value == '' || repeat.value == '' || pass.value.length > repeat.value.length){
	    pass.style.boxShadow = 'none';
		repeat.style.boxShadow = 'none';
		repeat.nextElementSibling.style.display = 'none'
		}else{			
	    pass.style.boxShadow = '0 0 3px #f44336';
		repeat.style.boxShadow = '0 0 3px #f44336';
		repeat.nextElementSibling.style.display = 'block'
		}
	}
}
	sub.addEventListener("submit", function(evt) {
	if(repeat.value != pass.value){
        evt.preventDefault();
        pass.style.boxShadow = '0 0 3px #f44336';
		repeat.style.boxShadow = '0 0 3px #f44336';
		repeat.nextElementSibling.style.display = 'block'
	}else{
		return true;
	}     
},true);
var select_users = document.getElementById("select_users");
var check_users = document.getElementsByClassName("check_users");

select_users.addEventListener("change", function(e){
	for (i = 0; i < check_users.length; i++) { 
		check_users[i].checked = select_users.checked;
	}
});


for (var i = 0; i < check_users.length; i++) {
	check_users[i].addEventListener('change', function(e){ 

		if(this.checked == false){
			select_users.checked = false;
		}

		if(document.querySelectorAll('.check_users:checked').length == check_users.length){
			select_users.checked = true;
		}
	});
}
// customer
var select_customer = document.getElementById("select_customer");
var check_customer = document.getElementsByClassName("check_customer");

select_customer.addEventListener("change", function(e){
	for (i = 0; i < check_customer.length; i++) { 
		check_customer[i].checked = select_customer.checked;
	}
});


for (var i = 0; i < check_customer.length; i++) {
	check_customer[i].addEventListener('change', function(e){ 

		if(this.checked == false){
			select_customer.checked = false;
		}

		if(document.querySelectorAll('.check_customer:checked').length == check_customer.length){
			select_customer.checked = true;
		}
	});
}
// deal
var select_deal = document.getElementById("select_deal");
var check_deal = document.getElementsByClassName("check_deal");

select_deal.addEventListener("change", function(e){
	for (i = 0; i < check_deal.length; i++) { 
		check_deal[i].checked = select_deal.checked;
	}
});


for (var i = 0; i < check_deal.length; i++) {
	check_deal[i].addEventListener('change', function(e){ 

		if(this.checked == false){
			select_deal.checked = false;
		}

		if(document.querySelectorAll('.check_deal:checked').length == check_deal.length){
			select_deal.checked = true;
		}
	});
}