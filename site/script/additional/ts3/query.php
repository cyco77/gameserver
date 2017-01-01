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


class Ts3Query
{	
	public function getAdditionalData($ip, $port, $queryport, $user, $pass)
	{
		include_once('ts3.php');
		$ts3 = new ts3();
		$ts3->connect($ip,$port,$queryport,2, $user, $pass);
		
		$data = $ts3->getData();
		
		$ts3->logout();
		$ts3->quit();
		
		return $data;
	}
}
?>