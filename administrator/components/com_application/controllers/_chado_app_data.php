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
	* Сделать аппликанта юзером!
	* @package
	* @subpackage
	*/
	function activate(){
		
		$old=false;
		if (!$old){ 
			//********************************************
			/* 	It has been got from here:
					http://stackoverflow.com/a/4212791/1522479
				...and slightly modified by source from here:
					http://stackoverflow.com/a/10173680/1522479
				...for an original source is deprecated for 2.5 version.
			*/
			// get the com_user params */			
			jimport('joomla.application.component.helper'); // include libraries/application/component/helper.php
			$usersParams = JComponentHelper::getParams( 'com_users' ); // load the Params
			//********************************************
			// собственный код:
			$pks=JRequest::getVar('cid'); // массив id id юзеров
			$model=$this->getModel('Item'); // будем получать данные аппликантов
			// перебрать и зарегистрировать полученных аппликантов:
			foreach ($pks as $i => $pk) {	
				
				if (!$applicant_data=$model->getItem($pk)) {
					JError::raiseWarning(100, JText::_('Не получены данные заявки...'));
					die("LINE: ".__LINE__);
				}else{
					
					//********************************************
					// http://stackoverflow.com/a/4212791/1522479
					// "generate" a new JUser Object
					// it's important to set the "0" otherwise your admin user information will be loaded
					$user = JFactory::getUser(0);
					// get the default usertype
					$usertype = $usersParams->get( 'new_usertype' );
					if (!$usertype) {
						 $usertype = 'Registered';
					}
					// set up the "main" user information
					//original logic of name creation
					//$data['name'] = $firstname.' '.$lastname; // add first- and lastname
					//default to defaultUserGroup i.e.,Registered:
					$defaultUserGroup = $usersParams->get('new_usertype', 2);
					$password=$this->generate_password(10);
					$xtra_data=serialize( array(
											'family'=>$applicant_data->family,
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
							'password' => $password, 
							'password2' => $password, 
							'groups'=>array($defaultUserGroup),
							'sendEmail' => 1, // should the user receive system mails?
							'block'=> 0,
							'data'=>$xtra_data
						);
				}
				if (!$user->bind($data)) { // now bind the data to the JUser Object, if it not works....
					JError::raiseWarning('', JText::_( $user->getError())); // ...raise an Warning
					return false;
				}
				if (!$user->save()) {
					JError::raiseWarning('', JText::_( $user->getError())); // ...raise an Warning
					return false; 
					//********************************************
				}else // удалим аппликанта, т.к. теперь он - юзер!
					$this->getModel('Chado_app_data')->delete($pks);
			}								
		}
		// отправляемся на страницу с текущим списком юзеров:
		$this->setRedirect(JRoute::_('index.php?option=com_users',false));
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