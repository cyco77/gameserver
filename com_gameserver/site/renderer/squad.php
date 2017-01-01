<?php

class squad
{
	public $squadNo;
	public $playerList;
	
	public function __construct($squadNo,$playerList)
	{
		$this->squadNo = $squadNo;
		$this->playerList = $playerList;	
	}	
}

?>