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

defined('DS') ? null : define('DS',DIRECTORY_SEPARATOR);

// import joomla controller library
jimport('joomla.application.component.controller');

// Get an instance of the controller prefixed by GameServer
$controller = JControllerLegacy::getInstance('GameServer');

// den request task ausleben
$controller->execute(JRequest::getCmd('task'));

// Redirect aus dem controller
$controller->redirect();

?>