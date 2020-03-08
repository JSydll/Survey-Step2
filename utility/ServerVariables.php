<?php

function GetVar($name)
{
    if (!isset($_GET[$name])) {
        echo "URL variable '" . $name . "' not set!<br>";
        return "";
    }
    return $_GET[$name];
}
