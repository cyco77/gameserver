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

class sourceRenderer extends abstractRenderer  {
	
	protected function getHeaderValues()
	{
		$array = array();
		
		$array[JText::_('SERVERNAME')] = $this->getHostname();
		if ($this->params->get( 'hideipaddresses' , 0) == 0) 
		{
			$array[JText::_('IP')] = '<div style="float:left;margin-right:5px;">'.$this->getProtectedImage('gq_password','1').'</div><div>'.$this->getConnectLink().'</div>'; 
		}
		$array[JText::_('PLAYERS')] = $this->getFormatedPlayerCount();
		if ($this->isModAllowed())
		{
			$array[JText::_('MODIFICATION')] = $this->getModName();
		}		
		$array[JText::_('MAP')] = $this->getMapname();
		$array[JText::_('ANTICHEATTOOL')] = $this->getAntiCheatTools();
		$array[JText::_('EXTENSIONS')] = $this->getExtensions();
		$array[JText::_('RANKS')] = $this->getRankingSystem();
		
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
		if ($property == 'teams') return false;	
		
		return parent::isAllowedProperty($property);
	}
	
	public function getMapname()
	{
		$mapname = $this->data['gq_mapname'];
		
		$pos = strpos($mapname, 'workshop');
		
		if ($pos !== false)
		{
			$mapname = substr (strrchr ($mapname, "/"), 1);
		}		
		
		return $mapname;
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
	
	private function getExtensions()
	{
		$tools = '';
		
		$tools = $this->getManiAdminPluginVersion();
		$tools == '' ? $tools = $this->getEventScriptVersion() : $tools .= $this->getEventScriptVersion() != '' ? '<br />' . $this->getEventScriptVersion() : '';
		$tools == '' ? $tools = $this->getMetaModVersion() : $tools .= $this->getMetaModVersion() != '' ? '<br />' . $this->getMetaModVersion() : '';
		$tools == '' ? $tools = $this->getSourceModVersion() : $tools .= $this->getSourceModVersion() != '' ? '<br />' . $this->getSourceModVersion() : '';
		$tools == '' ? $tools = $this->getCSSDeathMatchVersion() : $tools .= $this->getCSSDeathMatchVersion() != '' ? '<br />' . $this->getCSSDeathMatchVersion() : '';
		$tools == '' ? $tools = $this->getAmxModVersion() : $tools .= $this->getAmxModVersion() != '' ? '<br />' . $this->getAmxModVersion() : '';
		
		return $tools;		 
	}
	
	private function getRankingSystem()
	{
		$tools = '';
		
		$tools .= $this->getHLStatsXCEVersion();
		
		return $tools;		 
	}
	
	private function getAntiCheatTools()
	{
		$tools = '';
		
		$tools .= $this->getVac();
		$tools == '' ? $tools = $this->getZBlockVersion() : $tools .= $this->getZBlockVersion() != '' ? '<br />' . $this->getZBlockVersion() : '';
		$tools == '' ? $tools = $this->getSteamBansSourceVersion() : $tools .= $this->getSteamBansSourceVersion() != '' ? '<br />' . $this->getSteamBansSourceVersion() : '';
		
		return $tools;		 
	}
	
	private function getEventScriptVersion()
	{
		if (array_key_exists('eventscripts_ver',$this->data))
		{
			$version = $this->data['eventscripts_ver'];
			
			if ($version != '')
			{
				return 'EventScript ' . $version;
			}
		}
		
		return '';
	}
	
	private function getManiAdminPluginVersion()
	{
		if (array_key_exists('mani_admin_plugin_version',$this->data))
		{
			$version = $this->data['mani_admin_plugin_version'];
			
			if ($version != '')
			{
				return 'Mani Admin Plugin ' . $version;
			}
		}
		
		return '';
	}
	
	private function getMetaModVersion()
	{
		if (array_key_exists('metamod_version',$this->data))
		{
			$version = $this->data['metamod_version'];
			
			if ($version != '')
			{
				return 'MetaMod ' . $version;
			}
		}
		
		return '';
	}
	
	private function getAmxModVersion()
	{
		if (array_key_exists('amxmodx_version',$this->data))
		{
			$version = $this->data['amxmodx_version'];
			
			if ($version != '')
			{
				return 'AMXMODX ' . $version;
			}
		}
		
		return '';
	}
	
	private function getSourceModVersion()
	{
		if (array_key_exists('sourcemod_version',$this->data))
		{
			$version = $this->data['sourcemod_version'];
			
			if ($version != '')
			{
				return 'SourceMod ' . $version;
			}
		}
		
		return '';
	}
	
	private function getCSSDeathMatchVersion()
	{
		if (array_key_exists('cssdm_version',$this->data))
		{
			$version = $this->data['cssdm_version'];
			
			if ($version != '')
			{
				return 'CS:S Deathmatch ' . $version;
			}
		}
		
		return '';
	}
	
	private function getHLStatsXCEVersion()
	{
		$data = $this->data;
		
		if (array_key_exists('hlxce_version',$this->data))
		{
			$version = $data['hlxce_version'];
			
			if ($version != '')
			{
				$url = $data['hlxce_webpage'];
				$output =  'HLstatsX:CE ' . $version . (($url != '') ? ' - ' .'<a href="'.$url.'" target="_blank">'.$url.'</a>': '');
				return $output;
			}
		}
		
		return '';
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
	
	private function getZBlockVersion()
	{
		if (array_key_exists('zb_version',$this->data))
		{
			$version = $this->data['zb_version'];
			
			if ($version != '')
			{
				return 'ZBlock ' . $version;
			}
		}
		
		return '';
	}
	
	private function getSteamBansSourceVersion()
	{
		if (array_key_exists('sbsrc_version',$this->data))
		{
			$version = $this->data['sbsrc_version'];
			
			if ($version != '')
			{
				return 'SteamBans SouRCe ' . $version;
			}
		}
		
		return '';
	}
	
	protected function isModAllowed()
	{
		return ($this->data['gq_mod'] != '')
			&& ($this->data['gq_mod'] != 'cstrike')
			&& ($this->data['gq_mod'] != 'hl2mp')
			&& ($this->data['gq_mod'] != 'czero')
			&& ($this->data['gq_mod'] != 'dod')
			&& ($this->data['gq_mod'] != 'valve')
			&& ($this->data['gq_mod'] != 'csgo')
			&& ($this->data['gq_mod'] != 'aa4game')
			&& ($this->data['gq_mod'] != 'zps')
			&& ($this->data['gq_mod'] != 'tf')
			&& ($this->data['gq_mod'] != 'starbound')
			&& ($this->data['gq_mod'] != '7D2D')
			&& ($this->data['gq_mod'] != 'left4dead2')
			&& ($this->data['gq_mod'] != 'insurgency')
			&& ($this->data['gq_mod'] != 'garrysmod')
			&& (strpos($this->data['gq_mod'], 'homefront') === 0);
	}
	
	private function getModName()
	{
		switch ($this->data['gq_mod']){
			case "hl2mp":
				return "Halflife 2 Multiplayer";
				break;
			default:
				return $this->data['gq_mod'];
				break;
		}
	}
}

?>