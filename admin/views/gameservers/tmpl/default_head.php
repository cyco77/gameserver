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

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<tr>
	<th width="1%" class="nowrap center hidden-phone">
		<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'g.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
	</th>
	<th width="20">
		<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
	</th>
	<th width="30">
			
	</th>
	<th  class="title">
		<?php echo JHtml::_('grid.sort', 'COM_GAMESERVER_DISPLAYNAME', 'g.displayname', $listDirn, $listOrder); ?>
	</th>
	<th  class="title">
		<?php echo JHtml::_('grid.sort', 'COM_GAMESERVER_GAME', 'g.type', $listDirn, $listOrder); ?>
	</th>
	<th width="30">
			
	</th>
	<th  class="title">
		<?php echo JHtml::_('grid.sort', 'COM_GAMESERVER_IP', 'g.ip', $listDirn, $listOrder); ?>
	</th>
	<th class="title">
		<?php echo JHtml::_('grid.sort', 'COM_GAMESERVER_PORT', 'g.port', $listDirn, $listOrder); ?>
	</th>
	<th class="title">
		<?php echo JHtml::_('grid.sort', 'COM_GAMESERVER_PORT2', 'g.port2', $listDirn, $listOrder); ?>
	</th>
	<th class="title">
		<?php echo JHtml::_('grid.sort', 'COM_GAMESERVER_ADDEDBY', 'u.name', $listDirn, $listOrder); ?>
	</th>
	<th nowrap="nowrap" width="8%">
		<?php echo JHtml::_('grid.sort', 'JSTATUS', 'g.published', $listDirn, $listOrder); ?>
	</th>
	<th width="1%" nowrap="nowrap">
		<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'g.serverid', $listDirn, $listOrder); ?>
	</th>
</tr>