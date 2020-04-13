<?php
/**
 * @file Implements generation of a recommendations file
 *
 * @author Joschka Seydell
 * @date 07.03.2020
 */
namespace Step2;

require_once __DIR__ . "/../../Step2.php";

class Container extends DataObject
{
    public $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }
}

class ScriptedGenerator implements IResultGenerator
{
    // Private members
    private $schemaFile;
    private $scriptCallable;

    /**
     * @brief
     * @param schemaFile Used to validate the preprocessed data passed in the Generate(...) method.
     * @param scriptCallable A callable implementation of the script to be executed to
     */
    public function __construct(string $schemaFile, IScriptCallable &$scriptCallable)
    {
        $this->schemaFile = $schemaFile;
        $this->scriptCallable = $scriptCallable;
    }

    /**
     * @brief
     */
    public function Generate(array $processedData, bool $validate = true): DataObject
    {
        if ($validate and !Validate($processedData, $this->schemaFile)) {
            throw new HttpException(
                "Schema validation of raw data using '" . $this->schema . "' failed!",
                HttpStatusCode::INTERNAL_ERR
            );
        }

        $assocArray = $this->scriptCallable->Run($processedData);

        return new Container($assocArray);
    }
}
