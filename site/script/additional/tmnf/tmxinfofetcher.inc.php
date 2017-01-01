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

/* vim: set noexpandtab tabstop=2 softtabstop=2 shiftwidth=2: */

/**
 * TMXInfoFetcher - Fetch TMX info/records for TMO/TMS/TMN/TMU(F)/TMNF tracks
 * Created by Xymph <tmn@gamers.org>
 * Inspired by TMNDataFetcher & "Stats for TMN Aseco+RASP"
 *
 * v1.11: Added another check for valid $replayid
 * v1.10: Added magic __set_state function to support var_export()
 * v1.9: Fixed fetch records run-time warnings
 * v1.8: Optimized get_file URL parsing
 * v1.7: Added $worldrec (boolean); changed $visible to boolean; renamed
 *       $download to $dloadurl, $imgurl to $imageurl & $imgurlsmall to
 *       $thumburl; minor tweaks
 * v1.6: Added check for valid $replayid & $replayurl
 * v1.5: Added TMNF compatibility
 * v1.4: Added get_file function to handle TMX site timeouts
 * v1.3: Added $awards, $comments, $replayid, $replayurl; renamed $comment to
 *       $acomment (to better distinguish author comment from # of comments)
 * v1.2: Allowed 26-char UIDs too; added $pageurl
 * v1.1: Allowed TMX IDs too
 * v1.0: Initial release
 */
class TMXInfoFetcher {

	public $section, $prefix, $uid, $id, $records,
		$name, $userid, $author, $uploaded, $updated,
		$visible, $type, $envir, $mood, $style, $routes,
		$length, $diffic, $lbrating, $awards, $comments, $worldrec,
		$game, $acomment, $pageurl, $replayid, $replayurl,
		$imageurl, $thumburl, $dloadurl, $recordlist;

	/**
	 * Fetches a hell of a lot of data about a TMX track
	 *
	 * @param String $game
	 *        TMX section for 'TMO', 'TMS', 'TMN', 'TMU', 'TMNF'
	 * @param String $id
	 *        The challenge UID to search for (if a 26/27-char alphanum string),
	 *        otherwise the TMX ID to search for (if a number)
	 * @param Boolean $records
	 *        If true, the script also returns the world records (max. 10)
	 * @return TMXInfoFetcher
	 *        If $name is empty, track was not found
	 */
	public function TMXInfoFetcher($game, $id, $records) {

		$this->section = $game;
		switch ($game) {
		case 'TMO':
			$this->prefix = 'original';
			break;
		case 'TMS':
			$this->prefix = 'sunrise';
			break;
		case 'TMN':
			$this->prefix = 'nations';
			break;
		case 'TMU':
			$this->prefix = 'united';
			break;
		case 'TMNF':
			$this->prefix = 'tmnforever';
			break;
		default:
			$this->prefix = '';
			return;
		}

		$this->records = $records;
		// check for UID string
		if (preg_match('/^\w{26,27}$/', $id)) {
			$this->uid = $id;
			$this->getData(true);
		// check for TMX ID
		} elseif (is_numeric($id) && $id > 0) {
			$this->id = floor($id);
			$this->getData(false);
		}
	}  // TMXInfoFetcher

	public function __set_state($import) {

		$tmx = new TMXInfoFetcher('', 0, false);

		$tmx->section   = $import['section'];
		$tmx->prefix    = $import['prefix'];
		$tmx->uid       = $import['uid'];
		$tmx->id        = $import['id'];
		$tmx->records   = $import['records'];
		$tmx->name      = $import['name'];
		$tmx->userid    = $import['userid'];
		$tmx->author    = $import['author'];
		$tmx->uploaded  = $import['uploaded'];
		$tmx->updated   = $import['updated'];
		$tmx->visible   = $import['visible'];
		$tmx->type      = $import['type'];
		$tmx->envir     = $import['envir'];
		$tmx->mood      = $import['mood'];
		$tmx->style     = $import['style'];
		$tmx->routes    = $import['routes'];
		$tmx->length    = $import['length'];
		$tmx->diffic    = $import['diffic'];
		$tmx->lbrating  = $import['lbrating'];
		$tmx->awards    = $import['awards'];
		$tmx->comments  = $import['comments'];
		$tmx->worldrec  = $import['worldrec'];
		$tmx->game      = $import['game'];
		$tmx->acomment  = $import['acomment'];
		$tmx->pageurl   = $import['pageurl'];
		$tmx->replayid  = $import['replayid'];
		$tmx->replayurl = $import['replayurl'];
		$tmx->imageurl  = $import['imageurl'];
		$tmx->thumburl  = $import['thumburl'];
		$tmx->dloadurl  = $import['dloadurl'];
		$tmx->recordlist = null;

		return $tmx;
	}  // __set_state

	private function getData($isuid) {

		// get main track info
		$file = $this->get_file('http://' . $this->prefix . '.tm-exchange.com/apiget.aspx?action=apitrackinfo&' . ($isuid ? 'u' : '') . 'id=' . ($isuid ? $this->uid : $this->id));
		if ($file === false || $file == -1)
			return false;

		// check for API error message
		if (strpos($file, chr(27)) !== false)
			return false;

		// separate columns on Tabs
		$fields = explode(chr(9), $file);

		if ($isuid)
			$this->id     = $fields[0];

		$this->name     = $fields[1];
		$this->userid   = $fields[2];
		$this->author   = $fields[3];
		$this->uploaded = $fields[4];
		$this->updated  = $fields[5];
		$this->visible  = (strtolower($fields[6]) == 'true');
		$this->type     = $fields[7];
		$this->envir    = $fields[8];
		$this->mood     = $fields[9];
		$this->style    = $fields[10];
		$this->routes   = $fields[11];
		$this->length   = $fields[12];
		$this->diffic   = $fields[13];
		$this->lbrating = ($fields[14] > 0 ? $fields[14] : 'Classic!');
		$this->game     = $fields[15];

		$search = array(chr(31), '[url]', '[/url]');
		$replace = array('<br />', '<i>', '</i>');
		$this->acomment = str_ireplace($search, $replace, $fields[16]);
		$this->acomment = preg_replace('/\[url=".*"\]/', '<i>', $this->acomment);

		$this->pageurl  = 'http://' . $this->prefix . '.tm-exchange.com/main.aspx?action=trackshow&id=' . $this->id;
		$this->imageurl = 'http://' . $this->prefix . '.tm-exchange.com/get.aspx?action=trackscreen&id=' . $this->id;
		$this->thumburl = 'http://' . $this->prefix . '.tm-exchange.com/get.aspx?action=trackscreensmall&id=' . $this->id;
		$this->dloadurl = 'http://' . $this->prefix . '.tm-exchange.com/get.aspx?action=trackgbx&id=' . $this->id;

		// get misc. track info
		$file = $this->get_file('http://' . $this->prefix . '.tm-exchange.com/apiget.aspx?action=apisearch&trackid=' . $this->id);
		if ($file === false || $file == -1)
			return false;

		// check for API error message
		if (strpos($file, chr(27)) !== false)
			return false;

		// separate columns on Tabs
		$fields = explode(chr(9), $file);

		// id           = $fields[0];
		// name         = $fields[1];
		// userid       = $fields[2];
		// author       = $fields[3];
		// type         = $fields[4];
		// envir        = $fields[5];
		// mood         = $fields[6];
		// style        = $fields[7];
		// routes       = $fields[8];
		// length       = $fields[9];
		// diffic       = $fields[10];
		// lbrating     = ($fields[11] > 0 ? $fields[11] : 'Classic!');
		$this->awards   = $fields[12];
		$this->comments = $fields[13];
		$this->worldrec = (strtolower($fields[14]) == 'true');
		// game         = $fields[15];
		$this->replayid = $fields[16];
		// unknown      = $fields[17-21];
		// uploaded     = $fields[22];
		// updated      = $fields[23];

		if ($this->worldrec && $this->replayid > 0)
			$this->replayurl = 'http://' . $this->prefix . '.tm-exchange.com/get.aspx?action=recordgbx&id=' . $this->replayid;
		else
			$this->replayurl = '';

		// fetch records too?
		if ($this->records) {
			$file = $this->get_file('http://' . $this->prefix . '.tm-exchange.com/apiget.aspx?action=apitrackrecords&id=' . $this->id);
			if ($file === false || $file == -1)
				return false;

			$file = explode("\r\n", $file);
			$this->recordlist = array();
			$i = 0;
			while ($i < 10 && isset($file[$i]) && $file[$i] != '') {
				// separate columns on Tabs
				$fields = explode(chr(9), $file[$i]);
				$this->recordlist[$i++] = array(
				                            'replayid' => $fields[0],
				                            'userid'   => $fields[1],
				                            'name'     => $fields[2],
				                            'time'     => $fields[3],
				                            'replayat' => $fields[4],
				                            'trackat'  => $fields[5],
				                            'approved' => $fields[6],
				                            'score'    => $fields[7],
				                            'expires'  => $fields[8],
				                            'lockspan' => $fields[9],
				                          );
			}
		}
	}  // getData

	// Simple HTTP Get function with timeout
	// ok: return string || error: return false || timeout: return -1
	private function get_file($url) {

		$url = parse_url($url);
		$port = isset($url['port']) ? $url['port'] : 80;
		$query = isset($url['query']) ? "?" . $url['query'] : "";

		$fp = @fsockopen($url['host'], $port, $errno, $errstr, 4);
		if (!$fp)
			return false;

		fwrite($fp, 'GET ' . $url['path'] . $query . " HTTP/1.0\r\n" .
		            'Host: ' . $url['host'] . "\r\n\r\n");
		stream_set_timeout($fp, 2);
		$res = '';
		$info['timed_out'] = false;
		while (!feof($fp) && !$info['timed_out']) {
			$res .= fread($fp, 512);
			$info = stream_get_meta_data($fp);
		}
		fclose($fp);

		if ($info['timed_out']) {
			return -1;
		} else {
			if (substr($res, 9, 3) != '200')
				return false;
			$page = explode("\r\n\r\n", $res, 2);
			return trim($page[1]);
		}
	}  // get_file
}  // class TMXInfoFetcher
?>
