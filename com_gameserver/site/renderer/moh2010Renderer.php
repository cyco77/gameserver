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

class moh2010Renderer extends abstractRenderer  {
	
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
	
	protected function isAllowedProperty($property)
	{
		if ($property == 'sv_punkbuster') return false;
		if ($property == 'hostname') return false;
		if ($property == 'hostport') return false;
		if ($property == 'gametype') return false;
		if ($property == 'mapname') return false;
		if ($property == 'maxplayers') return false;
		if ($property == 'numplayers') return false;
		if ($property == 'players') return false;
		if ($property == 'map') return false;
		
		return parent::isAllowedProperty($property);
	}
	
	protected function getPlayerValues()
	{
		if ($this->detailviewteamview)
		{		
			return array(
				new playerValues(JText::_('CLANTAG'),'clanTag'),
				new playerValues(JText::_('PLAYERNAME'),'gq_name',alignment::left,false),
				new playerValues(JText::_('SCORE'),'score',alignment::center),
				new playerValues(JText::_('FRAGS'),'kills',alignment::center),
				new playerValues(JText::_('DEATHS'),'deaths',alignment::center),
				new playerValues(JText::_('PING'),'gq_ping',alignment::center),
				);
		}
		else
		{
			return array(
				new playerValues(JText::_('TEAM'),'clateamIdnTag'),
				new playerValues(JText::_('CLANTAG'),'clanTag'),
				new playerValues(JText::_('PLAYERNAME'),'gq_name',alignment::left,false),
				new playerValues(JText::_('SCORE'),'score',alignment::center,false),
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
			return $this->renderTeamPlayerList('teamId','1','2');
		}
		else
		{
			return parent::renderPlayerList();	
		}		
	}	
	
	public function renderTeam1Header()
	{
		$output .= $this->countPlayersBy($this->data['players'],'teamId','1').' '. JText::_('PLAYERS');
		
		return $output;
	}
	
	public function renderTeam2Header()
	{
		$output .= $this->countPlayersBy($this->data['players'],'teamId','2').' '. JText::_('PLAYERS');
		
		return $output;
	}
	
	public function getMapname()
	{
		switch (strtolower(parent::getMapname()))
		{			
			case "levels/mp_01":
				return "Mazar-i-Sharif Airfield";			
			case "levels/mp_02":
				return "Shah-i-Khot Mountains";
			case "levels/mp_04":
				return "Helmand Valley";
			case "levels/mp_05_domination":
				return "Kandahar Marketplace";
			case "levels/mp_05_overrun":
				return "Kandahar Marketplace";
			case "levels/mp_05_tdm":
				return "Kandahar Marketplace";
			case "levels/mp_06_domination":
				return "Diwagal Camp";
			case "levels/mp_06_overrun":
				return "Raid Diwagal Camp";
			case "levels/mp_06_tdm":
				return "Diwagal Camp";
			case "levels/mp_08_domination":
				return "Kunar Base";
			case "levels/mp_08_overrun":
				return "Kunar Base";
			case "levels/mp_08_tdm":
				return "Kunar Base";
			case "levels/mp_09_domination":
				return "Kabul City Ruins";
			case "levels/mp_09_overrun":
				return "Kabul City Ruins";
			case "levels/mp_09_tdm":
				return "Kabul City Ruins";
			case "levels/mp_10_domination":
				return "Garmzir Town";
			case "levels/mp_10_overrun":
				return "Garmzir Town";
			case "levels/mp_10_tdm":
				return "Garmzir Town ";
			default:
				return parent::getMapname();
		}		
	}
	
	protected function getModulePlayerValues()
	{
		return array(
			new playerValues(JText::_('PLAYERNAME'),'gq_name'),
			new playerValues(JText::_('SCORE'),'score',alignment::center)
			);
	}
}

?>