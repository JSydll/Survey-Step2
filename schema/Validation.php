<?php
/**
 * @file
 *
 * @author Joschka Seydell
 * @date 21.03.2020
 */
$ROOT = __DIR__ . "/..";

require_once "$ROOT/HttpException.php";
require_once "$ROOT/Logger.php";

function ParseSchema($schemaFile)
{
    return parse_ini_file($schemaFile);
}

function IsAssociativeArray($arr)
{
    return (array_keys($arr) !== range(0, count($arr) - 1));
}

function HasExpectedType($value, $typename)
{
    return (gettype($value) == $typename);
}

function Validate($map, $schemaFile)
{
    if (!IsAssociativeArray($map)) {
        Logger::Log()->Error("Data structure is not an associative array!");
        return false;
    }
    $schema = ParseSchema($schemaFile);
    if (!$schema) {
        throw new HttpException(
            "Schema '" . $schemaFile . "' could not be parsed.",
            HttpStatusCode::INTERNAL_ERR
        );
        return false;
    }
    foreach ($schema as $expectedField => $expectedType) {
        if (!array_key_exists($expectedField, $map)) {
            Logger::Log()->Error("Missing key " . $expectedField . " in map.");
            return false;
        }
        if (!HasExpectedType($map[$expectedField], $expectedType)) {
            Logger::Log()->Error("Key " . $expectedField . " has wrong type (" . $expectedType . ")");
            return false;
        }
    }
    return true;
}
