<?php
/**
 * Maniaplanet Web Services SDK for PHP
 *
 * @see		    http://code.google.com/p/maniaplanet-ws-sdk/
 * @copyright   Copyright (c) 2009-2011 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @author      $Author: baptiste33@gmail.com $:
 * @version     $Revision: 150 $:
 * @date        $Date: 2012-08-20 16:41:44 +0200 (lun., 20 août 2012) $:
 */

namespace Maniaplanet\WebServices;

class Players extends HTTPClient
{

	/**
	 * @param string $login Login of a Maniaplanet account
	 * @return object
	 * @throws \Maniaplanet\WebServices\Exception
	 */
	function get($login)
	{
		if(!$login)
		{
			throw new Exception('Invalid login');
		}
		return $this->execute('GET', '/players/%s/', array($login));
	}
}

?>