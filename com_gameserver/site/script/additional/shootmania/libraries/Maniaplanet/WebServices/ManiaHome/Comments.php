<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision: 140 $:
 * @author      $Author: melot.philippe $:
 * @date        $Date: 2012-08-03 11:47:54 +0200 (ven., 03 août 2012) $:
 */

namespace Maniaplanet\WebServices\ManiaHome;

class Comments extends \Maniaplanet\WebServices\HTTPClient
{

	/**
	 *
	 * @param int $notificationId
	 * @return int number of parameters
	 */
	function count($notificationId)
	{
		return $this->execute('GET', sprintf('/maniahome/notifications/%d/comments/count/', $notificationId));
	}

}

?>