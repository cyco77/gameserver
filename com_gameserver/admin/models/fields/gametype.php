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

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
require_once(JPATH_SITE.DS.'components'.DS.'com_gameserver'.DS.'gamedataprovider.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_gameserver'.DS.'game.php');	

class JFormFieldGameType extends JFormFieldList
{
	protected $type = 'GameType';

	protected function getOptions() 
	{
		$options = array();		
		
		$gameDataProvider = new GameDataProvider();
		
		foreach ($gameDataProvider->supported as $game) 
		{
			$options[] = JHtml::_('select.option', $game->type, $game->name);
		}	
		
		$options = array_merge(parent::getOptions(), $options);
		return $options;
	}
}

?>