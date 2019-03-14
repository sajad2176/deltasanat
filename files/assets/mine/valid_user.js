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