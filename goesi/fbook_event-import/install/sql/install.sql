-- settings
SET @iMaxOrder = (SELECT `menu_order` + 1 FROM `sys_options_cats` ORDER BY `menu_order` DESC LIMIT 1);
INSERT INTO `sys_options_cats` (`name`, `menu_order`) VALUES ('Fbook-Import', @iMaxOrder);
SET @iCategId = (SELECT LAST_INSERT_ID());
INSERT INTO `sys_options` (`Name`, `VALUE`, `kateg`, `desc`, `Type`, `check`, `err_text`, `order_in_kateg`, `AvailableValues`) VALUES
('goesi_fb_permalinks', 'on', 26, 'Enable', 'checkbox', '', '', '0', ''),
('goesi_fb_categorie', 'Last Friday, Saturday', @iCategId, 'Categorie', 'digit', '', '', '1', ''),
('goesi_fb_tags', 'Event, Party, Disco', @iCategId, 'Tags', 'digit', '', '', '2', ''),
('goesi_fb_user', '', @iCategId, 'Facebook Email', 'digit', '', '', '3', ''),
('goesi_fb_pass', '', @iCategId, 'Facebook Pass', 'digit', '', '', '4', '');

-- permalinks
INSERT INTO `sys_permalinks` VALUES (NULL, 'modules/?r=fbook_event-import/', 'm/fbook_event-import/', 'goesi_fb_permalinks');

-- admin menu
SET @iMax = (SELECT MAX(`order`) FROM `sys_menu_admin` WHERE `parent_id` = '2');
INSERT IGNORE INTO `sys_menu_admin` (`parent_id`, `name`, `title`, `url`, `description`, `icon`, `order`) VALUES
(2, 'goesi_fb', '_goesi_fb_import', '{siteUrl}modules/?r=fbook_event-import/administration/', 'Fbook-Import', 'modules/goesi/fbook_event-import/|icon.png', @iMax+1);

-- list
DROP TABLE IF EXISTS `goesi_fb_list`;
CREATE TABLE IF NOT EXISTS `goesi_fb_list` (
  `id` int(11) NOT NULL auto_increment,
  `active` tinyint(1) NOT NULL default '1',
  `url` varchar(255) NOT NULL,
  `last_job_time` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `checked_links` int(11) NOT NULL,
  `null_events_check` int(11) NOT NULL,
  KEY `id` (`id`,`url`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- CronJob
INSERT INTO `sys_cron_jobs` (`name`, `time`, `class`, `file`) VALUES ('goesi_fb', '0 */3 * * *', 'goesi_fb_cron', 'modules/goesi/fbook_event-import/classes/GoesiFbCron.php');
