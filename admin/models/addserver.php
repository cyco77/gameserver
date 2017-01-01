<?php
/*------------------------------------------------------------------------
# com_squadmanagement - Squadmanagement!
# ------------------------------------------------------------------------
# author    Lars Hildebrandt
# copyright Copyright (C) 2014 Lars Hildebrandt. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.larshildebrandt.de
# Technical Support:  Forum - http://www..larshildebrandt.de/forum/
-------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modelform');

require_once JPATH_SITE.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_squadmanagement'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'squadmanagementhelper.php';	

class GameServerModelAddserver extends JModelForm
{
	protected function populateState()
	{		
		$params = JFactory::getApplication()->getParams();
		$this->setState('params', $params);
	}
	
	public function getForm($data = array(), $loadData = true) 
	{
		// Get the form.
		$form = $this->loadForm('com_gameserver.addserver', 'addserver', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) 
		{
			return false;
		}
		
		return $form;
	}
	
	protected function loadFormData()
	{
		$data = (array)JFactory::getApplication()->getUserState('com_gameserver.addserver.data', array());
		return $data;
	}
	
	public function insertItem($data)
	{		
		$user = JFactory::getUser();
		if ($user->guest) {
			JError::raiseError(500, 'NOT_LOGGED_IN');
		}
		if (SquadmanagementHelper::isUserInSquad($user->get('id'), $data['squadid']))
		{
			JError::raiseError(500, 'USER_ALREADY_IN_SQUAD');
		}
		
		$userid = $user->get('id');
		
		// set the data into a query to update the record
		$db	= JFactory::getDBO();
		
		$joinus =new stdClass();
		$joinus->id = NULL;
		$joinus->userid = $userid;
		$joinus->memberstate = 0;
		$joinus->squadid = $data['squadid'];
		$joinus->joinusdescription = $data['joinusdescription'];
		$joinus->published = 0;

		$db = JFactory::getDBO();
		$db->insertObject('#__squad_member', $joinus);
		
		if (!SquadmanagementHelper::hasMemberAdditionalInfoEntry($userid))
		{
			$memberimage = new stdClass();
			$memberimage->id = null;
			$memberimage->userid = $userid;
			$memberimage->displayname = $data['displayname'];
			$memberimage->imageurl = null;
			
			$db = JFactory::getDBO();
			$db->insertObject( '#__squad_member_additional_info', $memberimage );				
		}
		
		if ($db->getErrorMsg()) 
		{
			JError::raiseError(500, $db->getErrorMsg());
			return false;
		} 
		
		$params = JComponentHelper::getParams( 'com_squadmanagement' );
		
		$enablejoinusmail = $params->get('enablejoinusmail','1');
		if ($enablejoinusmail == 1)
		{			
			SquadmanagementHelper::sendJoinUsMail($joinus,$params);
		}
		
		return true;		
	}
}
