<?php
/**
 * @version     2.1.0
 * @package     com_application
 * @copyright   Copyright (C) webapps 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      srgg <srgg67@gmail.com> - http://www.facebook.com/srgg67
 */

// No direct access
defined('_JEXEC') or die;
require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_application'.DS.'tables'.DS.'chado_app_data.php';
/**
 * application helper.
 */
class ApplicationHelper
{
	public static function getActions()
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		$actions = JAccess::getActions('com_application');

		foreach ($actions as $action) {
			$result->set($action->name,	$user->authorise($action->name, 'com_application'));
		}

		return $result;
	}
	/**
	 * Получить названия полей на русском
	 * @package
	 * @subpackage
	 */
	function getAppFields(){
		// ВНИМАНИЕ! Не изменять порядок элементов по 'email' ВКЛЮЧИТЕЛЬНО! 
		return	array('id'=>'id заявки','family'=>'Фамилия','name'=>'Имя','middle_name'=>'Отчество','email'=>'E-mail','child_name'=>'Имя ребёнка','kindergarten'=>'Дет. сад (№/название)','group'=>'Группа д/с','mobila'=>'Моб. тел.');
	}
/**
 * ДОбавить заявку с сайта
 * @package
 * @subpackage
 */
	function addApplication($post){
		$table = JTable::getInstance('Chado_app_data', 'ApplicationTable');
		$table->reset();
		$valid_fields=array_flip(ApplicationHelper::getAppFields());
		foreach ($post as $field=>$value)
		  	if (in_array($field,$valid_fields)) 
				$table->set($field,$value);
		// Check that the data is valid
		if ($table->check())
		{
			// Store the data in the table
			if (!$table->store(true))
			{	JError::raiseWarning(100, JText::_('Не удалось сохранить данные заявки...'));
				return false;
			}
		}
		return true;		
	}
/**
 * Загрузить настройки обслуживания
 * @package
 * @subpackage
 */
	function getSettings($option=false){
		$where=($option)? ' WHERE `option` = "'.$option.'"':'';
		$db=JFactory::getDBO();
		$query="SELECT * FROM #__chado_settings{$where}";
		$db->setQuery($query);
		$settings=$db->loadAssocList();
		$options=array();
		foreach($settings as $i=>$array){
			$option=$array['option'];
			unset($array['option']);
			$options[$option]=$array;
		}
		return $options;
	}
/**
 * Синхронизация полей заявки и данных юзера и создание массива для вывода в профайле поциента (с)
 * @package
 * @subpackage
 */
	function getUserServiceData($user_id){
		$db=JFactory::getDBO();
		$query="DESC #__chado_app_data";
		$db->setQuery($query);
		$fields=$db->loadResultArray();
		$query="SELECT `data` FROM #__users WHERE id = ".$user_id;
		$db->setQuery($query);
		$data=unserialize($db->loadResult());
		$arrAppData=array();	
		for($i=1,$j=count($fields);$i<$j;$i++)
			if ($fields[$i]!="name"&&$fields[$i]!="email")
				$arrAppData[$fields[$i]]=$data[$fields[$i]];
		$arrAppData['password']=$data['password'];
		return $arrAppData;
	}
}
