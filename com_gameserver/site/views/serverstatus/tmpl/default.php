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

require_once(JPATH_COMPONENT.DS.'renderer'.DS.'rendererFactory.php');

$serverName = '';
$serverAddress = '';
$serverStatus = '';

$response = array(
	'serverName' => $this->gameserver->displayname,
	'serverAddress' => '',
	'serverStatus' => '',
	'currentMap' => '',		
	'error' => JText::_( 'SERVER_DID_NOT_RESPOND')
	);

$hideipaddresses = $this->params->get( 'hideipaddresses' , 0) == 1;	

try
{		
	$task	= JRequest::getCmd('task');			
	
	switch ($task)
	{
		case 'list':	
			if ($this->gameDataProvider->isOnline())
			{	
				
				$renderer = rendererFactory::factory($this->gameserver->type,$this->gameDataProvider->getProt());
				$renderer->init($this->gameDataProvider,0, 150, 2,0,0,0);
				
				$serverName = $renderer->getHostname();
				$serverAddress = $renderer->getConnectLink();
				$serverStatus =  $renderer->getServerStatus();
				$gametype = $renderer->getGametype();
				$currentMap =  $gametype != '' ? $renderer->getMapname().' - '.$gametype : $renderer->getMapname();
				
				$response = array(
					'serverName' => $serverName,
					'serverAddress' => $hideipaddresses == false ? $serverAddress : "",
					'serverStatus' => $serverStatus,
					'currentMap' => $currentMap,
					'onlineimage' => '<img src="'.JURI::base().'components/com_gameserver/images/online.jpg" alt="Server online" title="Server online" width="16" height="16" />',
					'error' => ''
					);
			}
			else
			{
				$response = array(
					'serverName' => $this->gameserver->displayname,
					'serverAddress' => '',
					'serverStatus' => '',
					'currentMap' => '',		
					'error' => '',
					'onlineimage' => '<img src="'.JURI::base().'components/com_gameserver/images/offline.jpg" alt="Server offline" title="Server offline" width="16" height="16" />',
					);	
			}
			break;
		case 'advanced':
			if ($this->gameDataProvider->isOnline())
			{					
				$renderer = rendererFactory::factory($this->gameserver->type,$this->gameDataProvider->getProt());
				$renderer->init($this->gameDataProvider,$this->params->get( 'show_mapimages' ,1), 100, $this->params->get( 'usemapimagesfromglobalmapgallery' , 1),$this->params->get( 'usemapimagesfromexternalmapgallery' , 1),0);
				
				$serverName = $renderer->getHostname();
				$serverAddress = $hideipaddresses == false ? $renderer->getConnectLink() : "";
				$serverStatus = $renderer->getServerStatus();
				$gametype = $renderer->getGametype();
				$currentMap =  $gametype != '' ? $renderer->getMapname().' - '.$gametype : $renderer->getMapname();
				$currentMapImage = $renderer->getMapImageHtml();
				
				$response = array(
					'serverName' => $serverName,
					'serverAddress' => $serverAddress,
					'serverStatus' => $serverStatus,
					'currentMap' => $currentMap,
					'currentMapImage' => $currentMapImage,
					'error' => ''
					);
			}
			
			
			break;
		case 'filtered':
			if ($this->gameDataProvider->isOnline())
			{	
				$renderer = rendererFactory::factory($this->gameserver->type,$this->gameDataProvider->getProt());
				$renderer->init($this->gameDataProvider,$this->params->get( 'show_mapimages' ,1), 100, $this->params->get( 'usemapimagesfromglobalmapgallery' , 1),$this->params->get( 'usemapimagesfromexternalmapgallery' , 1),0);
				
				$serverName = $renderer->getHostname();
				$serverAddress = $hideipaddresses == false ? $renderer->getConnectLink() : "";
				$serverStatus = $renderer->getServerStatus();
				
				$gametype = $renderer->getGametype();
				
				$currentMap = $gametype != '' ? $renderer->getMapname().' - '.$gametype : $renderer->getMapname();
				$currentMapImage = $renderer->getMapImageHtml();
				
				$response = array(
					'serverName' => $serverName,
					'serverAddress' => $serverAddress,
					'serverStatus' => $serverStatus,
					'currentMap' => $currentMap,
					'currentMapImage' => $currentMapImage,
					'onlineimage' => '<img src="'.JURI::base().'components/com_gameserver/images/online.jpg" alt="Server online" title="Server online" width="16" height="16" />',
					'error' => ''
					);
			}
			else
			{
				$response = array(
					'serverName' => $this->gameserver->displayname,
					'serverAddress' => '',
					'serverStatus' => '',
					'currentMap' => '',		
					'error' => JText::_( 'SERVER_DID_NOT_RESPOND'),
					'onlineimage' => '<img src="'.JURI::base().'components/com_gameserver/images/offline.jpg" alt="Server offline" title="Server offline" width="16" height="16" />',
					);	
			}
			break;
		case 'detail':
			if ($this->gameDataProvider->isOnline())
			{	
				$renderer = rendererFactory::factory($this->gameserver->type,$this->gameDataProvider->getProt());
				$renderer->init($this->gameDataProvider,$this->params->get( 'show_mapimages' ,1), $this->params->get( 'mapimagewidth' , 200), $this->params->get( 'usemapimagesfromglobalmapgallery' , 1), $this->params->get( 'usemapimagesfromexternalmapgallery' , 1), $this->params->get( 'detailviewteamview' , 1));
				
				$gameservervalues = $renderer->renderHeader();
				$gameserverplayers = $renderer->renderPlayerList();
				$gameserversettings = $renderer->renderSettings();
							
				$response = array(
					'gameservervalues' => base64_encode($gameservervalues),
					'gameserverplayers' => base64_encode($gameserverplayers),
					'gameserversettings' => base64_encode($gameserversettings),
					'error' => base64_encode('')
					);
				
			}
			break;
		case 'detailmodule':
			if ($this->gameDataProvider->isOnline())
			{	
				$mapimagewidth	= JRequest::getCmd('mapimagewidth');	
				$showmap	= JRequest::getCmd('showmap');	
				$showplayers	= JRequest::getCmd('showplayers');	
				$maxplayerlistheight	= JRequest::getCmd('maxplayerlistheight');	
				$customurl	= base64_decode(JRequest::getCmd('customurl'));	
				
				$renderer = rendererFactory::factory($this->gameserver->type,$this->gameDataProvider->getProt());
				$renderer->init($this->gameDataProvider,$showmap, $mapimagewidth, 1,1,0);
				
				$moduleContent = $renderer->renderModuleContent($this->gameserver->serverid,$maxplayerlistheight,$showplayers,$customurl);
				
				$response = array(
					'gameserverdetailmodule' => base64_encode($moduleContent),
					'error' => base64_encode('')
					);
				
			}
			break;
		default:
		{
			if ($this->gameDataProvider->isOnline())
			{					
				$renderer = rendererFactory::factory($this->gameserver->type,$this->gameDataProvider->getProt());
				$renderer->init($this->gameDataProvider,1,150, $this->params->get( 'usemapimagesfromglobalmapgallery' , 1), $this->params->get( 'usemapimagesfromexternalmapgallery' , 1), 0);
				
				$header = $renderer->renderHeader();
				
				$response = array(
					'gameserverstatus' => $header,
					'error' => ''
					);
			}
			
			break;
		}
	}
}
catch(Exception $e)
{
	$response = array(
		'error' => $e->message
		);
}

echo json_encode($response); 	

?>