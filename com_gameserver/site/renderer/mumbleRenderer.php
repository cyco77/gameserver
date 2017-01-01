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

class mumbleRenderer extends abstractRenderer  {
	
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
		$output .= $this->printchannels($this->data,0,-1);
		$output .= '</table>';
		$output .= '</td>';			
		$output .= '</tr>	';
		$output .= '</table>';
		
		return $output;
	}		
	
	protected function isAllowedProperty($property)
	{
		if ($property == 'maxplayers') return false;	
		if ($property == 'id') return false;	
		if ($property == 'name') return false;	
		if ($property == 'x_connecturl') return false;	
		if ($property == 'x_gtmurmur_connectport') return false;	
		if ($property == 'x_gtmurmur_doclen') return false;	
		if ($property == 'x_gtmurmur_max_users') return false;			
		if ($property == 'players') return false;	
		if ($property == 'teams') return false;
		if (strpos($property, 'connection') === 0) return false;
		
		return parent::isAllowedProperty($property);
	}
	
	public function getCustomConnectLink()
	{		
		$ip = $this->data['x_connecturl'];
		return '<a href="mumble://'.$ip.'" title="'.$ip.'">'.$ip.'</a>';
	}
	
	function printchannels($data, $level, $parent) 
	{	
		$output = '';
		
		foreach ($data['teams'] as $channel)
		{
			if ($channel['parent'] == $parent) 
			{			
				$output .= '<tr class="gameserver_line">'. "\n";
				$output .= '<td style="padding-left: '.(16*$level).'px;">'. "\n";
				
				$output .= '<table><tr><td>'. "\n";
				
				$channelImageName = 'mumble_channel.png';
				
				$output .= '<img src="'.JURI::base().'components/com_gameserver/images/icons/voice/'.$channelImageName.'" border="0" alt="Channel" style="min-width:16px; max-width:16px;" title="Channel" />';   
				
				$channelName = $channel['name'];			
				
				$output .= '</td><td width="100%">'.$channelName.'</td></tr></table>';
				
				$output .= '</td></tr>';	
				$output .= $this->printchannels($data, $level+1, $channel['id']);
				
				if (isset($data['players']))
				{
					$output .= $this->printusersinchannel($data, $level+1, $channel['id']);
				}
			}	
		}
		
		return $output;
	}

	function printusersinchannel($data, $level, $parent) 
	{
		$output = '';
		
		foreach ($data['players'] as $player)
		{		
			if ($player['channel'] == $parent) 
			{			
				$output .= '<tr class="gameserver_line"><td style="padding-left: '.(16*$level).'px;">';
				
				$output .= '<table><tr><td>';
				
				$user = $player['name'];
				$userImage = 'mumble_user.png';
				
				if ($player['mute']) $userImage = 'mumble_player_input_muted.png';
				
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