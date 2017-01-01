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
		$params = JComponentHelper::getParams( 'com_gameserver' );
		
		$user = JFactory::getUser();
		if ($user->guest) {
			JError::raiseError(500, 'NOT_LOGGED_IN');
		}
		
		$userid = $user->get('id');		
		
		// set the data into a query to update the record
		$db	= JFactory::getDBO();
		
		$gameserverItem =new stdClass();
		
		$gameserverItem->serverid = null;
		$gameserverItem->displayname = $data['displayname'];
		$gameserverItem->type = $data['type'];
		$gameserverItem->ip = $data['ip'];
		$gameserverItem->port = $data['port'];
		$gameserverItem->port2 = $data['port2'];
		$gameserverItem->user = $data['user'];
		$gameserverItem->pass = $data['pass'];
		$gameserverItem->url = $data['url'];
		$gameserverItem->showsettings = $data['showsettings'];
		
		try
		{
			$geodata = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$data['ip']));

			$gameserverItem->country = $geodata['geoplugin_countryCode'];
			$gameserverItem->region = $geodata['geoplugin_continentCode'];
		}
		catch(exception $e )
		{
			$gameserverItem->region = null;
			$gameserverItem->country = null;
		}
		
		$gameserverItem->description = $data['description'];
		$gameserverItem->addedby = $userid;
		$gameserverItem->published = $params->get('addserver_enable','1');				

		$db = JFactory::getDBO();
		$db->insertObject('#__gameserver', $gameserverItem);
				
		if ($db->getErrorMsg()) 
		{
			JError::raiseError(500, $db->getErrorMsg());
			return false;
		} 
						
		return true;		
	}
}
