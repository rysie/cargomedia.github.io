<?php

/**
 * Virtual database handler
 * 
 * Reading and parsing provided JSON
 * SELECT method added for retrieving records
 * 
 * @author Marcin Maciński
 * 
 */
require_once("/helpers/content.php");

class vDB {

    /** @var array|null */
    protected $_db = null;

    /** @var array|null */
    protected $_dbmodel = array();

    /**
     * Constructor reads provided JSON file
     * Unifies data model
     * Stores data in $_db
     * 
     * @param string $file
     * @throws Exception
     */
    public function __construct($file = false) {
        if (!$file)
            throw new Exception("vDB: no filename given");
        if (!file_exists($file))
            throw new Exception("vDB: can't find file {$file}");

        $this->_db = json_decode(file_get_contents($file));

        if (!$this->_db || sizeof($this->_db) === 0)
            throw new Exception("vDB: {$file} doesn't contain any records");

        if (!$this->unifyModel())
            throw new Exception("vDB: can't unify data model");
    }

    /**
     * Data model unification
     * Sets _dbmodel array
     * Creates missing properties for each object (sets null if non-existent)
     * 
     * @return boolean
     */
    private function unifyModel() {
        $indices = array();

        foreach ($this->_db as $entry) {
            foreach ($entry as $property => $val) {
                $this->_dbmodel[$property] = true;

                if ($property == "id")
                    if (!array_key_exists($val, $indices))
                        $indices[$val] ++;
                    else
                        throw new Exception("vDB: duplicated index");
            }
        }

        foreach ($this->_db as &$entry) {
            foreach ($this->_dbmodel as $property => $val) {
                if (!property_exists($entry, $property))
                    $entry->$property = null;
            }
        }
        return true;
    }


    /**
     * Select defined property from DB
     * matching $where condition
     * 
     * @param int $property
     * @param int $value condition
     * 
     * @return mixed
     * @throws Exception
     */
    public function select($property = false, $value = false) {
        if (!array_key_exists($property, $this->_dbmodel))
            throw new Exception("vDB: undefined property {$property} selected");

        if ($value === false)
            throw new Exception("vDB: give property value to match");

        $result = array();

        foreach ($this->_db as $entry) {
            if ($entry->$property == $value)
                array_push($result, $entry);
        }

        return $result;
    }

}
