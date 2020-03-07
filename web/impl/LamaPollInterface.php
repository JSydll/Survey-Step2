<?php
/**
 * @file Implements a data interface for the LamaPoll survey tool
 * 
 * @author Joschka Seydell
 * @date 07.03.2020
 */
require_once("Dependencies.inc.php");

class LamaPollInterface implements IDataInterface 
{
    public function FetchData($token)
    {
        echo "LamaPollInterface fetching data from LamaPoll...<br>";
        return array("field1" => 1, "field2" => "something");
    }
}


?>