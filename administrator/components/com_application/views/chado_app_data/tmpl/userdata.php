<?php 
/**
 * @package		Joomla.Administrator
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');

// Load the tooltip behavior.
//JHtml::_('behavior.tooltip');
//JHtml::_('behavior.formvalidation');
//$canDo = ApplicationHelper::getActions();
$document = &JFactory::getDocument();
$document->addStyleSheet('components/com_application/assets/css/application.css');
// Get the form fieldsets.
//$fieldsets = $this->form->getFieldsets();

/*?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'user.cancel' || document.formvalidator.isValid(document.id('user-form'))) {
			Joomla.submitform(task, document.getElementById('user-form'));
		}
	}
</script>
<?	?>
<form action="<?php echo JRoute::_('index.php?option=com_application&layout=userdata&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="user-form" class="form-validate" enctype="multipart/form-data">
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_APPLICATION_USER_ACCOUNT_DETAILS'); ?></legend>
			<ul class="adminformlist">
			<?php foreach($this->form->getFieldset('user_details') as $field) :?>
				<li><?php echo $field->label; ?>
				<?php echo $field->input; ?></li>
			<?php endforeach; ?>
			</ul>
		</fieldset>

		<?php if ($this->grouplist) :?>
		<fieldset id="user-groups" class="adminform">
			<legend><?php echo JText::_('COM_APPLICATION_ASSIGNED_GROUPS'); ?></legend>
			<?php echo $this->loadTemplate('groups');?>
		</fieldset>
		<?php endif; ?>
	</div>

	<div class="width-40 fltrt">
		<?php
		echo JHtml::_('sliders.start');
		foreach ($fieldsets as $fieldset) :
			if ($fieldset->name == 'user_details') :
				continue;
			endif;
			echo JHtml::_('sliders.panel', JText::_($fieldset->label), $fieldset->name);
		?>
		<fieldset class="panelform">
		<ul class="adminformlist">
		<?php foreach($this->form->getFieldset($fieldset->name) as $field): ?>
			<?php if ($field->hidden): ?>
				<?php echo $field->input; ?>
			<?php else: ?>
				<li><?php echo $field->label; ?>
				<?php echo $field->input; ?></li>
			<?php endif; ?>
		<?php endforeach; ?>
		</ul>
		</fieldset>
		<?php endforeach; ?>
		<?php echo JHtml::_('sliders.end'); ?>

		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
<? */

$userdata=$this->userdata;
$fields_names=$this->fields;?>
<form action="<?php echo JRoute::_('index.php?option=com_application&id='.(int) $userdata['id']); ?>" method="post" name="adminForm" id="app-form" class="form-validate" enctype="multipart/form-data">
<div id="app_data_fields">
<input type="hidden" id="cb0" name="cid[]" value="<?=$userdata['id']?>" onclick="Joomla.isChecked(this.checked);" checked>
<?	$i=0;
//
foreach($userdata as $name=>$value):?>
<?="<b>".$fields_names[$name].":</b>";
	if (!$i):
		echo " ".$value;
	else:?>
<input name="<?=$name?>" type="text" required id="<?=$name?>" value="<?=$value?>"<?
if ($i==0||$i==5||$i==6){?> size="2"<? }?>>
<?	endif;
	if (!$i||$i==3||$i==6)
		echo '<hr size="1" color="#CCCCCC">';
	$i++;
endforeach; 
?>
<hr size="1" color="#CCCCCC">
<button type="submit" onClick="return handleForm();">Подтвердить</button>
</div>
<input type="hidden" name="task" value="application.update" />
<input type="hidden" name="boxchecked" value="1" />
<?php echo JHtml::_('form.token'); ?>
</form>

<script src="templates/bluestork/js/validation.js">
</script>