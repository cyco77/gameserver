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

class quake3Renderer extends abstractRenderer  {
	
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
		$array[JText::_('GAMETYPE')] = $this->getGametype();
		
		return $array;	
	}
	
	protected function getPlayerValues()
	{
		return array(
			new playerValues(JText::_('PLAYERNAME'),'gq_name',alignment::left,false),
			new playerValues(JText::_('SCORE'),'gq_score',alignment::center,false),
			new playerValues(JText::_('PING'),'gq_ping',alignment::center),	
			);
	}
	
	protected function isAllowedProperty($property)
	{
		if ($property == 'gametype') return false;
		if ($property == 'g_gametype') return false;
		if ($property == 'mapname') return false;
		if ($property == 'maxplayers') return false;
		if ($property == 'num_players') return false;
		if ($property == 'numplayers') return false;
		if ($property == 'numteams') return false;			
		if ($property == 'hostname') return false;	
		if ($property == 'players') return false;	
		if ($property == 'current_round') return false;
		if ($property == 'gamever') return false;
		if ($property == 'sv_punkbuster') return false;
		if ($property == 'sv_hostname') return false;
		
		return parent::isAllowedProperty($property);
	}
	
	protected function getPlayerValue($player, $attribute)
	{
		if ($attribute == 'gq_name')
		{
			return colorRenderer::q3Name2HTML($player[$attribute]);
		}
		else
		{
			return $player[$attribute];				
		}		
	}	
	
	public function getHostname()
	{
		return colorRenderer::q3Name2HTML(parent::getHostname());
	}
}

?>