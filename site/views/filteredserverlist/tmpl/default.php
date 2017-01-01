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
<div class="filteredserverlist_filter">
<form action="<?php echo JRoute::_( 'index.php?option=com_gameserver&amp;view=filteredserverlist' ); ?>" method="post" name="filterform" id="filterform">

<!-- Region Filter -->
<?php

$regions = array();
foreach ($this->rows as $row) 
{	  
	$region = JText::_($row->region);
	if (!in_array($region,$regions))
	{
		array_push($regions, $region);
	}		
}
natcasesort($regions);
?>
<select id="regionfilter" name="regionfilter" class="select">
<option value="all"><?php echo JText::_('ALLREGIONS'); ?></option>
<?php

$activeRegionFilter = $_POST['regionfilter'] == '' ? JText::_($this->regionFilter) : $_POST['regionfilter'];

foreach ($regions as $region) 
{	  
	if ($region == $activeRegionFilter)
	{
		echo '<option selected="selected" value="'.$region.'">'.$region.'</option>';	
	}
	else
	{
		echo '<option value="'.$region.'">'.$region.'</option>';	
	}		
}
?>
</select>

<!-- Game Filter -->
<?php
$gameDataProvider = new GameDataProvider();
$games = array();
foreach ($this->rows as $row) 
{	  
	$game = $gameDataProvider->getGameDisplayname($row->type);
	if (!in_array($game, $games))
	{
		array_push($games, $game);
	}		
}
natcasesort($games);

?>

<select id="gamefilter" name="gamefilter" >
<option value="Unsupported"><?php echo JText::_('ALLGAMES'); ?></option> 
<?php


$activeGameFilter = $_POST['gamefilter'] == '' ? $gameDataProvider->getGameDisplayname($this->gameFilter) : $_POST['gamefilter'];

foreach ($games as $game) 
{	  
	if ($game == $activeGameFilter)
	{
		echo '<option selected value="'.$game.'">'.$game.'</option>';	
	}
	else
	{
		echo '<option value="'.$game.'">'.$game.'</option>';	
	}			
}
?>
</select>

<input type="submit" class="button" name="submitfilter" value="<?php echo JText::_('FILTER') ?>"/>
</form>
</div>

<!-- Serverlist -->

<div class="filteredserverlist_items">
<?php } ?>	
	
<?php

if (count($this->rows) == 0) {
	echo 'No Servers found';
}
else {	
	
	foreach ($this->rows as $row) {	  
		
		if (($activeRegionFilter == 'all' || $activeRegionFilter == JText::_($row->region)) 
			&& ($activeGameFilter == 'Unsupported' || $activeGameFilter == $gameDataProvider->getGameDisplayname($row->type)))
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
			
			$cachedIsOnline = '<img src="'.JURI::base().'components/com_gameserver/images/offline.jpg" alt="Server offline" title="Server offline" width="16" height="16" />';;
			$cachedServername = $row->displayname;
			$cachedServerAddress = '';
			$cachedMapname = '';
			$cachedStatus = '';
			
			$cacheExpired = $gameDataProvider->isCacheExpired($maxCacheTime,$row->cachedatetime);
			
			if (!$cacheExpired)
			{
				$gameDataProvider->loadServerData($row,$maxCacheTime);	
				$renderer = rendererFactory::factory($row->type,$gameDataProvider->getProt());
				$renderer->init($gameDataProvider,$this->params->get( 'show_mapimages' ,1), 100, $this->params->get( 'usemapimagesfromglobalmapgallery' , 1),$this->params->get( 'usemapimagesfromexternalmapgallery' , 1),0);			
				
				if ($gameDataProvider->isOnline())
				{
					$cachedIsOnline = '<img src="'.JURI::base().'components/com_gameserver/images/online.jpg" alt="Server online" title="Server online" width="16" height="16" />';	
					
					$cachedServername = $renderer->getHostname();
					$cachedServerAddress = $renderer->getConnectLink();
					$cachedStatus = $renderer->getServerStatus();
					$gametype = $renderer->getGametype();
					$cachedMapname = $gametype != '' ? $renderer->getMapname().' - '.$gametype : $renderer->getMapname();
				}
			}
?>

<div class="filteredserverlist_item">
<div class="filteredserverlist_serverdata">
<div class="filteredserverlist_logo">
				<img src="<?php echo $iconfolder.$type.'.png'; ?>" alt="<?php echo $row->displayname; ?>" title="<?php echo $row->displayname; ?>" />
</div>
			<a class="filteredserverlist_serverdata_name" id="serverName<?php echo $rowNr;?>" href="<?php echo $link; ?>">
				<?php echo $cachedServername; ?>
</a>
			<div class="filteredserverlist_loading" id="loading<?php echo $rowNr;?>" style=";">
				<?php echo $loaderImage; ?>
</div>
</div>
<div class="filteredserverlist_serverdata">
			<div class="filteredserverlist_serverdata_onlineimage" id="onlineimage<?php echo $rowNr;?>">
				<?php echo $cachedIsOnline; ?>
</div>	
<div class="filteredserverlist_serverdata_address">
				<img src="<?php echo JURI::base().'components/com_gameserver/images/flags/'.strtolower($row->country); ?>.png" alt="<?php echo $row->country; ?>" title="<?php echo $row->country; ?>" /> 
				<div id="serverAddress<?php echo $rowNr;?>">
					<?php 
					if (!$hideipaddresses)
					{
						echo $cachedServerAddress; 
					}
					?>
</div>

</div>
			<div id="currentMap<?php echo $rowNr;?>" class="filteredserverlist_serverdata_mapname">
				<?php echo $cachedMapname; ?>
</div>
			<div id="serverStatus<?php echo $rowNr;?>" class="filteredserverlist_serverdata_playersonline">
				<?php echo $cachedStatus; ?>
</div>
</div>
</div>

<script language="javascript" type="text/javascript">			

	<?php

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
	jQuery('#loading<?php echo $rowNr;?>').show();

	jQuery.getJSON("<?php echo JURI::root() ?>index.php?option=com_gameserver&amp;view=serverstatus&amp;serverid=<?php echo $row->serverid ?>&amp;format=raw&amp;task=filtered", function(response)
	{
	jQuery.each(response, function(id, html)
	{
	jQuery('#' + id + <?php echo $rowNr;?>).html(html);
});
jQuery('#loading<?php echo $rowNr;?>').hide();
});

		setTimeout ( "queryServer<?php echo $rowNr;?>()", <?php echo $this->params->def('serverlistupdateinterval', 30)*1000;?> );
}								
</script>
	<?php
	
}
}

echo '</div>';

if ($this->params->get('showcopyrightlink',1) == 1)
{
	echo '<center>';
	$logo = JURI::base().'components/com_gameserver/images/icon.png';
	echo '<a href="http://joomla.larshildebrandt.de" target="_blank"><img src="'.$logo.'" alt="GameServer" /><br />powered by GameServer!</a>';
	echo '</center>';	
}

}
?>