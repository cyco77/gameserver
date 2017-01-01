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
// defined('_JEXEC') or die('Restricted access');

require_once __DIR__.'/libraries/autoload.php';

class ShootmaniaQuery
{	
	var $login;
	var $user;
	var $pass;
	
	public function getAdditionalData($login, $port, $queryport, $user, $pass)
	{		
		$this->login = $login;
		$this->user = $user;
		$this->pass = $pass;
		
		$maniaServers = new \Maniaplanet\WebServices\Servers($user, $pass);
		$maniaRankings = new \Maniaplanet\WebServices\Rankings\Storm($this->user, $this->pass);
		
		// return $maniaServers->getOnlinePlayers($login);
		// return $maniaRankings->getMultiplayerPlayer('jokair');	
		
		//$filter = array();
		//$filter['environment'] = 'storm';
		//$filter['playersMin'] = 5;
		
		//return $maniaServers->getFilteredList($filter);
		
		$server = $maniaServers->get($login);
		
		if ($server->isOnline == 0)
		{
			$result['gq_online'] = 0;
			return $result;				
		}
		
		$result['gq_prot'] = 'shootmania';
		$result['gq_type'] = 'shootmania';
		
		$result['gq_online'] = $server->isOnline;
		$result['gq_mapname'] = $server->mapsList[0];
		$result['gq_maxplayers'] = $server->maxPlayerCount;
		$result['gq_hostname'] = $server->serverName;
		$result['mapcycle'] = implode(', ', $server->mapsList);
		$result['isDedicated'] = $server->isDedicated;
		$result['description'] = $server->description;
		$result['isladder'] = $server->isLadder;
		$result['ladderlimitmin'] = $server->ladderLimitMin;
		$result['ladderlimitmin'] = $server->ladderLimitMin;
		$result['ladderlimitmax'] = $server->ladderLimitMax;
		$result['isprivate'] = $server->isPrivate;
		$result['version'] = $server->buildVersion;		
		$result['serverlogin'] = $login;		
		
		$serverplayers = $maniaServers->getOnlinePlayers($login);
		
		$players = array();
		$i=0;
		foreach ($serverplayers as $player) {
			$players[$i]['gq_name'] = $player->nickname;
			$players[$i]['path'] = $player->path;
			$players[$i]['id'] = $player->id;
			$players[$i]['idZone'] = $player->idZone;
			$players[$i]['login'] = $player->login;
			
			$splitPath = explode('|',$player->path);
			
			try
			{		
				$rankings = $maniaRankings->getMultiplayerPlayer($player->login);
			}
			catch (exception $e)
			{
				return $e;
			}
			
			// Ranking
			$players[$i]['worldranking'] = $rankings->ranks[0]->rank;
			
			$countryRanking = count($rankings->ranks) > 1 ? $rankings->ranks[1]->rank : '';
			$country = count($splitPath) > 1 ? $splitPath[1] : '';
			$players[$i]['country'] = $country;
			$players[$i]['countryranking'] = $countryRanking;
			$players[$i]['countrydisplay'] = $country . ': ' . $countryRanking;
			
			$regionRanking = count($rankings->ranks) > 2 ? $rankings->ranks[2]->rank : '';
			$region = count($splitPath) > 2 ? $splitPath[2] : '';
			$players[$i]['region'] = $region;
			$players[$i]['regionranking'] = $regionRanking;
			$players[$i]['regiondisplay'] = $region . ': ' . $regionRanking;
			
			$townRanking = count($rankings->ranks) > 3 ? $rankings->ranks[3]->rank : '';
			$town = count($splitPath) > 3 ? $splitPath[3] : '';
			$players[$i]['town'] = $town;
			$players[$i]['townranking'] = $townRanking;
			
			$players[$i]['towndisplay'] = $town != '' ? $town . ': ' . $townRanking : ' - ';
			
			$i++;
		}	
		
		$result['players'] = $players;		
		$result['gq_numplayers'] = count($players);
		
		return $result;	
	}
}


?>