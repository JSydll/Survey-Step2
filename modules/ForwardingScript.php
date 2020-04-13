<?php
/**
 * @file
 *
 * @author Joschka Seydell
 * @date 21.03.2020
 */
namespace Step2;

require_once __DIR__ . "/../Step2.php";

/**
 * @brief Callable implementation that simply forwards the data passed in to it.
 */
class ForwardingScriot implements IScriptCallable
{
    public function Run(array &$input): array {
        return $input;
    }
}