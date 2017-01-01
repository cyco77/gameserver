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


require("GbxRemote.inc.php");
require("tmfcolorparser.inc.php");
require("tmxinfofetcher.inc.php");

class TmnfQuery
{	
	public function getAdditionalData($ip, $port, $queryport, $user, $pass)
	{
		$client = new IXR_Client_Gbx();
		
		$server['gq_prot'] = 'tmnf';
		$server['gq_type'] = 'tmnf';
		
		if (!$client->InitWithIp($ip,$port)) {
			$str = $client->getErrorMessage();
			$server['gq_online'] = 0;
			return $server;
		}
		else
		{
			$server['gq_online'] = 1;
		}
		if ($user != '' & $pass != '')
		{
			if (!$client->query('Authenticate', $user, $pass)) {
				$str = 'login failed !';
				$server['errorstr'] = $str;
				$server['error'] = 1;
				return $server;
			}	
		}
		if($client->query('GetStatus')) {
			$Status = $client->getResponse();
			$server['status'] = $Status;
		}
		if($client->query('GetSystemInfo')) {
			$SystemInfo = $client->getResponse();
			$ServerLogin = $SystemInfo['ServerLogin'];
			$server['serverlogin'] = $ServerLogin; 
			$server['gq_address'] = $SystemInfo["PublishedIp"];
			$server['gq_port'] = $port;
		}
		if($client->query('GetCurrentChallengeInfo')) {
			$CurrentChallengeInfo = $client->getResponse();
			$server['gq_mapname'] = $CurrentChallengeInfo['Name'];
			$server['curchallenge'] = $CurrentChallengeInfo;
			$server['environment'] = $CurrentChallengeInfo['Environnement'];
			
			$infoFetcher = new TMXInfoFetcher('TMNF', $server['curchallenge']['UId'],false);
			if (isset($infoFetcher->imageurl))
			{
				$server['imageurl'] = $infoFetcher->imageurl;
			}
			else
			{
				$server['imageurl'] = '';
			}
		}		
		if($client->query('GetServerOptions')) {
			$ServerOptions = $client->getResponse();
			$server['gq_hostname'] = $ServerOptions['Name'];
			$server['gq_maxplayers'] = $ServerOptions['CurrentMaxPlayers'];
			$server['gq_password'] = 0;
			switch($Status["Code"]){
				case 4:
					$serverstatus = 'running';
					$serverstatus .= ($ServerOptions['PasswordForSpectator'] == '') ? ', public' : ', private';
					break;
				case 3:
					$serverstatus = 'loading new map';
					$server['curchallenge']['Name'] = 'loading...';
					break;
				default:
					$serverstatus = 'unknown (Code '.$Status["Code"].')';
					$server['curchallenge']['Name'] = '';
					break;
			}
			
			$server['serveroptions'] = $ServerOptions;
			$server['serverstatus'] = $serverstatus;
		}   
		if($client->query('GetPlayerList',500,0)) {
			$tmnfplayers = $client->getResponse();
			
			$pl=0;   
			foreach ($tmnfplayers as $tmnfplayer) {
				$pl++;
			}
			
			$players = array();
			$i=0;
			foreach ($tmnfplayers as $tmnfplayer) {
				$players[$i]['gq_name'] = $tmnfplayer['NickName'];
				$players[$i]['LadderRanking'] = $tmnfplayer['LadderRanking'];
				$players[$i]['gq_score'] = $tmnfplayer[''];
				$i++;
			}	
			
			$server['players'] = $players;
			$server['gq_numplayers'] = $pl;
		}	
		
		//Taking game info
		if($client->query('GetCurrentGameInfo')) {
			$GetCurrentGameInfo=$client->getResponse();
			$GameMode=$GetCurrentGameInfo["GameMode"];
			$server['gameinfo'] = $GetCurrentGameInfo;
			//Formatitng Game Modus
			switch($GameMode){
				case 0:
					$GameMode="Rounds";
					Break;
				case 1:
					$GameMode="Time Attack";
					Break;
				case 2:
					$GameMode="Team";
					Break;
				case 3:
					$GameMode="Laps";
					Break;
				case 4:
					$GameMode="Stunts";
					Break;
				case 5:
					$GameMode="Cup";
					Break;
			}
			$server['gq_gametype'] = $GameMode;
		}
		
		$client->Terminate();
		
		return $server;	
	}
}


?>