<?php 
/**
 * @package		Joomla.Administrator
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHTML::_('script','system/multiselect.js',false,true);
// Import CSS
$document = &JFactory::getDocument();
$document->addStyleSheet('components/com_application/assets/css/application.css');

$user	= JFactory::getUser();
$userId	= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$canOrder	= $user->authorise('core.edit.state', 'com_application');
$saveOrder	= $listOrder == 'a.ordering';
?>
<form action="<?php echo JRoute::_('index.php?option=com_application'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('Search'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select fltrt">

        <? 	$allow_state=false;
			if($allow_state)
				if($state=$this->state):?>
                <select name="filter_published" class="inputbox" onchange="this.form.submit()">
                    <option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
                    <?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), "value", "text", $state->get('filter.state'), true);?>
                </select>
          	<?	endif;?>      

		</div>
	</fieldset>
  <div class="clr"> </div>
	<? $cnt=0;?>
  <table class="adminlist">
	<thead>
        <tr>
            <th width="1%">
                <input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" /><? ++$cnt;?>
            </th>
            <th>
            <?php echo JHtml::_('grid.sort',  'Опция', /*COM_APPLICATION__CHADO_APP_DATA_FAMILY*/ 'a.option_name', $listDirn, $listOrder); ++$cnt;?> 
            </th>
            <th>
            <?php echo JHtml::_('grid.sort',  'Значение', /*COM_APPLICATION__CHADO_APP_DATA_NAME*/ 'a.value', $listDirn, $listOrder); ++$cnt;?>
            </th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <td colspan="<?=$cnt?>">
                <?php 
                if ($pgn=$this->pagination)
                    echo $pgn->getListFooter(); ?>
            </td>
        </tr>
    </tfoot>
    <?	if ($items=$this->items){?>
    <tbody>
    <?php 
        foreach ($items as $i => $item) {
            $canCreate	= $user->authorise('core.create',		'com_application');
            $canEdit	= $user->authorise('core.edit',			'com_application');
            $canCheckin	= $user->authorise('core.manage',		'com_application');
            $canChange	= $user->authorise('core.edit.state',	'com_application');
        ?>
        <tr class="row<?php echo $i % 2; ?>">
          <td class="center">
                <?php echo JHtml::_('grid.id', $i, $item->id); ?>
            </td>

            <td>
                <?php echo $item->option_name; ?>
            </td>
            <td>
            	<input name="value[<?=$item->id?>]" type="text" id="value[<?=$item->id?>]" value="<?php echo $item->value; ?>">
            </td>
        </tr>
<?php 	} ?>
    </tbody>
	<?	}?>
  </table>
	<?	if(!$items):?>
  <h4>Опции не установлены...</h4>
    <?	endif;?>
  <div>
		<input type="hidden" name="task" value="save" />
		<input type="hidden" name="layout" value="settings" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
  </div>
</form>
