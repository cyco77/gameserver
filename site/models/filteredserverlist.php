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


jimport('joomla.application.component.model');

class GameServerModelfilteredserverlist extends JModelLegacy
{
	function _getGameServerQuery( &$options )
	{
		$ids = JRequest::getVar( 'id', '', 'default', 'array' );
		
		$db = JFactory::getDBO();
		$select = 'g.*';
		$from = '#__gameserver   AS g';
		
		$idsWhere = Count($ids) >= 1 && $ids[0] != '' ? 'AND serverid in (' . implode(",", array_map('intval', $ids)) . ')' : '';
		
		$wheres = 'published = 1 ' . $idsWhere;
		
		$query = "SELECT   " . $select .
			"\n   FROM " . $from .
			"\n   WHERE " . $wheres .
			"\n   ORDER BY ordering, displayname";
		return $query;
	}
	
	function getGameServerList( $options=array() )
	{
		$query = $this->_getGameServerQuery( $options );	
		$result = $this->_getList( $query );
		return @$result;
	}
}