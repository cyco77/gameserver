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

class GameServerModelBlockServerlist extends JModelLegacy
{
	function _getGameServerQuery( &$options )
	{
		$db = JFactory::getDBO();
		$id =   @$options['id'];
		$select = 'g.*';
		$from = '#__gameserver   AS g';
		$wheres[] = 'g.published = 1';
		$query = "SELECT   " . $select .
			"\n   FROM " . $from .
			"\n   WHERE " . implode( "\n  AND ", $wheres ) .
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