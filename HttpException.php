<?php
/**
 * @file
 *
 * @author Joschka Seydell
 * @date 21.03.2020
 */
namespace Step2;

/**
 * @brief
 */
abstract class HttpStatusCode
{
    const OK = 200;
    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const INTERNAL_ERR = 500;

    /**
     * @brief Converts a status code into it's string representation
     */
    public function ToString(int $code)
    {
        switch ($code) {
            case OK:return "OK";
            case BAD_REQUEST:return "Bad request";
            case UNAUTHORIZED:return "Unauthorized";
            case FORBIDDEN:return "Forbidden";
            case NOT_FOUND:return "Not found";
            case INTERNAL_ERROR:return "Internal error";
            default:return "Unknown";
        }
    }
}

class HttpException extends \Exception
{
    /**
     * @brief Constuctor enforcing the message and code to be set.
     */
    public function __construct(string $message, int $code, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
