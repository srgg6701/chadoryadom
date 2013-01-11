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

jimport('joomla.application.component.view');

/**
 * View class for a list of application.
 */
class ApplicationViewChado_app_data extends JView
{
	protected $items;
	protected $pagination;
	protected $state;
	public $userdata;
	public $fields;
	public $clientsArray;
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{	
		// call: /joomla/application/component/view.php
		// there will be required the model that been set here by default
		// further it will call:
			// model()->getState() 
			// model()->getItems()
		// check model to ensure there they are!
		$this->state = $this->get('State');
		$this->items = $this->get('Items');
		// 
		$this->pagination	= $this->get('Pagination');
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
		if ($this->_layout=='payments')
			$this->setClientsData();
			
		$this->addToolbar($this->_layout);
		parent::display($tpl);
	}
/**
 * Получить список клиентов и их данные
 * @package
 * @subpackage
 */
	function setClientsData(){
		$model=$this->getModel('Chado_payments');
		$clients=$model->getClients();
		$users=array();
		foreach($clients as $i=>$array):
			$data=unserialize($array['data']);
			$users[$array['id']]=array(
						'name'=>$array['name'],
						'middle_name'=>$data['middle_name'],
						'child_name'=>$data['child_name'],
						'kindergarten'=>$data['kindergarten'],
						'email'=>$array['email'],
						'mobila'=>$data['mobila'],
					);
		endforeach;
		$this->clientsArray=$users;
	}
	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar($layout=false)
	{	
		$state	= $this->get('State');
		$canDo	= ApplicationHelper::getActions($state->get('filter.category_id'));
		$controller='_chado_app_data';
		switch($layout){
			case 'userdata':
				$header="Данные заявителя";
				$pic='_application_userdata.png';
					break;
			case 'payments':
				$header="Платежи";
				$pic='_application_payments.png';
				$controller='_chado_payments';
					break;
			default:
				$header="Заявки на подключение к сервису";
				$pic='_application_orders.png';
		}
		$defLink='index.php?option=com_application';
		echo "<div class=\"secondSubmenu\" align='right'>";
		echo "	<a href=\"".JRoute::_($defLink)."\">Заявки на подключение</a>";
		echo " 	&nbsp; | &nbsp; ";
		echo "	<a href=\"".JRoute::_($defLink.'&layout=payments')."\">Платежи</a>";
		echo "</div>";
		JToolBarHelper::title(JText::_($header),$pic); 
		if ($layout!='userdata'):
			JToolBarHelper::publish($controller.'.activate', 'Подтвердить', true);
			JToolBarHelper::divider();
		endif;
		JToolBarHelper::deleteList('', $controller.'.delete','JTOOLBAR_DELETE');

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_application');
		}
	}
}
