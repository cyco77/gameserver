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

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

class GameServerModelGameserver extends JModelAdmin
{
	public function getTable($type = 'Gameserver', $prefix = 'GameserverTable', $config = array()) 
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true) 
	{
		// Get the form.
		$form = $this->loadForm('com_gameserver.gameserver', 'gameserver', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) 
		{
			return false;
		}
		return $form;
	}

	public function getScript() 
	{
		return 'administrator/components/com_gameserver/models/forms/gameserver.js';
	}

	protected function loadFormData() 
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_gameserver.edit.gameserver.data', array());
		if (empty($data)) 
		{
			$data = $this->getItem();
		}
		return $data;
	}
	
	public function save($data)
	{	
		try
		{
			$geodata = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$data['ip']));

			$data['country'] = $geodata['geoplugin_countryCode'];
			$data['region'] = $geodata['geoplugin_continentCode'];
		}
		catch(exception $e )
		{
			$data['country'] = null;
			$data['region'] = null;
		}
		
		// fixing port2, user & pass
		if (!isset($data['port2'])) { $data['port2'] = ''; }
		if (!isset($data['user'])) { $data['user'] = ''; }
		if (!isset($data['pass'])) { $data['pass'] = ''; }
		$data['cachedatetime'] = '';
						
		if ($data['addedby'] == '-1' || $data['addedby'] == '0' || $data['addedby'] == '')
		{
			$user = JFactory::getUser();
			if ($user->guest) 
			{
				JError::raiseError(500, 'NOT_LOGGED_IN');
			}
			
			$userid = $user->get('id');			
			$data['addedby'] = $userid;
		}
		
		$result = parent::save($data);
		
		if ($data->ordering < 1)
		{
			$table = $this->getTable();
			$table->reorder();	
		}
		
		return $result;
	}
}
