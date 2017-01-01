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

class lifeisfeudalRenderer extends abstractRenderer  {
	
	protected function getHeaderValues()
	{
		$array = array();
		
		$array[JText::_('SERVERNAME')] = $this->getHostname();
		if ($this->params->get( 'hideipaddresses' , 0) == 0) 
		{
			$array[JText::_('IP')] = '<div style="float:left;margin-right:5px;">'.$this->getProtectedImage('gq_password','1').'</div><div>'.$this->getConnectLink().'</div>'; 
		}
		$array[JText::_('PLAYERS')] = $this->getFormatedPlayerCount();	
		$array[JText::_('ANTICHEATTOOL')] = $this->getAntiCheatTools();
		
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
	
	protected function isAllowedProperty($property)
	{
		if ($property == 'hostname') return false;
		if ($property == 'map') return false;
		if ($property == 'max_players') return false;
		if ($property == 'game_descr') return false;
		if ($property == 'players') return false;
		if ($property == 'hostname') return false;	
		
		return parent::isAllowedProperty($property);
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
	
	private function getAntiCheatTools()
	{
		$tools = '';
		
		$tools .= $this->getVac();
		
		return $tools;		 
	}	
	
	private function getVac()
	{
		$version = $this->data['secure'];
		
		if ($version == '1')
		{
			return 'Valve Anti-Cheat';
		}
		
		return '';
	}
	
	public function showMapImage()
	{
		return false;	
	}
}

?>