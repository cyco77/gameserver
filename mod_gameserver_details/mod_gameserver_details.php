<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

defined('DS') ? null : define('DS',DIRECTORY_SEPARATOR);

require_once (JPATH_SITE.DS.'components'.DS.'com_gameserver'.DS.'gamedataprovider.php');

jimport( 'joomla.application.component.helper' );
$componentparams  = JComponentHelper::getParams('com_gameserver');

$db = JFactory::getDBO();

$serverid = $params->get( 'serverid', '' );
$mapimagewidth = $params->get( 'mapimagewidth', 200 );
$showmap = $params->get( 'showmap', 1);
$showplayers = $params->get( 'showplayers', 1);
$maxplayerlistheight = $params->get( 'maxplayerlistheight', -1);
$customurl = $params->get( 'customurl', '');
		                                                     
if (is_numeric($serverid)) {
	// Server-Datensatz holen
	$query = ' SELECT * FROM #__gameserver '.
			 '  WHERE serverid = '.$serverid;  

	$db->setQuery($query);      
        
	$gameserver = $db->loadObject();

	require(JModuleHelper::getLayoutPath('mod_gameserver_details'));
}