<?php
/**
 * @file Implements a data interface for the LamaPoll survey tool
 *
 * @author Joschka Seydell
 * @date 07.03.2020
 */
require_once __DIR__ . "/../interfaces/IDataCollector.php";
require_once __DIR__ . "/schema/Validation.php";

class LamaPollInterface implements IDataCollector
{
    public function FetchData($token)
    {
        echo "LamaPollInterface fetching data from LamaPoll...<br>";
        $data = array("field_name1" => "something", "field_name2" => 1, "field_name3" => true);
        return $data;
    }
}
