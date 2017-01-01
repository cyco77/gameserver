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

class bf2Renderer extends abstractRenderer  {
	
	protected function getHeaderValues()
	{
		$bots = $this->getBotsCount();

		$array = array();
		
		$array[JText::_('SERVERNAME')] = $this->getHostname();
		if ($this->params->get( 'hideipaddresses' , 0) == 0) 
		{
			$array[JText::_('IP')] = '<div style="float:left;margin-right:5px;">'.$this->getProtectedImage('password','1').'</div><div>'.$this->getConnectLink().'</div>'; 
		}
		$array[JText::_('PLAYERS')] = $this->getFormatedPlayerCount();
		if ($bots > 0)
		{
			$array[JText::_('BOTS')] = $bots();
		}
		$array[JText::_('MAP')] = $this->getMapname();
		$array[JText::_('VARIANT')] = $this->getGamevariant();
		$array[JText::_('GAMETYPE')] = $this->getGametype();
		$array[JText::_('VERSION')] = $this->data['gamever'];
		
		return $array;	
	}
	
	protected function isAllowedProperty($property)
	{
		if ($property == 'sv_punkbuster') return false;
		if ($property == 'version') return false;
		if ($property == 'hostname') return false;
		if ($property == 'hostport') return false;
		if ($property == 'bf2_team1') return false;
		if ($property == 'bf2_team2') return false;
		if ($property == 'gametype') return false;
		if ($property == 'mapname') return false;
		if ($property == 'maxplayers') return false;
		if ($property == 'numplayers') return false;
		if ($property == 'players') return false;
		if ($property == 'teams') return false;
		
		return parent::isAllowedProperty($property);
	}
	
	protected function getPlayerIcon($player)
	{
		$imagePath = JURI::base().'components/com_gameserver/images/';
		
		$result = $imagePath.'player.png';

		if ($player['AIBot_'] == 1) { $result = $imagePath.'bot.png'; }
		
		return $result;
	}
	
	protected function getBotsCount()
	{
		if (!$this->data['gq_online']) 
		{
			return 0;
		}
		
		$bots = 0;			
		foreach ($this->data['players'] as $player)
		{													
			if ($player['AIBot_'] == 1)
			{
				$bots++;	
			}			
		}	
				
		return $bots;			
	}
	
	protected function getPlayerValues()
	{
		if ($this->detailviewteamview)
		{		
			return array(
				new playerValues(JText::_('PLAYERNAME'),'gq_name',alignment::left,false),
				new playerValues(JText::_('SCORE'),'score_',alignment::center),
				new playerValues(JText::_('FRAGS'),'skill_',alignment::center),
				new playerValues(JText::_('DEATHS'),'deaths_',alignment::center),
				new playerValues(JText::_('PING'),'gq_ping',alignment::center),
				);
		}
		else
		{
			return array(
				new playerValues(JText::_('TEAM'),'team_'),
				new playerValues(JText::_('PLAYERNAME'),'gq_name',alignment::left,false),
				new playerValues(JText::_('SCORE'),'score_',alignment::center),
				new playerValues(JText::_('FRAGS'),'skill_',alignment::center),
				new playerValues(JText::_('DEATHS'),'deaths_',alignment::center),
				new playerValues(JText::_('PING'),'gq_ping',alignment::center),
				);
		}		
	}
	
	public function getGamevariant()
	{
		$variant = '';
		if ($this->data['gamevariant'] == 'bf2') { $variant = 'Battlefield 2'; }
		elseif ($this->data['gamevariant'] == 'aix2') { $variant = 'AIX'; }
		elseif ($this->data['gamevariant'] == 'bfp2') { $variant = 'Battlefield Pirates 2'; }
		elseif ($this->data['gamevariant'] == 'dcon') { $variant = 'Desert Conflict'; }
		elseif ($this->data['gamevariant'] == 'bfa') { $variant = 'Battlefield Apocalypse'; }
		elseif ($this->data['gamevariant'] == 'pr') { $variant = 'Project Reality'; }
		elseif ($this->data['gamevariant'] == 'fh2') { $variant = 'Forgotten Hope 2'; }
		elseif ($this->data['gamevariant'] == 'alpha_project') { $variant = 'Alpha Project'; }
		elseif ($this->data['gamevariant'] == 'opk2') { $variant = 'Operation Peacekeeper 2'; }
		elseif ($this->data['gamevariant'] == 'usi2') { $variant = 'US Intervention'; }
		elseif ($this->data['gamevariant'] == 'poe2') { $variant = 'Point of Existence 2'; }
		else { $variant = $this->data['gamevariant']; }

		return $variant;
	}

	
	public function getGametype()
	{
		$mode = '';
		
		if ($this->data['gq_gametype'] == 'gpm_cq') { $mode = 'Assault and Secure'; }
		elseif ($this->data['gq_gametype'] == 'gpm_counter') { $mode = 'Counter-Attack'; }
		elseif ($this->data['gq_gametype'] == 'gpm_insurgency') { $mode = 'Insurgency'; }
		elseif ($this->data['gq_gametype'] == 'gpm_cnc') { $mode = 'Command & Control'; }
		elseif ($this->data['gq_gametype'] == 'gpm_training') { $mode = 'Training'; }
		else { $mode = $this->data['gq_gametype']; }
		
		return $mode;	
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
		$output = '<img src="'. JURI::base().'components/com_gameserver/images/icons/bf2/'.strtolower($this->data['bf2_team1']).'.jpg' .'" border="0" alt="'.$this->data['bf2_team1'].'" />&nbsp;';
		$output .= $this->countPlayersBy($this->data['players'],'team_','1').' '. JText::_('PLAYERS');
		
		return $output;
	}
	
	public function renderTeam2Header()
	{
		$output = '<img src="'. JURI::base().'components/com_gameserver/images/icons/bf2/'.strtolower($this->data['bf2_team2']).'.jpg' .'" border="0" alt="'.$this->data['bf2_team2'].'" />&nbsp;';
		$output .= $this->countPlayersBy($this->data['players'],'team_','2').' '. JText::_('PLAYERS');
		
		return $output;
	}
	
	public function getConnectLink()
	{	
		if ($this->data['hostport'] != '')
		{
			$ip = $this->data['orgaddress'].':'.$this->data['hostport'] ;			
		}
		else
		{
			$ip = $this->data['orgaddress'].':'.$this->data['gq_port'] ;			
		}
		
		return '<a href="hlsw://'.$ip.'" title="'.$ip.'">'.$ip.'</a>';
	}
	
	protected function isModAllowed()
	{
		return true;
	}
}

?>