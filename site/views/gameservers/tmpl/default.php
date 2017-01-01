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

$serverview = $this->params->get( 'serverview' , 0);	
$blockwidth = $this->params->get( 'blockwidth' , 200);
$blocksinrow = $this->params->get( 'blocksinrow' ,2);
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
	echo '<center>';
	echo '<table>';
	$col = 1;
	foreach ($this->rows as $row) 
	{	 				
		if ($col == 1) { echo '<tr>'; }
		
		$rowNr = $row->serverid;
		$type = $row->type;
		$ip = $row->ip;
		$port = $row->port;
		$port2 = $row->port2;
		$user = $row->user;
		$pass = $row->pass;
		$url = $row->url;
		$params = serialize($row);
		
		$link = JRoute::_( 'index.php?option=com_gameserver&amp;view=gameserver&amp;serverid='. $row->serverid );
		
		$gameDataProvider = new GameDataProvider();
		
		$maxCacheTime = $this->params->get( 'cacheinseconds', 60);
		
		$cachedIsOnline = '<img src="'.JURI::base().'components/com_gameserver/images/offline.jpg" alt="Server offline" title="Server offline" width="16" height="16" />';;
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
				$cachedIsOnline = '<img src="'.JURI::base().'components/com_gameserver/images/online.jpg" alt="Server online" title="Server online" width="16" height="16" />';	
				
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
		}
		
		echo '<td valign="top">';			
		echo '<fieldset class="groupbox" style="width:'.$blockwidth.'px;">'."\n";
		echo '<legend class="groupbox">'.$cachedServername.'</legend>'."\n";
?>
<div class="com_gameserver_fixedblock_gamelogo">
			<a class="com_gameserver_fixedblock_logo_link" href="<?php echo $link; ?>">
			<img class="com_gameserver_fixedblock_logo" src="<?php echo $logofolder.$type.'.png'; ?>" alt="<?php echo $gameDataProvider->getGameDisplayname($type); ?>" title="<?php echo $gameDataProvider->getGameDisplayname($type); ?>" />
</a>
</div>

		<a class="com_gameserver_fixedblock_servername_link" id="serverName<?php echo $rowNr;?>" href="<?php echo $link; ?>"><?php echo $cachedServername; ?></a>
		<div class="com_gameserver_fixedblock_error" id="error<?php echo $rowNr;?>"><?php echo $cachedError; ?></div>	
		<?php 
	if (!$hideipaddresses)
	{ 
?>
		<div class="com_gameserver_fixedblock_serveraddress" id="serverAddress<?php echo $rowNr;?>"><?php echo $cachedServerAddress; ?></div>
		
<?php
}
?>		
		<div class="com_gameserver_fixedblock_serverstatus" id="serverStatus<?php echo $rowNr;?>"><?php echo $cachedStatus; ?></div>	
		<div class="com_gameserver_fixedblock_map" id="currentMap<?php echo $rowNr;?>"><?php echo $cachedMapname; ?></div>	
<script type="text/javascript">				

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

jQuery.getJSON("<?php echo JURI::root() ?>index.php?option=com_gameserver&view=serverstatus&serverid=<?php echo $row->serverid ?>&format=raw&task=list", function(response)
{
jQuery.each(response, function(id, html)
{
jQuery('#' + id + <?php echo $rowNr;?>).html(html);
});
});

			setTimeout ( "queryServer<?php echo $rowNr;?>()", <?php echo $this->params->def('serverlistupdateinterval', 30) * 1000;?> );
}							
</script>
		<?php

		echo '</fieldset>'."\n";
		echo '</td>';
		if ($col == $blocksinrow) { echo '</tr>'; }

		$col++;
		if ($col > $blocksinrow) 
		{
			$col=1; 
		}				
	} // foreach
	
	if ($col <= $blocksinrow) 
	{
		while ($col <= $blocksinrow)
		{
			echo '<td></td>';
			$col++;
		}

		echo '</tr>';
	}
	echo '</table>';
	if ($this->params->get('showcopyrightlink',1) == 1)
	{
		$logo = JURI::base().'components/com_gameserver/images/icon.png';
		echo '<a href="http://joomla.larshildebrandt.de" target="_blank"><img src="'.$logo.'" alt="GameServer" /><br />powered by GameServer!</a>';
	}
	echo '</center>';
}		
?>