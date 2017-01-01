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

jimport('joomla.application.component.view');

class GameServerViewFilteredServerList extends JViewLegacy
{
	function display($tpl = null)
	{        
		$regionFilter = JRequest::getVar( 'region', '', 'default', 'string' );
		$this->assignRef('regionFilter', $regionFilter);
		
		$gameFilter = JRequest::getVar( 'type', '', 'default', 'string' );
		$this->assignRef('gameFilter', $gameFilter);
		
		$model = $this->getModel();
		$rows = $model->getGameServerList();		
		
		$this->assignRef('rows', $rows);
		$params = JComponentHelper::getParams( 'com_gameserver' ); 
		$this->assignRef('params',$params);	
		parent::display($tpl);
	}
}
?>