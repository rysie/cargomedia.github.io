<?php

if (is_array($_GET) && sizeof($_GET)) {

    require_once("helpers/content.php");
    require_once("classes/Social.php");

    // get default database file
    $social = new Social("data.json");


    // Check for array existence, needs to be more secure though
    if (array_key_exists("users", $_GET))
        switch ($_GET["users"]) {
            case "all":
                // get all users
                echo file_get_contents("data.json");
                break;
            case "fof":
                // get suggested friends only
                if (array_key_exists("fof", $_GET)) {
                    $suggestedFriends = $social->getSuggestedFriends($_GET["fof"], 2);
                    $friends = array();
                    foreach ($suggestedFriends as $friend) {
                        $friends[] = $social->getUser($friend);
                    }
                    echo json_encode($friends);
                }
                break;
        }
}
