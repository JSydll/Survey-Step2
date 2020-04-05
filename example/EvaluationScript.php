<?php
/**
 * @file
 *
 * @author Joschka Seydell
 * @date 07.03.2020
 */
require_once __DIR__ . "/../Step2.php";

class EvaluationScript implements Step2\IScriptCallable
{
    public function Run(array &$data): array
    {
        return $data;
    }
}
