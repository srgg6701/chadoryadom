<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

// jimport('joomla.application.component.modelitem');

/**
 * Content Component Article Model
 *
 * @package		Joomla.Site
 * @subpackage	com_content
 * @since 1.5
 */
class ApplicationModelApplication extends JModelLegacy
{
	/**
	 * Model context string.
	 *
	 * @var		string
	 */

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication('site');

		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);
	}

}
