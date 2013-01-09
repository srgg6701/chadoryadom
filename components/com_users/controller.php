<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Base controller class for Users.
 *
 * @package		Joomla.Site
 * @subpackage	com_users
 * @since		1.5
 */
class UsersController extends JControllerLegacy
{
	/**
	 * Method to display a view.
	 *
	 * @param	boolean			If true, the view output will be cached
	 * @param	array			An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	JController		This object to support chaining.
	 * @since	1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{	if ($testCall=JRequest::getVar('testCall')) echo "<h2>UsersController::display()</h2><h1>this: UsersController</h1>";
		// Get the document object.
		$document	= JFactory::getDocument();

		// Set the default view name and format from the Request.
		$vName	 = JRequest::getCmd('view', 'login');
		$vFormat = $document->getType();
		$lName	 = JRequest::getCmd('layout', 'default');
		if ($view = $this->getView($vName, $vFormat)) {
			// Do any specific processing by view.
			switch ($vName) {
				case 'registration':
					// If the user is already logged in, redirect to the profile page.
					$user = JFactory::getUser();
					if ($user->get('guest') != 1) {
						// Redirect to profile page.
						$this->setRedirect(JRoute::_('index.php?option=com_users&view=profile', false));
						return;
					}

					// Check if user registration is enabled
            		if(JComponentHelper::getParams('com_users')->get('allowUserRegistration') == 0) {
            			// Registration is disabled - Redirect to login page.
						$this->setRedirect(JRoute::_('index.php?option=com_users&view=login', false));
						return;
            		}

					// The user is a guest, load the registration model and show the registration page.
					$model = $this->getModel('Registration');
					break;

				// Handle view specific models.
				case 'profile':

					// If the user is a guest, redirect to the login page.
					$user = JFactory::getUser();
					if ($user->get('guest') == 1) {
						// Redirect to login page.
						$this->setRedirect(JRoute::_('index.php?option=com_users&view=login', false));
						return;
					}
					$model = $this->getModel($vName);
					break;

				// Handle the default views.
				case 'login':
					$model = $this->getModel($vName);
					break;

				case 'reset':
					// If the user is already logged in, redirect to the profile page.
					$user = JFactory::getUser();
					if ($user->get('guest') != 1) {
						// Redirect to profile page.
						$this->setRedirect(JRoute::_('index.php?option=com_users&view=profile', false));
						return;
					}

					$model = $this->getModel($vName);
					break;

				case 'remind':
					// If the user is already logged in, redirect to the profile page.
					$user = JFactory::getUser();
					if ($user->get('guest') != 1) {
						// Redirect to profile page.
						$this->setRedirect(JRoute::_('index.php?option=com_users&view=profile', false));
						return;
					}

					$model = $this->getModel($vName);
					break;

				default:
					$model = $this->getModel('Login');
					break;
			}
			// Push the model into the view (as default).
				if ($testCall) echo "line ".__LINE__.": <div>\$view->setModel(\$model, true)</div>"; 
			$view->setModel($model, true); 
			//
				if ($testCall) echo "line ".__LINE__.": <div>\$view->setLayout(\$lName)</div>"; 
			$view->setLayout($lName);
			// Push document object into the view.
				if ($testCall) echo "line ".__LINE__.": <div>\$view->assignRef('document', \$document)</div>"; 
			$view->assignRef('document', $document);
				if ($testCall) echo "line ".__LINE__.": <div>\$view->display()</div>"; 
				if ($testCall) echo "<blockquote style='padding:10px;border:solid 1px;'>";
			$view->display();
				if ($testCall) {
					echo "</blockquote>"; 
					if($testCall=='2') die();
				}
		}
	}
}
