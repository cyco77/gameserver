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

include_once JPATH_SITE.DS.'components'.DS.'com_gameserver'.DS.'renderer'.DS.'abstractRenderer.php';

class sevend2dRenderer extends abstractRenderer  {
	
	protected function getHeaderValues()
	{
		$array = array();
		
		$array[JText::_('SERVERNAME')] = $this->data['GameName'];
		if ($this->params->get( 'hideipaddresses' , 0) == 0) 
		{
			$array[JText::_('IP')] = '<div style="float:left;margin-right:5px;">'.$this->getProtectedImage('gq_password','1').'</div><div>'.$this->getConnectLink().'</div>'; 
		}
		$array[JText::_('PLAYERS')] = $this->getFormatedPlayerCount();	
		$array[JText::_('GAMETYPE')] = $this->data['GameMode'];	
		
		return $array;
	}
	
	public function showMapImage()
	{
		return false;	
	}
	
	protected function getPlayerValues()
	{
		return array(
			new playerValues(JText::_('PLAYERNAME'),'gq_name',alignment::left,false),
			new playerValues(JText::_('SCORE'),'gq_score',alignment::center,false),
			new playerValues(JText::_('TIMEONSERVER'),'time',alignment::center),	
			);
	}
		
	protected function getPlayersCount()
	{								
		if (!$this->data['gq_online']) 
		{
			return 0;
		}
		
		return $this->data['CurrentPlayers'] - $this->getBotsCount();		
	}
		
	protected function isAllowedProperty($property)
	{
		if ($property == 'hostname') return false;
		if ($property == 'map') return false;
		if ($property == 'max_players') return false;
		if ($property == 'game_descr') return false;
		if ($property == 'players') return false;
		if ($property == 'hostname') return false;	
		if ($property == 'GUID') return false;	
		if ($property == 'teams') return false;	
		if ($property == 'steamappid') return false;	
		if ($property == 'num_rules') return false;	
		if ($property == 'num_players') return false;	
		if ($property == 'gameinfo') return false;	
		if ($property == 'game_dir') return false;	
		if ($property == 'SteamID') return false;	
		if ($property == 'Port') return false;
		if ($property == 'Ping') return false;
		if ($property == 'Password') return false;
		if ($property == 'MaxPlayers') return false;
		if ($property == 'LevelName') return false;
		if ($property == 'IP') return false;
		if ($property == 'GameType') return false;
		if ($property == 'MaxPlayers') return false;
		if ($property == 'CurrentPlayers') return false;
		if ($property == 'GameName') return false;
		if ($property == 'GameMode') return false;
		if ($property == 'version') return false;
		if ($property == 'secure') return false;
		if ($property == 'protocol') return false;
		
		return parent::isAllowedProperty($property);
	}
}

?>