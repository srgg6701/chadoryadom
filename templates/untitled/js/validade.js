// JavaScript Document
function checkEmailValid(fieldValue){
	var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if (!filter.test(fieldValue)) {
		return 'Емэйл указан некорректно!';
	}else{
		return 1;
	}
}