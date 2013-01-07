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

/**
 * application helper.
 */
class ApplicationHelper
{
	public static function getActions()
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		$actions = JAccess::getActions('com_application');

		foreach ($actions as $action) {
			$result->set($action->name,	$user->authorise($action->name, 'com_application'));
		}

		return $result;
	}

}