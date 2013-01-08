// JavaScript Document
//$(function(){});
function handleForm(){
	var inputs=$('form#app-form input[type="text"]');
	var err=0;
	$(inputs).each(function(index, element) {
        if(element.value==""){
			$(element).css('background-color','#FF9');
			err++;
		}
    });
	if (err>0){
		alert('Заполнены не все поля!');
		return false;
	}
}
