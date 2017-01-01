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

class aa3Renderer extends abstractRenderer  {
	
	protected function getHeaderValues()
	{
		$array = array();
		
		$array[JText::_('SERVERNAME')] = colorRenderer::aaName2Html($this->getHostname());
		if ($this->params->get( 'hideipaddresses' , 0) == 0) 
		{
			$array[JText::_('IP')] = $this->getConnectLink(); 
		}
		$array[JText::_('PLAYERS')] = $this->getFormatedPlayerCount();
		$array[JText::_('MAP')] = $this->getMapname();
		$array[JText::_('ANTICHEATTOOL')] = $this->getAntiCheatTools();
		$array[JText::_('VERSION')] = $this->data['Version'];
		
		return $array;
	}
	
	protected function getPlayerValues()
	{
		return array(
			new playerValues(JText::_('TEAM'),'Team'),
			new playerValues(JText::_('PLAYERNAME'),'gq_name',alignment::left,false),
			new playerValues(JText::_('SCORE'),'gq_score',alignment::center,false),
			new playerValues(JText::_('PING'),'gq_ping',alignment::center),
			);		
	}
	
	protected function isAllowedProperty($property)
	{
		if ($property == 'FlagPunkbuster') return false;
		if ($property == 'IpAddress') return false;
		if ($property == 'Version') return false;
		
		return parent::isAllowedProperty($property);
	}
	
	private function getAntiCheatTools()
	{
		$tools = $this->getPunkbuster();
		
		return $tools;		 
	}
	
	private function getPunkbuster()
	{
		$punkbuster = $this->data['FlagPunkbuster'];
		
		if ($punkbuster == '1')
		{
			return 'Punkbuster';
		}
		
		return '';
	}
	
	protected function isModAllowed()
	{
		return ($this->data['gq_mod'] != '')
			&& ($this->data['gq_mod'] != 'Arma3');
	}
}

?>