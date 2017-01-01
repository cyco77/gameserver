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
require_once(JPATH_SITE.DS.'components'.DS.'com_gameserver'.DS.'script'.DS.'additional'.DS.'tm2'.DS.'tm2colorparser.inc.php');

class tm2Renderer extends abstractRenderer  {
	
	private $colors;
	
	public function __construct()
	{
		$this->colors = new TM2ColorParser();	
	}
	
	protected function getHeaderValues()
	{		
		return array(
			JText::_('SERVERNAME') => $this->getCustomConnectLink2(),
			JText::_('PLAYERS') => $this->getFormatedPlayerCount(),
			JText::_('MAP') => $this->getMapname(),
			JText::_('Mapcycle') => $this->data['mapcycle'],
			JText::_('version') => $this->data['version'],			
			);
	}
		
	protected function getPlayerValue($player, $attribute)
	{
		if ($attribute == 'gq_name') return $this->colors->toHTML($player['gq_name']);
		
		return $player[$attribute];	
	}	
	
	public function getHostname()
	{
		return $this->colors->toHTML($this->data['gq_hostname']);
	}
	
	public function getCustomConnectLink()
	{
		return '<a href="maniaplanet://#join='.$this->data['serverlogin'].'">Connect</a>';
	}
	
	public function getCustomConnectLink2()
	{
		return '<a href="maniaplanet://#join='.$this->data['serverlogin'].'">'.$this->getHostname().'</a>';
	}
	
	protected function getPlayerValues()
	{
		return array(
			new playerValues(JText::_('PLAYERNAME'),'gq_name',alignment::left,false),
			new playerValues(JText::_('World'),'worldranking'),	
			new playerValues(JText::_('Country'),'countrydisplay'),	
			new playerValues(JText::_('Region'),'regiondisplay'),	
			new playerValues(JText::_('Town'),'towndisplay'),	
			);
	}
	
	protected function isAllowedProperty($property)
	{
		if ($property == 'mapcycle') return false;
		if ($property == 'version') return false;
		if ($property == 'serverlogin') return false;
		if ($property == 'players') return false;
		
		return parent::isAllowedProperty($property);
	}
}

?>