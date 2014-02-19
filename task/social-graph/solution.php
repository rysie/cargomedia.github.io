<!DOCTYPE html>
<!--

    Reads provided JSON file
    Displays friends, friends of friends and suggested friends

-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        require_once("helpers/content.php");
        require_once("classes/Social.php");

        $social = new Social("data.json");
        
        try {
            $friends = $social->getFriends(13);
            print_arr($friends);

            $friendsOfFriends = $social->getFriendsOfFriends(13);
            print_arr($friendsOfFriends);

            $suggestedFriends = $social->getSuggestedFriends(13, 2);
            print_arr($suggestedFriends);
        } catch (Exception $e) {
            // Exception should be handled properly
            print("Exception: " . $e->getMessage());
        }
        ?>
    </body>
</html>
