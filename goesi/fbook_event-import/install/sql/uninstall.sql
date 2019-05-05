-- settings
SET @iCategId = (SELECT `ID` FROM `sys_options_cats` WHERE `name` = 'Fbook-Import' LIMIT 1);
DELETE FROM `sys_options` WHERE `kateg` = @iCategId;
DELETE FROM `sys_options_cats` WHERE `ID` = @iCategId;
DELETE FROM `sys_options` WHERE `Name` = 'goesi_fb_permalinks';


-- permalinks
DELETE FROM `sys_permalinks` WHERE `standard` = 'modules/?r=fbook_event-import/';

-- admin menu
DELETE FROM `sys_menu_admin` WHERE `name` = 'goesi_fb';

-- list
DROP TABLE `goesi_fb_list`;

-- Cron Job
    DELETE FROM `sys_cron_jobs` WHERE `name` = 'goesi_fb';