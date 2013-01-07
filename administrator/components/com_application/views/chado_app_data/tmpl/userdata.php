USERDATA<?php 
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
</form><? */
$userdata=$this->userdata; 
var_dump("<h1>userdata:</h1><pre>",$userdata,"</pre>");?>
