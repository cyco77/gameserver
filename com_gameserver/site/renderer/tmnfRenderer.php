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
require_once(JPATH_SITE.DS.'components'.DS.'com_gameserver'.DS.'script'.DS.'additional'.DS.'tmnf'.DS.'tmfcolorparser.inc.php');

class tmnfRenderer extends abstractRenderer  {
	
	private $colors;
	
	public function __construct()
	{
		$this->colors = new TMFColorParser();	
	}
	
	protected function getHeaderValues()
	{
		return array(
			JText::_('SERVERNAME') => $this->getConnectLink(),
			JText::_('PLAYERS') => $this->getFormatedPlayerCount(),
			JText::_('GAMETYPE') => $this->getGametype(),
			JText::_('MAP') => $this->getMapname(),
			JText::_('Environment') => $this->data['environment']
			);
	}
	
	protected function getPlayerValues()
	{
		return array(
			new playerValues(JText::_('PLAYERNAME'),'gq_name',alignment::left,false),
			new playerValues(JText::_('RANK'),'LadderRanking',alignment::center),	
			);
	}
	
	protected function getPlayerValue($player, $attribute)
	{
		if ($attribute == 'gq_name') return $color->toHTML($player['gq_name']);
		
		return $player[$attribute];	
	}
	
	protected function getCustomConnectLink()
	{
		$colors = new TMFColorParser();
		return '<a href="tmtp://#join='.$this->data['serverlogin'].'">'.$colors->toHTML($this->data['gq_hostname']).'</a>';
	}
}

?>