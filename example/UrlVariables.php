<?php
/**
 * @file
 *
 * @author Joschka Seydell
 * @date 21.03.2020
 */
$ROOT = __DIR__ . "/..";
require_once "$ROOT/HttpException.php";

/**
 * @brief Tries to get a variable from the URL
 */
function GetVar($name)
{
    if (!isset($_GET[$name])) {
        throw new HttpException(
            "URL variable '" . $name . "' not set!",
            HttpStatusCode::BAD_REQUEST
        );
    }
    return $_GET[$name];
}
