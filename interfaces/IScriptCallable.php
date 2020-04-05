<?php
/**
 * @file Interface definition for a processor for the raw data retreived a the survey tool.
 *
 * @author Joschka Seydell
 * @date 07.03.2020
 */
namespace Step2;

/**
 *
 */
interface IScriptCallable
{
    /**
     * @param input
     */
    public function Run(array &$input): array;
}
