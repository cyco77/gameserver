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

class dayzRenderer extends abstractRenderer  {
	
	protected function getHeaderValues()
	{
		$array = array();
		
		$array[JText::_('SERVERNAME')] = $this->getHostname();
		if ($this->params->get( 'hideipaddresses' , 0) == 0) 
		{
			$array[JText::_('IP')] = $this->getConnectLink(); 
		}
		$array[JText::_('PLAYERS')] = $this->getFormatedPlayerCount();
		$array[JText::_('MAP')] = $this->getMapname();
		
		return $array;
	}
	
	protected function getPlayerValues()
	{
		return array(
			new playerValues(JText::_('PLAYERNAME'),'gq_name',alignment::left,false),
			new playerValues(JText::_('SCORE'),'gq_score',alignment::center,false),
			new playerValues(JText::_('TIMEONSERVER'),'time',alignment::center),	
			);
	}
	
	protected function getPlayerValue($player, $attribute)
	{
		if ($attribute == 'time')
		{
			return ($player[$attribute] == -1) ? '-' : gmdate("H:i:s", $player[$attribute]);
		}
		else
		{
			return parent::getPlayerValue($player,$attribute);	
		}
	}
	
	protected function isAllowedProperty($property)
	{
		if ($property == 'teams') return false;
		if ($property == 'version') return false;
		if ($property == 'steamappid') return false;
		if ($property == 'secure') return false;
		if ($property == 'protocol') return false;
		if ($property == 'modHashes:0-1') return false;
		if ($property == 'max_players') return false;
		if ($property == 'map') return false;
		if ($property == 'hostname') return false;
		if ($property == 'hash') return false;
		if ($property == 'game_dir') return false;
		if ($property == 'dedicated') return false;
		
		return parent::isAllowedProperty($property);
	}	
}

?>