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
		return	array('id'=>'id заявки','family'=>'Фамилия','name'=>'Имя','middle_name'=>'Отчество','child_name'=>'Имя ребёнка','kindergarten'=>'Дет. сад (№/название)','group'=>'Группа д/с','email'=>'E-mail','mobila'=>'Моб. тел.');
	}

}
