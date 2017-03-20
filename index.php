<?php

    /*
     * This file is part of Panopto-PHP-Client.
     * 
     * Panopto-PHP-Client is free software: you can redistribute it and/or modify
     * it under the terms of the GNU General Public License as published by
     * the Free Software Foundation, either version 3 of the License, or
     * (at your option) any later version.
     * 
     * Panopto-PHP-Client is distributed in the hope that it will be useful,
     * but WITHOUT ANY WARRANTY; without even the implied warranty of
     * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     * GNU General Public License for more details.
     * 
     * You should have received a copy of the GNU General Public License
     * along with Panopto-PHP-Client.  If not, see <http://www.gnu.org/licenses/>.
     * 
     * Copyright: Andrew Martin, Newcastle University
     * 
     */
?>
<!DOCTYPE html>
<html>
    <head>

    </head>
    <body>
<?php
    //I'm keeping this index file in here for posterity to show examples of usage...
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once(dirname(__FILE__)."/includes/dataObjects/objects/AuthenticationInfo.php");
    require_once(dirname(__FILE__)."/includes/impl/4.2/client/AccessManagementClient.php");
    require_once(dirname(__FILE__)."/includes/impl/4.2/client/RemoteRecorderManagementClient.php");
    require_once(dirname(__FILE__)."/includes/impl/4.2/client/SessionManagementClient.php");
    error_reporting(E_ALL);
    date_default_timezone_set("Europe/London");

    $server = "yale.hosted.panopto.com";
    $auth = new AuthenticationInfo("user","password",null);
    $AMClient = new AccessManagementClient($server, $auth);
    $RRMClient = new RemoteRecorderManagementClient($server, $auth);
    $SMClient = new SessionManagementClient($server, $auth);

    try
    {
        $folderAccessDetailsResponse = $AMClient->getFolderAccessDetails("24855126-2d17-4c9c-bceb-2bf186ae2c17");
        $folderAccessDetails = $folderAccessDetailsResponse->getFolderAccessDetails();
        echo "<pre>";print_r($folderAccessDetails);echo "</pre>";
        foreach($folderAccessDetails->UsersWithCreatorAccess->getGuids() as $guid)
        {
            echo "<pre>";print_r($guid);echo "</pre>";
        }
    }
    catch(Exception $e)
    {
        echo "ERROR1: ".$e->getMessage();
    }

    echo "<hr/>";
    try
    {
        $folders = $SMClient->getFoldersList(new ListFoldersRequest(new Pagination(200,null), null, false, "Name", false))->getFolders();
        foreach($folders as $folder)
        {
            echo "<pre>";print_r($folder);echo "</pre>";
        }
    }
    catch(Exception $e)
    {
        echo "ERROR2: ".$e->getMessage();
    }

    echo "<hr/>";
    try
    {
        $sessions = $SMClient->getSessionsList(new ListSessionsRequest(null, null, new Pagination(200,null), null, null, null, null, null))->getSessions();
      $sessionListResponse = $client->getSessionsList(new ListSessionsRequest(null, null, new Pagination(200,null), null, null, null, null, null));
      $sessions = $sessionListResponse->getListSessionsResponse()->getSessions();
      $sessions = $sessionListResponse->getSessions();
        foreach($sessions as $session)
        {
            echo "<pre>";print_r($session);echo "</pre>";
        }
    }
    catch(Exception $e)
    {
        echo "ERROR3: ".$e->getMessage();
    }

    echo "<hr/>";
    try
    {
        $recorders = $RRMClient->getRemoteRecordersList(new Pagination(200,null))->getRecorders();
        foreach($recorders as $recorder)
        {
            echo "<pre>";print_r($recorder);echo "</pre>";
        }
    }
    catch(Exception $e)
    {
        echo "ERROR4: ".$e->getMessage();
    }

    echo "<hr/>";
    $folder = new Folder();
    try
    {
        $folder = $SMClient->addFolder("Php folder!")->getFolder();
        echo "<pre>";print_r($folder);echo "</pre>";
    }
    catch(Exception $e)
    {
        echo $e->getMessage();
    }

    echo "<hr/>";
    try
    {
        $session = $SMClient->addSession("Php Session", $folder->Id!=null?$folder->Id:"4b7dd8b9-ae16-409b-bd98-aa29d66ebe3b")->getSession();
        echo "<pre>";print_r($session);echo "</pre>";
    }
    catch(Exception $e)
    {
        echo $e->getMessage();
    }

    echo "<hr/>";
    $guid = "";
    try
    {
        $start = new DateTime();$start->setDate(2017, 03, 27);$start->setTime(20, 05, 00);
        $end = new DateTime();$end->setDate(2017, 03, 27);$end->setTime(20, 55, 00);
        $recorderSettings = array();
        //Schedule a recording in HERB.G. CA
        $recorderSettings[] = new RecorderSettings("4299a690-38b8-4e8d-95d6-209c218faab8", true, false);
        $recorderSettings[] = new RecorderSettings("19fb6654-dc09-4e4d-9984-02d75f6a9fc9", false, false);
        $guid = $RRMClient->scheduleRecording("Php scheduled recording", "4b7dd8b9-ae16-409b-bd98-aa29d66ebe3b", $start->format("Y-m-d\TH:i:s"), $end->format("Y-m-d\TH:i:s"),$recorderSettings);
        echo "<pre>";print_r($guid);echo "</pre>";
    }
    catch(Exception $e)
    {
        echo $e->getMessage();
    }

    //echo "<hr/>".$guid->getGuid();
    try
    {
        $daysOfWeek = array();
        $daysOfWeek[] = DayOfWeek::Monday;
        $daysOfWeek[] = DayOfWeek::Wednesday;
        $end = new DateTime();$end->setDate(2013, 04, 24);$end->setTime(20, 55, 00);
        //$guids = $RRMClient->scheduleRecurringRecording($guid->getGuid(), $daysOfWeek, $end->format("Y-m-d\TH:i:s"));
        //echo "<pre>";print_r($guids->getGuids());echo "</pre>";
    }
    catch(Exception $e)
    {
        echo $e->getMessage();
    }

    echo "<hr/>";
    try
    {
        $startDateAndTime = new DateTime();$startDateAndTime->setDate(2013, 03, 27);$startDateAndTime->setTime(20, 05, 00);
        $endDateAndTime = new DateTime();$endDateAndTime->setDate(2013, 03, 27);$endDateAndTime->setTime(20, 55, 00);
        $repeatingDaysOfWeek = array();
        $repeatingDaysOfWeek[] = DayOfWeek::Monday;
        $repeatingDaysOfWeek[] = DayOfWeek::Wednesday;
        $recorderSettings = array();
        //Schedule a recording in HERB.G. CA
        $recorderSettings[] = new RecorderSettings("4299a690-38b8-4e8d-95d6-209c218faab8", true, false);
        $recorderSettings[] = new RecorderSettings("19fb6654-dc09-4e4d-9984-02d75f6a9fc9", false, false);
        $endDateAndTimeOfRecurrence = new DateTime();$endDateAndTimeOfRecurrence->setDate(2013, 04, 24);$endDateAndTimeOfRecurrence->setTime(20, 55, 00);
        $guids = $RRMClient->scheduleNewRecurringRecording("Php scheduled recording", "4b7dd8b9-ae16-409b-bd98-aa29d66ebe3b", false, $startDateAndTime->format("Y-m-d\TH:i:s"), $endDateAndTime->format("Y-m-d\TH:i:s"), $repeatingDaysOfWeek, $endDateAndTimeOfRecurrence->format("Y-m-d\TH:i:s"), $recorderSettings);
        echo "<pre>";print_r($guids->getGuids());echo "</pre>";
    }
    catch(Exception $e)
    {
        echo $e->getMessage();
    }
?>
    </body>
</html>
