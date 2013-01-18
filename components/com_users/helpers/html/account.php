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
$gotmpl=JUri::base().'templates/'.$template;
$link=$gotmpl.'/css/application_form.css';
echo '<link href="'.$link.'" rel="stylesheet" type="text/css">';
	
class userAccount
{
	public static function accountManager($params) {	
		
		$link_base="index.php?option=com_users&view=";
														?>
        
<div style="position:relative">
    <form id="formGoLogout" action="<?php echo JRoute::_('index.php?option=com_users&task=user.logout'); ?>" method="post">
		<div>
			<button type="submit" class="button"><?php echo JText::_('JLOGOUT'); ?></button>
			<input type="hidden" name="return" value="<?php echo base64_encode($params); ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>
<!--<div>Меню личного кабинета</div>-->		
<div class="account menu">
    	<div<? 
		if ( JRequest::getVar('view')=='profile'
		     && JRequest::getVar('layout')=='edit'
		   ){?> class="menuActiveEdit"<? 
		}else
			userAccount::selCurrentLink('profile');
		?>><a href="<?=JRoute::_($link_base.'profile')?>">Профиль</a></div>
        <div<? userAccount::selCurrentLink('login','account');?>><a href="<?=$link_base.'login&layout=account'?>">Счёт</a></div>
        <div<? userAccount::selCurrentLink('login');?>><a href="<?=$link_base.'login'?>">Сервис</a></div>
        <div<? userAccount::selCurrentLink('profile','manuals');?>><a href="<?=$link_base.'profile&layout=manuals'?>">Для моб. устройств</a></div>
</div>
<?	}
/**
 * Построить таблицу платежей
 * @package
 * @subpackage
 */
	static public function buildPaymentTable(){
		JTable::addIncludePath(JPATH_SITE.'/administrator/components/com_application/tables');
		$tbl_name='chado_payments';
		$table =& JTable::getInstance($tbl_name,'ApplicationTable');
		$arrFields=array('id'=>'#','date_time'=>'Дата/время','summ'=>'Сумма','payment_mode'=>'Способ','identity'=>'Информация о платеже','applied'=>'Подтверждение');
		$fields=implode(",",array_keys($arrFields));
		$user = JFactory::getUser();
		$rows=userAccount::getUserAssets($user->get('id'),$fields);?>
<table class="tblUserData" cellspacing="0">
        	<tr	bgcolor="#B0EE62">
	<?	foreach($arrFields as $key=>$header):?>
    			<th><?=$header?></th>
	<?	endforeach;?>
        		<th class="command"><img src="administrator/templates/bluestork/images/menu/icon-16-delete.png" width="16" height="16" /></th>
    		</tr>
    
	<?	foreach($rows as $key=>$data):?>
    		<tr>
		<?	$i=0;
			foreach($data as $key=>$content):
				if ($key=='date_time'){
					$dttime=$content[8].$content[9].".".$content[5].$content[6].".".$content=$content[0].$content[1].$content[2].$content[3];
					if ($dttime=='00.00.0000')
						$dttime="<span style='color:red' title='Вы указали время платежа, не соответствующее требуемому формату (ЧЧ:ММ)'>".$dttime."</span>";
					$content="<div title=\"Время платежа: ".substr($content,11,5)."\">".$dttime."</div>";
				}
				if ($key=='applied') 
					$content=($content=='0')? 'нет...':'ОК';?>
				<td<? 
				if(!$i||$i==2){?> align="right"<? }
				elseif ($i==5) echo ' align="center"';?>><?=$content?></td>
        <?		$i++;
			endforeach;?>
        		<td><img src="administrator/templates/bluestork/images/menu/icon-16-delete.png" /></td>
            </tr>
	<?	endforeach;?>
        </table>
<form action="<?php echo JRoute::_('index.php?option=com_application'); ?>" method="post" name="paymentForm" id="<?="payment-form"?>" class="form-validate" enctype="multipart/form-data"<?
	if(JRequest::getVar('do')=='add'):
	?> style="display:block;"<?
	endif;
?>>

  <span class="req"></span>Дата: <? 
		echo JHTML::_('calendar', $value = '', $name='date', $id='date', $format = '%Y-%m-%d', $attribs = array('required'=>'','placeholder'=>'ГГГГ-ММ-ДД'));?>
  &nbsp;
  Время (желательно): 
  <input id="time" name="time" type="text" placeholder="ЧЧ:ММ"> 
  <br><span class="req"></span>Сумма: <input id="summ" name="summ" type="text" size="2" required> .руб
  &nbsp; 
  <span class="req"></span>Способ платежа: <input id="payment_mode" name="payment_mode" type="text" size="38" placeholder="Банк. перевод, эл. деньги и т.п." required>
  <br><span class="req"></span>Информация о платеже (нужна для его идентификации):
<textarea name="identity" id="identity" rows="5" placeholder="Отправитель платежа, название платёжной системы и т.п. сведения, позволяющие идентифицировать вас как плательщика..." required></textarea>
  <button type="submit" class="button">Сообщить!</button>
  &nbsp; &nbsp;  
  <a href="javascript:void()" id="cancel_payment">не сообщать...</a>
  <br>
  <input type="hidden" name="task" value="send_payment" />
</form>
        <div class="content_holder">
    <?	if (!$rows):?>
    	Платежей нет...&nbsp; | &nbsp; 
    <?	endif;?>
    	<a href="javascript:void()" id="send_payment"><b>Сообщить о платеже</b></a></div>
<? $app =& JFactory::getApplication();
$template = $app->getTemplate();
$gotmpl=JUri::base().'templates/'.$template;?>
<script src="<?=$gotmpl?>/js/check_payment_data.js"></script>
	<?	return true; 
	}	
/**
 * Описание
 * @package
 * @subpackage
 */
	static public function calculateUserAssets($user_id){
		$query="SELECT SUM(summ) 
  FROM #__chado_payments
 WHERE user_id = ".$user_id." AND applied <> 0";
		$db=JFactory::getDBO();
		$db->setQuery($query);
		$total_sum=$db->loadResult(); 
		$first_payment_date=userAccount::getBorderUserPaymentDate($user_id);
		if(strstr($_SERVER['HTTP_HOST'],"localhost")){
			$date_start_value = new DateTime($first_payment_date);
			$today = new DateTime(date("Y-m-d H:i:s"));
			$time_passed = $today->diff($date_start_value);
			require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_application'.DS.'helpers'.DS.'chado_app_data.php';
			$monthly_sum=ApplicationHelper::getSettings('monthly_sum');
			$day_payment_summ=(int)$monthly_sum['monthly_sum']['value']*12/365;
			$days_passed=$time_passed->d;
			
		}else{
			// 2012-12-29   0 7 : 3 0 : 3 7
			// 0123456789101112131415161718
			$year=$first_payment_date[0].
				  $first_payment_date[1].
				  $first_payment_date[2].
				  $first_payment_date[3];
			$days=$first_payment_date[8].$first_payment_date[9];
			$months=$first_payment_date[5].$first_payment_date[6];
			$hours=$first_payment_date[11].$first_payment_date[12];
			$minutes=$first_payment_date[14].$first_payment_date[15];
			$seconds=$first_payment_date[17].$first_payment_date[18];
			
			$timeX = mktime((int)$seconds, (int)$minutes, (int)$hours, (int)$months, (int)$days, (int)$year); //s:i:H m-d-Y
			$timeNow = time();
			$delta = $timeNow - $timeX;
			$days_passed=floor($delta/(24*60*60));
		}
		$cut_assets=$days_passed*$day_payment_summ;
		$balance=$total_sum-$cut_assets;
		$assetsData=array(
					'applied'=>$total_sum,
					'days_passed'=>$days_passed,
					'paid'=>round($cut_assets),
					'balance'=>round($balance),
				);
		return $assetsData;
	}
/**
 * Получить последний платёж юзера
 * @package
 * @subpackage
 */
	function getBorderUserPaymentDate($user_id,$border='MIN'){
		$query="SELECT ".$border."(date_time) 
  FROM #__chado_payments
 WHERE user_id = ".$user_id;
		$db=JFactory::getDBO();
		$db->setQuery($query);
		return $db->loadResult(); 
	}
/**
 * Описание
 * @package
 * @subpackage
 */
	static public function getArticleContent($id){
		$query = "SELECT * FROM #__content WHERE id = 2";
		//  Load query into an object
		$db = JFactory::getDBO();
		$db->setQuery($query);
		return $db->loadAssoc();
	}
/**
 * Описание
 * @package
 * @subpackage
 */
	function getUserAssets($user_id=false,$fields=false,$where=false){
		if (!$fields)
			$fields='date_time,summ,payment_mode,identity,applied';
		// Create a new query object.
        $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		// Select fields from the table.
		$query->select($fields); 
		$query->from($db->quoteName('#__chado_payments'));
		if ($user_id||$where){
			if ($user_id&&$where)
				$where.=" AND user_id = ".$user_id;
			elseif($user_id)
				$where='user_id = '.$user_id;
			$query->where($where);
		}
		// Add the list ordering clause.
		$query->order('id');
		$db->setQuery($query); // а иначе вытащит старый запрос!
		$result=$db->loadAssocList();
		return $result;  
	}
/**
 * Описание
 * @package
 * @subpackage
 */
	function getUserFromTable($user_id=false){
		if(!$user_id){
			$user = JFactory::getUser();
			$user_id=$user->id;
		}
		$query="SELECT * FROM #__users WHERE id = ".$user_id;
		$db=JFactory::getDBO();
		$db->setQuery($query);
		$user_data=$db->loadAssoc(); 
		$xtra=unserialize($user_data['data']);
		unset($user_data['data']);
		$data=array_merge($user_data,$xtra);
		return $data;
	}
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
