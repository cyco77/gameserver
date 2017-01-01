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

class ts2Renderer extends abstractRenderer  {
	
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
		$output .= '<table width="100%">';
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
	
	function printchannels($data, $level, $parent) {

		foreach ($data['teams'] as $team){
			if ($team['parent'] == $parent) {			
				$output .= '<tr class="gameserver_line">';
				$output .= '<td style="padding-left: '.(20*$level).'px;">';
				
				$output .= '<table><tr><td>';
				$output .= '<img src="'.JURI::base().'components/com_gameserver/images/icons/voice/ts3_channel.png" border="0" alt="Channel" title="Channel" />';          
				$output .= '</td><td>'.$this->removeQuotes($team['name']).'</td></tr></table>';
				
				$output .= '</td></tr>';	
				$output .= $this->printchannels($data, $level+1, $team['id']);
				
				$output .= $this->printusersinchannel($data, $level+1, $team['id']);
			}	
		}
		
		return $output;
	}

	function printusersinchannel($data, $level, $parent) {
		
		foreach ($data['players'] as $player){
			if ($player['team'] == $parent) {
				
				$output .= '<tr class="gameserver_line"><td style="padding-left: '.(20*$level).'px;">';
				
				$output .= '<table><tr><td>';
				$output .= '<img src="'.JURI::base().'components/com_gameserver/images/icons/voice/ts3_player.png" border="0" alt="User" title="User" />';          
				$output .= '</td><td>'.$this->removeQuotes($player['name']).'</td></tr></table>';
				
				$output .= "</td></tr>";	
			}	
		}
		
		return $output;
	}
	
	function removeQuotes($value)
	{
		if (strlen($value) < 2) return $value;
		
		return substr($value,1,strlen($value)-2);		
	}
	
	public function getModuleImageHtml()
	{
		$mapimage = JURI::root().'components/com_gameserver/images/logos/ts2.png';	
		
		return '<img src="'.$mapimage.'" border="0" width="96" height="96" title="Teamspeak 2" alt="Teamspeak 2" />';
	}
}

?>