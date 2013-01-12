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
	userAccount::calculateUserAssets($user->id)?>
    <h4 style="float:<?=$bAlign?>;">Ваш <a href="<?=JRoute::_('index.php?option=com_users&layout=account', false)?>">баланс</a>: БАЛАНС</h4>

	<?php if (($this->params->get('logoutdescription_show') == 1 && str_replace(' ', '', $this->params->get('logout_description')) != '')|| $this->params->get('logout_image') != '') : ?>
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
