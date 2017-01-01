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

class GameServerModeleditlightcss extends JModelLegacy
{
	function getCss()
	{
		jimport('joomla.filesystem.file');

		$csspath = JPATH_SITE . DS . 'components' . DS . 'com_gameserver' . DS . 'style' . DS . 'white.css';
		$content = JFile::read($csspath);

		if ($content !== false)
		{
			$content = htmlspecialchars($content, ENT_COMPAT, 'UTF-8');
		}

		return $content;
	}
}

?>