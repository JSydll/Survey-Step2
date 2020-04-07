<?php
/**
 * @file
 *
 * @author Joschka Seydell
 * @date 21.03.2020
 */

/**
 * @brief Tries to get a variable from the URL
 */
function GetVar($name)
{
    if (!isset($_GET[$name])) {
        return null;
    }
    return $_GET[$name];
}
