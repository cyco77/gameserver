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

class haloRenderer extends abstractRenderer  {
	
	protected function getHeaderValues()
	{		
		$array = array();
		
		$array[JText::_('SERVERNAME')] = $this->getHostname();
		if ($this->params->get( 'hideipaddresses' , 0) == 0) 
		{
			$array[JText::_('IP')] = '<div style="float:left;margin-right:5px;">'.$this->getProtectedImage('password','1').'</div><div>'.$this->getConnectLink().'</div>'; 
		}
		$array[JText::_('PLAYERS')] = $this->getFormatedPlayerCount();
		$array[JText::_('MAP')] = $this->getMapname();
		$array[JText::_('GAMETYPE')] = $this->getGametype();
		$array[JText::_('VARIANT')] = $this->getGamevariant();
		$array[JText::_('VERSION')] = $this->data['gamever'];
		
		return $array;
	}
	
	protected function isAllowedProperty($property)
	{
		if ($property == 'sv_punkbuster') return false;
		if ($property == 'gamever') return false;
		if ($property == 'hostname') return false;
		if ($property == 'hostport') return false;
		if ($property == 'mapname') return false;
		if ($property == 'maxplayers') return false;
		if ($property == 'num_players') return false;
		if ($property == 'numplayers') return false;
		if ($property == 'num_teams') return false;
		if ($property == 'players') return false;
		
		return parent::isAllowedProperty($property);
	}
	
	public function getGamevariant()
	{
		return $this->data['gq_mod'];
	}

	
	public function getGametype()
	{
		return $this->data['gq_gametype'];	
	}
	
	protected function getPlayerValues()
	{
		if ($this->detailviewteamview)
		{		
			return array(
				new playerValues(JText::_('PLAYERNAME'),'gq_name'),
				new playerValues(JText::_('SCORE'),'gq_score',alignment::center)
				);
		}
		else
		{
			return array(
				new playerValues(JText::_('TEAM'),'team'),
				new playerValues(JText::_('PLAYERNAME'),'gq_name',alignment::center),
				new playerValues(JText::_('SCORE'),'gq_score',alignment::center)
				);
		}
	}
	
	public function renderPlayerList()
	{
		if ($this->detailviewteamview == '1')
		{
			return $this->renderTeamPlayerList('team','0','1');
		}
		else
		{
			return parent::renderPlayerList();	
		}		
	}
	
	public function renderTeam1Header()
	{
		$output .= $this->countPlayersBy($this->data['players'],'team','0').' '. JText::_('PLAYERS');
		
		return $output;
	}
	
	public function renderTeam2Header()
	{
		$output .= $this->countPlayersBy($this->data['players'],'team','1').' '. JText::_('PLAYERS');
		
		return $output;
	}	
	
	protected function getPlayerValue($player, $attribute)
	{
		return utf8_encode($player[$attribute]);					
	}	
}

?>