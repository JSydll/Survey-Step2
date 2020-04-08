<?php
/**
 * @file
 *
 * @author Joschka Seydell
 * @date 21.03.2020
 */
namespace Step2;

require_once "../Step2.php";

function ParseSchema(string &$schemaFile)
{
    return parse_ini_file($schemaFile);
}

function IsAssociativeArray($arr)
{
    if (!\is_array($arr)) {return false;}
    return (array_keys($arr) !== range(0, count($arr) - 1));
}

function HasExpectedType($value, string &$typename)
{
    return (gettype($value) == $typename);
}

function Validate(array &$map, string &$schemaFile)
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
