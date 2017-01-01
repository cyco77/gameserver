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

class GameServerControllerEditDarkCss extends JControllerLegacy
{
	function editDarkCSS()
	{		
		JRequest::setVar( 'view', 'editdarkcss' );
		JRequest::setVar( 'layout', 'css' );
		
		parent::display();		
	}
	
	function save()
	{
		$return = $this->saveCss();

		if ($return)
		{
			$this->setRedirect('index.php?option=com_gameserver',  JText::_('File Saved'));
		}
		else {
			$this->setRedirect('index.php?option=com_gameserver', JText::_('Operation Failed').': '.JText::sprintf('Failed to open file for writing'.".", $file));
		}
	}
	
	function apply()
	{
		$return = $this->saveCss();

		if ($return)
		{
			$this->setRedirect('index.php?option=com_gameserver&controller=editdarkcss&view=editDarkCSS',  JText::_('File Saved'));
		}
		else {
			$this->setRedirect('index.php?option=com_gameserver&controller=editdarkcss&view=editDarkCSS', JText::_('Operation Failed').': '.JText::sprintf('Failed to open file for writing'.".", $file));
		}
	}
	
	function cancel()
	{
		$this->setRedirect('index.php?option=com_gameserver');
	}
	
	function saveCss()
	{
		// Initialize some variables
		$csspath = JPATH_SITE . DS . 'components' . DS . 'com_gameserver' . DS . 'style' . DS . 'black.css';
		$filecontent	= JRequest::getVar('gameservercss', '', 'post', 'string', JREQUEST_ALLOWRAW);

		if (!$filecontent) {
			$this->setRedirect('index.php?option=com_gameserver', JText::_('Operation Failed').': '.JText::_('Content empty').".");
		}

		jimport('joomla.filesystem.file');
		return JFile::write($csspath, $filecontent);
	}
}