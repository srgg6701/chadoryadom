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
$items=$this->items;
if (JRequest::getVar('itms')){
	var_dump('<h1>items</h1><pre>',$items,'</pre>'); die();
}
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
            <th style="text-align:left;padding-left: 9px;">
            <?php echo JHtml::_('grid.sort',  'id', /*COM_APPLICATION__CHADO_APP_DATA_FAMILY*/ 'a.user_id', $listDirn, $listOrder); ++$cnt;?> клиента, имя, отчество
            </th>
          	<th>Моб. тел.
            <?php
			// Не пытаться сортировать по этому значению, т.к. это - поле сериализованного массива  
			//echo JHtml::_('grid.sort',  'Моб. тел.', /*COM_APPLICATION__CHADO_APP_DATA_MOBILA*/ 'us.mobila', $listDirn, $listOrder); 
				++$cnt;?>
            </th>
            <th>
            <?php echo JHtml::_('grid.sort',  'Дата, время', /*COM_APPLICATION__CHADO_APP_DATA_NAME*/ 'a.date_time', $listDirn, $listOrder); ++$cnt;?>
            </th>
            <th>
            <?php echo JHtml::_('grid.sort',  'Сумма', /*COM_APPLICATION__CHADO_APP_DATA_MIDDLE_NAME*/ 'a.summ', $listDirn, $listOrder); ++$cnt;?>
            </th>
            <th>
            <?php echo JHtml::_('grid.sort',  'Способ оплаты',/*COM_APPLICATION__CHADO_APP_DATA_CHILD_NAME*/ 'a.payment_mode', $listDirn, $listOrder); ++$cnt;?>
            </th>
            <th>
            <?php echo JHtml::_('grid.sort',  'Информация о платеже', /*COM_APPLICATION__CHADO_APP_DATA_KINDERGARTEN*/ 'a.identity', $listDirn, $listOrder); ++$cnt;?>
            </th>
          <th align="center" class="command">
            <?php echo JHtml::_('grid.sort',  'ОК?', /*COM_APPLICATION__CHADO_APP_DATA_GROUP*/ 'a.applied', $listDirn, $listOrder); ++$cnt;?>
            </th>
            <th align="center" class="command"><img src="templates/bluestork/images/menu/icon-16-delete.png" />
            <?php ++$cnt;?>
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
    <?	if ($items){?>
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

            <td align="right">
                <?php echo $item->id; ?>
            </td>
            <td><?php 
			echo $this->escape($item->user_id).", ";
			$fdata=$item->data;?><a href="            index.php?option=com_users&view=user&layout=edit&id=<?=$item->user_id?>"><? echo $item->name." ".$item->data['middle_name']; ?></a>
            </td>
            <td>
	<?php	echo $item->data['mobila']; ?>
            </td>
            <td>
                <?php echo $item->date_time; ?>
            </td>
            <td align="right">
                <?php echo $item->summ; ?>
            </td>
            <td>
                <?php echo $item->payment_mode; ?>
            </td>
            <td>
                <?php echo $item->identity; ?>
            </td>
            <td align="center">
                <?php 
				$ok=$item->applied;
				if ($ok=="?"){?><a href="#"><b><?=$ok?></b></a><? }
				else echo $ok; ?>
            </td>
            <td align="center"><img src="templates/bluestork/images/menu/icon-16-delete.png" /></td>
        </tr>
<?php 	} ?>
    </tbody>
	<?	}?>
  </table>
	<?	if(!$items):?>
    	<h4>Платежей нет...</h4>
    <?	endif;?>
  <div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="layout" value="payments" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
<form action="<?php echo JRoute::_('index.php?option=com_application'); ?>" method="post" name="paymentForm" id="<?="payment-form"?>" class="form-validate" enctype="multipart/form-data">
	<span class="req"></span>
    User ID: 
    <select name="user_id" id="user_id">
    	<option value="0">-Выберите из списка:-</option>
    <? 	foreach ($this->clientsArray as $user_id=>$user_data):?>
    	<option value="<?=$user_id?>">id: <?=$user_id?>, <?=$user_data['name']." ".$user_data['middle_name']?>, имя чада: <?=$user_data['child_name']?>, моб.тел.: <?=$user_data['mobila']?></option>
	<?	endforeach;?>
    </select>
    <hr size="1" noshade style="margin:14px 0;">
  <span class="req"></span>Дата: <? 
		echo JHTML::_('calendar', $value = '', $name='date', $id='date', $format = '%Y-%m-%d', $attribs = array('required'=>'','placeholder'=>'ГГГГ-ММ-ДД'));?>
  &nbsp;
  Время: 
  <input id="time" name="time" type="text" placeholder="ЧЧ:ММ"> 
  <br><span class="req"></span>Сумма: <input id="summ" name="summ" type="text" size="2" required> .руб
  &nbsp; 
  <span class="req"></span>Способ зачисления: <input id="payment_mode" name="payment_mode" type="text" size="38" required>
  <br><span class="req"></span>Информация о платеже:
<textarea name="identity" id="identity" rows="5"></textarea>
  <button id="btnAdd" type="submit" class="button">Добавить!</button>
  &nbsp; &nbsp;  
  <a href="javascript:void()" id="cancel_payment">передумать...</a>
  <br>
  <input type="hidden" name="task" value="add_payment" />
</form>
<a href="javascript:void()" id="send_payment"><b>Добавить платёж</b></a></div>
<? $app =& JFactory::getApplication();
$template = $app->getTemplate();
$gotmpl=JUri::base().'templates/'.$template;?>
<script src="<?=$gotmpl?>/js/check_payment_data.js"></script>