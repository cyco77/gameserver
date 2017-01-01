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

class   GameServerViewServerTester extends JViewLegacy
{
	function display($tpl = null)
	{		
		$type = JRequest::getVar( 'type', '', 'default', 'string' );
		$ip = JRequest::getVar( 'ip', '', 'default', 'string' );
		$port = JRequest::getVar( 'port', '', 'default', 'string' );
		$port2 = JRequest::getVar( 'port2', '', 'default', 'string' );
		$user = JRequest::getVar( 'user', '', 'default', 'string' );
		$pass = JRequest::getVar( 'pass', '', 'default', 'string' );
		
		$this->assignRef('type', $type);
		$this->assignRef('ip', $ip);
		$this->assignRef('port', $port);
		$this->assignRef('port2', $port2);
		$this->assignRef('user', $user);
		$this->assignRef('pass', $pass);
		
		$params = JComponentHelper::getParams( 'com_gameserver' ); 
		
		$this->assignRef('params',$params);		
		
		parent::display();
	}
	
}