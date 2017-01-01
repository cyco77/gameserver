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

require_once(JPATH_COMPONENT.DS.'gamedataprovider.php');

jimport( 'joomla.application.component.view' );

class GameServerViewGameServer extends JViewLegacy
{
	function display($tpl = null)
	{		
		$id = JRequest::getVar( 'serverid', '', 'default', 'int' );
		
		$model = $this->getModel(); 
		$model->setId($id);
		
		$gameserver = $model->getData();
		
		if ($gameserver == false) {
			return JError::raiseWarning(404, JText::_('Item not found'));
		}
		
		$this->assignRef('gameserver', $gameserver);
		
		$params = JComponentHelper::getParams( 'com_gameserver' ); 
		
		$this->assignRef('params',$params);		
		
		$gameDataProvider = new GameDataProvider();
						
		$cacheTime = $params->get( 'cacheinseconds', 60);
				
		$saveServerdata = $gameDataProvider->loadServerData($gameserver,$cacheTime);
		
		if ($saveServerdata)
		{
			$model->saveCache($gameserver->serverid,$gameDataProvider->getEncodedServerdata());
		}
		
		$this->assignRef('gameDataProvider',$gameDataProvider);		
		
		parent::display();
	}
}