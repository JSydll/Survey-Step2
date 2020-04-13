<?php
/**
 * @file Implements generation of a recommendations file
 *
 * @author Joschka Seydell
 * @date 07.03.2020
 */
namespace Step2;

require_once __DIR__ . "/../../Step2.php";

class Map extends DataObject
{
    public $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }
}

class Pair
{
    public $key;
    public $value;

    public function __construct(string &$key, &$value)
    {
        $this->key = $key;
        $this->value = $value;
    }
}

function CreateNestedPair(string &$key, &$value): Pair
{
    if (IsAssociativeArray($value)) {
        $subvalues = [];
        foreach ($value as $subkey => $subvalue) {
            $subvalues[] = CreateNestedPair($subkey, $subvalue);
        }
        return new Pair($key, $subvalues);
    }
    return new Pair($key, $value);
}

function CreateMap(array &$assocArray): Map
{
    if (!IsAssociativeArray($assocArray)) {
        throw new HttpException(
            "Script did not return an associative array.",
            HttpStatusCode::INTERNAL_ERR
        );
    }
    $values = [];
    foreach ($assocArray as $key => $value) {
        $values[] = CreateNestedPair($key, $value);
    }
    return new Map($values);
}

class MapGenerator extends ScriptedGenerator
{

    /**
     * @brief
     * @param schemaFile Used to validate the preprocessed data passed in the Generate(...) method.
     * @param scriptCallable A callable implementation of the script to be executed to
     */
    public function __construct(string $schemaFile, IScriptCallable &$scriptCallable)
    {
        parent::__construct($schemaFile, $scriptCallable);
    }

    /**
     * @brief
     */
    public function Generate(array $processedData, bool $validate = true): DataObject
    {
        $container = parent::Generate($processedData, $validate);
        return CreateMap($container->items);
    }
}
