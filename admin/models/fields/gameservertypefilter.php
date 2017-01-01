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

require_once(JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_gameserver'.DIRECTORY_SEPARATOR.'gamedataprovider.php');

class JFormFieldGameServerTypeFilter extends JFormFieldList
{
	protected $type = 'GameServerTypeFilter';

	protected function getOptions() 
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$db->setQuery("select type from #__gameserver where published=1 group by type order by type");
		$messages = $db->loadObjectList();
		$options = array();
		if ($messages)
		{
			$gameDataProvider = new GameDataProvider();
			
			$options[] = JHtml::_('select.option', 'all', JText::_('ALLGAMES'));
			
			foreach($messages as $message) 
			{
				$options[] = JHtml::_('select.option', $message->type, $gameDataProvider->getGameDisplayname($message->type));
			}
		}
		$options = array_merge(parent::getOptions(), $options);
		return $options;	
	}
}
