<?php

    /***************************************************************************
    *
    *                            Facebook - Auto Event-Importer
    *                      
    *     copyright            : (C) 2012 Sven Goessling / SmileAndGo.de
    *     website              : http://www.sven-goessling.de
    *
    *     IMPORTANT: This is a commercial product made by Sven Goessling and cannot be modified for other than personal usage. 
    *     This product cannot be redistributed for free or redistribute it and/or modify without written permission from Sven Goessling. 
    *     This notice may not be removed from the source code.
    *     See license.txt file; if not, write to info@emsland-party.de 
    *
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

    bx_import('BxDolCron');

    require_once('GoesiFbModule.php');

    class goesi_fb_cron extends BxDolCron 
    {
        var $oSpyObject;
        var $iDaysForRows;

        /** 
         * Class constructor;
         */
        function goesi_fb_cron()
        {
        //echo 'Facebook Event Import:<br>'; 
// Anfang
	$this -> oModule     = BxDolModule::getInstance('GoesiFbModule');

//###############################  DO NOT EDIT BEFORE THIS!  ###############################


//Enter your User and Pass for Admin Area to catch cookie:
$admin_login_name = '';
$admin_login_pass = '';
$your_site_url = '';
// End



//###############################  DO NOT EDIT AFTER HERE!  ###############################
$admin_url = $your_site_url.'/administration/';
$cron_url = $your_site_url.'/m/fbook_event-import/administration/?cookie';



// Cookie Catching
if ($admin_login_name != '' && $admin_login_pass != '' ) {
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $admin_url);
		curl_setopt($ch, CURLOPT_POSTFIELDS,'ID='.urlencode($admin_login_name).'&Password='.urlencode($admin_login_pass));
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 2);
		curl_setopt($ch, CURLOPT_POST, 1); 
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_COOKIEJAR, 'site-cookie.txt');
		curl_setopt($ch, CURLOPT_COOKIEFILE, 'site-cookie.txt');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Mozilla/5.0 (Windows; U; Windows NT 6.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3");

	 $site_result = curl_exec($ch);
	 //echo htmlspecialchars($site_result);
 	preg_match_all('/form name="(.*)"/iU', $site_result, $ok);
	if (!$ok['1'] == "Redirect") { 
		echo 'Wrong Password';
		exit(); } else {
		curl_setopt($ch, CURLOPT_POST, 0);
    		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    		curl_setopt($ch, CURLOPT_URL, $cron_url);
    		$site_result = curl_exec($ch);
	//echo htmlspecialchars($site_result);
		}

		} else 
		{
		echo 'No Login data for cookie is set for Facebook Event Importer, edit GoesiFbCron.php ';
		exit(); 
		}
		@unlink('site-cookie.txt'); 
}
}