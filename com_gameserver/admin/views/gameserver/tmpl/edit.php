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

require_once(JPATH_SITE.DS.'components'.DS.'com_gameserver'.DS.'gamedataprovider.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_gameserver'.DS.'game.php');	

$gameDataProvider = new GameDataProvider();

JHtml::_('jquery.framework');

?>

<form action="<?php echo JRoute::_('index.php?option=com_gameserver&layout=edit&serverid='.(int) $this->item->serverid); ?>" method="post" name="adminForm" id="adminForm">
	<div class="row-fluid">
		<div class="form-horizontal span6">
			<fieldset>
  				<legend><?php echo JText::_( 'Details' ); ?></legend>
				
				<?php echo $this->form->getControlGroup('serverid'); ?>
				<?php echo $this->form->getControlGroup('displayname'); ?>
				<?php echo $this->form->getControlGroup('type'); ?>
				<?php echo $this->form->getControlGroup('id'); ?>
				<?php echo $this->form->getControlGroup('ip'); ?>
				<?php echo $this->form->getControlGroup('port'); ?>
				<?php echo $this->form->getControlGroup('port2'); ?>
				<?php echo $this->form->getControlGroup('user'); ?>
				<?php echo $this->form->getControlGroup('pass'); ?>
				<?php echo $this->form->getControlGroup('url'); ?>
				<?php echo $this->form->getControlGroup('showsettings'); ?>
				
				<?php
					if (isset($this->item->serverid))
				{
					echo $this->form->getControlGroup('addedby');
				}
				?>
				
				<?php echo $this->form->getControlGroup('published'); ?>
				<?php echo $this->form->getControlGroup('description'); ?>
			</fieldset>	
		</div>
		<div class="form-horizontal span6">
			<fieldset>
				<div id="ports">
					<div>
						<b><?php echo JText::_( 'COM_GAMESERVER_DEFAULTPORTS' ); ?></b>
					</div>
					<div id="connectionPortInfo">
					</div>
					<div id="queryPortInfo"/>
					</div>
				</div>
				<div id="zusatzInfos">
					<div>
						<input type="button" onclick="checkServer()" id="btnCheck" value="<?php  echo JText::_( 'COM_GAMESERVER_CHECKSERVER'); ?>"  />
					</div>
					<br />
				</div>
				<br />
				<br />
				<div id="loading" style="display:none;">
					<?php echo JText::_( 'COM_GAMESERVER_TESTING' ); ?>
				</div>
				<div id="gameserverstatus">
				</div>	
			</fieldset>	
		</div>
	<input type="hidden" name="task" value="gameserver.edit" />
	<?php echo JHtml::_('form.token'); ?>
</form>
<script language="javascript" type="text/javascript">

	jQuery(document).ready(function($) 
	{
		jQuery('#jform_type').attr('onchange','setInfos()');
		
		if ( jQuery('#jform_serverid').val() == '')
		{
			jQuery('#jform_type').val('alienswarm');
		}
		setInfos();
	});
                      
    function setInfos() 
	{                   			
		jQuery('#gameserverstatus').html("");							
		updateRequiredFields();
    }
                        
    function showInfo(el) 
	{
        document.getElementById(el).style.display = ''
    } 
                      
    function hideInfo(el) 
	{
        document.getElementById(el).style.display = 'none'
    }
						
	function updateRequiredFields()
	{
		var type = jQuery('#jform_type').val();
						
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
		if (showPort2) 
		{ 
			jQuery('#jform_port2').removeAttr('disabled'); 
		} 
		else 
		{
			jQuery('#jform_port2').attr('disabled', 'disabled');
			jQuery('#jform_port2').val('');
		}
		
		if (showUsername) 
		{ 
			jQuery('#jform_user').removeAttr('disabled'); 
		} 
		else 
		{
			jQuery('#jform_user').attr('disabled', 'disabled');
			jQuery('#jform_user').val('');
		}
		
		if (showPassword) 
		{ 
			jQuery('#jform_pass').removeAttr('disabled'); 
		} 
		else 
		{
			jQuery('#jform_pass').attr('disabled', 'disabled');
			jQuery('#jform_pass').val('');
		}
														
		jQuery('#connectionPortInfo').html('<?php echo JText::_( 'COM_GAMESERVER_PORT' ) ?>: ' + connectionPort);
							
		if (queryPort != '')
		{
			jQuery('#queryPortInfo').html('<?php echo JText::_( 'COM_GAMESERVER_PORT2' ) ?>: ' + queryPort);
		}
		else
		{
			jQuery('#queryPortInfo').html('');
		}							
							
		if ( jQuery('#jform_serverid').val() == '0')
		{
				jQuery('#jform_port').val(connectionPort);
				jQuery('#jform_port2').val(queryPort);
		}
	}				
						
	function checkServer() 
	{												
		jQuery('#gameserverstatus').html("");
		jQuery('#loading').show();
							
		jQuery.getJSON("<?php echo JURI::root() ?>index.php?option=com_gameserver&view=servertester&type=" + jQuery('#jform_type').val() + "&" +
		"ip=" + jQuery('#jform_ip').val() + "&" + 
		"port=" + jQuery('#jform_port').val() + "&" + 
		"port2=" + jQuery('#jform_port2').val() + "&" + 
		"user=" + jQuery('#jform_user').val() + "&" + 
		"pass=" + jQuery('#jform_pass').val() + "&" +					
		"&format=tester", function(response)
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
