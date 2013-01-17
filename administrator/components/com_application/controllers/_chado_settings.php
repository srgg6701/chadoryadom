<?php
/**
 * @version     2.1.0
 * @package     com_collector1
 * @copyright   Copyright (C) webapps 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      srgg <srgg67@gmail.com> - http://www.facebook.com/srgg67
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * _customer_orders list controller class.
 */
class ApplicationController_chado_settings extends JControllerAdmin
{
	private $direct='index.php?option=com_application&layout=settings';
	/**
	* Подтвердить изменение настроек!
	* @package
	* @subpackage
	*/
	function save(){
		$model=$this->getModel();
		// value[1], value[2] ... 
		$values=JRequest::getVar('value'); // Array
		foreach (JRequest::getVar('cid') as $i=>$id)
			if (!$model->save_settings($id,$values[$id]))
				die('Ошибка обновления записи...');
		// отправляемся на страницу с текущим списком юзеров:
		$this->setRedirect(JRoute::_($this->direct),"Настройки подтверждены.");
	}
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function &getModel($name = 'chado_settings', $prefix = 'ApplicationModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	
}