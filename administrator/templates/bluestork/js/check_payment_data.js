// JavaScript Document
$(function(){ 
	$('input#time').blur( function(){
			var tVal=$(this).val();
			if (tVal!=''){
				var re = /[^0-9:]/g; 
				if(re.test(tVal)){
					alert('Вы ввели недопустимые символы в поле для указания времени платежа. Допустимый формат: ЧЧ:ММ');
					return false; 
				}
			}
		});
	$('a#send_payment').click( function(){
			$('form#payment-form').fadeToggle(200);
		});
	$('a#cancel_payment').click( function(){
			$('form#payment-form').fadeOut(200);
		});
	var delImg=$('img[src$="delete.png"]');
	$(delImg).mouseover().attr('title','Удалить проводку')
		.click( function(){
			var trPayment=$(this).parents('tr');
			if ($(this).parent().attr('class')!="command"){
				var pId=$(trPayment).children('td').eq(1).text();
				if(!confirm('Удалить проводку?'))
					return false;
				else{
					// POST/GET
					var goUrl="index.php?option=com_application&task=delete_payment&id="+pId;
					//alert(goUrl); return false;
					
					var op=false;
					if (op){
						//alert(goUrl);
						//return false;
						window.open(goUrl,'ajax');
						return false;
					}
					$.ajax({
						type: "GET",
						url: goUrl,
						success: function(msg){
							$(trPayment).fadeOut(300);
						},
						error: function(msg){
							alert('Не удалось удалить проводку...');
						}
					 });
				}
			}
		});
	var applySign=$('td a[href="#"] b:contains("?")');
	$(applySign).mouseover().attr('title','Подтвердить проводку')
		.click( function(){
			var trPayment=$(this).parents('tr');
			if ($(this).parent().attr('class')!="command"){
				var pId=$(trPayment).children('td').eq(1).text();
				if(!confirm('Подтвердить проводку?'))
					return false;
				else{
					// POST/GET
					var goUrl="index.php?option=com_application&task=apply_payment&id="+pId;
					var op=true;
					if (op){
						//alert(goUrl);
						//return false;
						window.open(goUrl,'ajax');
						return false;
					}
					$.ajax({
						type: "GET",
						url: goUrl,
						success: function(msg){
							$(this).parent().html('OK');
						},
						error: function(msg){
							alert('Не удалось подтвердить проводку...');
						}
					 });
				}
			}
		});
	/*$('button#btnAdd').click(function(){
			if($('select#user_id').val()=='0'){
				alert('Вы не выбрали клиента из списка!');
				return false;
			}
		});*/
	$('form#payment-form').submit( function(){
			if($('select#user_id').val()=='0'){
				alert('Вы не выбрали клиента из списка!');
				return false;
			}
		});
});
