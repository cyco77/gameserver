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

require_once('game.php');

class GameDataProvider
{
	public $supported;
	
	public $type;
	public $port;
	public $prot;
	public $port2;
	public $username;
	public $password;
	public $url;
	
	public $serverdata;
	
	function __construct()
	{      
		$this->supported = Array(
			"sevend2d"				  => new Game("gameq2",		 false, "sevend2d",			   "7 Days to Die",                         "source",    25000,	  25001,    true),			
			"alienswarm"			  => new Game("gameq",		 true,  "alienswarm",		   "Alien Swarm",                           "source",    27015),
			"aa"					  => new Game("gameq",		 true,  "aa",				   "America's Army",                        "gamespy2",  1716,    1717,     true),
			"aa3"					  => new Game("gameq",		 true,  "aa3",				   "America's Army 3",                      "",          8777,    39300,    true),
			"aa3_2"					  => new Game("gameq",		 true,  "aa3_2",			   "America's Army 3.2+",                   "source",    8777,    27020,    true),
			"aa4"					  => new Game("gameq",		 true,  "aa4",				   "America's Army 4",                      "source",    8777,    27020,    true),
			"armedassault"			  => new Game("gameq",		 true,  "armedassault",		   "Armed Assault",                         "gamespy2",  2302),
			"armedassault2"			  => new Game("gameq",		 true,  "armedassault2",	   "Armed Assault 2",                       "gamespy3",  2302),
			"armedassault3"			  => new Game("gameq2",		 true,  "armedassault3",	   "ArmA 3",								"gamespy3",  2302),
			"bf1942"				  => new Game("gameq",		 true,  "bf1942",			   "Battlefield 1942",                      "gamespy",   15567,   23000,    true),
			"bfvietnam"				  => new Game("gameq",		 true,  "bfvietnam",		   "Battlefield Vietnam",                   "gamespy2",  15567,   23000,    true),
			"bf2"					  => new Game("gameq",		 true,  "bf2",				   "Battlefield 2",                         "gamespy3",  16567,   29900,    true),
			"bf3"					  => new Game("gameq",		 true,  "bf3",				   "Battlefield 3",                         "",          25200,   48888,    true),	
			"bf4"					  => new Game("gameq",		 true,  "bf4",				   "Battlefield 4",                         "",          25200,   48888,    true),	
			"bf2142"				  => new Game("gameq",		 true,  "bf2142",			   "Battlefield 2142",                      "gamespy3",  17567,   29900,    true),
			"bfbc2"					  => new Game("gameq",		 true,  "bfbc2",			   "Battlefield Bad Company 2",             "",          19567,   48888,    true),	
			"bfh"					  => new Game("gameq",		 true,  "bfh",				   "Battlefield Hardline",                  "",          25200,   48888,    true),	
			"brink"					  => new Game("gameq",		 true,  "brink",			   "Brink",                                 "",          27016),				
			"chivalrymedievalwarfare" => new Game("gameq",		 true,  "chivalrymedievalwarfare", "Chivalry: Medieval Warfare",            "source",    27015),			
			"cod"				      => new Game("gameq",		 true,  "cod",				   "Call of Duty",                          "quake3",    28960),
			"coduo"				      => new Game("gameq",		 true,  "coduo",			   "Call of Duty - United Offensive",       "quake3",    28960),
			"cod2"				      => new Game("gameq",		 true,  "cod2",				   "Call of Duty 2",                        "quake3",    28960),
			"cod4"				      => new Game("gameq",		 true,  "cod4",				   "Call of Duty 4",                        "quake3",    28960),
			"cod5"				      => new Game("gameq",		 true,  "cod5",				   "Call of Duty 5: World at War",          "quake3",    28960),
			"codmw2"			      => new Game("gameq",		 true,  "codmw2",			   "Call of Duty Modern Warfare 2",         "source",    28960),
			"codmw3"			      => new Game("gameq",		 true,  "codmw3",			   "Call of Duty Modern Warfare 3",         "source",    27014),
			"cs"				      => new Game("gameq",		 true,  "cs",				   "Counter-Strike",                        "source",    27015),
			"czero"				      => new Game("gameq",		 true,  "czero",			   "Counter-Strike Condition Zero",         "source",    27015),
			"cssource"			      => new Game("gameq",		 true,  "cssource",			   "Counter-Strike: Source",                "source",    27015),
			"csgo"				      => new Game("gameq2",		 true,  "csgo",				   "Counter-Strike: Global Offensive",      "source",    27015),
			"contagion"				  => new Game("gameq",		 true,  "contagion",		   "Contagion",								"source",    27015),
			"crysis"			      => new Game("gameq",		 true,  "crysis",			   "Crysis",                                "gamespy3",  64087),
			"crysiswars"		      => new Game("gameq",		 true,  "crysiswars",		   "Crysis Wars",                           "gamespy3",  64087),
			"crysis2"			      => new Game("gameq",		 true,  "crysis2",			   "Crysis 2",                              "gamespy3",  64087),
			"dod"				      => new Game("gameq",		 true,  "dod",				   "Day of Defeat",                         "source",    27015),
			"dayzmod"				  => new Game("gameq2",		 true,  "dayzmod",			   "DayZ",				                    "",			 2302),			
			"dayz"					  => new Game("gameq2",		 true,  "dayz",				   "DayZ Standalone",	                    "source",	 27015),			
			"dodsource"			      => new Game("gameq",		 true,  "dodsource",		   "Day of Defeat: Source",                 "source",    27015),
			"dota2"				      => new Game("gameq",		 true,  "dota2",			   "D.O.T.A. 2",						    "source",    27015),
			"doom3"				      => new Game("gameq",		 true,  "doom3",			   "Doom 3",                                "",          27666),	
			"quakewars"			      => new Game("gameq",		 true,  "quakewars",		   "Enemy Territory: Quake Wars",           "",          27733),			
			"fear"				      => new Game("gameq",		 true,  "fear",				   "F.E.A.R.",                              "gamespy2",  27888),
			"ffow"				      => new Game("gameq",		 true,  "ffow",				   "Frontline: Fuel of War",                "",          5476,    5478,     true),
			"garrysmod"			      => new Game("gameq",		 true,  "garrysmod",		   "Garry's Mod",                           "source",    27015),
			"graw"				      => new Game("gameq",		 true,  "graw",				   "Ghost Recon: Advanced Warfighter",      "gamespy2",  15250),
			"graw2"				      => new Game("gameq",		 true,  "graw2",			   "Ghost Recon: Advanced Warfighter 2",    "gamespy2",  16250),
			"halflife"			      => new Game("gameq",		 true,  "halflife",			   "Half-Life",                             "source",    27015),
			"halflife2"			      => new Game("gameq",		 true,  "halflife2",		   "Half-Life 2",                           "source",    27015),
			"halo"				      => new Game("gameq",		 true,  "halo",				   "Halo",	                                "gamespy2",  2301),
			"hiddendangerous2"		  => new Game("gameq",		 true,  "hiddendangerous2",	   "Hidden & Dangerous 2",	                "gamespy2",  11001,	  11004,    true),
			"homefront"			      => new Game("gameq",		 true,  "homefront",		   "Homefront",                             "source",    27015),
			"insurgency"		      => new Game("gameq",		 true,  "insurgency",		   "Insurgency",                            "source",    27015),			   
			"killingfloor"		      => new Game("gameq",		 true,  "killingfloor",		   "Killing Floor",                         "unreal2",   7708,    7709,     true),
			"killingfloor2"		      => new Game("gameq",		 true,  "killingfloor2",	   "Killing Floor 2",                       "source",	 7777,    7779,     true),
			"left4dead"			      => new Game("gameq",		 true,  "left4dead",		   "Left 4 Dead",                           "source",    27015),
			"left4dead2"		      => new Game("gameq",		 true,  "left4dead2",		   "Left 4 Dead 2",                         "source",    27015),			
			"lifeisfeudal"		      => new Game("gameq",		 false, "lifeisfeudal",		   "Life is Feudal",                        "source",    28000,	  28002,	true),			
			"minecraft"			      => new Game("gameq",		 true,  "minecraft",		   "Minecraft",                             "gamespy3",  25565),
			"mohallied"			      => new Game("gameq",		 true,  "mohallied",		   "Medal of Honor: Allied Assault",        "gamespy",   12203,   12300,    true),
			"mohbreak"			      => new Game("gameq",		 true,  "mohbreak",			   "Medal of Honor: Breakthrough",          "gamespy",   12203,   12300,    true),
			"mohpacific"		      => new Game("gameq",		 true,  "mohpacific",		   "Medal of Honor: Pacific Assault",       "gamespy",   12203,   13200,    true),
			"mohspear"			      => new Game("gameq",		 true,  "mohspear",			   "Medal of Honor: Spearhead",             "gamespy",   12203,   12300,    true),
			"moh2010"			      => new Game("gameq",		 true,  "moh2010",			   "Medal of Honor",                        "source",    19567,   48888,    true),
			"mohw"				      => new Game("gameq",		 true,  "mohw",				   "Medal of Honor: Warfighter",            "",          25200,   48888,    true),	
			"messiah"			      => new Game("gameq",		 true,  "messiah",			   "Dark Messiah of Might and Magic",       "source",    27015),
			"mtasa"				      => new Game("gameq",		 true,  "mtasa",			   "Multi Theft Auto",					    "ase",	     22003,'',true),			
			"mumble"				  => new Game("gameq2",		 true,	"mumble",			   "Mumble",								"",			 27800,'',true),						
			"ns"				      => new Game("gameq",		 true,  "ns",				   "Natural Selection",                     "source",    27015),
			"ns2"				      => new Game("gameq",		 true,  "ns2",				   "Natural Selection 2",                   "source",    27016),
			
			"nmrih"				      => new Game("gameq",		 true,  "nmrih",			   "No More Roon in Hell",                  "source",    27015),
			
			"openttd"			      => new Game("gameq",		 false, "openttd",			   "OpenTTD",                               "openttd",   3979),
			"quake3"			      => new Game("gameq",		 true,  "quake3",			   "Quake 3: Arena",                        "",          27960),
			"quake4"			      => new Game("gameq",		 true,  "quake4",			   "Quake 4",                               "doom3",     28004),				
			"redorchestra"		      => new Game("gameq",		 true,  "redorchestra",		   "Red Orchestra: Ostfront 41-45",         "gamespy",   7758,    7759,     true),
			"redorchestra2"		      => new Game("gameq",		 true,  "redorchestra2",	   "Red Orchestra 2",                       "source",    7777,    27015,    true),				
			"rfactor"			      => new Game("gameq",		 true,  "rfactor",			   "rFactor",                               "",          34397,   34297,    true),	
			"rtcw"				      => new Game("gameq",		 true,  "rtcw",				   "Return to Castle Wolfenstein",          "quake3",    27960),
			"rust"					  => new Game("gameq",		 true,  "rust",			       "Rust",									"source",    27015,	  27016,	true),
			"samp"				      => new Game("gameq",		 false, "samp",				   "San Andreas: Multiplayer",              "",          7777),
			"sauerbraten"		      => new Game("gameq",		 false, "sauerbraten",		   "Sauerbraten Engine",				    "",          28786),
			"shootmania"		      => new Game("shootmania",  true,  "shootmania",		   "Shootmania Storm",					    "",          -1,      "",       false,true,true),
			"sof2"				      => new Game("gameq",		 true,  "sof2",				   "Soldier of Fortune 2: Double Helix",    "quake3",    20100),			
			"se"				      => new Game("gameq",		 true,  "se",				   "Space Engineers",						"source",    27016),			
			"starbound"			      => new Game("gameq",		 false, "starbound",		   "Starbound",								"source",    27015),
			"jediacademy"		      => new Game("gameq",		 true,  "jediacademy",		   "Star Wars Jedi Knight: Jedi Academy",   "quake3",    29070),
			"jedioutcast"		      => new Game("gameq",		 true,  "jedioutcast",		   "Star Wars Jedi Knight II: Jedi Outcast","quake3",    29070),
			"teeworlds"			      => new Game("gameq",		 false, "teeworlds",		   "Teeworlds",                             "",          8300),
			"tmnf"				      => new Game("tmnf",		 true,  "tmnf",				   "Trackmania Nations Forever",            "",          5100,    "",       false,true,true),
			"tm2"				      => new Game("tm2",		 true,  "tm2",				   "Trackmania 2 Canyon",					"",          -1,      "",       false,true,true),
			"tf2"				      => new Game("gameq",		 true,  "tf2",				   "Team Fortress 2",                       "source",    27015),
			"ts2"				      => new Game("gameq",		 true,  "ts2",				   "Teamspeak 2",                           "",          8767,    "",       true),
			"ts3"				      => new Game("ts3",		 true,  "ts3",				   "Teamspeak 3",                           "",          9987,    "11010",  true,true,true),
			"ventrilo"			      => new Game("gameq",		 true,  "ventrilo",			   "Ventrilo",                              "",          3784),
			"ut"				      => new Game("gameq",		 true,  "ut",				   "Unreal Tournament",                     "unreal2",   7777,    7778,     true),
			"ut2003"			      => new Game("gameq",		 true,  "ut2003",			   "Unreal Tournament 2003",                "unreal2",   7757,    7778,     true),
			"ut2004"			      => new Game("gameq",		 true,  "ut2004",			   "Unreal Tournament 2004",                "unreal2",   7777,    7778,     true),
			"ut3"				      => new Game("gameq",		 true,  "ut3",				   "Unreal Tournament 3",                   "ut3",       7777,    7500,     true),
			"urbanterror"		      => new Game("gameq",		 true,  "urbanterror",		   "Urban Terror",                          "quake3",    27960),
			"warsow"				  => new Game("gameq",		 true,  "warsow",			   "Warsow",								"",			 44400),
			"et"				      => new Game("gameq",		 true,  "et",				   "Wolfenstein: Enemy Territory",          "quake3",    27960),			
			"zps"				      => new Game("gameq2",		 true,  "zps",				   "Zombie Panic Source",					"source",    27015)						
			);		
	}
	
	public function getGame($type)
	{
		if (!array_key_exists($type, $this->supported)) {
			return null;
		}
		else {
			return $this->supported[$type];	
		}
	}
	
	public function getGameDisplayname($value) {
		if (!array_key_exists($value, $this->supported)) {
			return 'Unsupported';
		}
		else {
			$game = $this->supported[$value];				
			return $game->name;
		}
	}
	
	public function getGameProt($value)
	{	
		$game = $this->getGame($type);
		
		if ($game == null) {
			return 'Unsupported';
		}
		else {
			return $game->prot;
		}
	}	
	
	public function loadServerData($gameserver,$maxcachedtime)
	{
		$this->type = $gameserver->type;
		$this->ip = $gameserver->ip;
		$this->port = $gameserver->port;
		$this->prot = isset($gameserver->prot) ? $gameserver->prot : "";
		$this->port2 = $gameserver->port2;
		$this->username = $gameserver->user;
		$this->password = $gameserver->pass;
		$this->url = $gameserver->url;
		
		if ($this->isCacheExpired($maxcachedtime,$gameserver->cachedatetime))
		{
			$this->serverdata = $this->queryServer();
			
			return true;
		}
		else
		{			
			$this->serverdata = unserialize(base64_decode($gameserver->cachedserverdata));
			
			return false;
		}	
	}	
	
	public function loadServerDataBySingleValues($type, $ip, $port, $port2, $user, $pass, $url)
	{
		$this->type = $type;
		$this->ip = $ip;
		$this->port = $port;
		$this->prot = '';
		$this->port2 = $port2;
		$this->username = $user;
		$this->password = $pass;
		$this->url = $url;
		
		$this->serverdata = $this->queryServer();
	}
	
	public function isCacheExpired($maxcachedtime,$lastcachedatetime)
	{		
		$cacheDateTime = strtotime($lastcachedatetime);
		
		$now = time();
		
		$difference = $now - $cacheDateTime;

		return $maxcachedtime == -1 || $difference > $maxcachedtime;
	}
	
	private function queryServer()
	{
		if ($this->ip == '')
		{
			return '';	
		}
		
		$game = $this->getGame($this->type);
		
		$ip = trim($this->ip);
		$port = trim($this->port);
		$port2 = trim($this->port2);
		
		switch ($game->engine) {
			case 'tmnf':
				require_once(JPATH_SITE.DS.'components'.DS.'com_gameserver'.DS.'script'.DS.'additional'.DS.'tmnf'.DS.'query.php');
			
				$query = new TmnfQuery();
				$data = $query->getAdditionalData($ip, $port, $port2, $this->username, $this->password);
				$data["orgaddress"] = $ip;
				return $data;	
			case 'ts3':
				require_once(JPATH_SITE.DS.'components'.DS.'com_gameserver'.DS.'script'.DS.'additional'.DS.'ts3'.DS.'query.php');
			
				$query = new Ts3Query();
				$data = $query->getAdditionalData($ip, $port, $port2, $this->username, $this->password);
				$data["orgaddress"] = $ip;
				return $data;
			case 'tm2':
				require_once(JPATH_SITE.DS.'components'.DS.'com_gameserver'.DS.'script'.DS.'additional'.DS.'tm2'.DS.'query.php');
			
				$query = new Tm2Query();
				$data = $query->getAdditionalData($ip, $port, $port2, $this->username, $this->password);
				return $data;	
			case 'shootmania':
				require_once(JPATH_SITE.DS.'components'.DS.'com_gameserver'.DS.'script'.DS.'additional'.DS.'shootmania'.DS.'query.php');			
			
				$query = new ShootmaniaQuery();
				$data = $query->getAdditionalData($ip, $port, $port2, $this->username, $this->password);
				return $data;
			case 'gameq2':
				require_once(JPATH_SITE.DS.'components'.DS.'com_gameserver'.DS.'script'.DS.'gameq2'.DS.'GameQ.php');
			
				$servers = array(
					array(
							'id' => 'server',
							'type' => trim($this->type), 
							'host' => trim($ip) .':'. (trim($port2) != '' ? trim($port2) : trim($port))
							)
					);

				$gq = new GameQ();
				$gq->addServers($servers);

				// You can optionally specify some settings
				$gq->setOption('timeout', 4); // Seconds

				// You can optionally specify some output filters,
				// these will be applied to the results obtained.
				$gq->setFilter('normalise');

				// Send requests, and parse the data
				$results = $gq->requestData(); 		
				
				foreach ($results as $id => $data) 
				{
					$data["orgaddress"] = $ip;
					$data["gq_prot"] = $game->prot;

					return $data;
				}	
				
			default:
				require_once(JPATH_SITE.DS.'components'.DS.'com_gameserver'.DS.'script'.DS.'GameQ'.DS.'GameQ.php');
				
				$gq = new GameQ();
				$gq->setOption('queryport', $port2);
				$gq->setOption('timeout', 1000);
				$gq->setFilter('normalise');
				$gq->setFilter('sortplayers', array('gq_score', false)); 			
				
				$server = array(trim($this->type), trim($ip), trim($port2) != '' ? trim($port2) : trim($port));			
				
				$gq->addServer('server',$server);	
				
				$results = $gq->requestData();
				
				foreach ($results as $id => $data) 
				{
					$data["orgaddress"] = $ip;

					return $data;
				}
		}
	}	
	
	public function getEncodedServerdata()
	{
		return base64_encode(serialize($this->serverdata));
	}
	
	public function isOnline()
	{
		return $this->serverdata['gq_online'];
	}
	
	public function getProt()
	{
		if (array_key_exists('gq_prot',$this->serverdata))
		{
			return $this->serverdata['gq_prot'];	
		}
		
		return '';
	}
}

?>