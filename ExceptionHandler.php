<?php
/**
 * @file
 *
 * @author Joschka Seydell
 * @date 21.03.2020
 */
namespace Step2;

require_once "Step2.php";

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
            Logger::Log()->Error("[" . $exception->getCode() . "] " . $exception->getMessage()
                . " in " . $exception->getFile() . ":" . $exception->getLine());
    }
}
