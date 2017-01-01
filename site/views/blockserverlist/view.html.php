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

class GameServerViewBlockServerlist extends JViewLegacy
{
	function display($tpl = null)
	{        
		$model = $this->getModel();
		$rows = $model->getGameServerList();
		$this->assignRef('rows', $rows);
		$params = JComponentHelper::getParams( 'com_gameserver' ); 
		$this->assignRef('params',$params);	
		parent::display($tpl);
	}
}
?>