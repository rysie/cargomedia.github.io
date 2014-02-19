<?php

/**
 * 
 * Content helpers
 * 
 */
if (!function_exists("print_arr")) {

    /**
     * print_arr - user friendly print_r
     * @access	public
     * @param	array
     * @return	string
     */
    function print_arr($variabile, $text = '', $return = FALSE) {
        if ($text <> '')
            $text = " $text=";
        $type = gettype($variabile);
        switch ($type) {
            case "array":
            case "object":
                $out = "<p><pre>$text" . print_r($variabile, TRUE) . "</pre></p>";
                break;
            case "NULL":
                $str = "<p>NULL</p>";
            case "string":
            default:
                if (!isset($str))
                    $str = strval($variabile);
                $out = "<p><pre>#" . $type . "[" . strlen($str) . "]:$text" . ($str) . "</pre></p>";
                break;
        }
        if (!$return)
            echo $out;
        else
            return $out;
    }

}



