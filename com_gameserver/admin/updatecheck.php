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

jimport('joomla.filesystem.folder');

function getVersion() 
{ 
	$folder = JPATH_ADMINISTRATOR .DS. 'components'.DS.'com_gameserver'; 
	if (JFolder::exists($folder)) 
	{ 
		$xmlFilesInDir = JFolder::files($folder, '.xml$'); 
	} else 
	{	
		$folder = JPATH_SITE .DS. 'components'.DS.'com_gameserver'; 
		if (JFolder::exists($folder)) 
		{ 
			$xmlFilesInDir = JFolder::files($folder, '.xml$'); 
		} else 
		{ 
			$xmlFilesInDir = null; 
		} 
	} 
	
	$xml_items = ''; 
	if (count($xmlFilesInDir)) 
	{ 
		foreach ($xmlFilesInDir as $xmlfile) 
		{ 
			if ($data = JApplicationHelper::parseXMLInstallFile($folder.DS.$xmlfile)) 
			{ 
				foreach($data as $key => $value) 
				{ 
					$xml_items[$key] = $value; 
				} 
			} 
		} 
	} 
	if (isset($xml_items['version']) && $xml_items['version'] != '' ) 
	{ 
		return $xml_items['version']; 
	} else 
	{ 
		return ''; 
	} 
}

$version = getVersion();

$critical = false;
$update = false;

$url = "http://joomla.larshildebrandt.de/_update/com_gameserver3_update.csv";
$fp = @fopen ($url, 'r') or print ('UPDATE SERVER OFFLINE');
$data = fgetcsv ($fp);   
fclose ($fp); 

if ($data[0] != $version && $data[1] == 1) { $critical = true; }
if ($data[0] != $version) { $update = true; }

if ($critical) { 
	print '<p style="color: #ff0000; font-weight: bold;" align="center">There is a critical update available!</p>';
	print '<p align="center">Your version: '.$version.'</p>';
	print '<p align="center">Available version: '.$data[0].'</p>';
	print '<p align="center">You can get it at <a href="'.$data[2].'">'.$data[2].'</a></p>';
}
else if ($update)
	{
		print '<p style="color: #008000;" align="center">There is an update available</p>';
		print '<p align="center">Your version: '.$version.'</p>';
		print '<p align="center">Available version: '.$data[0].'</p>';
		print '<p align="center">You can get it at <a href="'.$data[2].'">'.$data[2].'</a></p>';
	}
?>