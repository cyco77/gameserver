<?php // no direct access
defined('_JEXEC') or die('Restricted access'); 

require_once (JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_gameserver'.DIRECTORY_SEPARATOR.'gamedataprovider.php');
require_once (JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_gameserver'.DIRECTORY_SEPARATOR.'renderer'.DIRECTORY_SEPARATOR.'rendererFactory.php');	

$gameDataProvider = new GameDataProvider();

$maxCacheTime = $componentparams->get( 'cacheinseconds', 60);

$moduleContent = '';

$cacheExpired = $gameDataProvider->isCacheExpired($maxCacheTime,$gameserver->cachedatetime);

$doc = JFactory::getDocument();
$doc->addScript( JURI::root().'/components/com_gameserver/script/jquery/jquery-1.7.2.min.js' );
$doc->addScript( JURI::root().'/components/com_gameserver/script/jquery/jquery.noconflict.js' );
$doc->addScript( JURI::root().'/components/com_gameserver/script/jquery/base64.js' );

$style = $componentparams->get( 'detailstylesheet' , 1);	
if ($style == "1") {
	$cssHTML = JURI::base().'components/com_gameserver/style/white.css';
	$doc->addStyleSheet($cssHTML);
} elseif ($style == "2") {
	$cssHTML = JURI::base().'components/com_gameserver/style/black.css';
	$doc->addStyleSheet($cssHTML);
}
else {
	$cssHTML = JURI::base().'components/com_gameserver/style/blank.css';
	$doc->addStyleSheet($cssHTML);
}


echo '<div id="gameserverdetailmodule'.$serverid.'">';

if (!$cacheExpired)
{
	$gameDataProvider->loadServerData($gameserver,$maxCacheTime);	
	$renderer = rendererFactory::factory($server->type,$gameDataProvider->getProt());
	$renderer->init($gameDataProvider,$showmap, $mapimagewidth, 1,1,0);
	
	if ($gameDataProvider->isOnline())
	{
		echo $renderer->renderModuleContent($server->serverid,$maxplayerlistheight,$showplayers,$customurl);
	}
	else
	{
		echo JText::_('SERVER_DID_NOT_RESPOND');	
	}
}
else
{
	echo '<div class="moduleloading">'.JText::_('REFRESHING').'</div>';
}

?>
</div>

<script type="text/javascript">				

	<?php echo 'setTimeout ( "queryServer'.$serverid.'()", 1000);'; ?>

function queryServer<?php echo $serverid;?> ( )
{					

<?php echo 'jQuery.getJSON("'.JURI::root().'index.php?option=com_gameserver&view=serverstatus&serverid='.$serverid.'&format=raw&task=detailmodule&mapimagewidth='.$mapimagewidth.'&showmap='.$showmap.'&showplayers='.$showplayers.'&maxplayerlistheight='.$maxplayerlistheight.'&customurl='.base64_encode($customurl).'", function(response)'; ?>
{
jQuery.each(response, function(id, html)
{
jQuery('#' + id + <?php echo $serverid;?>).html(Base64.decode(html));
});

});

	setTimeout ( "queryServer<?php echo $serverid;?>()", <?php echo $params->def('updateinterval', 30)*1000;?> );
}

</script>
