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

class ApplicationController extends JController
{
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
		require_once JPATH_COMPONENT.'/helpers/chado_app_data.php';
		$default_name='chado_app_data';
		$layout=JRequest::getVar('layout');
		// We override the JController default display       
//method which expects a view named chado_app_data.
		// We want a view of 'chado_app_data' that uses the 'default' layout. 
		// Set the view and the model 
		$view=$this->getView($default_name, 'html' ); 
		$model=$this->getModel($default_name); 
		$view->setModel($model,true); 
		// Use the View display method
		$view->display(); 
	}
}
