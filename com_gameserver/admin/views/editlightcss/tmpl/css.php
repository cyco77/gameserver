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
 ?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
	<fieldset class="adminform">
    	<textarea name="gameservercss" id="gameservercss" wrap="wrap" style="width:98%;height:500px"><?php echo $this->css; ?></textarea>         
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_gameserver" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="editlightcss" />

</form>