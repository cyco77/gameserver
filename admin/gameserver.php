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

// import joomla controller library
jimport('joomla.application.component.controller');

defined('DS') ? null : define('DS',DIRECTORY_SEPARATOR);

$document = JFactory::getDocument();
$cssFile = JURI::base(true).'/components/com_gameserver/style/gameserver.css';
$document->addStyleSheet($cssFile, 'text/css', null, array());

$controller = JControllerLegacy::getInstance('GameServer');

$task	= JRequest::getCmd('task');

switch ($task)
{
	case 'orderup':
		$controller->orderItems( -1 );
		break;
	case 'orderdown':
		$controller->orderItems( 1 );
		break;
	case 'saveorder':
		$controller->saveorder();
		break;
	case 'publish':
		$controller->publish(1);
		break;
	case 'unpublish':
		$controller->publish(-1);
		break;		
	default:
	{
		// Perform the Request task
		$controller->execute( JRequest::getVar('task'));	
		break;
	}
}

// Redirect if set by the controller
$controller->redirect();
?>