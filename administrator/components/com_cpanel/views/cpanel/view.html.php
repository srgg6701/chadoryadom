<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_cpanel
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.module.helper');

/**
 * HTML View class for the Cpanel component
 *
 * @static
 * @package		Joomla.Administrator
 * @subpackage	com_cpanel
 * @since 1.0
 */
class CpanelViewCpanel extends JViewLegacy
{
	protected $modules = null;

	public function display($tpl = null)
	{
		
		$session =& JFactory::getSession();
		// если зашли в первый раз, будем проверять необработанные платежи и перенаправлять туда, где они выведены:
		if (!$session->get('admin_login')) {
			$session->set('admin_login',true);
			$query="SELECT COUNT(*) FROM #__chado_payments WHERE applied = 0";
			$db=JFactory::getDBO();
			$db->setQuery($query);
			$res=$db->loadResult();
			if ((int)$res) {
				// echo $_SERVER['HTTP_REFERER']; die();
				$app =& JFactory::getApplication(); 
				$app->redirect('index.php?option=com_application&layout=payments'); 
			}
		}
		// Set toolbar items for the page
		JToolBarHelper::title(JText::_('COM_CPANEL'), 'cpanel.png');
		JToolBarHelper::help('screen.cpanel');

		/*
		 * Set the template - this will display cpanel.php
		 * from the selected admin template.
		 */
		JRequest::setVar('tmpl', 'cpanel');

		// Display the cpanel modules
		$this->modules = JModuleHelper::getModules('cpanel');

		parent::display($tpl);
	}
}
