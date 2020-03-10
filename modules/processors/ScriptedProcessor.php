<?php
/**
 * @file Implements calculating a persons characteristics from the raw survey data
 *
 * @author Joschka Seydell
 * @date 07.03.2020
 */
$ROOT = __DIR__ . "/../..";
require_once "$ROOT/interfaces/IProcessor.php";
require_once "$ROOT/schema/Validation.php";

class ScriptedProcessor implements IProcessor
{
    // Private members
    private $schema;

    public function __construct($schema)
    {
        $this->schema = $schema;
    }

    public function Process($rawData)
    {
        echo "ScriptedProcessor got as raw data:<br>";
        foreach ($rawData as $key => $val) {
            echo $key . ": " . $val . "<br>";
        }
        if (!Validate($rawData, $this->schema)) {
            echo "<b>Schema validation failed!</b><br>";
        }
        $evaluatedData = array("eval1" => 4, "eval2" => 1);
        return $evaluatedData;
    }
}
