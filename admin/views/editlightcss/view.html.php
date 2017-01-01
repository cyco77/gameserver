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

jimport( 'joomla.application.component.view' );

class GameServerVieweditlightcss extends JViewLegacy
{	
	function display($tpl = null)
	{
		JToolBarHelper::title(   JText::_( 'Gameservers - CSS Editor' ),  'gameserver' );
		
		JToolBarHelper::apply('editlightcss.apply', 'JTOOLBAR_APPLY');
		JToolBarHelper::save('editlightcss.save', 'JTOOLBAR_SAVE');
		
		$css = $this->get( 'Css');
		$this->assignRef('css',$css);
		
		JToolBarHelper::cancel( 'editlightcss.cancel','JTOOLBAR_CANCEL' );

		JRequest::setVar( 'hidemainmenu', 1 );
		
		parent::display($tpl);
	}
}