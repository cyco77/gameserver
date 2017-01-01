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

class GameServerControllerGameServer extends JControllerForm
{
	function __construct()
	{
		parent::__construct();
	}

	public function add()
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
			$this->setRedirect( JRoute::_('index.php?option=com_gameserver&view=addserver'),JText::_('COM_GAMESERVER_ADDSERVER_NOACCESS') );
			return false;
		}

		$added = $model->insertItem($data);
		
		if ($added) 
		{
			$app->setUserState('com_squadmanagement.joinus.data', null);
			$this->setRedirect( JRoute::_('index.php?option=com_gameserver&view=addserver'),JText::_('COM_GAMESERVER_ADDSERVER_DONE') );
			return true;
		}
		
		$this->setRedirect( JRoute::_('index.php?option=com_gameserver&view=addserver'), JText::_('COM_GAMESERVER_ADDSERVER_FAILED') );
		return false;	
	}
}
?>