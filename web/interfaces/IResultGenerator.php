<?php
/**
 * @file Interface definition for creating a file on the basis of the processed survey data.
 *
 * @author Joschka Seydell
 * @date 07.03.2020
 */

interface IResultGenerator
{
    /**
     * @brief Generates a custom tailored result file.
     *
     * @param evaluatedData Used to determine how to generate the result.
     * @return fileName Name of the generated result file
     */
    public function GenerateFile($evaluatedData): string;
}
