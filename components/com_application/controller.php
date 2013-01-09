<?php 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
//jimport('joomla.application.component.controller');
 
/**
 * Application Controller
 */
class ApplicationController extends JControllerLegacy
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
	{	if ($testCall=JRequest::getVar('testCall')) echo "<h2>ApplicationController::display()</h2><h1>this: ApplicationController</h1>";
		// Get the document object.
		$document	= JFactory::getDocument();
		// Set the default view name and format from the Request.
		$vName	 = JRequest::getCmd('view', 'application');
		$vFormat = $document->getType(); 
		$lName	 = JRequest::getCmd('layout', 'default');
		if ($view = $this->getView($vName, $vFormat)) {
			$model = $this->getModel('Application');
			$view->setModel($model, true);
			$view->setLayout($lName);
			$view->assignRef('document', $document);
			
				if ($testCall) echo "<blockquote style='padding:10px;border:solid 1px;'>";
			$view->display();
				if ($testCall) echo "</blockquote>";
		}
	}
}
