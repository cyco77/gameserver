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
include_once JPATH_SITE.DS.'components'.DS.'com_gameserver'.DS.'renderer'.DS.'playerValues.php';
include_once JPATH_SITE.DS.'components'.DS.'com_gameserver'.DS.'renderer'.DS.'colorRenderer.php';

abstract class abstractRenderer 
{
	protected $gameDataProvider;
	protected $data;
	protected $showMapImage;
	protected $mapimagewidth;
	protected $mapimageheight;
	protected $useGlobalMapGallery = 1;
	protected $useExternalMapGallery = 1;
	protected $detailviewteamview = 1;
	protected $mapimage;
	public $mapimagesponsor;
	public $ip;	
	protected $params;	
	
	public function init($gameDataProvider,$showMapImage,$mapimagewidth,$usemapimagesfromglobalmapgallery,$usemapimagesfromexternalmapgallery,$detailviewteamview) 
	{
		$this->gameDataProvider = $gameDataProvider;
		$this->data = $gameDataProvider->serverdata;
		$this->showMapImage = $showMapImage;
		$this->mapimagewidth = $mapimagewidth;
		$this->useGlobalMapGallery = $usemapimagesfromglobalmapgallery;
		$this->useExternalMapGallery = $usemapimagesfromexternalmapgallery;	
		$this->detailviewteamview = $detailviewteamview;
		
		$params = JComponentHelper::getParams( 'com_gameserver' ); 
		
		$this->params = $params;
	}
	
	function dataCleasing($data){
		$data = strip_tags ($data); // remove HTML Tags
		// remove Incorrect encoding characters
		$data = preg_replace ( '/[^(\x20-\x7F)]*/', "", $data ); 
		$data = str_replace ( "\n", "", $data );//remove Enter
		$data = str_replace ( ",", "", $data );//remove Comma
		$data = str_replace ( "\t", "", $data );//remove TAB
		$data = str_replace ( "\r\n", "", $data );//remove Enter
		$data = trim($data);
		return $data;
	}
	
	public function renderHeader()
	{	
		$output = '';
		
		$output .= '  <div class="gameserver_detailtable">';
		
		$output .= '   <div class="gameserver_detailtable_left">';
		$output .= '      <img src="'.$this->getGameLogo().'" border="0" alt="'.$this->getGamename().'" />';
		$output .= '   </div>';
		
		if ($this->showMapImage())
		{
			$output .= '   <div class="gameserver_detailtable_right">';
			$output .= $this->getMapImageHtml();
			$output .= '   </div>';
		}
		
		if ($this->showMapImage())
		{
			$output .= '   <div class="gameserver_detailtable_center" style="margin-right: '.($this->mapimagewidth+2).'px;" >';
		}
		else
		{
			$output .= '   <div class="gameserver_detailtable_center" >';			
		}
		$output .= '   <table width="100%" border="0" cellspacing="1" cellpadding="3">';
		foreach ($this->getHeaderValues() as $caption => $value) 
		{
			$output .= '<tr class="gameserver_line" >';
			$output .= '<td class="gameserver_titel">'.$caption.'</td>';
			$output .= '<td class="gameserver_value">'.$value.'</td>';
			$output .= '</tr>';
		}		
		$output .= '   </table>';
		$output .= '   </div>';
		
		$output .= '  </div>';
		
		return $output;		
	}
	
	public function showMapImage()
	{
		return $this->showMapImage == 1;	
	}
	
	protected function getHeaderValues()
	{
		$hideipaddresses = $this->params->get( 'hideipaddresses' , 0) == 1;	
		
		if ($hideipaddresses)
		{
			return array(
				JText::_('SERVERNAME') => $this->getHostname(), 
				JText::_('PLAYERS') => $this->getFormatedPlayerCount()
				);
		}
		else
		{
			return array(
				JText::_('SERVERNAME') => $this->getHostname(), 
				JText::_('IP') => $this->getConnectLink(),
				JText::_('PLAYERS') => $this->getFormatedPlayerCount()
				);
		}
	}
	
	public function renderPlayerList()
	{
		return $this->renderSimplePlayerList();	
	}
	
	public function renderSimplePlayerList()
	{
		$output = '<table width="100%">';
		$output .= '<tr>';
		$output .= '<td colspan="2">';
		$output .= '<table width="100%" border="0" cellspacing="1" cellpadding="3" class="gameserver_table" >';				
		$output .= '<tr>';
		$output .= '<th class="gameserver_titel"></th>';
		
		$output .= $this->renderPlayerHeader();

		$output .= '</tr>';

		foreach ($this->data['players'] as $player)
		{														
			$output .= $this->renderPlayerValues($player);
		}

		$output .= '</table>';
		$output .= '</td>';			
		$output .= '</tr>';
		$output .= '</table>';
		
		return $output;
	}
	
	public function renderTeamPlayerList($teamattribute,$teamattributevalue1,$teamattributevalue2)
	{		
		$output = '<table width="100%">';
		$output .= '<tr>';
		$output .= '<td>';
		$output .= $this->renderTeam1Header();
		$output .= '</td>';
		$output .= '<td>&nbsp;';
		$output .= '</td>';
		$output .= '<td>';
		$output .= $this->renderTeam2Header();
		$output .= '</td>';
		$output .= '</tr>';
		$output .= '<tr>';
		$output .= '<td valign="top">';
		$output .= '<table width="100%" border="0" cellspacing="1" cellpadding="3" class="gameserver_table" >';				
		$output .= '<tr>';
		$output .= '<th class="gameserver_titel"></th>';
		
		$output .= $this->renderPlayerHeader();

		$output .= '</tr>';

		foreach ($this->data['players'] as $player){	
			
			if ($player[$teamattribute] == $teamattributevalue1)
			{
				$output .= $this->renderPlayerValues($player);
			}			
		}

		$output .= '</table>';
		$output .= '</td>';		
		$output .= '<td>&nbsp;';		
		$output .= '</td>';		
		$output .= '<td valign="top">';
		$output .= '<table width="100%" border="0" cellspacing="1" cellpadding="3" class="gameserver_table" >';				
		$output .= '<tr>';
		$output .= '<th class="gameserver_titel"></th>';
		
		$output .= $this->renderPlayerHeader();

		$output .= '</tr>';

		foreach ($this->data['players'] as $player){	
			
			if ($player[$teamattribute] == $teamattributevalue2)
			{
				$output .= $this->renderPlayerValues($player);
			}			
		}

		$output .= '</table>';
		$output .= '</td>';		
		$output .= '</tr>';
		$output .= '</table>';
		
		return $output;
	}
	
	protected function renderPlayerHeader()
	{
		$output = '';
		
		foreach ($this->getPlayerValues() as $playerValue)
		{			
			$cssclass = 'gameserver_titel';
			if ($playerValue->alignment == alignment::right) { $cssclass = 'gameserver_titel_right'; }
			if ($playerValue->alignment == alignment::center) { $cssclass = 'gameserver_titel_center'; }
			
			if ($playerValue->hideMobile) {$cssclass .= ' gameserver_titel_hidemobile'; }
			
			$output .= '<th class="'.$cssclass.'">'.$playerValue->caption.'</th>';
		}
		
		return $output;	
	}
	
	protected function renderPlayerValues($player)
	{
		$output =  '<tr class="gameserver_line">';
		$output .=  '<td class="gameserver_value" align="center">';
		$output .=  '<img src="'.$this->getPlayerIcon($player).'" border="0" alt="'.$this->escapeSpecialCharacters($player['gq_name']).'" />';
		$output .=  '</td>';
		
		foreach ($this->getPlayerValues() as $playerValue)
		{
			$cssclass = 'gameserver_value';
			if ($playerValue->alignment == alignment::right) { $cssclass = 'gameserver_value_right'; }
			if ($playerValue->alignment == alignment::center) { $cssclass =   'gameserver_value_center'; }
			
			if ($playerValue->hideMobile) {$cssclass .= ' gameserver_value_hidemobile'; }
			
			$output .=   '<td class="'.$cssclass.'">';
			$output .=   $this->getPlayerValue($player,$playerValue->attribute);
			$output .=   '</td>';
		}
		$output .=   '</tr>';
		
		return $output;
	}
	
	protected function renderTeam1Header()
	{
		return '&nbsp;';
	}
	
	protected function renderTeam2Header()
	{
		return '&nbsp;';
	}
	
	protected function getPlayerValues()
	{
		return array(
			new playerValues(JText::_('PLAYERNAME'),'gq_name',alignment::left,false),
			new playerValues(JText::_('SCORE'),'gq_score',alignment::center)
			);
	}
	
	protected function getPlayerIcon($player)
	{
		$result = JURI::base().'components/com_gameserver/images/player.png';
		
		return $result;
	}
	
	protected function getPlayersCount()
	{								
		if (!$this->data['gq_online']) 
		{
			return 0;
		}
		
		return $this->data['gq_numplayers'] - $this->getBotsCount();		
	}
	
	protected function getBotsCount()
	{
		return 0;
	}
	
	protected function getFormatedPlayerCount()
	{
		$playerCount = $this->getPlayersCount();
		$maxPlayers = $this->data['gq_maxplayers'];
		
		if ($this->data['gq_maxplayers'] !=0) 
		{
			$percent = ($playerCount / $maxPlayers) * 100;
			
			return $playerCount . "/" . $maxPlayers . " (" . number_format ($percent, 2) . "%)";
		} 
		else 
		{
			return $playerCount;
		}	
	}	
	
	public function countPlayersBy($players, $property, $value)
	{
		$result = 0;			
		foreach ($players as $player)
		{													
			if ($player[$property] == $value)
			{
				$result++;	
			}			
		}			
		return $result;			
	}
	
	public function getServerStatus()
	{								
		if (!$this->data['gq_online']) 
		{
			return JText::_('SERVER_DID_NOT_RESPOND');
		}
		
		return $this->getPlayersCount().' / '.$this->data['gq_maxplayers'].' Users online';
	}
	
	protected function getPlayerValue($player, $attribute)
	{
		return $player[$attribute];	
	}
	
	public function renderSettings()
	{
		$output = '<table width="100%">';
		$output .= '<tr>';
		$output .= '<td colspan="2">';
		$output .= '<table width="100%" border="0" cellspacing="1" cellpadding="3" class="simple_table" >';
		$output .= '<tr>';
		$output .= '<th class="gameserver_titel"></th>';
		$output .= '<th class="gameserver_titel">'. JText::_('SETTING'). '</th>';
		$output .= '<th class="gameserver_titel">'. JText::_('VALUE') .'</th>';
		$output .= '</tr>';
		
		foreach ($this->data as $key => $val) 
		{   
			if ($this->isAllowedProperty($key))
			{ 			
				$output .= '<tr class="gameserver_line">';
				$output .= '<td class="gameserver_value" width="30" align="center"><img src="'. JURI::base().'components/com_gameserver/images/setting.png'.'" border="0" alt="" /></td>';
				$output .= '<td class="gameserver_value">'. $key .'</td>';
				$output .= '<td class="gameserver_value">'. $val .'</td>';
				$output .= '</tr>';
			}
		}
		$output .= '</table>';
		$output .= '</td>';
		$output .= '</tr>';
		$output .= '</table>';
		
		return $output;
	}
	
	protected function isAllowedProperty($property)
	{
		return substr($property, 0, 3) != 'gq_' 
			&& $property != 'orgaddress'
			&& $property != 'gamename'
			&& $property != 'players';
	}
	
	public function isPlayerlistAvailable()
	{
		$game=$this->gameDataProvider->getGame($this->data['gq_type']);
		
		return $game->renderPlayerList;	
	}
	
	protected function getGamename()
	{
		return $this->gameDataProvider->getGameDisplayname($this->data['gq_type']);	
	}
	
	protected function getGameLogo()
	{
		$gamelogofolder = JURI::root().'components/com_gameserver/images/logos/';		
		return $gamelogofolder.$this->data['gq_type'].'.png';	
	}
	
	public function getHostname()
	{
		$hostname = $this->data['gq_hostname'];
		return ($hostname != false) ? $this->escapeSpecialCharacters($hostname) : '';
	}
	
	public function getConnectLink()
	{
		$ip = $this->getIpPort();
		
		if ($this->gameDataProvider->url != '')
		{
			return '<a href="'.$this->gameDataProvider->url.'" title="'.$ip.'" target="_blank">'.$ip.'</a>';
		}
		
		return $this->getCustomConnectLink();
	}
	
	public function getCustomConnectLink()
	{
		$ip = $this->getIpPort();
		
		return '<a href="hlsw://'.$ip.'" title="'.$ip.'">'.$ip.'</a>';
	}
	
	public function getIpPort()
	{
		return $this->data['orgaddress'].':'.trim($this->gameDataProvider->port) ;		
	}
	
	protected function getProtectedImage($attribute,$value)
	{
		if ($attribute == '' || $value == '') return '';
		
		$imagePath = JURI::root().'components/com_gameserver/images/key.png';
		
		if ($this->data[$attribute] == $value)
		{
			return '<img src="'.$imagePath.'" border="0" alt="Password Protected" />';		
		}
		
		return '';		
	}
	
	public function getMapImageHtml()
	{
		if (!$this->showMapImage())
		{
			return '';
		}
		
		$mapimage = $this->getMapImageUrlEx();	
		
		list($width, $height, $type, $attr) = getimagesize($mapimage);
		
		$factor = $width / $this->mapimagewidth;
		$mapimageheight = round($height / $factor);
		$this->mapimageheight = $mapimageheight; //test
		$this->mapimage = $mapimage; //test
		$mapimagewidth = $this->mapimagewidth;
		
		$height = $mapimageheight !=  '0' ? $mapimageheight.'px' : 'auto';
		
		//test
		if (strpos($this->mapimage,'unknown') === false
			&& $this->mapimage != $this->getGameLogo())
		{			
			return '<img src="'.$mapimage.'" border="0" style="height:'.$height.';width:'.$mapimagewidth.'px;min-width:'.$mapimagewidth.'px;" title="'.$this->getMapname().'" alt="'.$this->getMapname().'" class="gameserver_mapimage" />';
		}
		else {
			return '<img src="'.$mapimage.'" border="0" style="height:'.$height.';width:'.$mapimagewidth.'px;min-width:'.$mapimagewidth.'px;" title="'.$this->getMapname().'" alt="'.$this->getMapname().'" />';
		}		
		//test
	}
	
	public function getMapImageUrlEx()
	{
		$mapname = $this->data['gq_mapname'];		
		$mapname = $this->cleanMapname($mapname);	

		$mapImage = $this->searchLocalMapImage($mapname);
		
		if($mapImage != '') 
		{			
			return $mapImage;
		}
		else
		{
			$mapImage = '';
			
			if ($this->useGlobalMapGallery == 1)
			{
				$mapImage = $this->getMapFromOwnGallery($mapname);
			}				
			
			if ($mapImage == '')
			{
				$mapImage =  JURI::root().'components/com_gameserver/images/maps/'.JText::_('NOMAPIMAGEFOUND');			
			}
			
			return $mapImage;
		}
	}
	
	private function searchLocalMapImage($mapname)
	{		
		// new way... inside the images folder
		$path = JPATH_SITE.DS.'images'.DS.'gameserver'.DS.$this->getType().DS;
		$joomlaPath = JURI::root().'images/gameserver/'.$this->getType().'/';
		
		if ($this->isModAllowed())
		{
			if(file_exists($path.$this->getMod().DS.$mapname.'.jpg')) 
			{ 
				return $joomlaPath.$this->getMod().'/'.$mapname.'.jpg';	
			}
		}
		
		if (file_exists($path.$this->getMod().DS.$mapname.'.png')) 
		{ 
			return $joomlaPath.$this->getMod().'/'.$mapname.'.png'; 
		}	
		else if(file_exists($path.$mapname.'.jpg')) 
		{ 
			return $joomlaPath.$mapname.'.jpg'; 
		}	
		else if(file_exists($path.$mapname.'.png')) 
		{ 
			return $joomlaPath.$mapname.'.png'; 
		}	
		
		// old way... inside the component folder
		$path = JPATH_SITE.DS.'components'.DS.'com_gameserver'.DS.'images'.DS.'maps'.DS.$this->getType().DS;
		$joomlaPath = JURI::root().'components/com_gameserver/images/maps/'.$this->getType().'/';
		
		if ($this->isModAllowed())
		{
			if(file_exists($path.$this->getMod().DS.$mapname.'.jpg')) 
			{ 
				return $joomlaPath.$this->getMod().'/'.$mapname.'.jpg';	
			}
		}
		
		if (file_exists($path.$this->getMod().DS.$mapname.'.png')) 
		{ 
			return $joomlaPath.$this->getMod().'/'.$mapname.'.png'; 
		}	
		else if(file_exists($path.$mapname.'.jpg')) 
		{ 
			return $joomlaPath.$mapname.'.jpg'; 
		}	
		else if(file_exists($path.$mapname.'.png')) 
		{ 
			return $joomlaPath.$mapname.'.png'; 
		}	
		
		return '';
	}
	
	private function cleanMapname($map)
	{
		$map = str_replace(' ','_',$map);
		$map = str_replace(':','',$map); 
		$map = strtolower($map);		
		
		$map = str_replace('maps/','',$map);	
		$map = str_replace('levels/','',$map);	
		$map = str_replace('mp/','',$map);	
		
		return $map;		
	}
	
	private function getMapFromOwnGallery($map)
	{
		if ($this->isModAllowed())
		{
			$mapimage = 'http://www.larshildebrandt.de/images/phocagallery/'.$this->getType().'/'.$this->getMod().'/thumbs/phoca_thumb_l_'.trim($map);
			
			return $this->getMapImageWithExtension($mapimage, $map);		
		}		
		
		$mapimage = 'http://www.larshildebrandt.de/images/phocagallery/'.$this->getType().'/thumbs/phoca_thumb_l_'.trim($map);
		
		return $this->getMapImageWithExtension($mapimage, $map);
	}
	
	private function getMapImageWithExtension($mapimage, $map)
	{
		if (@fopen($mapimage.'.jpg',"r")) {
			$mapimage .= '.jpg';
			$map = $map .= '.jpg';
		} else {
			if (@fopen($mapimage.'.png',"r")) {
				$mapimage .= '.png';
				$map = $map .= '.png';
			} else {
				if (@fopen($mapimage.'.gif',"r")) {
					$mapimage .= '.gif';
					$map = $map .= '.gif';
				} else {
					$mapimage = '';
					$map = '';
				}
			}
		}
		
		if ($map != '')
		{
			$this->cacheMapImage($mapimage, $map);
		}
		
		return $mapimage;
	}
	
	private function cacheMapImage($mapimage, $map)
	{
		$path = JPATH_SITE.DS.'images'.DS.'gameserver';
		
		if (!file_exists($path)) 
		{ 
			mkdir($path, 0777, true); 
		}
		
		if (!file_exists($path.DS.$this->getType())) 
		{ 
			mkdir($path.DS.$this->getType(), 0777, true); 
		}
		
		if ($this->getMod() != '' && $this->isModAllowed() && !file_exists($path.DS.$this->getType().DS.$this->getMod())) 
		{ 
			mkdir($path.DS.$this->getType().DS.$this->getMod(), 0777, true); 
		}
		
		if ($this->getMod() != '' && $this->isModAllowed())
		{
			$fullimagepath = $path.DS.$this->getType().DS.$this->getMod().DS.$map;
		}
		else
		{
			$fullimagepath = $path.DS.$this->getType().DS.$map;
		}		
		
		file_put_contents($fullimagepath, fopen($mapimage, 'r'));
	}
	
	public function getMapname()
	{
		$mapname = $this->data['gq_mapname'];
		return ($mapname != false) ? $mapname : '';
	}	
	
	protected function getMod()
	{
		$mod = $this->data['gq_mod'];	
		return ($mod != false) ? $mod : '';
	}
	
	protected function getType()
	{
		$type = $this->data['gq_type'];	
		return ($type != false) ? $type : '';
	}
	
	public function getGametype()
	{
		$type = $this->data['gq_gametype'];	
		return ($type != false) ? $type : '';
	}
	
	public function ShortenText($text, $chars) 
	{
		if ($chars != -1)
		{
			if (strlen($text) > $chars)
			{		
				$text = substr($text,0,$chars);	
				$text = $text."...";
			}
		}
		return $text;	
	}	
	
	protected function getModulePlayerValues()
	{
		return array(
			new playerValues(JText::_('PLAYERNAME'),'gq_name'),
			new playerValues(JText::_('SCORE'),'gq_score',alignment::center)
			);
	}
	
	public function getModuleImageHtml()
	{
		return $this->getMapImageHtml();	
	}
	
	public function renderModuleContent($serverid,$maxplayerlistheight,$showplayers,$customurl = '')
	{
		$output = '<center>';
		
		$output .= '<a href="'.JURI::base().'index.php?option=com_gameserver&amp;view=gameserver&amp;serverid='.$serverid.'">'.$this->getHostname().'</a>';
		$output .= "<br />";
		
		$imagehtml = $this->getModuleImageHtml();
		if ($this->showMapImage == 1)
		{
			if ($customurl == '')
			{
				$output .= '<a href="'.JURI::base().'index.php?option=com_gameserver&amp;view=gameserver&amp;serverid='.$serverid.'">'.$imagehtml.'</a>';
			}
			else
			{
				$output .= '<a href="'.$customurl.'">'.$imagehtml.'</a>';
			}
			$output .= "<br />";
		}
		
		$output .= $this->getConnectLink();
		$output .= "<br />";
		if ($this->getMapname() != '')
		{
			$output .=  JText::_('MAP').': '.$this->getMapname();
			$output .=  "<br />";        	
		}	
		$output .=  $this->getFormatedPlayerCount();
		$output .=  "<br />"; 
		
		if ($showplayers == 1 && $this->getPlayersCount() > 0)
		{
			if ($maxplayerlistheight > -1)
			{
				$output .=  '<div style="max-height:'.$maxplayerlistheight.'px;overflow:auto;">';
			}
			
			$output .= $this->renderModulePlayerList();
			
			if ($maxplayerlistheight > -1)
			{
				$output .= '</div>';
			}		
		}
		$output .= '</center>';
		
		return $output;
	}
	
	public function renderModulePlayerList()
	{
		$output = '<table width="100%">';
		$output .= '<tr>';
		$output .= '<td colspan="2">';
		$output .= '<table width="100%" border="0" cellspacing="1" cellpadding="3" class="gameserver_table" >';				
		$output .= '<tr>';
		$output .= '<th></th>';
		
		$output .= $this->renderModulePlayerHeader();

		$output .= '</tr>';

		foreach ($this->data['players'] as $player)
		{														
			$output .= $this->renderModulePlayerValues($player);
		}

		$output .= '</table>';
		$output .= '</td>';			
		$output .= '</tr>';
		$output .= '</table>';
		
		return $output;
	}	
	
	protected function renderModulePlayerHeader()
	{
		foreach ($this->getModulePlayerValues() as $playerValue)
		{			
			$cssvalue = 'left';
			if ($playerValue->alignment == alignment::right) { $cssvalue = 'right'; }
			if ($playerValue->alignment == alignment::center) { $cssvalue = 'center'; }
			
			$output .= '<th style="text-align: '.$cssvalue.'">'.$playerValue->caption.'</th>';
		}
		
		return $output;	
	}
	
	protected function renderModulePlayerValues($player)
	{
		$output .=  '<tr>';
		$output .=  '<td align="center">';
		$output .=  '<img src="'.$this->getPlayerIcon($player).'" border="0" alt="'.$this->escapeSpecialCharacters($player['gq_name']).'" />';
		$output .=  '</td>';
		
		foreach ($this->getModulePlayerValues() as $playerValue)
		{
			$cssvalue = 'left';
			if ($playerValue->alignment == alignment::right) { $cssvalue = 'right'; }
			if ($playerValue->alignment == alignment::center) { $cssvalue = 'center'; }
			
			$output .=   '<td style="text-align: '.$cssvalue.'">';
			$output .=   $this->getPlayerValue($player,$this->escapeSpecialCharacters($playerValue->attribute));
			$output .=   '</td>';
		}
		
		$output .=   '</tr>';
		
		return $output;
	}
	
	protected function isModAllowed()
	{
		return false;	
	}
	
	private function escapeSpecialCharacters($text)
	{
		$chars["\""] = "&quot;";
		$chars["&"] = "&amp;";
		$chars["'"] = "&#39;";
		$chars["="] = "&#61;";
		$chars["<"] = "&#60;";
		$chars[">"] = "&#62;";
		
		$count = 0;
		foreach($chars as $key => $value)
		{			
			if (strstr ( $text , $key)) 
			{
				$text = str_replace ( $key, $value, $text);
			}
		}	
		
		return $text;	
	}	
}