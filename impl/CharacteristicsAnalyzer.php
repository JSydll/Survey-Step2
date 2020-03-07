<?php
/**
 * @file Implements calculating a persons characteristics from the raw survey data
 *
 * @author Joschka Seydell
 * @date 07.03.2020
 */
require_once __DIR__ . "/../interfaces/IProcessor.php";
require_once __DIR__ . "/schema/Validation.php";

class CharacteristicsAnalyzer implements IProcessor
{
    public function Process($rawData)
    {
        echo "CharacteristicsAnalyzer got as raw data:<br>";
        foreach ($rawData as $key => $val) {
            echo $key . ": " . $val . "<br>";
        }
        if (!Validate($rawData, __DIR__ . "/schema/raw.ini")) {
            echo "<b>Schema validation failed!</b><br>";
        }
        $evaluatedData = array("eval1" => 4, "eval2" => 1);
        return $evaluatedData;
    }
}
