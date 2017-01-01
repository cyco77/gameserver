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

require_once(JPATH_COMPONENT.DS.'gamedataprovider.php');	
require_once(JPATH_COMPONENT.DS.'renderer'.DS.'rendererFactory.php');

$gameDataProvider = new GameDataProvider();
$gameDataProvider->loadServerDataBySingleValues($this->type, $this->ip, $this->port, $this->port2, $this->user, $this->pass,'');

if (!$gameDataProvider->isOnline())
{
	$status = JText::_( 'SERVER_DID_NOT_RESPOND');	
	
	$response = array(
		'serverName' => '',
		'serverAddress' => '',
		'serverStatus' => '',
		'currentMap' => '',		
		'error' => $status
		);

	echo json_encode($response); 
}
else
{
	try
	{	
		$header = '';

		$renderer = rendererFactory::factory($this->type,$gameDataProvider->getProt());
		$renderer->init($gameDataProvider,1,150, $this->params->get( 'usemapimagesfromglobalmapgallery' , 1), $this->params->get( 'usemapimagesfromexternalmapgallery' , 1), 0);

		$header = $renderer->renderHeader();

		$response = array(
			'gameserverstatus' => $header,	
			'error' => ''
			);

		echo json_encode($response); 

	}
	catch(Exception $e)
	{
		$response = array(
			'error' => $e->message
			);

		echo json_encode($response); 
	}	
}

?>