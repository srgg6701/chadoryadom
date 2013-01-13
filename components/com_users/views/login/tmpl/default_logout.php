<?php	// die('LOGOUT');
/**
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @since		1.5
 */

defined('_JEXEC') or die;
$user = JFactory::getUser();
$userdata=unserialize($user->data);?>
<div class="logout<?php echo $this->pageclass_sfx?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
	<h1>
		<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
	<?php endif; ?>
<?	userAccount::accountManager($this->params->get('logout_redirect_url', $this->form->getValue('return')));
	$session =& JFactory::getSession();
	if(!$session->get('greeting')||JRequest::getVar('gr')):?>
	<h4 style="float:left;">Здравствуйте, <?=$user->name." ".$userdata['middle_name']?>!</h4>
<?		$session->set('greeting',true);
		$bAlign='right';
	else:
		$bAlign='left';	
	endif;
	$assetsData=userAccount::calculateUserAssets($user->id)?>
    <h4 style="float:<?=$bAlign?>;">Ваш <a href="<?=JRoute::_('index.php?option=com_users&layout=account', false)?>" title="Перейти в раздел истории проводок">баланс</a>: <a style="color:#FF9900;" href="javascript:void()" id="expandBalance" title="Подробности..."><?=$assetsData['balance']?></a> руб.</h4>
    <div class="cleared"></div>
  <div id="balance_details" style="display:<?="none"?>;">
      <div class="content_holder" style="background:#CFC; border-radius:8px; display:inline-block">
    	<div>Сумма подтверждённых платежей: <?=$assetsData['applied']?> руб.</div>
        <div>Дней предоставления услуг: <?=$assetsData['days_passed']?></div>
        <div>Списано: <?=$assetsData['paid']?> руб.</div>
      </div>
    </div>
    <hr size="1" noshade>
	<br>
    <div>
    <strong>Инструкции по настройке сервиса для:</strong>
<ul>
      <li><a href="<?=JRoute::_('index.php?option=com_users&layout=manual&device=iphone', false)?>">IPhone</a></li>
        <li><a href="<?=JRoute::_('index.php?option=com_users&layout=manual&device=android', false)?>">Android</a></li>
      </ul>
    </div>
<?	if(!$arrCamsData=ApplicationHelper::getAppCameraScriptData($user->id)):?>
	<h5>Данные подключения не установлены. Обратитесь к администрации.</h5>
<?	else:?>
<br>
<script type="text/javascript" src="http://www.devline.ru/js/swfobject.js"></script>	
<?		function setScript($cam_user_number,$cam_index,$script_params){?>
	<h4>Камера <?=$cam_user_number?>.</h4>
	<div class="videoBox" style="border:solid 1px #CCC;">

<script type="text/javascript">
var flashvars={};
var attributes={
		allowfullscreen:"true",
		menu:"false",
		quality:"hight"
	};
var params={
		value:"ip=<? 
			echo $script_params['server'];?>,port=<?
			echo $script_params['port'];?>,login=<?
			echo $script_params['script_login'];?>,pass=<?
			echo $script_params['script_password'];?>,uriCamera=/cameras/<?
			echo $cam_index;?>,quality=60,fps=8,sound=<?
			echo $script_params['sound'];?>,ptz=true,logo=false"
	};
swfobject.embedSWF("http://www.devline.ru/miniflash.swf","devline_639","640","480","9.0.115",flashvars,params,attributes);
</script>

<object type="application/x-shockwave-flash" data="http://www.devline.ru/miniflash.swf" width="640" height="480" id="devline_639" style="visibility: visible;">

<param name="allowfullscreen" value="true">
<param name="menu" value="false">
<param name="quality" value="hight">
<param name="flashvars" value="value=ip=<?
			echo $script_params['server'];?>,port=<?
			echo $script_params['port'];?>,login=<?
			echo $script_params['script_login'];?>,pass=<?
			echo $script_params['script_password'];?>,uriCamera=/cameras/<?
			echo $cam_index;?>,quality=60,fps=8,sound=<?
			echo $script_params['sound'];?>,ptz=true,logo=false">
</object> 
	</div>
			
	<?	}
	endif;
	$script_params=array(
						'server'=>$arrCamsData['server'],
						'port'=>$arrCamsData['port'],
						'script_login'=>$arrCamsData['script_login'],
						'script_password'=>$arrCamsData['script_password'],
						'sound'=>$arrCamsData['sound'],
					);
	
	foreach ($arrCamsData as $key=>$data):
		if (strstr($key,'camera ')):
			$cmnmb=explode(" ",$key);
			if ($data!='') 
				setScript( 	// значения аргументов теоретически могут не совпадать
							array_pop($cmnmb), 	// string
						   	$data,				// number
							$script_params
						 );
			//var_dump('<h1>camera ('.gettype((int)array_pop($cmnmb)).')'.(int)array_pop($cmnmb).', script_params:</h1><pre>',$script_params,'</pre>');
		endif;
	endforeach;	/*?>
	<h4>Камера 1.</h4>
	<div class="videoBox" style="border:solid 1px #CCC;">
<script type="text/javascript" src="http://www.devline.ru/js/swfobject.js"></script>

<script type="text/javascript">
var flashvars={};
var attributes={
		allowfullscreen:"true",
		menu:"false",
		quality:"hight"
	};
var params={
		value:"ip=37.8.158.72,port=9786,login=admin,pass=str0890full,uriCamera=/cameras/0,quality=60,fps=8,sound=true,ptz=true,logo=false"
	};
swfobject.embedSWF("http://www.devline.ru/miniflash.swf","devline_639","640","480","9.0.115",flashvars,params,attributes);
</script>

<object type="application/x-shockwave-flash" data="http://www.devline.ru/miniflash.swf" width="640" height="480" id="devline_639" style="visibility: visible;">

<param name="allowfullscreen" value="true">
<param name="menu" value="false">
<param name="quality" value="hight">
<param name="flashvars" value="value=ip=37.8.158.72,port=9786,login=admin,pass=str0890full,uriCamera=/cameras/0,quality=60,fps=8,sound=true,ptz=true,logo=false">

</object> 
	</div>



<?php */
	
	if (($this->params->get('logoutdescription_show') == 1 && str_replace(' ', '', $this->params->get('logout_description')) != '')|| $this->params->get('logout_image') != '') : ?>
    <div class="logout-description">
	<?php endif ; ?>

		<?php if ($this->params->get('logoutdescription_show') == 1) : ?>
			<?php echo $this->params->get('logout_description'); ?>
		<?php endif; ?>

		<?php if (($this->params->get('logout_image')!='')) :?>
			<img src="<?php echo $this->escape($this->params->get('logout_image')); ?>" class="logout-image" alt="<?php echo JTEXT::_('COM_USER_LOGOUT_IMAGE_ALT')?>"/>
		<?php endif; ?>

	<?php if (($this->params->get('logoutdescription_show') == 1 && str_replace(' ', '', $this->params->get('logout_description')) != '')|| $this->params->get('logout_image') != '') : ?>
	</div>
	<?php endif ; 
	$button='hide';
	if ($button!='hide'):?>
	<form action="<?php echo JRoute::_('index.php?option=com_users&task=user.logout'); ?>" method="post">
		<div>
			<button type="submit" class="button"><?php echo JText::_('JLOGOUT'); ?></button>
			<input type="hidden" name="return" value="<?php echo base64_encode($this->params->get('logout_redirect_url', $this->form->getValue('return'))); ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
<?	endif;?>
</div>
<script>
$(function(){
		$('a#expandBalance').click(function(){
				$('div#balance_details').fadeToggle(300);
			});
	})
</script>