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

require_once "$ROOT/HttpException.php";

class ScriptedProcessor implements IProcessor
{
    // Private members
    private $schema;

    /**
     * @brief
     */
    public function __construct($schema)
    {
        $this->schema = $schema;
    }

    /**
     * @brief
     */
    public function Process($rawData, bool $validate = true)
    {
        if ($validate and !Validate($rawData, $this->schema)) {
            throw new HttpException(
                "Schema validation of raw data using '" . $this->schema . "' failed!",
                HttpStatusCode::INTERNAL_ERR
            );
        }
        // Do something here
        $evaluatedData = array("eval1" => 4, "eval2" => 1);
        return $evaluatedData;
    }
}
