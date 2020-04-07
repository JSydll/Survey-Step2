<?php
/**
 * @file
 *
 * @author Joschka Seydell
 * @date 07.03.2020
 */
require_once __DIR__ . "/../Step2.php";

class GenerationScript implements Step2\IScriptCallable
{
    public function Run(array &$data): array
    {
        $files = ["page1.pdf", "page2.pdf"];
        return $files;
    }
}
