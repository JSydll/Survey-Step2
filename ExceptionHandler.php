<?php
/**
 * @file
 *
 * @author Joschka Seydell
 * @date 21.03.2020
 */
$ROOT = __DIR__;
require_once "$ROOT/Logger.php";

/**
 *
 */
function LogException($exception)
{
    switch ($exception->getCode()) {
        case HttpStatusCode::BAD_REQUEST:
            Logger::Log()->Error("[" . $exception->getCode() . "] " . $exception->getMessage());
            break;
        case HttpStatusCode::UNAUTHORIZED:
        case HttpStatusCode::FORBIDDEN:
        case HttpStatusCode::NOT_FOUND:
            Logger::Log()->Warning("[" . $exception->getCode() . "] " . $exception->getMessage());
            break;
        default:
            Logger::Log()->Info("[" . $exception->getCode() . "] " . $exception->getMessage());
    }
}
