<?php
/**
 * Maniaplanet Web Services SDK for PHP
 *
 * @see		    http://code.google.com/p/maniaplanet-ws-sdk/
 * @copyright   Copyright (c) 2009-2011 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @author      $Author: baptiste33@gmail.com $:
 * @version     $Revision: 113 $:
 * @date        $Date: 2012-07-11 14:56:37 +0200 (mer., 11 juil. 2012) $:
 */

namespace Maniaplanet\WebServices;

/**
 * Exception thrown by the services when something goes wrong
 */
class Exception extends \Exception
{

	protected $HTTPStatusCode;
	protected $HTTPStatusMessage;

	function __construct($message='', $code=0, $HTTPStatusCode=0,
		$HTTPStatusMessage='')
	{
		parent::__construct($message ? : $HTTPStatusMessage, $code ? : $HTTPStatusCode);

		$this->HTTPStatusCode = $HTTPStatusCode;
		$this->HTTPStatusMessage = $HTTPStatusMessage;
	}

	/**
	 * The HTTP status code returned in case of an error, eg. 404
	 * @return int
	 */
	function getHTTPStatusCode()
	{
		return $this->HTTPStatusCode;
	}

	/**
	 * The HTTP status message returned in case of an error, eg. "Not Found"
	 * @return string
	 */
	function getHTTPStatusMessage()
	{
		return $this->HTTPStatusMessage;
	}

}

?>