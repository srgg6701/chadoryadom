<?php
/**
 * @version     2.1.0
 * @package     com_collector1
 * @copyright   Copyright (C) webapps 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      srgg <srgg67@gmail.com> - http://www.facebook.com/srgg67
 */

// No direct access
defined('_JEXEC') or die;
jimport('joomla.application.component.controllerform');
// подключить главный контроллер компонента:
require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_application'.DS.'controller.php';
/**
 * Customer_orders controller class.
 */
class ApplicationControllerApplication extends JControllerForm
{
	public $default_view='chado_app_data';
    function __construct() {
        $this->view_list = '_chado_app_data';
        parent::__construct();
    }
		/**
 * Загрузить данные заявки для редактирования
 * @package
 * @subpackage
 */
	public function edit(){ 
		$pk=JRequest::getVar('id');
		$query = 'SELECT * FROM #_'.$this->view_list.' WHERE id = '.$pk;
		$db =& JFactory::getDBO();
		$db->setQuery($query);
		$view=$this->prepareView('userdata');
		$view->userdata=$db->loadAssoc();
		$view->fields=ApplicationHelper::getAppFields();
		$this->display($view);
	}
	public function display($view=false)
	{	if(!$view)
			$view=$this->prepareView($this->default_view);
		$view->display(); 
	}
/**
 * Подготовить данные представления
 * @package
 * @subpackage
 */
	public function prepareView($layout=false,$dview=false){
		if (!$dview) $dview=$this->default_view;
		$view=$this->getView($dview, 'html' ); 
		$model=$this->getModel('Item'); 
		$view->setModel($model,true);
		$view->setLayout($layout);
		return $view; 
	}
/**
 * Обновить данные заявки
 * @package
 * @subpackage
 */
	function update(){
		$post=JRequest::get('post');
		$pk=$post['cid'][0];
		$table = JTable::getInstance($this->default_view, 'ApplicationTable');
		$valid_fields=array_flip(ApplicationHelper::getAppFields());
		if (!$table->load($pk))
		{
		  // handle failed load
		  die($table->getError());
		}
		else
		{
		  foreach ($post as $name=>$value)
		  	if (in_array($name,$valid_fields)) 
				$table->set($name, $value);
		  
		  if ($table->check())
		  {
			if (!$table->store(true))
			{
				// handle failed update
				die($table->getError());
			}
		  }
		  else
		  {
			// handle invalid input
			die($table->getError());
		  }
		}
		$this->setRedirect('index.php?option=com_application');
	}	
}