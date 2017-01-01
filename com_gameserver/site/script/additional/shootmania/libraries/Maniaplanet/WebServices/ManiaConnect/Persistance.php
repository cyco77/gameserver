<?php
/**
 * Maniaplanet Web Services SDK for PHP
 *
 * @see		    http://code.google.com/p/maniaplanet-ws-sdk/
 * @copyright   Copyright (c) 2009-2011 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @author      $Author: maximeraoust $:
 * @version     $Revision: 48 $:
 * @date        $Date: 2011-09-29 16:47:40 +0200 (jeu., 29 sept. 2011) $:
 */

namespace Maniaplanet\WebServices\ManiaConnect;

interface Persistance
{

	function init();

	function destroy();

	function getVariable($name, $default=null);

	function setVariable($name, $value);

	function deleteVariable($name);
}

?>