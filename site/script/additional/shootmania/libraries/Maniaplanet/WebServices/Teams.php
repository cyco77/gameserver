<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision: 113 $:
 * @author      $Author: baptiste33@gmail.com $:
 * @date        $Date: 2012-07-11 14:56:37 +0200 (mer., 11 juil. 2012) $:
 */

namespace Maniaplanet\WebServices;

class Teams extends HTTPClient
{
	/**
	 * @param int $id Id of the team
	 * @return object
	 * @throws Exception
	 */
	function get($id)
	{
		if (!$id)
		{
			throw new Exception('Invalid id');
		}

		return $this->execute('GET', '/teams/%d/', array($id));
	}

	/**
	 * @param int $id Id of the team
	 * @return object
	 * @throws Exception
	 */
	function getPlayers($id)
	{
		if (!$id)
		{
			throw new Exception('Invalid id');
		}

		return $this->execute('GET', '/teams/%d/players/', array($id));
	}


	/**
	 * @param int $id Id of the team
	 * @return object
	 * @throws Exception
	 */
	function getAdmins($id)
	{
		if (!$id)
		{
			throw new Exception('Invalid id');
		}

		return $this->execute('GET', '/teams/%d/admins/', array($id));
	}
}