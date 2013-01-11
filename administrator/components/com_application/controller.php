<?php	
/**
 * @version     2.1.0
 * @package     com_application
 * @copyright   Copyright (C) webapps 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      srgg <srgg67@gmail.com> - http://www.facebook.com/srgg67
 */
require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_application'.DS."tables".DS."chado_app_data.php";

require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_application'.DS."helpers".DS."chado_app_data.php";
// No direct access
defined('_JEXEC') or die;

class ApplicationController extends JController
{
	private $direct='index.php?option=com_application&layout=payments';
	/**
	 * Method to display a view.
	 *
	 * @param	boolean			$cachable	If true, the view output will be cached
	 * @param	array			$urlparams	An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	JController		This object to support chaining.
	 * @since	1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{	
		$model_name='chado_';
		$model_name.=($layout=JRequest::getVar('layout'))? $layout:'app_data';
		$view=$this->getView('chado_app_data', 'html' ); 
		$model=$this->getModel($model_name); 
		$view->setModel($model,true);
		$view->setLayout( $layout ); 
		// Use the View display method 
		$view->display(); 
	}
	/**
 * Описание
 * @package
 * @subpackage
 */
	public function edit(){
		$pk=JRequest::getVar('id');
		$model=$this->getModel('Item');
		$model->getItem($pk);
		$this->display();
	}
/**
 * Добавить проводку
 * @package
 * @subpackage
 */
	function add_payment(){
		$model=$this->getModel('Chado_payments');
		if (!$model->add_payment(JRequest::getVar('user_id')))
			die('Ошибка добавления данных');
		else
			$this->setRedirect(JRoute::_($this->direct,false));
	}
/**
 * Подтвердить платёж!
 * @package
 * @subpackage
 */
	function apply_payment(){
		$model=$this->getModel('Chado_payments');
		if (!$model->apply_payment(JRequest::getVar('id')))
			die('Ошибка обновления данных');
		else 
			$this->setRedirect(JRoute::_($this->direct,false));
	}	
/**
 * Удалить проводку
 * @package
 * @subpackage
 */
	function delete_payment(){
		$model=$this->getModel('Chado_payments');
		if (!$model->delete_payment(JRequest::getVar('id')))
			die('Ошибка удаления данных');
		else
			$this->setRedirect(JRoute::_($this->direct,false));
	}
	
}
