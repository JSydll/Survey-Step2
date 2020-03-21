<?php
/**
 * @file
 *
 * @author Joschka Seydell
 * @date 21.03.2020
 */
$ROOT = __DIR__ . "/..";

require_once "$ROOT/HttpException.php";

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
        // log "Data structure is not an associative array!<br>";
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
            // log "Missing key " . $expectedField . " in map.<br>";
            return false;
        }
        if (!HasExpectedType($map[$expectedField], $expectedType)) {
            // log "Key " . $expectedField . " has wrong type (" . $expectedType . ")<br>";
            return false;
        }
    }
    return true;
}
