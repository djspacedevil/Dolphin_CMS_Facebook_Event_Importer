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

    
        echo 'Facebook Event Import:<br>'; 
// Anfang
	$this -> oModule     = BxDolModule::getInstance('GoesiFbModule');

// Zugriff auf Inhalte nur f�r Registierte Facebook User
$login_email = $this-> oModule ->_oDb->FacebookUser($user); // get user
$login_pass  = $this-> oModule ->_oDb->FacebookPass($pass); // get pass
$categorie = $this-> oModule ->_oDb->FacebookCategorie($cat); //get categorie
$tags = $this-> oModule ->_oDb->FacebookTags($tag); //get tags
$face_cookie = 'cookie.txt'; 

$fb_token = '?access_token=AAAHFZBjp3ZAZCcBAOMSWX6cHhfAd1AvZCpwlIcJKVTX8ZCqL4LJZB14XTDvJUlAUNhGVK0GhDMuYZCXUm7tIz0dBl6Nxr8gKrz0mMd26iyp5vuaZAjMVfZAs4';

//SQL Daten laden
 $iId = $this-> oModule ->_oDb->listURL($listURL); // Count for while
 $ids = $this-> oModule ->_oDb->URL($URL); // get urls
$w = 0;

while($w < $iId[0]['count(*)']) 
 { 
$imp_url = $ids[$w][url];

//Check for Active
$getActive = $this-> oModule ->_oDb->getActive($imp_url);
 
if ($getActive == 1) {


$getTime  = $this-> oModule ->_oDb->getTime($imp_url); // get last runtime

 if (strtotime($getTime) >= time() - (60 * 60 * 24 * 2)) {
 echo 'UpToDate: '. $imp_url.'<br>';
} else {
 



$ch = curl_init();
			if ($login_email != '' && $login_pass != '' ) {
		curl_setopt($ch, CURLOPT_URL, 'https://login.facebook.com/login.php?m&next=http%3A%2F%2Fm.facebook.com%2Fhome.php');
		curl_setopt($ch, CURLOPT_POSTFIELDS,'email='.urlencode($login_email).'&pass='.urlencode($login_pass).'&login=Login');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_POST, 1); } else {
		curl_setopt($ch, CURLOPT_URL, $imp_url); }

		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $face_cookie);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $face_cookie);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Mozilla/5.0 (Windows; U; Windows NT 6.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3");

	 $result = curl_exec($ch);


	
	//�ffne nun die Informations Seite
if ($login_email != '' && $login_pass != '' ) {
    		curl_setopt($ch, CURLOPT_POST, 0);
    		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    		curl_setopt($ch, CURLOPT_URL, $imp_url);
    		$result = curl_exec($ch);
////////////////////////////////////////////////
// Token
    		curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/oauth/access_token?client_id=499153490110455&client_secret=1f9ab3e14fab60039f8e37546a367926&grant_type=client_credentials');
    		curl_setopt($ch, CURLOPT_POST, 0);
    		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        $new_token = curl_exec($ch);
    		
    		preg_match_all('/access_token=(.*)/i', $new_token, $token);
    		//mysql_query("UPDATE goesi_fb_ue_config SET value='".$token['1']['0']."' WHERE name='goesi_fb_access-token'");
    		$fb_token= '?access_token='.$token['1']['0']; //get token
//
}
			preg_match_all('/\/events\/(?<![0-9])[0-9]{15}(?![0-9])/', $result, $events);
			if (count($events[0]) > 0) {
				$z = 0;
				foreach ($events[0] as $event) {
					$events[1][$z] = preg_replace("/[^0-9,.]/", "", $event);
					$z++;
				}
			}			
			if (count($events['1']) > 0) { 
				unset($events[0]);
				$events[1] = array_unique($events[1], SORT_REGULAR);
				echo 'HTTPS:'.count($events[1]).' Link<br>'; 
			} else {
				echo 'NO EVENTS FOUND ON URL: '.$imp_url.'<br>';
			}
//Set Links
$count =count($events[1]);
if ($count > 0 ) {
mysql_query("UPDATE `goesi_fb_list` SET `checked_links`='{$count}' WHERE `url` = '{$imp_url}'"); 
mysql_query("UPDATE `goesi_fb_list` SET `null_events_check`='0' WHERE `url` = '{$imp_url}'");
} else {

$null_event_check = $this-> oModule ->_oDb->getNull_events_check($imp_url);
if ($null_event_check > 112 ) { mysql_query("UPDATE `goesi_fb_list` SET `active`='0' WHERE `url` = '{$imp_url}'"); } else { 
mysql_query("UPDATE `goesi_fb_list` SET `null_events_check`=null_events_check+1 WHERE `url` = '{$imp_url}'");
}
}


        //echo"Auto-Event Script for Smileandgo.de <br><br><br><br><br><br>";
	$i=0;
foreach ($events[1] as $single_event)
 { 
echo $i.'= '.$single_event;
echo" ";

$contents = file_get_contents('https://graph.facebook.com/'.$single_event.$fb_token);
$event = '';
$event = json_decode($contents,true);
$sonderzeichen=array( "ö" => "oe", "ü" => "ue", "ß" => "ss","ä" => "ae", "Ä" => "Ae", "Ü" => "Ue", "Ö" => "Oe", "é" => "E", "€" => "Euro", "'" => "");

//Event-Daten abgreifen
if ($event) {
if (!isset($event['end_time']) || $event['end_time'] == "") {
	$event['end_time'] = $event['start_time'];
	// 5 Hours add when no event end time is set sec. +18000
		$event_end_time = date('H,i,s,m,d,Y', strtotime($event['end_time'])+18000);
		$EventEnds = strtotime($event['end_time'])+18000;
	} else {
		$event_end_time = $EventEnds = strtotime($event['end_time']);
	}
$event_id = $event['id'];
$name = $event_owner_name  = strtr($event['owner']['name'], $sonderzeichen);
$event_owner_category  = strtr($event['owner']['category'], $sonderzeichen);
$event_owner_id  = $event['owner']['id'];
$name_pre = $event_name  = strtr($event['name'], $sonderzeichen);
$event_description = strtr($event['description'], $sonderzeichen);
$description = $event_description = "<b>Owner</b>: <a href=\"https://facebook.com/" . $event_owner_id . "\" target=\"_blank\">" . $event_owner_name . "</a><br/><b>Link</b>: <a href=https://www.facebook.com/event.php?eid=" . $event_id . " target=\"_blank\">" . $event_name . "</a><br/><br/>" . $event_description; 
$event_start_time = date('H,i,s,m,d,Y', strtotime($event['start_time']));
$EventStarts = $event_start_time = strtotime($event['start_time']);

$location = $event_location = strtr($event['location'] , $sonderzeichen);
$street = $event_venue_street = strtr($event['venue']['street'] , $sonderzeichen);
$city_state= $event_venue_city = strtr($event['venue']['city'] , $sonderzeichen);
$event_venue_state = strtr($event['venue']['state'] , $sonderzeichen);
$country = $event_venue_country = strtr($event['venue']['country'] , $sonderzeichen);
$event_venue_latitude = $event['venue']['latitude'];
$event_venue_longitude = $event['venue']['longitude'];
$event_venue_id = $event['venue']['id'];

$event_privacy = $event['privacy'];
$event_updated_time = strtotime($event['updated_time']);

if (!$event_venue_id) $event_venue_id = $event['owner']['id'];

$content = file_get_contents('https://graph.facebook.com/'.$event_venue_id);
$event = json_decode($content,true);

if (!$street) { 
if (isset($event['location']['street'])) $street = $event_venue_street = strtr($event['location']['street'] , $sonderzeichen);
}

if (isset($event['location']['zip'])) $zip = $event_venue_zip = strtr($event['location']['zip'] , $sonderzeichen);

if (!$city_state) {
if (isset($event['location']['city'])) $city_state= $event_venue_city = strtr($event['location']['city'] , $sonderzeichen);
}
 
if (!$country) {
if (isset($event['location']['country'])) $country = $event_venue_country = strtr($event['location']['country'] , $sonderzeichen);
}
 

$city_state = $zip.' '.$city_state.', '.$street;

include('GoesiFbCountry.php');
} else {
echo ' - Error: Events not public';
//break;
}
//                                        
                            $name_pres   = preg_replace( "/ /", "_", $name_pre );
                            $uri         = preg_replace( "/[^a-zA-Z0-9_]/", "", $name_pres );
                            //$name        = preg_replace( "/[^a-zA-Z0-9 @]/", "", $name_pres );
							$name        = preg_replace( "/'/", "", $event_name);
                            $location    = preg_replace( "/'/", "", $location );
                            $description = preg_replace( "/(\r\n|\r|\n)/", "<br>", $description );
							$description = preg_replace( "/'/", "", $description );
                            $iProfileId  = 1;
							
				$sql_update  = mysql_query("SELECT `Description` FROM `bx_events_main` WHERE `EntryUri` = '$uri'");
				$row = mysql_fetch_row ($sql_update);
	//TimeUpdate
	$this-> oModule ->_oDb->TimeUpdate($imp_url);
			if ($row[0] != $description) {

//Event- Image abgreifen
$imgfile   = 'https://graph.facebook.com/'.$single_event . '/picture?type=large';
 $aFileInfo = array(
                    'medTitle' => $event_name,
                    'medDesc' => $event_owner_name . ' ' .$event_name,
                    'Categories' => array(
                    'Events' 
                    ),
         'medTags' => 'Events, Party, '.$event_owner_name.', '.$event_venue_city,
         'album' => 'Hidden' 
);

// Path for uploads - goesi
if (!is_dir('../tmp')) {
mkdir('../tmp', 0777);
echo '<br> /tmp/ folder not available; Will create now.'; }
//
$sPath     = '../tmp/' . $event_id . '_.jpg';
$sTmpFile  = $imgfile;
//copy( $sTmpFile, $sPath );
//Get the file
$content = file_get_contents($imgfile);
//Store in the filesystem.
$fp = fopen($sPath, "w");
fwrite($fp, $content);
fclose($fp);


//
$iProfileId = getLoggedId();
$iRet         = BxDolService::call( 'photos', 'perform_photo_upload', array(
                $sPath,
                $aFileInfo,
                false 
), 'Uploader' );

$subFrndQuery = "SELECT `bx_photos_main`.* FROM `bx_photos_main` WHERE `bx_photos_main`.`Owner` = '{$iProfileId}' ORDER BY `bx_photos_main`.`Date` DESC LIMIT 1";
                                        if ( $iRet ) {
                                                   $subFrndRes = mysql_query( $subFrndQuery );
								
                                        while ( $subFrndArr = mysql_fetch_assoc( $subFrndRes ) ) {
						$PicID = $subFrndArr[ 'ID' ];
                                        } 
		} else { exit(); }
				
				unlink($sPath);
				$sql_delete  = "DELETE FROM `bx_events_main` WHERE `EntryUri` = '$uri'";                            
                            if ( mysql_query( $sql_delete ) ) {
                                        $sql = "INSERT INTO `bx_events_main` (
        `Title`, 
        `EntryUri`, 
        `Description`, 
        `Status`, 
        `Country`, 
        `City`, 
        `Place`, 
        `PrimPhoto`, 
        `Date`, 
        `EventStart`, 
        `EventEnd`, 
        `ResponsibleID`, 
        `EventMembershipFilter`, 
        `Tags`, 
        `Categories`, 
        `Views`, 
        `Rate`, 
        `RateCount`, 
        `CommentsCount`, 
        `FansCount`, 
        `Featured`, 
        `allow_view_event_to`, 
        `allow_view_participants_to`, 
        `allow_comment_to`, 
        `allow_rate_to`, 
        `allow_join_to`, 
        `allow_post_in_forum_to`, 
        `JoinConfirmation`, 
        `allow_upload_photos_to`, 
        `allow_upload_videos_to`, 
        `allow_upload_sounds_to`, 
        `allow_upload_files_to`
        ) 

        VALUES (
        '$name', 
        '$uri', 
        '$description', 
        'approved', 
        '$country', 
        '$city_state', 
        '$location',
        '$PicID',
        UNIX_TIMESTAMP(),
        '$EventStarts',
        '$EventEnds', 
        '$iProfileId', 
        '', 
        '$tags', 
        '$categorie', 
        0, 
        0, 
        0, 
        0, 
        0, 
        0, 
        3, 
        3, 
        3, 
        3, 
        3, 
        'p', 
        0, 
        'a', 
        'a', 
        'a', 
        'a'
        );";
                                        if ( mysql_query( $sql ) ) {
                                                    $sqlQuery = "SELECT `bx_events_main`.`ID` FROM bx_events_main WHERE `bx_events_main`.`ResponsibleID` = '{$iProfileId}' ORDER BY `bx_events_main`.`Date` DESC LIMIT 1";
                                                    $iRet     = (int) db_value( $sqlQuery );
                                        } //mysql_query($sql)
                                        mysql_query( "INSERT INTO `bx_events_images` SET `entry_id` = '{$iRet}', `media_id` = '{$PicID}' " );
                                        if ( $iRet ) {
                                                    $sql2 = "INSERT INTO `bx_wall_events` SET
              `date` = UNIX_TIMESTAMP(),
              `owner_id` = '{$iProfileId}',
              `object_id` = '{$iRet}',
              `action` = 'add', 
              `type` = 'bx_events',
              `content`  = '{$name}',
              `title` = '{$name}',
              `description` = '{$name}'
      ";
                                        } 
	} 
}                           

//Ende

	unlink($sPath);

$i++;
}



// Ende
}
$w++;
} else { Echo 'Disabled: '.$imp_url.'<br>'; $w++;} 
}
   
        	

	

