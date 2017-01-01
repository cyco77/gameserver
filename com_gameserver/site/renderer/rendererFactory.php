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

class rendererFactory  {

	public static function factory($type,$prot)
	{
		$classname = 'defaultRenderer';
		
		$file = JPATH_SITE.DS.'components'.DS.'com_gameserver'.DS.'renderer'.DS.$type.'Renderer.php';
		if (file_exists($file)) { 
			include_once JPATH_SITE.DS.'components'.DS.'com_gameserver'.DS.'renderer'.DS.$type.'Renderer.php';
			$classname = $type.'Renderer';	
		} 
		else
		{
			$file = JPATH_SITE.DS.'components'.DS.'com_gameserver'.DS.'renderer'.DS.$prot.'Renderer.php';
			if (file_exists($file)) { 
				include_once JPATH_SITE.DS.'components'.DS.'com_gameserver'.DS.'renderer'.DS.$prot.'Renderer.php';
				$classname = $prot.'Renderer';	
			} 
			else
			{
				include_once JPATH_SITE.DS.'components'.DS.'com_gameserver'.DS.'renderer'.DS.'defaultRenderer.php';
			}
		}
		
		return new $classname;
	}
}

?>