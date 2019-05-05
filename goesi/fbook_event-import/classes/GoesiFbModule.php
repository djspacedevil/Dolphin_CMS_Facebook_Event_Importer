<?php
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

bx_import('BxDolModule');

class GoesiFbModule extends BxDolModule {

    function GoesiFbModule(&$aModule) {        
        parent::BxDolModule($aModule);
    }
//Admin Bereich
    function actionAdministration () {

        if (!$GLOBALS['logged']['admin']) {
            $this->_oTemplate->displayAccessDenied ();
            return;
        }

        $this->_oTemplate->pageStart();
	$cId = $this->_oDb->getSettingsCategory(); 
	 $iId = $this->_oDb->listURL($listURL); 
	if(empty($iId)) { // if category is not found display page not found
            echo MsgBox(_t('_sys_request_page_not_found_cpt'));
            $this->_oTemplate->pageCodeAdmin (_t('_goesi_fb_import'));
            return;
        }
	 $ids = $this->_oDb->URL($URL); // get id
	 
$i=0;
$URLList='';

if(isset($_POST['addurl'])) {
	echo '<center><img src="'.BX_DOL_URL_ROOT .'modules/goesi/fbook_event-import/templates/base/images/loader.gif"><br>'.$_POST['url'].'<br></center>';
	$addURL = $this->_oDb->addURL($_POST['addurl']);
       echo'<META HTTP-EQUIV=Refresh CONTENT="0">';
}
if(isset($_POST['deak'])) {
	$addURL = $this->_oDb->unsetActive($_POST['deak']);
}
if(isset($_POST['akti'])) {
	$addURL = $this->_oDb->setActive($_POST['akti']);
}


if(isset($_POST['delete'])) {
 echo '<center><img src="'.BX_DOL_URL_ROOT .'modules/goesi/fbook_event-import/templates/base/images/loader.gif"><br>'._t('_goesi_fb_deleted').' '.$_POST['url'].'</center>';
 $delURL = $this->_oDb->delURL($_POST['url']);
 echo'<META HTTP-EQUIV=Refresh CONTENT="0">';
}
if(isset($_POST['job_now'])) {
 echo '<center><img src="'.BX_DOL_URL_ROOT .'modules/goesi/fbook_event-import/templates/base/images/loader.gif"><br>'._t('_goesi_fb_import_now_exec').'<br> '.$_POST['url'].'</center>';
 require_once('GoesiFbImport.php');
 echo'<META HTTP-EQUIV=Refresh CONTENT="3">';
}
if(isset($_POST['cookie']) || isset($_GET['cookie'])) {
 echo '<center><img src="'.BX_DOL_URL_ROOT .'modules/goesi/fbook_event-import/templates/base/images/loader.gif"><br>SYNC ALL...</center>';
 require_once('GoesiFbImportALL.php');
 echo'<META HTTP-EQUIV=Refresh CONTENT="3">';
}

while($i < $iId[0]['count(*)']) 
 { 

$URL = $ids[$i][url];
//TimeCheck
 if (strtotime($ids[$i][last_job_time]) >= time() - (60 * 60 * 24 * 7)) {
 $date= '<font color=#008000>'.date("d-m-y H:i:s", strtotime ($ids[$i][last_job_time])).' <img src="'.BX_DOL_URL_ROOT .'modules/goesi/fbook_event-import/templates/base/images/ok.png"></font>';
} else {
 $date= '<font color=#FF0000>'.date("d-m-y H:i:s", strtotime ($ids[$i][last_job_time])).' <img src="'.BX_DOL_URL_ROOT .'modules/goesi/fbook_event-import/templates/base/images/no_ok.png"></font>';
}
//Active Check
$getActive = $this->_oDb->getActive($URL); // get activ
if ($getActive == 1 ) {
$activ = '<form action="" method="post"><input type="image" src="'.BX_DOL_URL_ROOT .'modules/goesi/fbook_event-import/templates/base/images/go.png"><input type="hidden" name="deak" value="'.$URL.'"></form>';
$button_deak = '';
} else { 
$activ = '<form action="" method="post"><input type="image" src="'.BX_DOL_URL_ROOT .'modules/goesi/fbook_event-import/templates/base/images/no_go.png"><input type="hidden" name="akti" value="'.$URL.'"></form>';
$button_deak = 'disabled="disabled"';
}

//Errors or grabbed Links
$null_event_check = $this ->_oDb->getNull_events_check($URL);
if ($null_event_check > 0 ) { $getLinks = $null_event_check.'-R.E'; } else {
$getLinks = $this->_oDb->getLinks($URL); // get links
}
	 
$URLList = $URLList . ' <tr><td align="center">'.$activ.'</td><td align="center">'.$ids[$i][id].'</td><td align="center"><a href="'.$ids[$i][url].'" target="blank">'.$ids[$i][url].'</a>&nbsp;</td>
<td align="center">'.$date.'</td><td align="center">'.$getLinks.'</td>
<td align="center"><form action="'.header(BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri()).'" method="post">
     <input type="submit" name="job_now" value="'._t('_goesi_fb_import_now').'" '.$button_deak.'><input type="hidden" name="url" value="'.$URL.'">
</form></td>
   <td align="center"><form action="'.header(BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri()).'" method="post">
     <input type="submit" name="delete" value="'._t('_goesi_fb_delete_url').'"><input type="hidden" name="url" value="'.$ids[$i][id].'">
</form></td></tr>';
	$i++;
}

$aVars = array (
            'module_url' 	=> BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri(),
	     'all'		=> $iId[0]['count(*)'],
	     'alle'		=> _t('_goesi_fb_alle'),
	     'newurls'	=> $URLList,
        );
        bx_import('BxDolAdminSettings'); // import class

        $mixedResult = '';
        if(isset($_POST['save']) && isset($_POST['cat'])) { 
            $oSettings = new BxDolAdminSettings($cId);
            $mixedResult = $oSettings->saveChanges($_POST);
        }

        $oSettings = new BxDolAdminSettings($cId); 
        $sResult = $oSettings->getForm();
                   
        if($mixedResult !== true && !empty($mixedResult)) 
            $sResult = $mixedResult . $sResult . header("Location: " . BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() .'administration/');

	$sContent = $this->_oTemplate->parseHtmlByName ('admin', $aVars);


        echo $this->_oTemplate->adminBlock ($sContent, _t('Facebook Event Importer'));

        echo DesignBoxAdmin (_t('_goesi_fb_import'), $sResult);
        
        $this->_oTemplate->pageCodeAdmin (_t('_goesi_fb_import'));
    }





//User Bereich
    function actionHome () {
        $this->_oTemplate->pageStart();
        $aVars = array ();
        echo $this->_oTemplate->parseHtmlByName('main', $aVars);
        $this->_oTemplate->pageCode(_t('_goesi_fb_import'), true);
    }


}

?>
