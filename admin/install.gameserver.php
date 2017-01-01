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
defined( '_JEXEC' ) or die( 'Restricted access' );

function com_install() {
	$db	= JFactory::getDBO();
	$tablename = $db->nameQuote('#__gameserver');
	
	$query ='SHOW COLUMNS FROM '.$tablename.';';
	$db->setQuery( $query );
	if ($result = $db->query())
	{
		if (!AddColumnIfNotExists($error, $tablename, 'user'))
		{
        echo $error;
    }
		if (!AddColumnIfNotExists($error, $tablename, 'pass'))
		{
        echo $error;
    }
	}
}

function AddColumnIfNotExists(&$errorMsg, $table, $column, $attributes = "varchar(255) NULL default ''", $after = '' ) {
		
		global $mainframe;
		$db	= JFactory::getDBO();
		$columnExists 	= false;

		$query = 'SHOW COLUMNS FROM '.$table;
		$db->setQuery( $query );
		if (!$result = $db->query()){return false;}
		$columnData = $db->loadObjectList();
		
		foreach ($columnData as $valueColumn) {
			if ($valueColumn->Field == $column) {
				$columnExists = true;
				break;
			}
		}
		
		if (!$columnExists) {
			if ($after != '') {
				$query = 'ALTER TABLE '.$table.' ADD '.$db->nameQuote($column).' '.$attributes.' AFTER '.$db->nameQuote($after).';';
			} else {
				$query = 'ALTER TABLE '.$table.' ADD '.$db->nameQuote($column).' '.$attributes.';';
			}
			$db->setQuery( $query );
			if (!$result = $db->query()){return false;}
			$errorMsg = 'notexistcreated';
		}
		
		return true;
	}

?>