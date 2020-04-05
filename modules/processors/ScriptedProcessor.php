<?php
/**
 * @file Implements calculating a persons characteristics from the raw survey data
 *
 * @author Joschka Seydell
 * @date 07.03.2020
 */
namespace Step2;

require_once __DIR__ . "/../../Step2.php";

class ScriptedProcessor implements IProcessor
{
    // Private members
    private $schemaFile;
    private $scriptCallable;

    /**
     * @brief
     * @param schemaFile Used to validate the raw data passed in the Process(...) method.
     * @param scriptCallable A callable implementation of the script to be executed to 
     * actually process the data. 
     */
    public function __construct(string $schemaFile, IScriptCallable &$scriptCallable)
    {
        $this->schemaFile = $schemaFile;
        $this->scriptCallable = $scriptCallable;
    }

    /**
     * @brief
     */
    public function Process(array $rawData, bool $validate = true): array
    {
        if ($validate and !Validate($rawData, $this->schemaFile)) {
            throw new HttpException(
                "Schema validation of raw data using '" . $this->schemaFile . "' failed!",
                HttpStatusCode::INTERNAL_ERR
            );
        }
        return $this->scriptCallable->Run($rawData);
    }
}
