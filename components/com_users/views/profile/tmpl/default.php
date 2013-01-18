<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @since		1.6
 */

defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
?>
<div class="profile<?php echo $this->pageclass_sfx?>">
<?php if ($this->params->get('show_page_heading')) : ?>
<h1>
	<?php echo $this->escape($this->params->get('page_heading')); ?>
</h1>
<?php endif; ?>
<?	$user_id=$this->data->id;
	userAccount::accountManager($this->params->get('logout_redirect_url', $this->form->getValue('return')));
	$userTableData=userAccount::getUserFromTable($user_id);?>    
<?php //echo $this->loadTemplate('core'); ?>
<?php //echo $this->loadTemplate('params'); ?>
<?php //echo $this->loadTemplate('custom'); ?>
    <div style="float:left; width:20%;">
        <div style="background:#CCC; border-radius:8px; margin-bottom:10px; padding:10px;">&nbsp;</div>
        <div align="center" style="background:#CCC; border-radius:8px; padding:4px;">Загрузить</div>
    </div>
    <div style="float:left; width:80%;">
    <table id="userTable">
      <tr>
        <td>Фамилия</td>
        <td><?=$userTableData['family']?></td>
        </tr>
      <tr>
        <td>Имя</td>
        <td><?=$userTableData['name']?></td>
        </tr>
      <tr>
        <td>Отчество</td>
        <td><?=$userTableData['middle_name']?></td>
        </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        </tr>
      <tr>
        <td>Баланс</td>
        <td><? 
		$assetsData=userAccount::calculateUserAssets($user_id);
		echo $assetsData['balance'];?> руб. (<a href="<?
		echo JRoute::_("index.php?option=com_users&view=login&layout=account&do=add");
		?>">пополнить</a>)</td>
        </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        </tr>
      <tr>
        <td>Моб.телефон</td>
        <td id="tdMobila"><span id="cellMobila"><span id="mobila_value"><?=$userTableData['mobila']?></span> (<a href="javascript:void()" id="edit_mobila">изменить</a>)</span></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        </tr>
      <tr>
        <td>Учётные данные для доступа к видео</td>
        <td><a href="javascript:void()" id="show_video_data">Показать/скрыть</a></td>
        </tr>
<?php	$arrCamsData=ApplicationHelper::getAppCameraScriptData($user_id); 
		if (JFactory::getUser()->id == $this->data->id) : ?>
      <tr style="display:none;" id="video_data">
        <td colspan="2"><?
        
		$arrCamsData=ApplicationHelper::getAppCameraScriptData($user_id);?>
		 <div style="background:#CF9; padding:10px; border-radius:8px;">
         	<table width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td>Server IP:</td>
    <td><?=$arrCamsData['server']?></td>
  </tr>
  <tr>
    <td>Port:</td>
    <td><?=$arrCamsData['port']?></td>
  </tr>
  <tr>
    <td>Login:</td>
    <td><?=$arrCamsData['script_login']?></td>
  </tr>
  <tr>
    <td>Password:</td>
    <td><?=$arrCamsData['script_password']?></td>
  </tr>
  <tr>
    <td>Камера 1:</td>
    <td><?=($arrCamsData['camera 1']!='')? $arrCamsData['camera 1']:'Не установлена'?></td>
  </tr>
  <tr>
    <td>Камера 2:</td>
    <td><?=($arrCamsData['camera 2']!='')? $arrCamsData['camera 2']:'Не установлена'?></td>
  </tr>
  <tr>
    <td>Камера 3:</td>
    <td><?=($arrCamsData['camera 3']!='')? $arrCamsData['camera 3']:'Не установлена'?></td>
  </tr>
  <tr>
    <td>Камера 4:</td>
    <td><?=($arrCamsData['camera 4']!='')? $arrCamsData['camera 4']:'Не установлена'?></td>
  </tr>
  <tr>
    <td>Звук:</td>
    <td><?=($arrCamsData['sound'])? 'Включён':'Выключен'?></td>
  </tr>
</table>
         </div>
		
		</td>
      </tr>
      <tr>
        <td><br>
    <a href="<?php echo JRoute::_('index.php?option=com_users&task=profile.edit&user_id='.(int) $this->data->id);?>">
        <?php echo JText::_('COM_USERS_Edit_Profile'); ?></a>
    </td>
        <td>&nbsp;</td>
      </tr>
    <?php endif; ?>
    </table>
            <div class="cleared"></div>
  </div>
</div>
<script>
$(function(){
	var mobila='',spanMobila=$('span#cellMobila');
	var tdContent=$('td#tdMobila').html();
	$('a#edit_mobila').click( function(){
		if ($(spanMobila).css('display')!='none'){
			var inputMobila=$('<input>',{
					id:'mobila',
					name:'mobila',
				}).css({
					fontSize:'13px',
					margin:'-3px',
					marginRight:'4px',
					width:'80px',
				}).attr({
						type:'text',
						value:$('#mobila_value').text()
					});
			var OK=$('<a>',{
					id:'changeMobila',
					href:'javascript:void()'
				});
			var space=$('<span>',{
					id:'space',
				});
			var cancel=$('<a>',{
					id:'changeMobilaCancel',
					href:'javascript:void()'
				});
			$(OK).text('OK');
			$(space).html('&nbsp; &nbsp;');
			$(cancel).text('Отмена');
			$(spanMobila).hide();
			$('td#tdMobila').append(inputMobila,OK,space,cancel);
		}
		return false;
	});
	$('a#changeMobila').live('click',function(){
			var goTask='index.php?option=com_users&task=profile.changeMobila&mobila='+$('input#mobila').val();
			//alert(goTask);
			location.href=goTask;
		});
	$('a#changeMobilaCancel').live('click',function(){
			$(spanMobila).show();
			$('input#mobila').remove();
			$('a#changeMobila').remove();
			$('#space').remove();
			$('a#changeMobilaCancel').remove();
		});
	$('#show_video_data').click( function(){
			$('#video_data').fadeToggle(500);
		});
})
</script>