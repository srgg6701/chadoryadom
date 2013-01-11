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