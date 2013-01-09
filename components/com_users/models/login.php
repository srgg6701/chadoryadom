<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
jimport('joomla.application.component.modelform');
jimport('joomla.event.dispatcher');
/**
 * Rest model class for Users.
 *
 * @package		Joomla.Site
 * @subpackage	com_users
 * @since		1.6
 */
class UsersModelLogin extends JModelForm
{
	/**
	 * Method to get the login form.
	 *
	 * The base form is loaded from XML and then an event is fired
	 * for users plugins to extend the form with extra fields.
	 *
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{	if ($testCall=JRequest::getVar('testCall')) echo "<h2>UsersModelLogin::getForm()</h2><h1>this: UsersModelLogin</h1>";
		// Get the form.
		$form = $this->loadForm('com_users.login', 'login', array('load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	array	The default data is an empty array.
	 * @since	1.6
	 */
	protected function loadFormData()
	{	if ($testCall=JRequest::getVar('testCall')) echo "<h2>UsersModelLogin::loadFormData()</h2><h1>this: UsersModelLogin</h1>";
		// Check the session for previously entered login form data.
		$app	= JFactory::getApplication();
		$data	= $app->getUserState('users.login.form.data', array());

		// check for return URL from the request first
		if ($return = JRequest::getVar('return', '', 'method', 'base64')) {
			$data['return'] = base64_decode($return);
			if (!JURI::isInternal($data['return'])) {
				$data['return'] = '';
			}
		}

		// Set the return URL if empty.
		if (!isset($data['return']) || empty($data['return'])) {
			$data['return'] = 'index.php?option=com_users&view=profile';
		}
		$app->setUserState('users.login.form.data', $data);

		return $data;
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState()
	{	if ($testCall=JRequest::getVar('testCall')) echo "<h2>UsersModelLogin::populateState()</h2><h1>this: UsersModelLogin</h1>";
		// Get the application object.
			if ($testCall) echo "line ".__LINE__.": <div>\$params = JFactory::getApplication()->getParams('com_users')</div>"; 
		$params	= JFactory::getApplication()->getParams('com_users');
			if ($testCall) var_dump("<h1>params:</h1><pre>",$params,"</pre>");
		// Load the parameters.
			if ($testCall) echo "line ".__LINE__.": <div>\$this->setState('params', \$params)</div>"; 
		$this->setState('params', $params);
	}

	/**
	 * Method to allow derived classes to preprocess the form.
	 *
	 * @param	object	A form object.
	 * @param	mixed	The data expected for the form.
	 * @param	string	The name of the plugin group to import (defaults to "content").
	 * @throws	Exception if there is an error in the form event.
	 * @since	1.6
	 */
	protected function preprocessForm(JForm $form, $data, $group = 'user')
	{	if ($testCall=JRequest::getVar('testCall')) echo "<h2>UsersModelLogin::preprocessForm()</h2>";
		// Import the approriate plugin group.
		JPluginHelper::importPlugin($group);
		// Get the dispatcher.
		$dispatcher	= JDispatcher::getInstance();
		// Trigger the form preparation event.
		$results = $dispatcher->trigger('onContentPrepareForm', array($form, $data));
		// Check for errors encountered while preparing the form.
		if (count($results) && in_array(false, $results, true)) {
			// Get the last error.
			$error = $dispatcher->getError();
			// Convert to a JException if necessary.
			if (!($error instanceof Exception)) {
				throw new Exception($error);
			}
		}
	}
}
