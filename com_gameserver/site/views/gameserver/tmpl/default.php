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

$doc = JFactory::getDocument();

$style = $this->params->get( 'detailstylesheet' , 1);	
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

if ($this->params->get( 'loadjquery' , 1) == 1)
{
	JHtml::_('jquery.framework');
}

$doc->addScript( JURI::root().'/components/com_gameserver/script/jquery/base64.js' );

echo '<h2 class="componentheading">'. JText::_('DETAILS'). '</h2>';
echo '<div id="loading" style="display:none">'.JText::_('REFRESHING').'</div>';

try
{	

	if (!$this->gameDataProvider->isOnline()) {
		echo JText::_('SERVER_DID_NOT_RESPOND');
	}
	else{	

		if ($this->params->get( 'detailviewautorefresh' ,1))
		{
			
?>

			<script language="javascript" type="text/javascript">
				setTimeout ( "queryServer()", <?php echo $this->params->get( 'detailviewupdateinterval' , 30) ?>*1000 );

				function queryServer()
				{					
					jQuery('#loading').show();

					jQuery.getJSON("<?php echo JURI::root() ?>index.php?option=com_gameserver&amp;view=serverstatus&amp;serverid=<?php echo $this->gameserver->serverid ?>&amp;format=raw&amp;task=detail", function(response)
					{
				
					jQuery('#loading').hide();
				
					jQuery.each(response, function(id, html)
					{
						jQuery('#' + id).html(Base64.decode(html));
					});
					});
				
					setTimeout ( "queryServer()", <?php echo $this->params->get( 'detailviewupdateinterval' , 30) ?>*1000 );
				}				
				
				</script>
				
				<?php
			}
			
			$renderer = rendererFactory::factory($this->gameserver->type,$this->gameDataProvider->getProt());
			$renderer->init($this->gameDataProvider,$this->params->get( 'show_mapimages' ,1), $this->params->get( 'mapimagewidth' , 200), $this->params->get( 'usemapimagesfromglobalmapgallery' , 1), $this->params->get( 'usemapimagesfromexternalmapgallery' , 1), $this->params->get( 'detailviewteamview' , 1));
			
			$showsettings = $this->gameserver->showsettings;
			
			echo '<div id="gameservervalues">';
			echo $renderer->renderHeader();
			echo '</div>';
			
			if ($this->gameserver->description != '')
			{
				echo '<div style="clear: both;"></div>';
				echo $this->gameserver->description;
			}
			
			echo '<div style="clear: both;"></div>';
			
			if ($renderer->isPlayerlistAvailable())
			{
				if ($showsettings == 1)
				{				
					echo JHtml::_('tabs.start');
					echo JHtml::_('tabs.panel',JText::_('PLAYERS'),'panel_players');	
				}
				else
				{
					echo '<br />';	
				}
				
				echo '<div id="gameserverplayers">';
				echo $renderer->renderPlayerList();
				echo '</div>';
				
				if ($showsettings == 1)
				{
					echo JHtml::_('tabs.panel',JText::_('SETTINGS'),'panel_settings');	
				}
			}
			
			if ($showsettings == 1)
			{				
				echo '<div id="gameserversettings">';
				echo $renderer->renderSettings();
				echo '</div>';
			}
			
			if ($renderer->isPlayerlistAvailable())
			{
				if ($showsettings == 1)
				{				
					echo JHtml::_('tabs.end');			
				}
			}
			
			if ($this->params->get('showcopyrightlink',1) == 1)
			{
				echo '<center>';
				$logo = JURI::base().'components/com_gameserver/images/icon.png';
				echo '<a href="http://joomla.larshildebrandt.de" target="_blank"><img src="'.$logo.'" alt="GameServer" /><br />powered by GameServer!</a>';
				echo '</center>';	
			}
		}
	}
	catch(Exception $e)
	{
		echo $e->getMessage();
	}

	?>
	