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


jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldGameServer extends JFormFieldList
{
	protected $type = 'GameServer';

	protected function getOptions() 
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('serverid,displayname');
		$query->from('#__gameserver');
		$query->where('published = 1');
		$query->order('displayname');
		$db->setQuery((string)$query);
		$messages = $db->loadObjectList();
		$options = array();
		if ($messages)
		{
			foreach($messages as $message) 
			{
				$options[] = JHtml::_('select.option', $message->serverid, $message->displayname);
			}
		}
		$options = array_merge(parent::getOptions(), $options);
		return $options;	
	}
}
