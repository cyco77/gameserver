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

jimport('joomla.application.component.controller');

class GameServerController extends JControllerLegacy
{
	function display($cachable = false, $urlparams = false)
	{
		// set default view if not set
		JRequest::setVar('view', JRequest::getCmd('view', 'gameservers'));
		
		parent::display();
		
		return $this;
	}
	
	function edit_light_css() 
	{
		$this->setRedirect("index.php?option=com_gameserver&controller=editlightcss&view=editLightCSS");
	}
	
	function edit_dark_css() 
	{
		$this->setRedirect("index.php?option=com_gameserver&controller=editdarkcss&&view=editdarkcss");
	}
	
	function publish()
	{
		$this->setRedirect( 'index.php?option=com_gameserver&view=gameservers' );

		// Initialize variables
		$db			=& JFactory::getDBO();
		$user		=& JFactory::getUser();
		$cid		= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$task		= JRequest::getCmd( 'task' );
		$publish	= ($task == 'publish');
		$n			= count( $cid );

		if (empty( $cid )) 
		{
			return JError::raiseWarning( 500, JText::_( 'No items selected' ) );
		}

		JArrayHelper::toInteger( $cid );
		$cids = implode( ',', $cid );

		$query = 'UPDATE #__gameserver'
			. ' SET published = ' . (int) $publish
			. ' WHERE serverid IN ( '. $cids .' )';
		
		$db->setQuery( $query );
		if (!$db->query()) {
			return JError::raiseWarning( 500, JText::_( 'No items selected' ) );
		}
		$this->setMessage(JText::sprintf( $publish ? 'Items published' : 'Items unpublished', $n ) );
	}
}
?>