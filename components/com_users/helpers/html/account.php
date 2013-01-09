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
    	<div<? userAccount::selCurrentLink('profile');?>><a href="<?=JRoute::_($link_base.'profile')?>">Профиль</a></div>
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
}
