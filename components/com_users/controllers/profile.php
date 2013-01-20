<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/controller.php';

/**
 * Profile controller class for Users.
 *
 * @package		Joomla.Site
 * @subpackage	com_users
 * @since		1.6
 */
class UsersControllerProfile extends UsersController
{
	private $goBack='index.php?option=com_users&view=profile';
	/**
	 * Method to check out a user for editing and redirect to the edit form.
	 *
	 * @since	1.6
	 */
	public function edit()
	{
		$app			= JFactory::getApplication();
		$user			= JFactory::getUser();
		$loginUserId	= (int) $user->get('id');

		// Get the previous user id (if any) and the current user id.
		$previousId = (int) $app->getUserState('com_users.edit.profile.id');
		$userId	= (int) JRequest::getInt('user_id', null, '', 'array');

		// Check if the user is trying to edit another users profile.
		if ($userId != $loginUserId) {
			JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
			return false;
		}

		// Set the user id for the user to edit in the session.
		$app->setUserState('com_users.edit.profile.id', $userId);

		// Get the model.
		$model = $this->getModel('Profile', 'UsersModel');

		// Check out the user.
		if ($userId) {
			$model->checkout($userId);
		}

		// Check in the previous user.
		if ($previousId) {
			$model->checkin($previousId);
		}

		// Redirect to the edit screen.
		$this->setRedirect(JRoute::_('index.php?option=com_users&view=profile&layout=edit', false));
	}

	/**
	 * Method to save a user's profile data.
	 *
	 * @return	void
	 * @since	1.6
	 */
	public function save($datapass=false)
	{
		//var_dump('<h1>post</h1><pre>',JRequest::get('post'),'</pre>'); die();
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		// Initialise variables.
		$app	= JFactory::getApplication();
		$model	= $this->getModel('Profile', 'UsersModel');
		$user	= JFactory::getUser();
		$userId	= (int) $user->get('id');

		// Get the user data.
		$data = JRequest::getVar('jform', array(), 'post', 'array');
		if($datapass){
			$data['name']=$user->name;
			$data['username']=$user->username;
			$data['email1']=$user->email;
			$data['email2']=$user->email;
			//$data['params']=unserialize($user->params);
			$data['password1']=$datapass['password1'];
			$data['password2']=$datapass['password1'];
		}
		// Force the ID to this user.
		$data['id'] = $userId;

		// Validate the posted data.
		$form = $model->getForm();
		if (!$form) {
			JError::raiseError(500, $model->getError());
			return false;
		}
		$xtra_data=unserialize($user->data);

		$xtra_data['password']=($data['password1'])? 
			$data['password1']:$data['jform[password1]'];
		
		// Validate the posted data.
		if(!$datapass)
			$data = $model->validate($form, $data);
		
		$data['data']=serialize($xtra_data);
		
		//var_dump('<h1>data(line: '.__LINE__.')</h1><pre>',$data,'</pre>'); 
		// Check for errors.
		if ($data === false) {
			// Get the validation messages.
			$errors	= $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
				if ($errors[$i] instanceof Exception) {
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				} else {
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			// Save the data in the session.
			$app->setUserState('com_users.edit.profile.data', $data);

			// Redirect back to the edit screen.
			$userId = (int) $app->getUserState('com_users.edit.profile.id');
			$this->setRedirect(JRoute::_('index.php?option=com_users&view=profile&layout=edit&user_id='.$userId, false));
			return false;
		}

		// Attempt to save the data.
		$return	= $model->save($data);
		
		//if ($datapass) return true;
		//var_dump('<h1>data(line: '.__LINE__.')</h1><pre>',$data,'</pre>'); die();
		// Check for errors.
		if ($return === false) {
			// Save the data in the session.
			$app->setUserState('com_users.edit.profile.data', $data);

			// Redirect back to the edit screen.
			$userId = (int)$app->getUserState('com_users.edit.profile.id');
			$this->setMessage(JText::sprintf('COM_USERS_PROFILE_SAVE_FAILED', $model->getError()), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_users&view=profile&layout=edit&user_id='.$userId, false));
			return false;
		}

		// Redirect the user and adjust session state based on the chosen task.
		switch ($this->getTask()) {
			case 'apply':
				// Check out the profile.
				$app->setUserState('com_users.edit.profile.id', $return);
				$model->checkout($return);

				// Redirect back to the edit screen.
				$this->setMessage(JText::_('COM_USERS_PROFILE_SAVE_SUCCESS'));
				$this->setRedirect(JRoute::_(($redirect = $app->getUserState('com_users.edit.profile.redirect')) ? $redirect : 'index.php?option=com_users&view=profile&layout=edit&hidemainmenu=1', false));
				break;

			default:
				// Check in the profile.
				$userId = (int)$app->getUserState('com_users.edit.profile.id');
				if ($userId) {
					$model->checkin($userId);
				}

				// Clear the profile id from the session.
				$app->setUserState('com_users.edit.profile.id', null);
				if (!strstr($this->getTask(),'changePassword')){
					// Redirect to the list screen.
					$this->setMessage(JText::_('COM_USERS_PROFILE_SAVE_SUCCESS'));
					$this->setRedirect(JRoute::_(($redirect = $app->getUserState('com_users.edit.profile.redirect')) ? $redirect : 'index.php?option=com_users&view=profile&user_id='.$return, false));
				}else 
					$this->setRedirect(JRoute::_($this->goBack,false));
					
				break;
		}

		// Flush the data from the session.
		$app->setUserState('com_users.edit.profile.data', null);
	}
/**
 * Описание
 * @package
 * @subpackage
 */
	function changeMobila(){
		$data=$this->extractUserData();
		$data['mobila']=JRequest::getVar('mobila');
		$this->pushUserDataBack($array_data);
	}	
/**
 * Описание
 * @package
 * @subpackage
 */
	function changePassword(){
		$data=$this->extractUserData();
		$pass=JRequest::getVar('password');
		$jform=JRequest::getVar('jform');
		$pass1=$data['password1']=$jform['password1'];
		$pass2=$data['password2']=$jform['password2'];
		if (!($pass&&$pass1&&$pass2))
			$error='missed';
		else{
			if ($data['password']!=$pass)
				$error='wrong_pass';
			elseif ($pass1!=$pass2)
				$error='diff_passords';	
		}
		if ($error){
			$this->setRedirect(JRoute::_($this->goBack.'&error='.$error, false));
		}else{
			$this->save($data);
			//$this->setRedirect(JRoute::_($this->goBack, false));
		}
	}
/**
 * Описание
 * @package
 * @subpackage
 */
	function extractUserData(){
		$user = JFactory::getUser();
		$where=' id = '.$user->id;
		$tbl='#__users';
		$query="SELECT `data` FROM $tbl WHERE $where";
		$db=JFactory::getDBO();
		$db->setQuery($query);
		$data=unserialize($db->loadResult()); 
		return $data;
	}
/**
 * Описание
 * @package
 * @subpackage
 */
	function pushUserDataBack($array_data){
		$data=serialize($array_data);
		$db = JFactory::getDBO();
		$query	= $db->getQuery(true);
		$query->update($tbl);
		$query->set(" `data` = '$data' ");
		$query->where($where);
		$db->setQuery((string) $query);
		if (!$db->query()) {
            //sendErrorMess включён
			JError::raiseError(500, $db->getErrorMsg());
		}
		// Redirect to the edit screen.
		$this->setRedirect(JRoute::_($this->goBack, false));
	}			
}
