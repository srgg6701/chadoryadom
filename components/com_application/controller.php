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
/**
 * Описание
 * @package
 * @subpackage
 */
	function delete_payment(){
		JTable::addIncludePath(JPATH_SITE.'/administrator/components/com_application/tables');
		$table =& JTable::getInstance('chado_payments','ApplicationTable');
		if(!$table->delete(JRequest::getVar('id')))
			echo $table->getError();
		else die('OK');
	}

/**
 * Обработать информацию о платеже:
 * @package
 * @subpackage
 */
	function send_payment(){
		JTable::addIncludePath(JPATH_SITE.'/administrator/components/com_application/tables');
		$tbl_name='chado_payments';
		$table =& JTable::getInstance($tbl_name,'ApplicationTable');
		$arrFields=array('user_id','date_time','summ','payment_mode','identity');
		$user = JFactory::getUser();
		foreach($arrFields as $i=>$field){
			$data=($field=='date_time')? JRequest::getVar('date')." ".JRequest::getVar('time'):JRequest::getVar($field);
			if ($field=='user_id')
				$data=$user->get('id');			
			$table->set($field,$data);
		}
		// Check that the data is valid
		if ($table->check())
		{
			// Store the data in the table
			if (!$table->store(true))
			{	JError::raiseWarning(100, JText::_('Не удалось сохранить данные...'));
			}else
				$this->setRedirect(JRoute::_('index.php?option=com_users&view=login&layout=account',false));

		}else die("Формат данных не валиден...");
	}
	
}
