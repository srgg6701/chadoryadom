<?
if(JRequest::getVar('stat')=='applicated')
	require_once dirname(__FILE__).'/applicated.php';
else{?>
<style>
form#app-form{ 
	margin:20px 0px !important;
}
form#app-form
	input[type="text"]{
	margin:6px;
	width:200px;
}
form#app-form
	span{
	display:inline-block;
	text-align:right;
	width:200px;
}
</style>
<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// echo $this->msg;
require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_application'.DS.'helpers'.DS.'chado_app_data.php';?>
<h1>Заявка на подключение к сервису.</h1>
<div>все поля обязательны для заполнения</div>
<form action="<?php echo JRoute::_('index.php?option=com_application'); ?>" method="post" name="adminForm" id="app-form" class="form-validate" enctype="multipart/form-data">

<?	$arrTable=ApplicationHelper::getAppFields();
	$i=0;
	foreach($arrTable as $field=>$desc):
		if ($field!="id"):?>
<span><?="<b>".$desc.":</b>";?></span>
<input name="<?=$field?>" type="text" required id="<?=$field?>"<? if($field=="mobila"){?> placeholder="71234567890"<? }?>><br>    
<?		endif;
		$i++;
	endforeach;?>
    <br>
    <div align="center" style="width:400px;"><button type="submit">Отправить заявку</button></div>
    <br>
    <br>
    <input type="hidden" name="task" value="add" />
</form>
<script src="templates/untitled/js/validade.js"></script>
<script>
$(function(){ 
	$('input#email').blur( function(){
			if ($(this).val()){
				var ret=checkEmailValid($(this).val());
				if(ret!=1){
					alert(ret);
					$(this).css('border','solid 1px red').focus();
					return false;
				}else{
						// POST/GET
					var goUrl="<?=$url?>index.php?option=com_application&task=email_check&email="+$(this).val();
					<? 	if ($t){?>
					window.open(goUrl,'ajax');
					<?	}?>
					jQuery.ajax({
						type: "GET",
						url: goUrl,
						success: function(msg){
							if (msg=='found'){
								$('input#email').css({
									'background-color':'#CF9',
									border:'initial'
								}).focus();
								alert('У нас на рассмотрении уже есть заявка, где указан введённый вами e-mail. Вам не нужно подавать её повторно.');
								return false;
							}
						}
					 });
				}
			}
		});
});
</script><?
}