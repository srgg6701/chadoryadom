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
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
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

        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.'chado_app_data';
        if (file_exists($formPath)) {

		    if ($canDo->get('core.edit')) {
			    JToolBarHelper::editList('chado_app_data.edit','JTOOLBAR_EDIT');
		    }

        }

		if ($canDo->get('core.edit.state')) {

            if (isset($this->items[0]->state)) {
			    JToolBarHelper::divider();
            } else {
                //If this component does not use state then show a direct delete button as we can not trash
                JToolBarHelper::deleteList('', '_chado_app_data.delete','JTOOLBAR_DELETE');
            }
		}
        
        //Show trash and delete for components that uses the state field
        if (isset($this->items[0]->state)) {
		    if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
			    JToolBarHelper::deleteList('', '_chado_app_data.delete','JTOOLBAR_EMPTY_TRASH');
			    JToolBarHelper::divider();
		    } else if ($canDo->get('core.edit.state')) {
			    JToolBarHelper::trash('_chado_app_data.trash','JTOOLBAR_TRASH');
			    JToolBarHelper::divider();
		    }
        }

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_application');
		}
	}
}
