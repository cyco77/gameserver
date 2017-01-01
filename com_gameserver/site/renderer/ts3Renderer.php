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

class ts3Renderer extends abstractRenderer  {
	
	protected function getHeaderValues()
	{
		$array = array();
		
		$array[JText::_('SERVERNAME')] = $this->getHostname();
		if ($this->params->get( 'hideipaddresses' , 0) == 0) 
		{
			$array[JText::_('IP')] = $this->getConnectLink(); 
		}
		$array[JText::_('PLAYERS')] = $this->getFormatedPlayerCount();
		
		return $array;
	}
	
	public function showMapImage()
	{
		return false;	
	}
	
	public function renderPlayerList()
	{
		$output = '<table width="100%">';
		$output .= '<tr>';
		$output .= '<td colspan="2">';
		$output .= '<table width="100%" border="0" cellspacing="1" cellpadding="3" class="simple_table" >';
		$output .= $this->printchannels($this->data,0,0);
		$output .= '</table>';
		$output .= '</td>';			
		$output .= '</tr>	';
		$output .= '</table>';
		
		return $output;
	}		
	
	protected function isAllowedProperty($property)
	{
		if ($property == 'players') return false;	
		if ($property == 'channels') return false;
		if (strpos($property, 'connection') === 0) return false;
		
		return parent::isAllowedProperty($property);
	}
	
	public function getCustomConnectLink()
	{
		$ip = $this->getIpPort();
		return '<a href="ts3server://'.$ip.'" title="'.$ip.'">'.$ip.'</a>';
	}
	
	function printchannels($data, $level, $parent) 
	{	
		foreach ($data['channels'] as $channel)
		{
			if ($channel['pid'] == $parent) 
			{			
				$output .= '<tr class="gameserver_line">'. "\n";
				$output .= '<td style="padding-left: '.(16*$level).'px;">'. "\n";
				
				$output .= '<table style="width: 100%;"><tr><td>'. "\n";
				
				$channelImageName = 'ts3_channel.png';
				
				if ($channel['channel_flag_password'] == 1) $channelImageName = 'ts3_channel_yellow.png';
				if ($channel['channel_maxclients'] > -1 && $channel['total_clients'] >= $channel['channel_maxclients']) $channelImageName = 'ts3_channel_red.png';
				
				$defaultChannelImage = '';
				
				if ($channel['channel_flag_default'] == 1) $defaultChannelImage = '<img src="'.JURI::base().'components/com_gameserver/images/icons/voice/ts3_channel_default.png" border="0" alt="Default-Channel" title="Default-Channel" />';
				
				$securedChannelImage = '';
				
				if ($channel['channel_flag_password'] == 1) $securedChannelImage = '<img src="'.JURI::base().'components/com_gameserver/images/icons/voice/ts3_channel_secured.png" border="0" alt="Secured-Channel" title="Secured-Channel" />';
							
				if(preg_match('/spacer/', $channel['channel_name']) !== 0)
				{
					$channelName = $this->replace_spacer($channel['channel_name']);			
				}
				else
				{
					$channelName = $channel['channel_name'];
					$channelName = $channel['channel_topic'] != '' ? $channelName.' - '.$channel['channel_topic'] : $channelName;
					$output .= '<img src="'.JURI::base().'components/com_gameserver/images/icons/voice/'.$channelImageName.'" border="0" alt="Channel" style="min-width:16px; max-width:16px;" title="Channel" />';   	
				}
				
				$output .= '</td><td width="100%">'.$channelName.'</td>';
				$output .= '<td>'.$defaultChannelImage.$securedChannelImage.'</td></tr></table>';
				
				$output .= '</td></tr>';	
				$output .= $this->printchannels($data, $level+1, $channel['cid']);
				
				if (isset($data['players']))
				{
					$output .= $this->printusersinchannel($data, $level+1, $channel['cid']);
				}
			}	
		}
		
		return $output;
	}

	function replace_spacer($channel_name)
	{
		// Init dimension for the repeat spacer
		$dim = 20;
		
		// Spacer Treatment
		if(preg_match('/spacer/', $channel_name) !== 0)
		{
			$traite = explode("]", $channel_name);
			
			/*
			 * $traite[0] = [..spacer#
			 * $traite[1] = the display channel name
			*/
			
			if($traite[1] != "")
			{
				switch (substr($traite[0], 1, 1)) 
				{
					case "*" :	
						$channel_name = "<div>";
						for($i=0; $i<$dim; $i++)
						{
							$channel_name .= $traite[1];
						}
						$channel_name .= "</div>";
						break;
					case "l" :
						$channel_name = "<div align='left'>".$traite[1]."</div>";
						break;
					case "c" :
						$channel_name = "<div align='center'>".$traite[1]."</div>";
						break;
					case "r" :
						$channel_name = "<div align='right'>".$traite[1]."</div>";
						break;
					case "s" :
						$channel_name = "<div align='left'>".$traite[1]."</div>";
						break;
				}
			}
		}
		
		return $channel_name;
	}

	function printusersinchannel($data, $level, $parent) 
	{
		foreach ($data['players'] as $player)
		{		
			if ($player['cid'] == $parent) 
			{			
				$output .= '<tr class="gameserver_line"><td style="padding-left: '.(16*$level).'px;">';
				
				$output .= '<table><tr><td>';
				
				$user = $player['client_nickname'];
				$userImage = 'ts3_player.png';
				
				if ($player['client_input_muted']) $userImage = 'ts3_player_input_muted.png';
				if ($player['client_output_muted']) $userImage = 'ts3_player_output_muted.png';
				
				if ($player['client_input_hardware'] != 1) $userImage = 'ts3_player_inputhardware_disabled.png';
				if ($player['client_output_hardware'] != 1) $userImage = 'ts3_player_outputhardware_disabled.png';
				
				if ($player['client_away']) 
				{
					$userImage = 'ts3_player_away.png';
					$user .= $player['client_away_message'] != '' ? ' - '.$player['client_away_message'] : '';
				}
				
				$output .= '<img src="'.JURI::base().'components/com_gameserver/images/icons/voice/'.$userImage.'" border="0" style="min-width:16px; max-width:16px;" alt="User" title="User" />';          
				$output .= '</td><td>'.$user.'</td>';
				
				$output .= '</tr></table>';
				
				$output .= "</td></tr>";	
			}	
		}
		return $output;
	}

	public function renderModulePlayerList()
	{
		return $this->renderPlayerList();
	}
	
	public function getModuleImageHtml()
	{
		$mapimage = JURI::root().'components/com_gameserver/images/logos/ts3.png';	
		
		return '<img src="'.$mapimage.'" border="0" width="96" height="96" title="Teamspeak 3" alt="Teamspeak 3" />';
	}
}

?>