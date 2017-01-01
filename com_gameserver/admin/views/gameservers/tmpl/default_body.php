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
<td>
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
<td>
	<?php echo $gameDataProvider->getGameDisplayname($item->type); ?>
</td>
<td>
	<?php 
	if ($item->country != null)
	{
		echo '<img src="'.JURI::root().'/components/com_gameserver/images/flags/'.strtolower($item->country).'.png" title="'.$this->gameserver->country.'" />';
	}
	?>
</td>
<td>	
	<?php echo $item->ip; ?>
</td>
<td>
	<?php echo $item->port; ?>
</td>
<td>
	<?php echo $item->port2; ?>
</td>
<td>
	<?php echo $item->addedbyname; ?>
</td>
<td class="center">
	<?php echo JHtml::_('jgrid.published', $item->published, $i, 'gameservers.', true, 'cb', $item->publish_up, $item->publish_down); ?>
</td>
<td align="center">
	<?php echo $item->serverid; ?>
</td>
</tr>
<?php endforeach; ?>