<?php
/**
 * @version     2.1.0
 * @package     com_collector1
 * @copyright   Copyright (C) webapps 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      srgg <srgg67@gmail.com> - http://www.facebook.com/srgg67
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * _customer_orders list controller class.
 */
class ApplicationController_chado_app_data extends JControllerAdmin
{
	/**
	* Описание
	* @package
	* @subpackage
	*/
	function activate(){
		
		$pks=JRequest::getVar('cid'); // массив id id юзеров
	  	$model=$this->getModel('Item'); // будем получать данные аппликантов
		$errors=0;
		if(!$table = JTable::getInstance('Users', 'ApplicationTable'))
			JError::raiseWarning(100, JText::_('Не получена таблица пользователей...'));
		// 
		$registerDate=date("Y-m-d H:i:s");
		foreach ($pks as $i => $pk)
		{	if (!$applicant_data=$model->getItem($pk)) {
				JError::raiseWarning(100, JText::_('Не получены данные заявки...'));
				die("LINE: ".__LINE__);
			}
			else{
				$table->reset();
				$password=$this->generate_password(10);
				$xtra_data=serialize( array(
										'middle_name'=>$applicant_data->middle_name,
										'child_name'=>$applicant_data->child_name,
										'kindergarten'=>$applicant_data->kindergarten,
										'group'=>$applicant_data->group,
										'mobila'=>$applicant_data->mobila,
										'password'=>$password
									));
				$data=array(
						'id' => '0', // А без этого добавит ТОЛЬКО ОДНУ запись!!!
						'name' => $applicant_data->name,
						'username' => $applicant_data->email,
						'email' => $applicant_data->email,
						'password' => md5($password), 
						'registerDate' =>$registerDate,
						'groups'=>array('2'),
						'data'=>$xtra_data
					);
			}
			foreach ($data as $field=>$value)
				$table->set($field,$value);
			// Check that the data is valid
			if ($table->check())
			{
				// Store the data in the table
				if (!$table->store(true))
				{	JError::raiseWarning(100, JText::_('Не удалось сохранить данные для id '.$pk.'...'));
					$errors++;
				}
			}else die("Данные не валидны...");
		}
		if($errors) {
				JError::raiseWarning(100, JText::_('Не удалось добавить пользователя'));
				$errors++;
		}else{
			$this->getModel('Chado_app_data')->delete($pks);
			$this->setRedirect('index.php?option=com_users');
		}
	}
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function &getModel($name = 'chado_app_data', $prefix = 'ApplicationModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	
	public function generate_password($number) {
		$arr = array('a','b','c','d','e','f',
					 'g','h','i','j','k','l',
					 'm','n','o','p','r','s',
					 't','u','v','x','y','z',
					 'A','B','C','D','E','F',
					 'G','H','I','J','K','L',
					 'M','N','O','P','R','S',
					 'T','U','V','X','Y','Z',
					 '1','2','3','4','5','6',
					 '7','8','9','0');
		// Генерируем пароль
		$pass = "";
		for($i = 0; $i < $number; $i++){
		  // Вычисляем случайный индекс массива
		  $index = rand(0, count($arr) - 1);
		  $pass .= $arr[$index];
		}
		return $pass;
	}
	/**
	 * Получим id последней коллекции или заказа:
	 */
	static function getLastId($table_name,$db=false){
		//получить:
		$query="SELECT max(id) FROM $table_name";
		if (!$db) $db = JFactory::getDBO();
		$db->setQuery($query);
		$last_id=$db->loadResult();
		return (!$last_id)? false:$last_id;
	}
	
}