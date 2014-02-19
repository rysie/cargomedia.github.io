<?php

/**
 * Social class
 * 
 * Retrieving user data, user's friends,
 * friends of friends and suggested friends
 * 
 * Default setting: 
 * Minimum 2 common friends to find suggested users ($_minCommon = 2)
 * Can be overriden externally by calling setMinCommon method
 * 
 * 
 * @author Marcin Maciński
 */
require_once("vDB.php");

class Social extends vDB {

    /**
     * --------------
     * VARS
     * --------------
     */
    protected $_user;
    protected $_minCommon = 2;

    /**
     * --------------
     * GETTERS
     * --------------
     */

    /**
     * Select user by ID
     * 
     * @param int
     * 
     * @return object
     * @throws Exception
     */
    public function getUser($id = false) {
        if ($id === false)
            throw new Exception("vDB: give user ID");

        $result = $this->select("id", $id);

        if (sizeof($result))
            $this->_user = $result[0];
        else
            $this->_user = false;

        return $this->_user;
    }

    /**
     * Gets friends of selected user
     * 
     * @param int $id
     * @return array
     */
    public function getFriends($id = false) {
        $this->getUser($id);

        if (!is_array($this->_user->friends))
            return array();
        else
            return $this->_user->friends;
    }

    /**
     * Gets friends of friends
     * Based on getFriends and hasFriends methods
     * 
     * @param int $id
     * @return array
     */
    public function getFriendsOfFriends($id = false) {
        $fof = array();
        foreach ($this->getFriends($id) as $friend) {
            $friendsArray = $this->getFriends($friend);
            foreach ($friendsArray as $f) {
                // friends of friends, no direct connection to user
                if (!$this->hasFriend($id, $f))
                    $fof[$f] = $f;
            }
        }

        return $fof;
    }

    /**
     * Checks if user has given friend in his friends' array
     * 
     * @param int $id
     * @param int $friend_id
     * @return boolean
     */
    private function hasFriend($id, $friend_id) {
        $friends = $this->getFriends($id);
        if (!$friends)
            return false;
        return in_array($friend_id, $friends);
    }

    /**
     * Gets an array of suggested friends
     * Minimum $_minCommon friends needed
     * 
     * @param int $id
     * @return array
     */
    public function getSuggestedFriends($id) {
        $suggested = array();

        $myFriends = $this->getFriends($id);
        $myFoF = $this->getFriendsOfFriends($id);

        foreach ($myFoF as $fof) {
            if (sizeof(array_intersect($myFriends, $this->getFriends($fof))) >= $this->_minCommon && $fof != $id)
                $suggested[] = $fof;
        }

        return $suggested;
    }

    /**
     * --------------
     * SETTERS
     * --------------
     */

    /**
     * Overrides minimum common friends variable
     * Used by getSuggestedFriends method
     * 
     * @param int $mc
     * @throws Exception
     * 
     */
    public function setMinCommon($mc) {
        if (!is_int($mc))
            throw new Exception("Social: provide minimum common friends");
        if ($mc < 2)
            throw new Exception("Social: minimum common friends value should be > 1");

        $this->$_minCommon = $mc;
    }

}
