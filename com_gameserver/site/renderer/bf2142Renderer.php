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

class bf2142Renderer extends abstractRenderer  {
	
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
		$array[JText::_('ANTICHEATTOOL')] = $this->getAntiCheatTools();
		$array[JText::_('VERSION')] = $this->data['gamever'];
		
		return $array;	
	}
	
	protected function isAllowedProperty($property)
	{
		if ($property == 'sv_punkbuster') return false;
		if ($property == 'version') return false;
		if ($property == 'hostname') return false;
		if ($property == 'hostport') return false;
		if ($property == 'bf2142_team1') return false;
		if ($property == 'bf2142_team2') return false;
		if ($property == 'gametype') return false;
		if ($property == 'mapname') return false;
		if ($property == 'maxplayers') return false;
		if ($property == 'numplayers') return false;
		if ($property == 'players') return false;
		if ($property == 'teams') return false;
		
		return parent::isAllowedProperty($property);
	}
	
	protected function getPlayerValues()
	{
		if ($this->detailviewteamview)
		{		
			return array(
				new playerValues(JText::_('PLAYERNAME'),'playername',alignment::left,false),
				new playerValues(JText::_('SCORE'),'score',alignment::center),
				new playerValues(JText::_('FRAGS'),'kills',alignment::center),
				new playerValues(JText::_('DEATHS'),'deaths',alignment::center),
				new playerValues(JText::_('PING'),'gq_ping',alignment::center),
				);
		}
		else
		{
			return array(
				new playerValues(JText::_('TEAM'),'team'),
				new playerValues(JText::_('PLAYERNAME'),'playername',alignment::left,false),
				new playerValues(JText::_('SCORE'),'score',alignment::center),
				new playerValues(JText::_('FRAGS'),'kills',alignment::center),
				new playerValues(JText::_('DEATHS'),'deaths',alignment::center),
				new playerValues(JText::_('PING'),'gq_ping',alignment::center),
				);
		}
	}
	
	public function renderPlayerList()
	{
		if ($this->detailviewteamview == '1')
		{
			return $this->renderTeamPlayerList('team_','1','2');
		}
		else
		{
			return parent::renderPlayerList();	
		}		
	}	
	
	public function renderTeam1Header()
	{
		$output = $this->data['bf2142_team1'].'&nbsp;';
		$output .= $this->countPlayersBy($this->data['players'],'team_','1').' '. JText::_('PLAYERS');
		
		return $output;
	}
	
	public function renderTeam2Header()
	{
		$output = $this->data['bf2142_team2'].'&nbsp;';
		$output .= $this->countPlayersBy($this->data['players'],'team_','2').' '. JText::_('PLAYERS');
		
		return $output;
	}
}

?>