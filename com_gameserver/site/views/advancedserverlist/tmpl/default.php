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

if ($this->params->get( 'loadjquery' , 1) == 1)
{
	JHtml::_('jquery.framework');
}

$style = $this->params->get( 'detailstylesheet' , 1);	
if ($style == "1") {
	$cssHTML = JURI::base().'components/com_gameserver/style/white.css';
	$doc->addStyleSheet($cssHTML);
} elseif ($style == "2") {
	$cssHTML = JURI::base().'components/com_gameserver/style/black.css';
	$doc->addStyleSheet($cssHTML);
} else {
	$cssHTML = JURI::base().'components/com_gameserver/style/blank.css';
	$doc->addStyleSheet($cssHTML);
	$loaderImage = '<img src="'.JURI::base().'components/com_gameserver/images/loader_dark.gif" alt="Loading" title="Loading" />';	
}

$hideipaddresses = $this->params->get( 'hideipaddresses' , 0) == 1;	

$iconfolder = JURI::base().'components/com_gameserver/images/icons/';
$logofolder = JURI::base().'components/com_gameserver/images/logos/';

$rowNr = 0;

?>
			
<?php

if ( $this->params->def( 'show_page_title', 1 ) ) { ?>
<div class="componentheading<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
	<?php echo $this->params->get('page_title'); ?>
</div>
<?php } ?>	
	
<?php

if (count($this->rows) == 0) {
	echo 'No Servers found';
}
else {	
	
?>

<table cellpadding="3" cellspacing="3" width="100%">
<?php

foreach ($this->rows as $row) {	  
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
	
	$cachedServername = $row->displayname;
	$cachedServerAddress = '';
	$cachedMapname = '';
	$cachedStatus = '';
	$cachedError = '';
	$cachedMapImageHtml = '<img class="com_gameserver_advancedlist_mapimage" src="'.JURI::base().'components/com_gameserver/images/maps/'.JText::_('NOMAPIMAGEFOUND').'" style="width:100px;height:100px;min-width:100px;max-width:100px;" alt="map" />';
	$showMapImage = true;
	
	$cacheExpired = $gameDataProvider->isCacheExpired($maxCacheTime,$row->cachedatetime);
	
	if (!$cacheExpired)
	{
		$gameDataProvider->loadServerData($row,$maxCacheTime);	
		$renderer = rendererFactory::factory($row->type,$gameDataProvider->getProt());
		$renderer->init($gameDataProvider,$this->params->get( 'show_mapimages' ,1), 100, $this->params->get( 'usemapimagesfromglobalmapgallery' , 1),$this->params->get( 'usemapimagesfromexternalmapgallery' , 1),0);
		
		if ($gameDataProvider->isOnline())
		{
			$cachedIsOnline = '<img src="'.JURI::base().'components/com_gameserver/images/online.jpg" alt="Server online" title="Server online" style="width:16px;height:16px; min-width: 16px;" />';	
			
			$cachedServername = $renderer->getHostname();
			$cachedServerAddress = $renderer->getConnectLink();
			$cachedStatus = $renderer->getServerStatus();
			$gametype = $renderer->getGametype();
			$cachedMapname = $gametype != '' ? $renderer->getMapname().' - '.$gametype : $renderer->getMapname();			
		}
		else
		{	
			$cachedError = JText::_( 'SERVER_DID_NOT_RESPOND');
		}
		
		$showMapImage = $renderer->showMapImage();
	}	
	
?>
<tr>
<td>
	<a class="com_gameserver_advancedlist_logo_link" href="<?php echo $link; ?>"><img class="com_gameserver_advancedlist_logo" src="<?php echo $logofolder.$type.'.png'; ?>" alt="<?php echo $row->displayname; ?>" title="<?php echo $row->displayname; ?>" style="width: 96px; height: 96px; min-width: 96px;" /></a>	
</td>
<td width="100%">
	<?php
	
	echo '<a class="com_gameserver_advancedlist_servername_link" id="serverName'.$rowNr.'" href="'.$link.'">'.$cachedServername.'</a>';
	if (!$hideipaddresses)
	{
		echo '<div class="com_gameserver_advancedlist_serveraddress" id="serverAddress'.$rowNr.'">'.$cachedServerAddress.'</div>';
	}
	echo '<div class="com_gameserver_advancedlist_map" id="currentMap'.$rowNr.'">'.$cachedMapname.'</div>';
	echo '<div class="com_gameserver_advancedlist_serverstatus" id="serverStatus'.$rowNr.'">'.$cachedStatus.'</div>';
	echo '<div class="com_gameserver_advancedlist_error" id="error'.$rowNr.'">'.$cachedError.'</div>';	
	
	?>
</td>
<td>
<?php
if ($showMapImage)
{
	echo '<div class="com_gameserver_advancedlist_mapimage_div" id="currentMapImage'.$rowNr.'">'.$cachedMapImageHtml.'</div>';
}
	?>	
<script language="javascript" type="text/javascript">	
		
<?php

echo 'setTimeout ( "queryServer'.$rowNr.'()", 1000);';

?>

function queryServer<?php echo $rowNr;?> ( )
{		

jQuery.getJSON("<?php echo JURI::root() ?>index.php?option=com_gameserver&amp;view=serverstatus&amp;serverid=<?php echo $row->serverid ?>&amp;format=raw&amp;task=advanced", function(response)
{
jQuery.each(response, function(id, html)
{
jQuery('#' + id + <?php echo $rowNr;?>).html(html);
});
});

	setTimeout ( "queryServer<?php echo $rowNr;?>()", <?php echo $this->params->def('serverlistupdateinterval', 30)*1000;?> );
}								
</script>
</td>
</tr>
	<?php
}
?>
</table>
	<?php
	
	if ($this->params->get('showcopyrightlink',1) == 1)
	{
		echo '<center>';
		$logo = JURI::base().'components/com_gameserver/images/icon.png';
		echo '<a href="http://joomla.larshildebrandt.de" target="_blank"><img src="'.$logo.'" alt="GameServer" /><br />powered by GameServer!</a>';
		echo '</center>';	
	}
	
}
?>