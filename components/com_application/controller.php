<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');
 
/**
 * Application Controller
 */
class ApplicationController extends JController
{
	/**
 * Добавить заявку
 * @package
 * @subpackage
 */
	function add(){
		require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_application'.DS.'helpers'.DS.'chado_app_data.php';
		ApplicationHelper::addApplication(JRequest::get('post'));
		$this->setRedirect(JRoute::_('index.php?option=com_application&view=application&stat=applicated',false));
		
	}
/**
 * Проверить, нет ли уже такого емэйла в БД
 * @package
 * @subpackage
 */
	function email_check(){
		$query="SELECT COUNT(id) FROM #__chado_app_data WHERE email = '".JRequest::getVar('email')."'";
		$db=JFactory::getDBO();
		$db->setQuery($query);
		echo ($db->loadResult())? 'found':'new'; 
		exit;
	}	
}
