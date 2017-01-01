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


jimport( 'joomla.application.component.modellist' );


class GameServerModelGameServers extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'g.serverid',
				'displayname', 'g.displayname',
				'type', 'g.type',
				'ip', 'g.ip',
				'port', 'g.port',
				'port2', 'g.port2',
				'addedbyname', 'u.name', 
				'published', 'g.published',
				);
		}

		parent::__construct($config);
	}
	
	protected function getListQuery()
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('g.serverid,g.displayname,g.type,g.ip,g.port,g.port2,g.user,g.pass,g.url,g.cachedserverdata,g.cachedatetime,g.showsettings,g.region,g.country,g.addedby,g.ordering,g.published,u.name as addedbyname');
		$query->from('#__gameserver g LEFT OUTER JOIN #__users u ON g.addedby = u.id');
		
		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published))
		{
			$query->where('g.published = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(g.published IN (0, 1))');
		}
		
		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('g.serverid = ' . (int) substr($search, 3));
			}
			elseif (stripos($search, 'author:') === 0)
			{
				$search = $db->quote('%' . $db->escape(substr($search, 7), true) . '%');
				$query->where('(u.name LIKE ' . $search . ')');
			}
			else
			{
				$search = $db->quote('%' . $db->escape($search, true) . '%');
				$query->where('(g.displayname LIKE ' . $search . ')');
			}
		}
		
		// Add the list ordering clause
		$orderCol = $this->state->get('list.ordering', 'g.displayname');
		$orderDirn = $this->state->get('list.direction', 'asc');
		$query->order($db->escape($orderCol . ' ' . $orderDirn));
						
		return $query;
	}
	
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication();
		$context = $this->context;

		$search = $this->getUserStateFromRequest($context . '.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $this->getUserStateFromRequest($context . '.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_gameserver');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('g.ordering', 'asc');
	}
	
	public function getTable($type = 'GameServer', $prefix = 'GameServerTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
}
