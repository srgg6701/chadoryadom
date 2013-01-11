<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Users Html Helper
 *
 * @package		Joomla.Site
 * @subpackage	com_users
 * @since		1.6
 */
 
if (!$app) 
	$app =& JFactory::getApplication();
$template = $app->getTemplate();
$link=JUri::base().'templates/'.$template.'/css/application_form.css';
//die("link=".$link);
?><link href="<?=$link?>" rel="stylesheet" type="text/css"><?	
class userAccount
{
	public static function accountManager($params)
	{	$link_base="index.php?option=com_users&view=";
														?>
        
<div style="position:relative">
    <form style="position:absolute; right:0;top:3px;" action="<?php echo JRoute::_('index.php?option=com_users&task=user.logout'); ?>" method="post">
		<div>
			<button type="submit" class="button"><?php echo JText::_('JLOGOUT'); ?></button>
			<input type="hidden" name="return" value="<?php echo base64_encode($params); ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>
<!--<div>Меню личного кабинета</div>-->		
<div class="account menu">
    	<div<? 
		if ( JRequest::getVar('view')=='profile'
		     && JRequest::getVar('layout')=='edit'
		   ){?> class="menuActiveEdit"<? 
		}else
			userAccount::selCurrentLink('profile');
		?>><a href="<?=JRoute::_($link_base.'profile')?>">Профиль</a></div>
        <div<? userAccount::selCurrentLink('login','account');?>><a href="<?=$link_base.'login&layout=account'?>">Счёт</a></div>
        <div<? userAccount::selCurrentLink('login');?>><a href="<?=$link_base.'login'?>">Сервис</a></div>
</div>
<?	}
/**
 * Описание
 * @package
 * @subpackage
 */
	public static function selCurrentLink($tView,$tLayout=false){
		$view=JRequest::getVar('view');
		$layout=JRequest::getVar('layout');
		if ( $view == $tView // login
			 && ( (!$layout&&!$tLayout) || $tLayout==$layout ) // account
		   ) echo ' class="menuActive"';
	}
/**
 * Описание
 * @package
 * @subpackage
 */
	static public function buildPaymentTable(){
		JTable::addIncludePath(JPATH_SITE.'/administrator/components/com_application/tables');
		$tbl_name='chado_payments';
		$table =& JTable::getInstance($tbl_name,'ApplicationTable');
		$arrFields=array('id'=>'#','date_time'=>'Дата/время','summ'=>'Сумма','payment_mode'=>'Способ','identity'=>'Информация о платеже','applied'=>'Подтверждение');
		$fields=implode(",",array_keys($arrFields));
		$user = JFactory::getUser();
		$query="SELECT ".$fields." FROM #__".$tbl_name." WHERE user_id = ".$user->get('id')." ORDER BY date_time DESC";
		$db=JFactory::getDBO();
		$db->setQuery($query);
		$rows=$db->loadAssocList();?>
<table class="tblUserData" cellspacing="0">
        	<tr	bgcolor="#B0EE62">
	<?	foreach($arrFields as $key=>$header):?>
    			<th><?=$header?></th>
	<?	endforeach;?>
        		<td><img src="administrator/templates/bluestork/images/menu/icon-16-delete.png" width="16" height="16" /></td>
    		</tr>
    
	<?	foreach($rows as $key=>$data):?>
    		<tr>
		<?	foreach($data as $key=>$content):
				if ($key=='date_time'){
					$dttime=$content[8].$content[9].".".$content[5].$content[6].".".$content=$content[0].$content[1].$content[2].$content[3];
					if ($dttime=='00.00.0000')
						$dttime="<span style='color:red' title='Вы указали время платежа, не соответствующее требуемому формату (ЧЧ:ММ)'>".$dttime."</span>";
					$content="<div title=\"Время платежа: ".substr($content,11,5)."\">".$dttime."</div>";
				}?>
				<td><?=$content?></td>
        <?	endforeach;?>
        		<td><img src="administrator/templates/bluestork/images/menu/icon-16-delete.png" /></td>
            </tr>
	<?	endforeach;?>
        </table>
<form action="<?php echo JRoute::_('index.php?option=com_application'); ?>" method="post" name="paymentForm" id="<?="payment-form"?>" class="form-validate" enctype="multipart/form-data">

  <span class="req"></span>Дата: <? 
		echo JHTML::_('calendar', $value = '', $name='date', $id='date', $format = '%Y-%m-%d', $attribs = array('required'=>'','placeholder'=>'ГГГГ-ММ-ДД'));?>
  &nbsp;
  Время (желательно): 
  <input id="time" name="time" type="text" placeholder="ЧЧ:ММ"> 
  <br><span class="req"></span>Сумма: <input id="summ" name="summ" type="text" size="2" required> .руб
  &nbsp; 
  <span class="req"></span>Способ платежа: <input id="payment_mode" name="payment_mode" type="text" size="38" placeholder="Банк. перевод, эл. деньги и т.п." required>
  <br><span class="req"></span>Информация о платеже (нужна для его идентификации):
<textarea name="identity" id="identity" rows="5" placeholder="Отправитель платежа, название платёжной системы и т.п. сведения, позволяющие идентифицировать вас как плательщика..." required></textarea>
  <button type="submit" class="button">Сообщить!</button>
  &nbsp; &nbsp;  
  <a href="javascript:void()" id="cancel_payment">не сообщать...</a>
  <br>
  <input type="hidden" name="task" value="send_payment" />
</form>
<script>
$(function(){ 
	$('input#time').blur( function(){
			var tVal=$(this).val();
			if (tVal!=''){
				var re = /[^\w:]/g; 
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
				var pId=$(trPayment).children('td').eq(0).text();
				if(!confirm('Удалить проводку?'))
					return false;
				else{
					// POST/GET
					var goUrl="<?=JUri::root()?>index.php?option=com_application&task=delete_payment&id="+pId;
					//alert(goUrl); return false;
					<? 	$t=false;
						if ($t){?>
					window.open(goUrl,'ajax');
					<?	}?>
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
			});
	/*$('form#payment-form').submit( function(){
			alert('go check!');
			return false;
		});*/
});
</script>
        <div class="content_holder">
    <?	if (!$rows):?>
    	Платежей нет...&nbsp; | &nbsp; 
    <?	endif;?>
    	<a href="javascript:void()" id="send_payment"><b>Сообщить о платеже</b></a></div>
	<?	return true; 
	}	
}
