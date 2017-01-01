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

error_reporting(E_ERROR | E_WARNING | E_PARSE);

class ts3  
{	
	private $ip;
	private $port;
	private $qport;
	private $server;
	
	private $connected;
	public $errorMessage;
	
	function ts3()
	{
		$connected = true;	
	}
	
	function connect($host, $port, $queryport, $timeout = 2, $user = '', $pass = '') 
	{		
		$this->ip = $host;
		$this->port = $port;
		$this->qport = $queryport;
		$this->server = @fsockopen($host, $queryport, $errnum, $errstr, $timeout);
		
		if(!$this->server) 
		{
			$this->errorMessage = 'Connection failed';
			return false;
		}
		elseif(!$this->isTeamspeakServer()) 
		{
			$this->errorMessage = 'No Teamspeak 3 service found';
			return false;
		}
		else 
		{
			if ($user != '' && $pass != '')
			{
				if (!$this->executeCommand('login ' . $user . ' ' . $pass))
				{
					$this->errorMessage = 'serverlogin as Lars failed';
					return false;
				}
			}
			
			$this->connected = true;
			return true;
		}
	}
	
	function getChannelList()
	{
		$channels;
		
		$channelList = $this->executeCommand("channellist -topic -flags -voice -limits -icon");		
		$channelInfo = explode('|', $channelList);
		
		$i = 0;
		foreach ($channelInfo as $channel) 
		{				
			$values = explode(' ', $channel);
			foreach($values as $attribute)
			{
				$channelAttributeValue = explode('=',$attribute,2);
				$channels[$i][$channelAttributeValue[0]] = $this->convertText($channelAttributeValue[1]);
			}
			
			//if ($channels[$i]['channel_icon_id'] != '0')
			//{
			//	$icon = $this->downloadIcon($channels[$i]['channel_icon_id']);
			//}
			
			$i++;			
		}
		
		return $channels;
	}
	
	function downloadIcon($iconid)
	{
		return $this->executeCommand("ftinitdownload clientftfid=1 name=\/icon_$iconid clientftfid=0 cid=0 cpw=0 seekpos=0");		
	}
	
	function getClientList()
	{
		$players;
		
		$playerList = $this->executeCommand("clientlist -uid -away -voice -groups");
		$playerInfo = explode('|', $playerList);
		
		$i = 0;
		foreach ($playerInfo as $player) 
		{						
			$playerValues = explode(' ', $player);
			if(strpos($playerValues[4], 'client_type=0') !== false)
			{
				foreach($playerValues as $attribute)
				{
					$playerAttributeValue = explode('=',$attribute,2);
					$players[$i][$playerAttributeValue[0]] = $this->convertText($playerAttributeValue[1]);
				}
				$i++;	
			}						
		}
		
		return $players;
	}
	
	function getData()
	{
		if (!$this->server)
		{
			$data['gq_online'] = 0;
			return $data;	
		}
		
		$this->executeCommand("use port=".$this->port);
		$serverinfo = $this->executeCommand("serverinfo");		
		$serverinfos = explode(' ', $serverinfo);
		
		$data['gq_online'] = 1;
		$data['gq_prot'] = 'ts3';
		$data['gq_type'] = 'ts3';
		$data['gq_address'] = $this->ip;;
		$data['gq_port'] = $this->port;
		$data['gq_mapname'] = '';
		$data['gq_hostname'] = $this->convertText(str_replace('virtualserver_name=', '', $serverinfos[1]));
		$data['gq_maxplayers'] = str_replace('virtualserver_maxclients=', '', $serverinfos[5]);
		$data['gq_numplayers'] = (str_replace('virtualserver_clientsonline=', '', $serverinfos[7]))-(str_replace('virtualserver_queryclientsonline=', '', $serverinfos[40])+1);
		
		$data['channels'] = $this->getChannelList();
		$data['players'] = $this->getClientList();		
		
		foreach ($serverinfos as $info)
		{
			$pair = explode('=',$info,2);	

			if ($pair[0] != 'virtualserver_unique_identifier'
				&& count($pair) == 2
				&& $pair[0] != 'virtualserver_password'
				&& $pair[0] != 'virtualserver_flag_password'
				&& $pair[0] != 'virtualserver_machine_id'
				&& $pair[0] != 'id'
				&& $pair[0] != 'msg')
			{				
				$data[$pair[0]] = $this->convertText($pair[1]);			
			}
		} 
		
		return $data;	
	}	

	private function isTeamspeakServer() 
	{
		if(strpos(fgets($this->server), 'TS3') !== false) 
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	private function executeCommand($command) {
		$data = '';
		fputs($this->server, $command."\n");
		do 
		{
			$data .= fgets($this->server);
		} while(strpos($data, 'msg=') === false);
		
		if(strpos($data, 'error id=0') === false) 
		{
			return false;
		}
		else
		{
			$value = explode('error id=0 msg=ok',$data);
			
			return trim($value[0],"\r");
		}
	}
	
	private function executeWithoutFetch($command) {
		@fputs($this->server, $command."\n");
		if(strpos(@fgets($this->server), 'id=0') !== false) {
			return true;
		}else{
			return false;
		}
	}
	
	public function logout() 
	{
		if(!$this->connected) { return false; }
		$bool = $this->executeWithoutFetch("logout");
		if($bool)
		{
			$this->connected = false;
		}
		else
		{
			$this->connected = false;
		}
	}
	
	public function quit() 
	{
		@fclose($this->server);
	}
	
	private function convertText($text) 
	{
		$find = array('\\\\', 	"\/", 		"\s", 		"\p", 		"\a", 	"\b", 	"\f", 		"\n", 		"\r", 	"\t", 	"\v");
		$rplc = array(chr(92),	chr(47),	chr(32),	chr(124),	chr(7),	chr(8),	chr(12),	chr(10),	chr(3),	chr(9),	chr(11));
		
		return str_replace($find, $rplc, $text);
	}
}

?>