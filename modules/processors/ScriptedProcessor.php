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
    private $schemaFile;
    private $scriptFunctor;

    /**
     * @brief
     * @param schemaFile Used to validate the raw data passed in the Process(...) method.
     * @param scriptFunctor Method to be run to evaluate the data. Must match a signature
     * of function(array):array and pass a self-test with an empty array resulting in an
     * empty array itself.
     */
    public function __construct(string $schemaFile, callable $scriptFunctor)
    {
        $this->schemaFile = $schemaFile;
        // Self-test
        if (($scriptFunctor == null) or (!is_array($scriptFunctor([])))) {
            throw new HttpException(
                "No suitable script functor set for ScriptedProcessor.",
                HttpStatusCode::INTERNAL_ERR
            );
        }
        $this->scriptFunctor = $scriptFunctor;
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
        return call_user_func($this->scriptFunctor, $rawData);
    }
}
