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

class minecraftRenderer extends abstractRenderer  {
	
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
		$array[JText::_('VERSION')] = $this->data['version'];
		
		return $array;
	}
	
	public function showMapImage()
	{
		return false;	
	}
	
	protected function isAllowedProperty($property)
	{
		if ($property == 'version') return false;
		if ($property == 'hostname') return false;
		if ($property == 'hostport') return false;
		if ($property == 'gametype') return false;
		if ($property == 'map') return false;
		if ($property == 'maxplayers') return false;
		if ($property == 'numplayers') return false;
		if ($property == 'players') return false;
		if ($property == 'plugins') return false;
		
		return parent::isAllowedProperty($property);
	}
	
	protected function getPlayerValues()
	{

		return array(
			new playerValues(JText::_('PLAYERNAME'),'gq_name',alignment::left,false));		
	}
	
	public function getModuleImageHtml()
	{
		$mapimage = JURI::root().'components/com_gameserver/images/logos/minecraft.png';	
		
		return '<img src="'.$mapimage.'" border="0" width="96" height="96" title="Minecraft" alt="Minecraft" />';
	}
}

?>