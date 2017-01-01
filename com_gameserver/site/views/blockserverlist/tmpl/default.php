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

?>
			
	<?php
	
	if ( $this->params->def( 'show_page_title', 1 ) ) { ?>
	<div class="componentheading<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
		<?php echo $this->params->get('page_title'); ?>
</div>
	<?php } ?>	
	
	<?php
	
	if (count($this->rows) == 0) 
	{
		echo 'No Servers found';
	}
	else
	{				
		echo '<center>';
		$col = 1;
		foreach ($this->rows as $row) 
		{	 				
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
				
		echo '<div class="blockserverlist_item">';
	
		echo '	<div class="blockserverlist_item_gamelogo">';
			echo '		<a class="com_gameserver_block_logo_link" href="'.$link.'">';
			echo '			<img class="com_gameserver_block_logo" src="'.$logofolder.$type.'.png'.'" alt="'.$gameDataProvider->getGameDisplayname($type).'" title="'.$gameDataProvider->getGameDisplayname($type).'" />';
			echo '		</a>';
		echo '	</div>';
		echo '	<a class="com_gameserver_block_servername_link" id="serverName'.$rowNr.'" href="'.$link.'">'.$cachedServername.'</a>';
		echo '	<div class="com_gameserver_block_error" id="error'.$rowNr.'">'.$cachedError.'</div>';
		
		if (!$hideipaddresses)
		{ 
			echo '	<div class="com_gameserver_block_serveraddress" id="serverAddress'.$rowNr.'">'.$cachedServerAddress.'</div>';
		}	
		echo '	<div class="com_gameserver_block_serverstatus" id="serverStatus'. $rowNr.'">'.$cachedStatus.'</div>';
		echo '	<div class="com_gameserver_block_map" id="currentMap'.$rowNr.'">'.$cachedMapname.'</div>';
		
		?>
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

		echo '</div>';

	
	} // foreach
	
	echo '<div style="clear:both;"/>';
	
	if ($this->params->get('showcopyrightlink',1) == 1)
	{
		$logo = JURI::base().'components/com_gameserver/images/icon.png';
		echo '<a href="http://joomla.larshildebrandt.de" target="_blank"><img src="'.$logo.'" alt="GameServer" /><br />powered by GameServer!</a>';
	}
	echo '</center>';
}
?>