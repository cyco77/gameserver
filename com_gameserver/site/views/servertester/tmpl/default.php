<?php

require_once(JPATH_SITE.DS.'components'.DS.'com_gameserver'.DS.'gamedataprovider.php');	

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
}
else {
	$cssHTML = JURI::base().'components/com_gameserver/style/blank.css';
	$doc->addStyleSheet($cssHTML);
}

$gameDataProvider = new GameDataProvider();

echo '<h2 class="componentheading">'. JText::_('SERVERTESTER'). '</h2>';

?>

<script language="javascript" type="text/javascript">

                      	onload = function() {
                      		setInfos()
                      	} 
                      
                      	function setInfos() { 
                  			
							document.getElementById("gameserverstatus").innerHTML = '';	
							
							updateRequiredFields();
                        }
                        
                        function showInfo(el) {
                      		document.getElementById(el).style.display = ''
                      	} 
                      
                      	function hideInfo(el) {
                      		document.getElementById(el).style.display = 'none'
                      	}
						
						function updateRequiredFields()
						{
							var type = document.adminForm.type.value;
						
							switch (type) 
							{
								<?php 
								foreach ($gameDataProvider->supported as &$game) 
								{
									$isPort2Useable = $game->isPort2Useable ? 'true' : 'false';
									$isUsernameUseable = $game->isUsernameUseable ? 'true' : 'false';
									$isPasswordUseable = $game->isPasswordUseable ? 'true' : 'false';
									
									$port = $game->port != '' ? $game->port : "''";								
									$port2 = $game->port2 != '' ? $game->port2 : "''";								
									
									echo 'case "'.$game->type.'": updateFields('.$isPort2Useable.','.$isUsernameUseable.','.$isPasswordUseable.','.$port.','.$port2.'); break;';									
								}									
								
								?>
							  default:
								 updateFields(true,true,true,'',''); break;
								break;
							}		
						}
						
						function updateFields(showPort2,showUsername,showPassword,connectionPort,queryPort)
						{				
							document.adminForm.port2.disabled = !showPort2;
							document.adminForm.user.disabled = !showUsername;
							document.adminForm.pass.disabled = !showPassword;
							document.adminForm.port.value = connectionPort;
							document.adminForm.port2.value = queryPort;
						}				
						
						function checkServer() { 					
														
							jQuery('#gameserverstatus').html("");
							jQuery('#loading').show();
							
							jQuery.getJSON("<?php echo JURI::root() ?>index.php?option=com_gameserver&amp;view=servertester&amp;type=" + document.adminForm.type.value + "&amp;" +
							"ip=" + document.adminForm.ip.value + "&amp;" + 
							"port=" + document.adminForm.port.value + "&amp;" + 
							"port2=" + document.adminForm.port2.value + "&amp;" + 
							"user=" + document.adminForm.user.value + "&amp;" + 
							"pass=" + document.adminForm.pass.value + "&amp;" +					
							"format=tester", function(response)
							{
								//hide the progress bar
								jQuery('#loading').hide();  
							
								jQuery.each(response, function(id, html)
								{
									jQuery('#' + id).html(html);
								});
							}); 					
						}

                     </script> 

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div>
	<fieldset class="adminform">
  	<table border="0" width="100%">
        <tr>
            <td width="110" class="key">
            	<label for="type">
            		<?php echo JText::_( 'GAME' ); ?>:
            	</label>
            </td>
            <td>
            			   
            	<select name="type" id="type" size="10" onchange="setInfos()">
            	<?php			
            				
            	$typeToSelect = $this->type != '' ? $this->type : 'alienswarm';
            				
            	foreach ($gameDataProvider->supported as &$game) {
            		$selected = ($game->type == $typeToSelect) ? ' selected="selected"' : '';
            		echo '<option value="'.$game->type.'"'. $selected.'>'.$game->name.'</option>';
            	}					
            	?>
            	</select>
            </td>
        </tr>		
        <tr>
            <td width="110" class="key">
            	<label for="ip">
            		<?php echo JText::_( 'IP' ); ?>:
            	</label>
            </td>
            <td>
            	<input class="inputbox" type="text" name="ip" id="ip" size="60" value="<?php echo $this->ip; ?>" />
            </td>
        </tr>	
        <tr>
            <td width="110" class="key">
            	<label for="port">
            		<?php echo JText::_( 'PORT' ); ?>:
            	</label>
            </td>
            <td>
            	<input class="inputbox" type="text" name="port" id="port" size="60" value="<?php echo $this->port; ?>" />
            </td>
        </tr>		
		<tr>
            <td width="110" class="key">
            	<label for="port2">
            		<?php echo JText::_( 'PORT2' ); ?>:
            	</label>
            </td>
            <td>
            	<input class="inputbox" type="text" name="port2" id="port2" size="60" value="<?php echo $this->port2; ?>" />
            </td>
        </tr>	
		<tr>
            <td width="110" class="key">
            	<label for="user">
            		<?php echo JText::_( 'USER' ); ?>:
            	</label>
            </td>
            <td>
            	<input class="inputbox" type="text" name="user" id="user" size="60" value="<?php echo $this->user; ?>" />
            </td>
        </tr>
		<tr>
            <td width="110" class="key">
            	<label for="pass">
            		<?php echo JText::_( 'PASS' ); ?>:
            	</label>
            </td>
            <td>
            	<input class="inputbox" type="text" name="pass" id="pass" size="60" value="<?php echo $this->pass; ?>" />
            </td>
        </tr>	
		<tr>
			<td colspan="2">
				<div>
					<input class="button" type="button" onclick="checkServer()" id="btnCheck" value="<?php  echo JText::_( 'CHECKSERVER'); ?>"  />
				</div>
				<div id="loading" style="display:none;">
				Teste Serverdaten
				</div>
				<div id="gameserverstatus"></div>
				<div id="error"></div>
			</td>
		</tr>				
  	</table>
	</fieldset>
</div>

</form>
<?php
if ($this->params->get('showcopyrightlink',1) == 1)
{
	echo '<center>';
	$logo = JURI::base().'components/com_gameserver/images/icon.png';
	echo '<a href="http://joomla.larshildebrandt.de" target="_blank"><img src="'.$logo.'" alt="GameServer" /><br />powered by GameServer!</a>';
	echo '</center>';	
}
?>