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

class GameServerModelGameServer extends JModelLegacy
{
	function __construct()
	{
		parent::__construct();
	}

	public function setId($id)
	{
		// Set id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
	}

	public function &getData()
	{
		// Load the data
		if (empty( $this->_data )) {
			$query = ' SELECT * FROM #__gameserver '.
					'  WHERE serverid = '.$this->_id;
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->serverid = 0;
			$this->_data->displayname = null;
			$this->_data->type = null;
			$this->_data->ip = null;
			$this->_data->port = null;
			$this->_data->port2 = null;
		}
		return $this->_data;
	}
	
	public function saveCache($id, $data)
	{
		$query = " UPDATE #__gameserver ".
			" SET cachedserverdata = '". $data . "', cachedatetime = '". date("Y-m-d H:i:s") . "'".
			'  WHERE serverid = '.$id;
		
		$this->_db->setQuery($query);
		$this->_db->query();
	}
}
?>
