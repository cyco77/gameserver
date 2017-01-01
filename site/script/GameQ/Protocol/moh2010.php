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

/**
 * This file is part of GameQ.
 *
 * GameQ is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * GameQ is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * $Id: moh2010.php,v 1.2 2010/06/02 23:03:26 evilpie Exp $  
 */
 
require_once GAMEQ_BASE . 'Protocol.php';


/**
 * Battlefield Bad Company 2 Protocol
 *
 * See <http://blogs.battlefield.ea.com/battlefield_bad_company/archive/2010/02/05/remote-administration-interface-for-bfbc2-pc.aspx> for more
 * information.
 *
 * @author         Tom Schuster <evilpies@users.sf.net>
 * @version        $Revision: 1.2 $
 */
class GameQ_Protocol_moh2010 extends GameQ_Protocol
{
    public function status()
    {	
		$this->p->read(8); /* skip header */
		$words = $this->decodeWords();
		
		if (!isset ($words[0]) || $words[0] != 'OK')
		{
			throw new GameQ_ParsingException ($this->p);
		}
		
		$this->r->add ('hostname', $words[1]);
		$this->r->add ('numplayers', $words[2]);
		$this->r->add ('maxplayers', $words[3]);
		$this->r->add ('gametype', $words[4]);
		$this->r->add ('map', $words[5]);
		
    }
    
    public function version()
    {
		$this->p->read(8);	
		$words = $this->decodeWords();
		
		/* version info isnt that important, se we don't throw */
		if(isset($words[0]) && $words[0] == 'OK') 		
		{
			$this->r->add('version', $words[2]);
		}
	}
	
	public function players()
	{
		$this->p->read(8);		
		$words = $this->decodeWords();
		
		if(!isset($words[0]) && $words[0] != 'OK')
			return;
		
		$num_tags = $words[1];
		$position = 2;
		$tags = array();
		
		for (; $position < $num_tags + 2 ; $position++)
		{
			$tags[] = $words[$position];
		}
		
		$num_players = $words[$position];
		$position++;
		$start_position = $position;		
		
		for (; $position < $num_players * $num_tags + $start_position; 
			$position += $num_tags)
		{
			for ($a = $position, $b = 0; $a < $position + $num_tags; 
				$a++, $b++)
			{
				$this->r->addPlayer($tags[$b], $words[$a]);
			}
		}
	}
	
	private function decodeWords ()
	{
		$num_words = $this->p->readInt32 ();
		$result = array ();
		
		for ($i = 0; $i < $num_words; $i++)
		{
			$len = $this->p->readInt32 ();
			$result[] = $this->p->read ($len);		
			$this->p->read (1); /* 0x00 string ending */
		}
		
		return $result;
	}

}
?>
