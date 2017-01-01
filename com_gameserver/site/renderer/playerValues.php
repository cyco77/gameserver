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

class alignment
{
	const left = 0;
	const right = 1;
	const center = 2;
	// etc.
}


class playerValues
{
	public $caption;
	public $attribute;
	public $alignment;
	public $hideMobile;
	
	function __construct($caption, $attribute, $alignment = alignment::left, $hideMobile = true) {
		$this->caption = $caption;
		$this->attribute = $attribute;
		$this->alignment = $alignment;
		$this->hideMobile = $hideMobile;
	}
}

?>