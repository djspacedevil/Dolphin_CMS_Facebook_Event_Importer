<?
/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -------------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2007 BoonEx Group
*     website              : http://www.boonex.com
* This file is part of Dolphin - Smart Community Builder
*
* Dolphin is free software; you can redistribute it and/or modify it under
* the terms of the GNU General Public License as published by the
* Free Software Foundation; either version 2 of the
* License, or  any later version.
*
* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Dolphin,
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/

bx_import('BxDolModuleDb');

class GoesiFbDb extends BxDolModuleDb {

	function GoesiFbDb(&$oConfig) {
		parent::BxDolModuleDb();
        $this->_sPrefix = $oConfig->getDbPrefix();
    }
function getSettingsCategory() {
        return $this->getOne("SELECT `ID` FROM `sys_options_cats` WHERE `name` = 'Fbook-Import' LIMIT 1");
    } 
function getURL() {
        return $this->getOne("SELECT VALUE FROM `sys_options` WHERE `Name` = 'goesi_fb_url' LIMIT 1");
	return $getURL;
    }  

 function addURL($URL) {
        return $this->query("INSERT INTO goesi_fb_list (id, url, last_job_time) VALUES (NULL , '{$URL}', '0')");
 }
function delURL($URL) {
        return $this->query("DELETE FROM goesi_fb_list where id = '{$URL}'");
}

function getNull_events_check($imp_url) {
        return $this->getOne("SELECT `null_events_check` FROM `goesi_fb_list` WHERE `url` = '{$imp_url}'");
	return $getNull_events_check;
}



function getActive($URL) {
        return $this->getOne("SELECT `active` FROM `goesi_fb_list`WHERE `url` = '{$URL}'");
	return $getActive;
}
function setActive($URL) {
        return $this->query("UPDATE `goesi_fb_list` SET `active`='1' WHERE `url` = '{$URL}'");
}
function unsetActive($URL) {
        return $this->query("UPDATE `goesi_fb_list` SET `active`='0' WHERE `url` = '{$URL}'");
}

function getLinks($URL) {
        return $this->getOne("SELECT `checked_links` FROM `goesi_fb_list`WHERE `url` = '{$URL}'");
	return $getLinks;
}

function setLinks($imp_url,  $count) {
        return $this->query("UPDATE `goesi_fb_list` SET `checked_links`='{$count}' WHERE `url` = '{$imp_url}'");
}

function getTime($imp_url) {
        return $this->getOne("SELECT `last_job_time` FROM `goesi_fb_list`WHERE `url` = '{$imp_url}'");
	return $getTime;
}


function TimeUpdate($imp_url) {
        return $this->query("UPDATE `goesi_fb_list` SET `last_job_time`=NOW() WHERE `url` = '{$imp_url}'");
}

function listURL($URL) {
        $listURL = $this->getAll("Select count(*) from goesi_fb_list");
	return $listURL;
	}
function URL($URL) {
        $URL = $this->getAll("Select * from goesi_fb_list");
	return $URL;
	}
function FacebookUser() {
        return $this->getOne("SELECT VALUE FROM `sys_options` WHERE `Name` = 'goesi_fb_user' LIMIT 1");
	 return $user;
	}
function FacebookPass() {
        return $this->getOne("SELECT VALUE FROM `sys_options` WHERE `Name` = 'goesi_fb_pass' LIMIT 1");
	 return $pass;
	}
function FacebookCategorie() {
        return $this->getOne("SELECT VALUE FROM `sys_options` WHERE `Name` = 'goesi_fb_categorie' LIMIT 1");
	 return $cat;
	}
function FacebookTags() {
        return $this->getOne("SELECT VALUE FROM `sys_options` WHERE `Name` = 'goesi_fb_tags' LIMIT 1");
	 return $tag;
	}

}

?>
