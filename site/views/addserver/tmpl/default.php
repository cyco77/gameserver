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

JHtml::_('jquery.framework');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.tooltip');

require_once(JPATH_SITE.DS.'components'.DS.'com_gameserver'.DS.'gamedataprovider.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_gameserver'.DS.'game.php');	

$gameDataProvider = new GameDataProvider();

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root().'components/com_gameserver/style/form.css');
$document->addStyleSheet(JURI::root().'components/com_gameserver/style/blank.css');

$html = array();

if ($this->params->get('show_page_heading', 1)) : 
	$html[] = '<h1>';
	if ($this->escape($this->params->get('page_heading'))) :
		$html[] = $this->escape($this->params->get('page_heading')); 
	else : 
		$html[] = $this->escape($this->params->get('page_title')); 
	endif;
	$html[] = '</h1>';
endif; 

// User
$user = JFactory::getUser();
$user_id = $user->get('id');

$html[] = '<form class="form-validate" action="'. JRoute::_('index.php?option=com_gameserver&view=addserver') . '" method="post" name="addserverForm" id="addserver-form">';
$html[] = '<div class="adminform">';
$html[] = '				<div>'. $this->form->getLabel('displayname');
$html[] = $this->form->getInput('displayname').'</div>';
$html[] = '				<div>'. $this->form->getLabel('type');
$html[] = $this->form->getInput('type').'</div>';
$html[] = '				<div>'. $this->form->getLabel('ip');
$html[] = $this->form->getInput('ip').'</div>';
$html[] = '				<div>'. $this->form->getLabel('port');
$html[] = $this->form->getInput('port').'</div>';
$html[] = '				<div>'. $this->form->getLabel('port2');
$html[] = $this->form->getInput('port2').'</div>';
$html[] = '				<div>'. $this->form->getLabel('user');
$html[] = $this->form->getInput('user').'</div>';
$html[] = '				<div>'. $this->form->getLabel('pass');
$html[] = $this->form->getInput('pass').'</div>';
$html[] = '				<div>'. $this->form->getLabel('url');
$html[] = $this->form->getInput('url').'</div>';
$html[] = '				<div>'. $this->form->getLabel('showsettings');
$html[] = $this->form->getInput('showsettings').'</div>';
$html[] = '			<div class="clr"></div>';
$html[] = $this->form->getLabel('description');
$html[] = '			<div class="clr"></div>';
$html[] = $this->form->getInput('description');
$html[] = '			<div class="clr"></div>';
$html[] = '			<div id="zusatzInfos">';
$html[] = '				<div style="float:left;">';
$html[] = '					<button class="button" type="button" onclick="checkServer()" id="btnCheck" >'. JText::_( 'COM_GAMESERVER_CHECKSERVER').'</button>';
$html[] = '					<button type="submit" class="button">' . JText::_('COM_GAMESERVER_ADDSERVER_SAVE') . '</button>';
$html[] = '					<button type="reset" class="button">' . JText::_('COM_GAMESERVER_ADDSERVER_RESET') . '</button>';
$html[] = '				</div>';
$html[] = '				<div class="clr"></div>';
$html[] = '				<br />';
$html[] = '			</div>';
$html[] = '			<br />';
$html[] = '			<br />';
$html[] = '			<div id="loading" style="display:none;">';
$html[] = JText::_( 'COM_GAMESERVER_TESTING' );
$html[] = '			</div>';
$html[] = '			<div id="gameserverstatus">';
$html[] = '			</div>';
$html[] = '</div>';
$html[] = ' <input type="hidden" name="option" value="com_gameserver" />';
$html[] = '	<input type="hidden" name="task" value="gameserver.add" />';
$html[] = JHtml::_('form.token');
$html[] = '</form>';

echo implode("\n", $html); 

?>

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
		
		jQuery('#jform_port').val(connectionPort);
		jQuery('#jform_port2').val(queryPort);
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