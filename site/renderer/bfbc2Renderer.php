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
include_once JPATH_SITE.DS.'components'.DS.'com_gameserver'.DS.'renderer'.DS.'squad.php';

class bfbc2Renderer extends abstractRenderer  {
	
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
			case "levels/mp_001": return "Panama Canal";
			case "levels/mp_003": return "Laguna Alta";
			case "levels/mp_005": return "Atacama Desert";
			case "levels/mp_007": return "White Pass";
			case "levels/mp_009cq": return "Laguna Presa";
			case "levels/mp_006cq": return "Arica Harbor";
			case "levels/mp_012cq": return "Port Valdez";
			case "levels/mp_008cq": return "Nelson Bay";
			case "levels/bc1_oasis": return "Oasis";
			case "levels/bc1_harvest_day": return "Harvest Day";
			case "levels/mp_sp_005cq": return "Heavy Metal";
			case "levels/mp_002": return "Valparaiso";
			case "levels/mp_004": return "Rush Isla Inocentes";
			case "levels/mp_006": return "Arica Harbor";
			case "levels/mp_008": return "Rush Nelson Bay";
			case "levels/mp_012gr": return "Port Valdez";
			case "levels/mp_009gr": return "Laguna Presa";
			case "levels/mp_005gr": return "Atacama Desert";
			case "levels/mp_007gr": return "White Pass";
			case "levels/bc1_oasis_gr": return "Oasis";
			case "levels/bc1_harvest_day_gr": return "Harvest Day";
			case "levels/mp_sp_002gr": return "Cold War";
			case "levels/mp_001sr": return "Panama Canal";
			case "levels/mp_002sr": return "Valparaiso";
			case "levels/mp_005sr": return "Atacama Desert";
			case "levels/mp_012sr": return "Port Valdez";
			case "levels/mp_003sr": return "Laguna Alta";
			case "levels/mp_009sr": return "Laguna Presa";
			case "levels/bc1_oasis_sr": return "Oasis";
			case "levels/bc1_harvest_day_sr": return "Harvest Day";
			case "levels/mp_sp_002sr": return "Cold War";
			case "levels/mp_004sdm": return "Isla Inocentes";
			case "levels/mp_006sdm": return "Arica Harbor";
			case "levels/mp_007sdm": return "White Pass";
			case "levels/mp_009sdm": return "Laguna Presa";
			case "levels/mp_008sdm": return "Nelson Bay";
			case "levels/mp_001sdm": return "Panama Canal";
			case "levels/bc1_oasis_sdm": return "Oasis";
			case "levels/bc1_harvest_day_sdm": return "Harvest Day";
			case "levels/mp_sp_002sdm": return "Cold War";
			case "levels/mp_sp_005sdm": return "Heavy Metal";
			case "levels/nam_mp_002cq": return "Vantage Point";
			case "levels/nam_mp_003cq": return "Hill 137";
			case "levels/nam_mp_005cq": return "Cao Son Temple";
			case "levels/nam_mp_006cq": return "Phu Bai Valley";
			case "levels/nam_mp_007cq": return "Operation Hastings";
			case "levels/nam_mp_002r": return "Vantage Point";
			case "levels/nam_mp_003r": return "Hill 137";
			case "levels/nam_mp_005r": return "Cao Son Temple";
			case "levels/nam_mp_006r": return "Phu Bai Valley";
			case "levels/nam_mp_007r": return "Operation Hastings";
			case "levels/nam_mp_002sr": return "Vantage Point";
			case "levels/nam_mp_003sr": return "Hill 137";
			case "levels/nam_mp_005sr": return "Cao Son Temple";
			case "levels/nam_mp_006sr": return "Phu Bai Valley";
			case "levels/nam_mp_007sr": return "Operation Hastings";
			case "levels/nam_mp_002sdm": return "Vantage Point";
			case "levels/nam_mp_003sdm": return "Hill 137";
			case "levels/nam_mp_005sdm": return "Cao Son Temple";
			case "levels/nam_mp_006sdm": return "Phu Bai Valley";
			case "levels/nam_mp_007sdm": return "Operation Hastings";
			
			default:
				return parent::getMapname();
		}	
	}
	
	public function renderTeamPlayerList($teamattribute,$teamattributevalue1,$teamattributevalue2)
	{		
		// 1st Column - Team 1
		
		$output = '<table width="100%">';
		$output .= '<tr>';
		$output .= '<td>';
		$output .= $this->renderTeam1Header();
		$output .= '</td>';
		$output .= '<td>&nbsp;';
		$output .= '</td>';
		$output .= '<td>';
		$output .= $this->renderTeam2Header();
		$output .= '</td>';
		$output .= '</tr>';
		$output .= '<tr>';
		$output .= '<td valign="top">';
		$output .= '<table width="100%" border="0" cellspacing="1" cellpadding="3" class="gameserver_table" >';				
		$output .= '<tr>';
		$output .= '<th class="gameserver_titel"></th>';
		
		$output .= $this->renderPlayerHeader();

		$output .= '</tr>';
		
		$team1 = $this->filterPlayersByTeam($this->data['players'],1);		
		$squads = $this->getSquads($team1);		
		$output .= $this->renderSquads($squads);
		
		$output .= '</table>';
		$output .= '</td>';		
		
		// Divider
		$output .= '<td>&nbsp;';	
		
		// 2nd Column - Team 2
		
		$output .= '</td>';		
		$output .= '<td valign="top">';
		$output .= '<table width="100%" border="0" cellspacing="1" cellpadding="3" class="gameserver_table" >';				
		$output .= '<tr>';
		$output .= '<th class="gameserver_titel"></th>';
		
		$output .= $this->renderPlayerHeader();

		$output .= '</tr>';
		
		$team2 = $this->filterPlayersByTeam($this->data['players'],2);

		$squads = $this->getSquads($team2);		
		$output .= $this->renderSquads($squads);

		$output .= '</table>';
		$output .= '</td>';		
		$output .= '</tr>';
		$output .= '</table>';

		return $output;
	}
	
	private function renderSquads($squads)
	{		
		foreach ($squads as $squad)
		{			
			$columns = count($this->getPlayerValues());
			
			$output .= '<tr>';
			$output .= '<td class="gameserver_titel">&nbsp;</td><td colspan="'.$columns.'" class="gameserver_titel">';
			
			$output .= 'Squad: '.( ($squad->squadNo == 0) ? JText::_('NOSQUAD') : $squad->squadNo);
			$output .= '</td>';
			$output .= '</tr>';
			
			foreach ($squad->playerList as $player){				
				$output .= $this->renderPlayerValues($player);		
			}				
		}	
		
		return $output;
	}
	
	private function isInTeam1($player)
	{
		return $player['teamId'] == '1';
	}

	private function isInTeam2($player)
	{
		return $player['teamId'] == '2';
	}	
	
	private function isInSquad1($player)
	{
		return $player['squadId'] == '1';
	}
	
	private function filterPlayersBySquad($players,$squad)
	{
		$array = array_filter($players,array($this, "isInSquad".$squad));
		
		return $array;
	}
	
	private function filterPlayersByTeam($players,$team)
	{
		$array = array_filter($players,array($this, "isInTeam".$team));
		
		return $array;
	}
	
	private function getSquads($players)
	{
		$squads = array();
		
		$max = 0;
		foreach($players as $player)
		{
			if($player['squadId'] > $max)
			{
				$max = $player['squadId'];
			}
		}		
		
		for ($i = 0; $i <= $max; $i++) 
		{
			$squad = $this->getSquadMembers($players,$i);
			if (count($squad) > 0)
			{
				array_push($squads,new squad($i,$squad));
			}
			
		}
		
		return $squads;
	}
	
	private function getSquadMembers($players, $squadNo)
	{
		$members = array();
		
		foreach ($players as $player)
		{
			if ($player['squadId'] == $squadNo)
			{
				array_push($members,$player);
			}
		}
		
		return $members;
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