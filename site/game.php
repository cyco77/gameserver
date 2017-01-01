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

class Game
{
	public $type;
	public $name;
	public $port;
	public $prot;
	public $port2;	
	public $renderPlayerList;
	public $engine;
	public $isPort2Useable;
	public $isUsernameUseable;
	public $isPasswordUseable;
	
	public function __construct(
		$engine,
		$renderPlayerList,
		$type,
		$name,
		$prot,
		$port,
		$port2='',
		$isPort2Useable=false,
		$isUsernameUseable=false,
		$isPasswordUseable=false)
	{
		$this->engine = $engine;
		$this->type = $type;
		$this->name = $name;
		$this->prot = $prot;
		$this->port = $port;
		$this->port2 = $port2;
		$this->renderPlayerList = $renderPlayerList;	
		$this->isPort2Useable = $isPort2Useable;
		$this->isUsernameUseable = $isUsernameUseable;
		$this->isPasswordUseable = $isPasswordUseable;					
	}
}

?>
