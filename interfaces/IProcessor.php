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
interface IProcessor
{
    /**
     * @brief Processes the raw data and returns data structure that can be used to generate individualized results.
     *
     * @note Throws an exception if the processing fails.
     * 
     * @param rawData Raw data received from the external survey tool.
     * @return evaluatedData Map of result characteristics.
     */
    public function Process(array $rawData) : array;
}
