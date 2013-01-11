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
class ApplicationController_chado_payments extends JControllerAdmin
{
	private $direct='index.php?option=com_application&layout=payments';
	/**
	* Подтвердить платёж!
	* @package
	* @subpackage
	*/
	function activate(){
		//var_dump('<h1>_POST, cid</h1><pre>',$_POST['cid'],'</pre>'); //die('activate');
		foreach (JRequest::getVar('cid') as $i=>$id)
			if (!$this->getModel()->apply_payment($id))
				die('Ошибка обновления записи...');
		// отправляемся на страницу с текущим списком юзеров:
		$this->setRedirect(JRoute::_($this->direct,false));
	}
	/**
	* Удалить платёж!
	* @package
	* @subpackage
	*/
	function delete(){
		//var_dump('<h1>_POST, cid</h1><pre>',$_POST['cid'],'</pre>'); die('delete');
		foreach (JRequest::getVar('cid') as $i=>$id)
			if (!$this->getModel()->delete_payment($id))
				die('Ошибка удаления записи...');
		// отправляемся на страницу с текущим списком юзеров:
		$this->setRedirect(JRoute::_($this->direct,false));
	}
	
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function &getModel($name = 'chado_payments', $prefix = 'ApplicationModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	
}