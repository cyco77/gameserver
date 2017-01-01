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

class bf4Renderer extends abstractRenderer  {
	
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
				new playerValues(JText::_('PLAYERNAME'),'gq_name',alignment::left,false),
				new playerValues(JText::_('SCORE'),'score',alignment::center),
				new playerValues(JText::_('FRAGS'),'kills',alignment::center),
				new playerValues(JText::_('DEATHS'),'deaths',alignment::center),
				);
		}
		else
		{
			return array(
				new playerValues(JText::_('TEAM'),'clateamIdnTag'),
				new playerValues(JText::_('PLAYERNAME'),'gq_name',alignment::left,false),
				new playerValues(JText::_('SCORE'),'score',alignment::center,false),
				new playerValues(JText::_('FRAGS'),'kills',alignment::center),
				new playerValues(JText::_('DEATHS'),'deaths',alignment::center),
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
			case "mp_abandoned": return "Zavod 311";
			case "mp_damage": return "Lancang Dam";
			case "mp_flooded": return "Flood Zone";
			case "mp_journey": return "Golmud Railway";
			case "mp_naval": return "Paracel Storm";
			case "mp_prison": return "Operation Locker";
			case "mp_resort": return "Hainan Resort";
			case "mp_siege": return "Siege Of Shanghai";
			case "mp_thedish": return "Rogue Transmission";
			case "mp_tremors": return "Dawnbreaker";
			
			case "xp4_wlkrftry": return "Giants of Karelia";
			case "xp4_subbase": return "Hammerhead";
			case "xp4_titan": return "Hangar 21";
			case "xp4_arctic": return "Operation Whiteout";
			
			case "xp3_urbangdn": return "Lumphini Garden";
			case "xp3_marketpl": return "Pearl Market";
			case "xp3_prpganda": return "Propaganda";
			case "xp3_wtrfront": return "Sunken Dragon";
			
			case "xp2_001": return "Lost Islands";
			case "xp2_002": return "Nansha Strike";
			case "xp2_004": return "Operation Mortar";
			case "xp2_003": return "Wave Breaker";
			case "xp1_002": return "Altai Range";
			case "xp1_004": return "Dragon Pass";
			case "xp1_003": return "Guilin Peaks";
			case "xp1_001": return "Silk Road";
			case "xp0_caspian": return "Caspian Border 2014";
			case "xp0_oman": return "Gulf of Oman 2014";
			case "xp0_metro": return "Operation Metro 2014";
			case "xp0_firestorm": return "Firestorm 2014";
			default:
				return parent::getMapname();
		}	
	}
	
	public function getGametype()
	{
		$mode = $this->data['gq_gametype'];	
		
		switch ($mode)
		{
			case "ConquestAssaultSmall0":
				$mode = "Conquest Assault";
				break;
			case "ConquestAssaultSmall1":
				$mode = "Conquest Assault";
				break;
			case "ConquestAssaultLarge1":
				$mode = "Conquest Assault Large";
				break;
			case "ConquestLarge0":
				$mode = "Conquest Large";
				break;
			case "ConquestSmall0":
				$mode = "Conquest";
				break;
			case "Rush0":
				$mode = "Rush";
				break;
			case "RushLarge0":
				$mode = "Rush";
				break;
			case "SquadRush0":
				$mode = "Squad Rush";
				break;
			case "SquadDeathMatch0":
				$mode = "Squad Deathmatch";
				$mode;
			case "TeamDeathMatch0":
				$mode = "Team Deathmatch";
				break;
			case "GunMaster0":
				$mode = "Gun Master";
				break;
			case "TankSuperiority0":
				$mode = "Tank Superiority";
				break;
			case "Scavenger0":
				$mode = "Scavenger";
				break;
			case "Domination0":
				$mode = "Domination";
				break;
		}		
		
		return $mode;	
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
	
	protected function renderPlayerValues($player)
	{
		$imagepath = JURI::base().'components/com_gameserver/images/ranks/bf4/'.$player['rank'].'.png';
		
		$output .=  '<tr class="gameserver_line">';
		$output .=  '<td class="gameserver_value" align="center">';
		$output .=  '<img src="'.$imagepath.'" border="0" alt="'.$player['rank'].' title="'.$player['rank'].'" />';
		$output .=  '</td>';	
							
		foreach ($this->getPlayerValues() as $playerValue)
		{
			$cssclass = 'gameserver_value';
			if ($playerValue->alignment == alignment::right) { $cssclass = 'gameserver_value_right'; }
			if ($playerValue->alignment == alignment::center) { $cssclass =   'gameserver_value_center'; }
			
			$output .=   '<td class="'.$cssclass.'">';
			if ($playerValue->attribute == 'gq_name')
			{
				$name = $this->getPlayerValue($player,$playerValue->attribute);
				$playUrl = 'http://battlelog.battlefield.com/bf4/user/'.$name;
				
				$output .= '<a href="'.$playUrl.'" target="blank">'.$name.'</a>';
			}
			else
			{
				$output .=   $this->getPlayerValue($player,$playerValue->attribute);	
			}			
			
			$output .=   '</td>';
		}
		$output .=   '</tr>';
		
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
	
	protected function renderModulePlayerValues($player)
	{
		$imagepath = JURI::base().'components/com_gameserver/images/ranks/bf4/'.$player['rank'].'.png';
		
		$output .=  '<tr>';
		$output .=  '<td align="center">';
		$output .=  '<img src="'.$imagepath.'" border="0" style="width: 24px; height: 24px;" alt="'.$player['rank'].' title="'.$player['rank'].'" />';
		$output .=  '</td>';
		
		foreach ($this->getModulePlayerValues() as $playerValue)
		{
			$cssvalue = 'left';
			if ($playerValue->alignment == alignment::right) { $cssvalue = 'right'; }
			if ($playerValue->alignment == alignment::center) { $cssvalue = 'center'; }
			
			$output .=   '<td style="text-align: '.$cssvalue.'">';
			if ($playerValue->attribute == 'gq_name')
			{
				$name = $this->getPlayerValue($player,$playerValue->attribute);
				$playUrl = 'http://battlelog.battlefield.com/bf4/user/'.$name;
				
				$output .= '<a href="'.$playUrl.'" target="blank">'.$name.'</a>';
			}
			else
			{
				$output .=   $this->getPlayerValue($player,$playerValue->attribute);	
			}		
			$output .=   '</td>';
		}
		
		$output .=   '</tr>';
		
		return $output;
	}
}

?>