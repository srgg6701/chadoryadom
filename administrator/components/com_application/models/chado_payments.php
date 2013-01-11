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
class ApplicationModelChado_payments extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array	An optional associative array of configuration settings.
	 *
	 * @see		JController
	 * @since   1.6
	 * Также указывает столбцы сортировки данных
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'user_id', 'a.user_id',
				'date_time', 'a.date_time',
				'summ', 'a.summ',
				'payment_mode', 'a.payment_mode',
				'identity', 'a.identity',
				'applied', 'a.applied',
			);
		}
		parent::__construct($config);
	}
	/**
	 * Удалить заявку на подключение к сервису
	 * 
	 * 
	 */
	public function delete(&$pks)
	{
		// Initialise variables.
		$user	= JFactory::getUser();
		$table = JTable::getInstance('Chado_payments', 'ApplicationTable');
		//$table	= $this->getTable();
		
		$pks	= (array) $pks;
		// Check if I am a Super Admin
		$iAmSuperAdmin	= $user->authorise('core.admin');

		//var_dump("<h1>pks:</h1><pre>",$pks,"</pre>");
		
		// Iterate the items to delete each one.
		foreach ($pks as $i => $pk)
		{
			if ($table->load($pk))
			{
				// Access checks.
				$allow = $user->authorise('core.delete', 'com_application');
				// Don't allow non-super-admin to delete a super admin
				$allow = (!$iAmSuperAdmin && JAccess::check($pk, 'core.admin')) ? false : $allow;

				if ($allow)
				{	$test=false; if ($test) die('delete record id '.$pk.", LINE: ".__LINE__);
					if (!$table->delete($pk))
					{
						$this->setError($table->getError());
						return false;
					}
					else
					{
						// reserved:
						// Trigger the onCustomerAfterDelete event.
						// $dispatcher->trigger('onCustomerAfterDelete', array($customer_to_delete->getProperties(), true, $this->getError()));
					}
				}
				else
				{
					// Prune items that you can't change.
					unset($pks[$i]);
					JError::raiseWarning(403, JText::_('JERROR_CORE_DELETE_NOT_PERMITTED'));
				}
			}
			else
			{
				$this->setError($table->getError());
				return false;
			}
		}
		return true;
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
		$query->select($this->getState('list.select',"a.id, a.user_id,
    DATE_FORMAT(a.date_time, '%e.%m.%Y %H:%i') as date_time, 
    a.summ,
    a.payment_mode,
    a.identity,
    if (a.applied<>0,'ok','?') as applied,
        us.name, 
        us.data")); 
		$query->from($db->quoteName('#__chado_payments').' as a, #__users as us');
		//FROM xjn5z_chado_payments as a, xjn5z_users as us WHERE us.id = a.user_id;
		$query->where('us.id = a.user_id');
		// Add the list ordering clause.
		$query->order($db->getEscaped($this->getState('filter_order')) . ' ' . $db->getEscaped($this->getState('filter_order_Dir', 'DESC')));
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
	 * Также имеет отношение к сортировке отображения данных. 
	 * См. здесь: http://docs.joomla.org/Adding_sortable_columns_to_a_table_in_a_component
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');
		
		if(!$filter_order=JRequest::getCmd('filter_order'))
			$filter_order='id';
        $filter_order_Dir = JRequest::getCmd('filter_order_Dir');
        $this->setState('filter_order', $filter_order);
        $this->setState('filter_order_Dir', $filter_order_Dir);
		// List state information.
		parent::populateState('a.id', 'desc');
	}
/**
 * Добавить платёж
 * @package
 * @subpackage
 */
	function add_payment($user_id){
		$table = JTable::getInstance('Chado_payments', 'ApplicationTable');
		$arrFields=array('user_id','date_time','summ','payment_mode','identity');
		foreach($arrFields as $i=>$field){
			$data=($field=='date_time')? JRequest::getVar('date')." ".JRequest::getVar('time'):JRequest::getVar($field);
			if ($field=='user_id')
				$data=$user_id;			
			$table->set($field,$data);
		}
		// Check that the data is valid
		if ($table->check())
		{
			// Store the data in the table
			if (!$table->store(true))
			{	JError::raiseWarning(100, JText::_('Не удалось сохранить данные...'));
			}else 
				return true;
		}else die("Формат данных не валиден...");
	}
/**
 * Получить список клиентов и их данные
 * @package
 * @subpackage
 */
	function getClients(){
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		// Select fields from the table.
		$query->select("a.id,  a.name,  a.email, a.data"); 
		$query->from($db->quoteName('#__users').' as a');
		$query->where('a.data LIKE "%child_name%"');
		// Add the list ordering clause.
		$query->order('id');
		$db->setQuery($query); // а иначе вытащит старый запрос!
		$result=$db->loadAssocList();
		return $result;  
	}
	
}
