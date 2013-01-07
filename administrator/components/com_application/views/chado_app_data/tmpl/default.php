<?php echo "<?xml version=\"1.0\" encoding=\"utf-8\"?".">"; ?>
<?php
/**
 * @version     2.1.0
 * @package     com_application
 * @copyright   Copyright (C) webapps 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      srgg <srgg67@gmail.com> - http://www.facebook.com/srgg67
 */


// no direct access
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
				<?php echo JHtml::_('grid.sort',  'id', /*COM_APPLICATION__CHADO_APP_DATA_ID*/ 'a.id', $listDirn, $listOrder); ++$cnt;?>
				</th>
				<th>
				<?php echo JHtml::_('grid.sort',  'Фамилия', /*COM_APPLICATION__CHADO_APP_DATA_FAMILY*/ 'a.family', $listDirn, $listOrder); ++$cnt;?>
				</th>
				<th>
				<?php echo JHtml::_('grid.sort',  'Имя', /*COM_APPLICATION__CHADO_APP_DATA_NAME*/ 'a.name', $listDirn, $listOrder); ++$cnt;?>
				</th>
				<th>
				<?php echo JHtml::_('grid.sort',  'Отчество', /*COM_APPLICATION__CHADO_APP_DATA_MIDDLE_NAME*/ 'a.middle_name', $listDirn, $listOrder); ++$cnt;?>
				</th>
				<th>
				<?php echo JHtml::_('grid.sort',  'Имя ребёнка',/*COM_APPLICATION__CHADO_APP_DATA_CHILD_NAME*/ 'a.child_name', $listDirn, $listOrder); ++$cnt;?>
				</th>
				<th>
				<?php echo JHtml::_('grid.sort',  'Детский сад', /*COM_APPLICATION__CHADO_APP_DATA_KINDERGARTEN*/ 'a.kindergarten', $listDirn, $listOrder); ++$cnt;?>
				</th>
				<th>
				<?php echo JHtml::_('grid.sort',  'Группа', /*COM_APPLICATION__CHADO_APP_DATA_GROUP*/ 'a.group', $listDirn, $listOrder); ++$cnt;?>
				</th>
                <th>
				<?php echo JHtml::_('grid.sort',  'E-mail', /*COM_APPLICATION__CHADO_APP_DATA_EMAIL*/ 'a.email', $listDirn, $listOrder); ++$cnt;?>
                </th>
              <th>
				<?php echo JHtml::_('grid.sort',  'Моб. тел.', /*COM_APPLICATION__CHADO_APP_DATA_MOBILA*/ 'a.mobila', $listDirn, $listOrder); ++$cnt;?>
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
					<?php echo $item->id; ?>
				</td>
				<td>
				<?php 
				if ($canEdit) : ?>
					<a href="<?php echo JRoute::_('index.php?option=com_application&task=child_app_data.edit&id='.(int) $item->id); ?>">
					<?php echo $this->escape($item->family); ?></a>
				<?php else :
					echo $this->escape($item->family);
				endif; ?>
				</td>
				<td>
					<?php echo $item->name; ?>
				</td>
				<td>
					<?php echo $item->middle_name; ?>
				</td>
				<td>
					<?php echo $item->child_name; ?>
				</td>
				<td>
					<?php echo $item->kindergarten; ?>
				</td>
				<td>
					<?php echo $item->group; ?>
				</td>
				<td>
					<?php echo $item->email; ?>
				</td>
				<td>
					<?php echo $item->mobila; ?>
				</td>
			</tr>
	<?php 	} ?>
		</tbody>
	<?	}?>
  </table>
	<?	if(!$items):?>
    	<h4>Заявок нет...</h4>
    <?	endif;?>
  <div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>