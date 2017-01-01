<?php
/**
 * Maniaplanet Web Services SDK for PHP
 *
 * @see		    http://code.google.com/p/maniaplanet-ws-sdk/
 * @copyright   Copyright (c) 2009-2011 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @author      $Author: maximeraoust $:
 * @version     $Revision: 99 $:
 * @date        $Date: 2012-01-19 11:52:50 +0100 (jeu., 19 janv. 2012) $:
 */

namespace Maniaplanet\WebServices;

class Transaction
{

	public $id;
	public $creatorLogin;
	public $creatorPassword;
	public $creatorSecurityKey;
	public $fromLogin;
	public $toLogin;
	public $message;
	public $cost;

}

?>