<?php
/**
 * @version     2.1.0
 * @package     com_collector1
 * @copyright   Copyright (C) webapps 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      srgg <srgg67@gmail.com> - http://www.facebook.com/srgg67
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Customer_orders controller class.
 */
class ApplicationControllerApplication extends JControllerForm
{
	public $default_view='chado_app_data';
    function __construct() {
        $this->view_list = '_chado_app_data';
        parent::__construct();
    }
		/**
 * Описание
 * @package
 * @subpackage
 */
	public function edit(){ 
		$pk=JRequest::getVar('id');
		$query = 'SELECT * FROM #_'.$this->view_list.' WHERE id = '.$pk;
		$db =& JFactory::getDBO();
		$db->setQuery($query);
		$view=$this->prepareView('userdata');
		$view->userdata=$db->loadAssoc();
		$this->display($view);
	}
	public function display($view=false)
	{	if(!$view)
			$view=$this->prepareView($this->default_view);
		$view->display(); 
	}
/**
 * Описание
 * @package
 * @subpackage
 */
	public function prepareView($layout=false,$dview=false){
		if (!$dview) $dview=$this->default_view;
		require_once JPATH_COMPONENT.'/helpers/chado_app_data.php';
		$view=$this->getView($dview, 'html' ); 
		$model=$this->getModel('Item'); 
		$view->setModel($model,true);
		$view->setLayout($layout);
		return $view; 
	}
	
}