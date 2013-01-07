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
		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{	
		require_once JPATH_COMPONENT.DS.'helpers'.DS.'chado_app_data.php';

		$state	= $this->get('State');
		$canDo	= ApplicationHelper::getActions($state->get('filter.category_id'));

		JToolBarHelper::title(JText::_('Заявки на подключение к сервису'), '_application_orders.png'); 


		JToolBarHelper::publish('_chado_app_data.activate', 'Подтвердить', true);
		JToolBarHelper::divider();
		JToolBarHelper::deleteList('', '_chado_app_data.delete','JTOOLBAR_DELETE');

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_application');
		}
	}
}
