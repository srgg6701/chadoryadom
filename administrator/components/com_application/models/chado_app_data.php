<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @package     Joomla.Administrator
 * @subpackage  com_menus
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Menu List Model for Menus.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_menus
 * @since       1.6
 */
class ApplicationModelChado_app_data extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array	An optional associative array of configuration settings.
	 *
	 * @see		JController
	 * @since   1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'family', 'a.family',
				'email', 'a.email',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Overrides the getItems method to attach additional metrics to the list.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 *
	 * @since   1.6.1
	 */
	public function getItems()
	{
		// Get a storage key.
		$store = $this->getStoreId('getItems');

		// Try to load the data from internal storage.
		if (!empty($this->cache[$store]))
		{
			return $this->cache[$store];
		}

		// Load the list items.
		$items = parent::getItems();

		// If emtpy or an error, just return.
		if (empty($items))
		{
			return array();
		}

		// Getting the following metric by joins is WAY TOO SLOW.
		// Faster to do three queries for very large menu trees.

		// Get the menu types of menus in the list.
		$db = $this->getDbo();
		$families = JArrayHelper::getColumn($items, 'family');

		// Quote the strings.
		$families = implode(
			',',
			array_map(array($db, 'quote'), $families)
		);
		$query = $db->getQuery(true)
			->select('*')
			->from('#__chado_app_data')
			->order('id')
			;
		$db->setQuery($query);
		$items = $db->loadAssocList();
		if ($db->getErrorNum())
		{
			$this->setError($db->getErrorMsg());
			return false;
		}
		// Add the items to the internal cache.
		$this->cache[$store] = $items;
		return $this->cache[$store];
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return  string  An SQL query
	 *
	 * @since   1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select all fields from the table.
		$query->select($this->getState('list.select', 'a.*'));
		$query->from($db->quoteName('#__menu_types').' AS a');


		$query->group('a.id, a.menutype, a.title, a.description');

		// Add the list ordering clause.
		$query->order($db->escape($this->getState('list.ordering', 'a.id')).' '.$db->escape($this->getState('list.direction', 'ASC')));

		return $query;
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// List state information.
		parent::populateState('a.id', 'asc');
	}

	/**
	 * Gets the extension id of the core mod_menu module.
	 *
	 * @return  integer
	 *
	 * @since   2.5
	 */
	public function getModMenuId()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select('e.extension_id')
			->from('#__extensions AS e')
			->where('e.type = ' . $db->quote('module'))
			->where('e.element = ' . $db->quote('mod_menu'))
			->where('e.client_id = 0');
		$db->setQuery($query);

		return $db->loadResult();
	}

	/**
	 * Gets a list of all mod_mainmenu modules and collates them by menutype
	 *
	 * @return  array
	 */
	public function &getModules()
	{
		$model	= JModelLegacy::getInstance('Menu', 'MenusModel', array('ignore_request' => true));
		$result	= &$model->getModules();

		return $result;
	}
}
