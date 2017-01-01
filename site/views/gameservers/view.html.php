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

class GameServerViewGameservers extends JViewLegacy
{
	function display($tpl = null)
	{        
		$params = JComponentHelper::getParams( 'com_gameserver' );
		
		$model = $this->getModel();
		$rows = $model->getGameServerList($params);
		$this->assignRef('rows', $rows);
		$this->assignRef('params',$params);	
		parent::display($tpl);
	}
}
?>