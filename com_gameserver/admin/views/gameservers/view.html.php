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

jimport( 'joomla.application.component.view' );

class GameServerViewGameServers extends JViewLegacy
{	
	protected $items;

	protected $pagination;

	protected $state;
	
	function display($tpl = null)
	{		
		$this->state = $this->get('State');
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		
		// Preprocess the list of items to find ordering divisions.
		// TODO: Complete the ordering stuff with nested sets
		foreach ($this->items as &$item)
		{
			$item->order_up = true;
			$item->order_dn = true;
		}
		
		// Set the toolbar
		$this->addToolBar();
		$this->sidebar = JHtmlSidebar::render();
		
		// Display the template
		parent::display($tpl);
	}
	
	protected function addToolBar() 
	{
		JToolbarHelper::title(   JText::_( 'Gameservers' ),  'gameserver' );
		JToolbarHelper::addNew('gameserver.add');
		JToolbarHelper::editList('gameserver.edit');
		JToolbarHelper::deleteList('','gameservers.delete');
		JToolbarHelper::divider();
		JToolbarHelper::publishList('gameservers.publish');
		JToolbarHelper::unpublishList('gameservers.unpublish');
		JToolbarHelper::divider();
		JToolbarHelper::custom( 'edit_light_css', 'css.png', 'css_f2.png', JText::_('Edit Light-CSS'), false, false );
		JToolbarHelper::custom( 'edit_dark_css', 'css.png', 'css_f2.png', JText::_('Edit Dark-CSS'), false, false );
		JToolbarHelper::divider();
		
		JToolbarHelper::preferences('com_gameserver',400,650);
		
		JHtmlSidebar::setAction('index.php?option=com_gameserver&view=gameservers');

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_PUBLISHED'),
			'filter_published',
			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true)
			);
	}
	
	protected function getSortFields()
	{
		return array(
			'g.displayname' => JText::_('COM_GAMESERVER_DISPLAYNAME'),
			'g.type' => JText::_('COM_GAMESERVER_GAME'),
			'g.ip' => JText::_('COM_GAMESERVER_IP'),
			'g.port' => JText::_('COM_GAMESERVER_PORT'),
			'g.port2' => JText::_('COM_GAMESERVER_PORT2'),
			'u.name' => JText::_('COM_GAMESERVER_ADDEDBY')
			);
	}
}