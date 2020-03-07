<?php

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
        echo "Data structure is not an associative array!<br>";
        return false;
    }
    $schema = ParseSchema($schemaFile);
    if (!$schema) {
        echo "Schema could not be parsed! <br>";
        return false;
    }
    foreach ($schema as $expectedField => $expectedType) {
        if (!array_key_exists($expectedField, $map)) {
            echo "Missing key " . $expectedField . " in map.<br>";
            return false;
        }
        if (!HasExpectedType($map[$expectedField], $expectedType)) {
            echo "Key " . $expectedField . " has wrong type (" . $expectedType . ")<br>";
            return false;
        }
    }
    return true;
}
