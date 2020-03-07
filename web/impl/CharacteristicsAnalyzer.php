<?php
/**
 * @file Implements calculating a persons characteristics from the raw survey data
 * 
 * @author Joschka Seydell
 * @date 07.03.2020
 */
require_once("Dependencies.inc.php");

class CharacteristicsAnalyzer implements IProcessor 
{
    public function Process($rawData)
    {
        echo "CharacteristicsAnalyzer got as raw data:<br>";
        foreach($rawData as $key => $val)
        {
            echo $key.": ".$val."<br>";
        }
        return array("characteristic1" => 4, "characteristic2" => "something");
    }
}
?>