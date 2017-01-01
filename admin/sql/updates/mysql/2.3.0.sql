ALTER TABLE #__gameserver ADD cachedserverdata text NULL;

ALTER TABLE #__gameserver ADD cachedatetime timestamp NULL default CURRENT_TIMESTAMP;

ALTER TABLE #__gameserver ADD showsettings tinyint(4) NOT NULL default '1';