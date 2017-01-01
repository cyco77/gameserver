<?php
/*------------------------------------------------------------------------
# com_gameserver - GameServer!
# ------------------------------------------------------------------------
# author    Lars Hildebrandt
# copyright Copyright (C) 2014 Lars Hildebrandt. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.larshildebrandt.de
# Technical Support:  Forum - http://www..larshildebrandt.de/forum/
-------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

class GameServerControllerAddserver extends JControllerForm
{
	public function getModel($name = '', $prefix = '', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, array('ignore_request' => false));
	}
	
	public function submit()
	{
		// Check for request forgeries.
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		// Initialise variables.
		$app	= JFactory::getApplication();
		$model	= $this->getModel('addserver');
		
		// Get the data from the form POST
		$data = JRequest::getVar('jform', array(), 'post', 'array');

		$app->setUserState('com_gameserver.addserver.data', $data);

		$user = JFactory::getUser();
		if ($user->guest) 
		{
			$this->setRedirect( JRoute::_('index.php?option=com_gameserver&view=addserver'),JText::_('COM_SQUADMANAGEMENT_JOINUS_SAVE_NOT_LOGGED_IN') );
			return false;
		}

		$added = $model->insertItem($data);
		
		if ($added) 
		{
			$app->setUserState('com_squadmanagement.joinus.data', null);
			$this->setRedirect( JRoute::_('index.php?option=com_gameserver&view=addserver'),JText::_('COM_SQUADMANAGEMENT_JOINUS_SAVE_SUCCESS') );
			return true;
		}
		
		$this->setRedirect( JRoute::_('index.php?option=com_gameserver&view=addserver'), JText::_('COM_SQUADMANAGEMENT_JOINUS_SAVE_FAIL') );
		return false;	
	}
}
