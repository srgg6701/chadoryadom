<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
$canDo = UsersHelper::getActions();

// Get the form fieldsets.
$fieldsets = $this->form->getFieldsets();
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'user.cancel' || document.formvalidator.isValid(document.id('user-form'))) {
			Joomla.submitform(task, document.getElementById('user-form'));
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_users&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="user-form" class="form-validate" enctype="multipart/form-data">
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_USERS_USER_ACCOUNT_DETAILS'); ?></legend>
			<?php foreach($this->form->getFieldset('user_details') as $field) :?>
				<li><?php echo $field->label; ?>
				<?php echo $field->input; ?></li>
			<?php endforeach; ?>
			</ul>
		</fieldset>
		<!--	xtra data - APPLICATION	-->
        <!--	только если уже зарегистрирован! -->
      	<? 	if ($this->item->id):?>
        <fieldset class="adminform">
<?	require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_application'.DS.'helpers'.DS.'chado_app_data.php';?>        
			<legend><?php echo JText::_('Параметры заявки'); //COM_USERS_USER_ACCOUNT_DETAILS ?></legend>
			<ul class="adminformlist">
<?	$arrTable=ApplicationHelper::getAppFields();
	$arrTable['password']="Пароль";
	$arrAppData=ApplicationHelper::getUserServiceData(JRequest::getVar('id'));
	$xtra_fields_for_controller=array(); // контейнер для сохранения значений поля data. Будет отсылаться контроллеру (в виде строки) для того, чтобы он понял, что это - данные, которые должны быть сохранены в этом поле в виде сериализованного массива.  
	foreach($arrAppData as $label=>$desc):
		$xtra_fields_for_controller[]=$label;?>
    <li>
    	<label id="jform_<?=$label?>-lbl" for="jform_<?=$desc?>" aria-invalid="false"><?=$arrTable[$label]?></label>
    <? 	if($label=="password"):?>
<input type="text" value="<?=$arrAppData[$label]?>" class="inputbox" size="30" aria-invalid="false" style="border:none;">
	<?	else:?>
<input type="text" name="jform[<?=$label?>]" id="jform_<?=$label?>" value="<?=$arrAppData[$label]?>" autocomplete="off" class="inputbox" size="30" aria-invalid="false"><?
		endif;?></li>
<? 	endforeach;?>
			</ul>
<input name="jform[xtra]" id="jform_xtra" type="hidden" value="<?=implode(",",$xtra_fields_for_controller)?>">
		</fieldset>
		<!--	/xtra data - APPLICATION	-->
        <?	endif;?>
		<?php if ($this->grouplist) :?>
		<fieldset id="user-groups" class="adminform">
			<legend><?php echo JText::_('COM_USERS_ASSIGNED_GROUPS'); ?></legend>
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
</form>
