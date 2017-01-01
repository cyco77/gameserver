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

$doc = JFactory::getDocument();

$style = $this->params->get( 'detailstylesheet' , 1);	
if ($style == "1") {
	$cssHTML = JURI::base().'components/com_gameserver/style/white.css';
	$doc->addStyleSheet($cssHTML);
	$loaderImage = '<img src="'.JURI::base().'components/com_gameserver/images/loader_light.gif" alt="Loading" title="Loading" />';
	
} elseif ($style == "2") {
	$cssHTML = JURI::base().'components/com_gameserver/style/black.css';
	$doc->addStyleSheet($cssHTML);
	$loaderImage = '<img src="'.JURI::base().'components/com_gameserver/images/loader_dark.gif" alt="Loading" title="Loading" />';	
}
else {
	$cssHTML = JURI::base().'components/com_gameserver/style/blank.css';
	$doc->addStyleSheet($cssHTML);
	$loaderImage = '<img src="'.JURI::base().'components/com_gameserver/images/loader_dark.gif" alt="Loading" title="Loading" />';	
}

if ($this->params->get( 'loadjquery' , 1) == 1)
{
	JHtml::_('jquery.framework');
}

$hideipaddresses = $this->params->get( 'hideipaddresses' , 0) == 1;	

$iconfolder = JURI::base().'components/com_gameserver/images/icons/';
$logofolder = JURI::base().'components/com_gameserver/images/logos/';

$rowNr = 0;

if ( $this->params->def( 'show_page_title', 1 ) ) 
{
	echo '<div class="componentheading'.$this->params->get( 'pageclass_sfx' ).'">';
	echo $this->params->get('page_title');
	echo '</div>';
}

if (count($this->rows) == 0) {
	echo 'No Servers found';
}
else 
{	
	echo '<div class="gameserverlist">';
	echo '<table cellpadding="3" cellspacing="3" width="100%">';
	echo '<tr>';
	echo '<th></th>';
	echo '<th align="left">';
	echo JText::_('DISPLAYNAME');
	echo '</th>';

	if (!$hideipaddresses)
	{ 
		echo '<th align="left" class="gameserverlist_hidemobile">';
		echo JText::_('IP');
		echo '</th>';
	} 
	
	echo '<th align="left" class="gameserverlist_hidemobile">';
	echo JText::_('MAP');
	echo '</th>';
	echo '<th></th>';
	echo '<th align="left">';
	echo JText::_('STATUS');
	echo '</th>';
	echo '</tr>';

	foreach ($this->rows as $row) 
	{	  
		$rowNr++;
		$type = $row->type;
		$ip = $row->ip;
		$port = $row->port;
		$port2 = $row->port2;
		$user = $row->user;
		$pass = $row->pass;
		$url = $row->url;
		
		$link = JRoute::_( 'index.php?option=com_gameserver&amp;view=gameserver&amp;serverid='. $row->serverid );
		
		$gameDataProvider = new GameDataProvider();
		
		$maxCacheTime = $this->params->get( 'cacheinseconds', 60);
		
		$cachedIsOnline = '<img src="'.JURI::base().'components/com_gameserver/images/offline.jpg" alt="Server offline" title="Server offline" style="width:16px;height:16px;min-width:16px" />';;
		$cachedServername = $row->displayname;
		$cachedServerAddress = '';
		$cachedMapname = '';
		$cachedStatus = '';
		$cachedError = '';
		
		$cacheExpired = $gameDataProvider->isCacheExpired($maxCacheTime,$row->cachedatetime);
		
		if (!$cacheExpired)
		{
			$gameDataProvider->loadServerData($row,$maxCacheTime);	
			$renderer = rendererFactory::factory($row->type,$gameDataProvider->getProt());
			$renderer->init($gameDataProvider,$this->params->get( 'show_mapimages' ,1), 100, $this->params->get( 'usemapimagesfromglobalmapgallery' , 1),$this->params->get( 'usemapimagesfromexternalmapgallery' , 1),0);			
			
			if ($gameDataProvider->isOnline())
			{
				$cachedIsOnline = '<img src="'.JURI::base().'components/com_gameserver/images/online.jpg" alt="Server online" title="Server online" style="width:16px;height:16px;min-width:16px" />';	
				
				$cachedServername = $renderer->getHostname();
				$cachedServerAddress = $renderer->getConnectLink();
				$cachedStatus = $renderer->getServerStatus();
				$gametype = $renderer->getGametype();
				$cachedMapname = $gametype != '' ? $renderer->getMapname().' - '.$gametype : $renderer->getMapname();
			}
			else
			{	
				$cachedError = '';
			}
		}
		

		echo '<tr>';
		echo '<td>';
		echo '<a class="gameserverlist_logo_link" href="'.$link.'">';
		echo '<img class="gameserverlist_logo" src="'.$iconfolder.$type.'.png'.'" alt="'.$row->displayname.'" title="'.$row->displayname.'" style="width: 24px;height:24px;min-width: 24px;" />';
		echo '</a>';
		echo '</td>';
		echo '<td>';
		echo '<a class="gameserverlist_servername_link" id="serverName'.$rowNr.'" href="'.$link.'">'.$cachedServername.'</a>';
		echo '<div class="gameserverlist_error" id="error'.$rowNr.'">'.$cachedError.'</div>';
		echo '</td>';
		if (!$hideipaddresses)
		{ 
			echo '<td class="gameserverlist_hidemobile">';
			echo '<div class="gameserverlist_serveraddress" id="serverAddress'.$rowNr.'">'.$cachedServerAddress.'</div>';
			echo '</td>';
		} 
		echo '<td class="gameserverlist_hidemobile">';
		echo '<div class="gameserverlist_map" id="currentMap'.$rowNr.'">'.$cachedMapname.'</div>';
		echo '</td>';
		echo '<td>';
		echo '<div class="gameserverlist_onlineimage" id="onlineimage'.$rowNr.'">'.$cachedIsOnline.'</div>';
		echo '<div class="gameserverlist_loading" id="loading'.$rowNr.'" style=";">'.$loaderImage.'</div>';
		echo '</td>';
		echo '<td>';
		echo '<div class="gameserverlist_serverstatus" id="serverStatus'.$rowNr.'">'.$cachedStatus.'</div>';

		echo '<script type="text/javascript">';

		if ($cacheExpired)
		{
			echo 'setTimeout ( "queryServer'.$rowNr.'()", 1000);';
		}
		else
		{
			echo 'setTimeout ( "queryServer'.$rowNr.'()", '.$this->params->def('serverlistupdateinterval', 30)*1000 .');';
		}

?>

	function queryServer<?php echo $rowNr;?> ( )
{					

	jQuery('#onlineimage<?php echo $rowNr;?>').hide();
	jQuery('#loading<?php echo $rowNr;?>').show();

	jQuery.getJSON("<?php echo JURI::root() ?>index.php?option=com_gameserver&amp;view=serverstatus&amp;serverid=<?php echo $row->serverid ?>&amp;format=raw&amp;task=list", function(response)
	{
	jQuery.each(response, function(id, html)
	{
	jQuery('#' + id + <?php echo $rowNr;?>).html(html);
});

		jQuery('#onlineimage<?php echo $rowNr;?>').show();
		jQuery('#loading<?php echo $rowNr;?>').hide();
});

		setTimeout ( "queryServer<?php echo $rowNr;?>()", <?php echo $this->params->def('serverlistupdateinterval', 30)*1000;?> );
}

	<?php

	echo '</script>';
	echo '</td>';
	echo '</tr>';
} // foreach ($this->rows as $row) 

echo '</table>';
echo '</div>';

echo '<center>';
if ($this->params->get('showcopyrightlink',1) == 1)
{
	$logo = JURI::base().'components/com_gameserver/images/icon.png';
	echo '<a href="http://joomla.larshildebrandt.de" target="_blank"><img src="'.$logo.'" alt="GameServer" /><br />powered by GameServer!</a>';
}
echo '</center>';
}

?>