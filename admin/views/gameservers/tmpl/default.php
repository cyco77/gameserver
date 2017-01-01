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

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

require_once(JPATH_SITE.DS.'components'.DS.'com_gameserver'.DS.'gamedataprovider.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_gameserver'.DS.'game.php');

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root().'components/com_gameserver/style/blank.css');

$app		= JFactory::getApplication();
$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$ordering 	= ($listOrder == 'g.ordering');
$canChange  = true; 
$canOrder	= $user->authorise('core.edit.state',	'com_gameserver');
$saveOrder 	= ($listOrder == 'g.ordering' && $listDirn == 'asc');
if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_gameserver&task=gameservers.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'articleList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$sortFields = $this->getSortFields();
?>
<script type="text/javascript">
	Joomla.orderTable = function() 
	{
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>')
		{
			dirn = 'asc';
		} 
		else 
		{
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_gameserver'); ?>" method="post" name="adminForm" id="adminForm">
	<?php if (!empty( $this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
	<?php else : ?>
	<div id="j-main-container">
	<?php endif;?>
		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<label for="filter_search" class="element-invisible"><?php echo JText::_('COM_GAMESERVER_ITEMS_SEARCH_FILTER');?></label>
				<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('COM_GAMESERVER_ITEMS_SEARCH_FILTER'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_TAGS_ITEMS_SEARCH_FILTER'); ?>" />
			</div>
			<div class="btn-group hidden-phone">
				<button class="btn tip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button class="btn tip" type="button" onclick="document.id('filter_search').value='';this.form.submit();" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC');?></label>
				<select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JFIELD_ORDERING_DESC');?></option>
					<option value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING');?></option>
					<option value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING');?></option>
				</select>
			</div>
			<div class="btn-group pull-right">
				<label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY');?></label>
				<select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JGLOBAL_SORT_BY');?></option>
					<?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder);?>
				</select>
			</div>
			<div class="clearfix"></div>
		</div>	
	
	<table class="table table-striped" id="articleList">
		<thead>
			<tr>
				<th width="1%" class="nowrap center hidden-phone">
					<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'g.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
				</th>
				<th width="20" class="nowrap center hidden-phone">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th width="30">
			
				</th>
				<th  class="nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_GAMESERVER_DISPLAYNAME', 'g.displayname', $listDirn, $listOrder); ?>
				</th>
				<th  class="nowrap hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_GAMESERVER_GAME', 'g.type', $listDirn, $listOrder); ?>
				</th>
				<th width="30">
			
				</th>
				<th  class="nowrap hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_GAMESERVER_IP', 'g.ip', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_GAMESERVER_PORT', 'g.port', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_GAMESERVER_PORT2', 'g.port2', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_GAMESERVER_ADDEDBY', 'u.name', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap hidden-phone">
					<?php echo JHtml::_('grid.sort', 'JSTATUS', 'g.published', $listDirn, $listOrder); ?>
				</th>
				<th width="1%" class="nowrap hidden-phone">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'g.serverid', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>
		<tfoot><?php echo $this->loadTemplate('foot');?></tfoot>
		<tbody>
		<?php
			$gameDataProvider = new GameDataProvider();

			?>	
			<?php foreach($this->items as $i => $item): 

				$ordering	= $listOrder == 'g.ordering';
				$link 		= JRoute::_( 'index.php?option=com_gameserver&controller=gameserver&task=gameserver.edit&serverid='. $item->serverid );

			?>
			<tr class="row<?php echo $i % 2; ?>">
			<td class="order nowrap center hidden-phone">
					<?php if ($canChange) :
						$disableClassName = '';
						$disabledLabel	  = '';
						if (!$saveOrder) :
							$disabledLabel    = JText::_('JORDERINGDISABLED');
							$disableClassName = 'inactive tip-top';
						endif; ?>
						<span class="sortable-handler hasTooltip<?php echo $disableClassName?>" title="<?php echo $disabledLabel?>">
							<i class="icon-menu"></i>
						</span>
						<input type="text" style="display:none" name="order[]" size="5"
						value="<?php echo $item->ordering;?>" class="width-20 text-area-order " />
					<?php else : ?>
						<span class="sortable-handler inactive" >
							<i class="icon-menu"></i>
						</span>
					<?php endif; ?>
			</td>
			<td class="center hidden-phone">
				<?php echo JHtml::_('grid.id', $i, $item->serverid); ?>
			</td>
			<td>
				<?php
				$gamelogofolder = JURI::root().'components/com_gameserver/images/icons/';		
				$image = $gamelogofolder.$item->type.'.png';
				echo '<img src="'.$image.'" border="0" alt="'.$gameDataProvider->getGameDisplayname($item->type).'" />';
				?>
			</td>
			<td valign="middle">
				<a href="<?php echo $link; ?>"><?php echo $item->displayname; ?></a>
			</td>
			<td class="hidden-phone">
				<?php echo $gameDataProvider->getGameDisplayname($item->type); ?>
			</td>
			<td class="hidden-phone">
				<?php 
				if ($item->country != null)
				{
					echo '<img src="'.JURI::root().'/components/com_gameserver/images/flags/'.strtolower($item->country).'.png" title="'.$item->country.'" />';
				}
				?>
			</td>
			<td class="hidden-phone">	
				<?php echo $item->ip; ?>
			</td>
			<td class="hidden-phone">
				<?php echo $item->port; ?>
			</td>
			<td class="hidden-phone">
				<?php echo $item->port2; ?>
			</td>
			<td class="hidden-phone">
				<?php echo $item->addedbyname; ?>
			</td>
			<td class="center">
				<?php echo JHtml::_('jgrid.published', $item->published, $i, 'gameservers.'); ?>
			</td>
			<td align="center hidden-phone">
				<?php echo $item->serverid; ?>
			</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
<?php echo JHtml::_('form.token'); ?>
</form>

<table width="100%">
	<tr>
		<td align="center" valign="top">
			<?php
			include(JPATH_COMPONENT.DS.'updatecheck.php');
			?>
		</td>
		<td align="center" valign="top">
			<p><?php echo JText::_('COM_GAMESERVER_DONATE'); ?></p>
			<div class="paypal_donation">
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
					<input type="hidden" name="cmd" value="_donations">
					<input type="hidden" name="business" value="cyco@punk-and-roll.de">
					<input type="hidden" name="item_name" value="joomla.larshildebrandt - GameServer">
					<input type="hidden" name="no_shipping" value="0">
					<input type="hidden" name="no_note" value="1">
					<input type="hidden" name="currency_code" value="EUR">
					<input type="hidden" name="tax" value="0">
					<input type="hidden" name="bn" value="PP-DonationsBF">
					<input type="hidden" name="amount" value="">   
					<input style="border:0;" type="image" src="https://www.paypal.com/en_GB/i/btn/btn_donateCC_LG.gif" name="submit" alt="PayPal Donation">            
					<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
				</form>
			</div>
		</td>
	</tr>
</table>
