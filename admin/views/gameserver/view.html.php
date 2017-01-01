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

// import Joomla view library
jimport('joomla.application.component.view');

class GameServerViewGameserver extends JViewLegacy
{
	function display($tpl = null)
	{
		// get the Data
		$form = $this->get('Form');
		$item = $this->get('Item');
		// $script = $this->get('Script');
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		// Assign the Data
		$this->form = $form;
		$this->item = $item;
		// $this->script = $script;
		
		// Set the toolbar
		$this->addToolBar();
		
		// Display the template
		parent::display($tpl);
		
		// Set the document
		$this->setDocument();
	}
	
	protected function addToolBar() 
	{
		JRequest::setVar('hidemainmenu', true);
		
		$isNew = $this->item->serverid == 0;
				
		JToolBarHelper::title(   $isNew ? 'New Gameserver' : 'Edit Gameserver',  'gameserver' );
		JToolBarHelper::apply('gameserver.apply');
		JToolBarHelper::save('gameserver.save');
		JToolbarHelper::save2new('gameserver.save2new');
		
		if ($isNew) 
		{
			JToolBarHelper::cancel('gameserver.cancel', 'JTOOLBAR_CANCEL');
		}
		else
		{
			JToolBarHelper::cancel('gameserver.cancel', 'JTOOLBAR_CLOSE');
		}
	}
	
	protected function setDocument() 
	{
		$isNew = $this->item->serverid == 0;
		$document = JFactory::getDocument();
		$document->setTitle($isNew ? 'New Gameserver' : 'Edit Gameserver');
	}
}