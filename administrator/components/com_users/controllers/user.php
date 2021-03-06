<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * User controller class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_users
 * @since       1.6
 */
class UsersControllerUser extends JControllerForm
{
	/**
	 * @var    string  The prefix to use with controller messages.
	 * @since  1.6
	 */
	protected $text_prefix = 'COM_USERS_USER';

	/**
	 * Overrides JControllerForm::allowEdit
	 *
	 * Checks that non-Super Admins are not editing Super Admins.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key.
	 *
	 * @return  boolean  True if allowed, false otherwise.
	 *
	 * @since   1.6
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		// Check if this person is a Super Admin
		if (JAccess::check($data[$key], 'core.admin'))
		{
			// If I'm not a Super Admin, then disallow the edit.
			if (!JFactory::getUser()->authorise('core.admin'))
			{
				return false;
			}
		}

		return parent::allowEdit($data, $key);
	}

	/**
	 * Method to run batch operations.
	 *
	 * @param   object  $model  The model.
	 *
	 * @return  boolean  True on success, false on failure
	 *
	 * @since   2.5
	 */
	public function batch($model = null)
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Set the model
		$model = $this->getModel('User', '', array());

		// Preset the redirect
		$this->setRedirect(JRoute::_('index.php?option=com_users&view=users' . $this->getRedirectToListAppend(), false));

		return parent::batch($model);
	}

	/**
	 * Overrides parent save method to check the submitted passwords match.
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if successful, false otherwise.
	 *
	 * @since   1.6
	 */
	public function save($key = null, $urlVar = null)
	{
		$data = JRequest::getVar('jform', array(), 'post', 'array');
		/**
		 * srgg67@gmail.com
		 */
		// проверить и обработать дополнительные поля:
		if(isset($data['xtra'])||isset($data['videoscript'])){
			$setXtraData=true;
			//var_dump('<h1>data</h1><pre>',$data,'</pre>'); //die();
			$db	= JFactory::getDBO();
			$query	= $db->getQuery(true);
			$query->update('#__users');
		}
		if(isset($data['xtra'])) {
			// получить названия полей доп.данных:
			$xtra=explode(",",$data['xtra']);
			//var_dump("<h1>xtra:</h1><pre>",$xtra,"</pre>");
			$xtra_data=array();
			foreach($xtra as $i=>$field){
				// присвоить эл. массива доп. данных значения:
				$xtra_data[$field]=$data[$field];
				if ($field!="password"){
					// удалить данные, не соответствующие текущим полям:
					unset($data[$field]);
				}
			}
			unset($data['xtra']); // удалить элемент доп. данных	
			$data['xtra_data']=serialize($xtra_data); // сохранить доп. данные в поле как сериализованный массив
			$fill_data_field=" `data` = '$data[xtra_data]' ";
		}
		if(isset($data['videoscript'])){
			$videoscript=explode(",",$data['videoscript']);
			$videoscript_params=array();
			foreach($videoscript as $i=>$field){
				// присвоить эл. массива доп. данных значения:
				$videoscript_params[$field]=$data[$field];
				unset($data[$field]);
			}
			unset($data['videoscript']); // удалить элемент доп. данных	
			$data['script_data']=serialize($videoscript_params); // сохранить доп. данные в поле как сериализованный массив
			$fill_script_field=" `script_params` = '$data[script_data]' ";
		}
		if(isset($setXtraData)){
			if ($fill_data_field&&$fill_script_field)
				$set_exp=$fill_data_field.", ".$fill_script_field;
			else
				$set_exp=($fill_data_field)? $fill_data_field:$fill_script_field;
			//echo "<div class=''>set_exp= ".$set_exp."</div>";die();
			$query->set($set_exp);
			$query->where(" id = $data[id] ");
			$db->setQuery((string) $query);
		}
		//***************************************************************
		// См. окончание после проверки соответствия паролей
		
		// TODO: JForm should really have a validation handler for this.
		if (isset($data['password']) && isset($data['password2']))
		{
			// Check the passwords match.
			if ($data['password'] != $data['password2'])
			{
				$this->setMessage(JText::_('JLIB_USER_ERROR_PASSWORD_NOT_MATCH'), 'warning');
				$this->setRedirect(JRoute::_('index.php?option=com_users&view=user&layout=edit', false));
			}
			unset($data['password2']);
		}
		// если получали дополнительные данные:
		if(isset($setXtraData)){
			if (!$db->query()) 
				JError::raiseError(500, $db->getErrorMsg());
			unset($data['xtra_data']);
			unset($data['videoscript']);
		}
		// конец проверки и обработки дополнительных полей. Далее все поля обрабатываются в том виде, в котором предусмотрено по умолчанию
		
		return parent::save();
	}
}
