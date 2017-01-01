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

class JFormFieldGameServerRegionFilter extends JFormFieldList
{
	protected $type = 'GameServerRegionFilter';

	protected function getOptions() 
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$db->setQuery("select region from #__gameserver where published=1 group by region order by region");
		$messages = $db->loadObjectList();
		$options = array();
		if ($messages)
		{
			$options[] = JHtml::_('select.option', 'all', JText::_('ALLREGIONS'));
			
			foreach($messages as $message) 
			{
				$options[] = JHtml::_('select.option', $message->region, JText::_($message->region));
			}
		}
		$options = array_merge(parent::getOptions(), $options);
		return $options;	
	}
}
