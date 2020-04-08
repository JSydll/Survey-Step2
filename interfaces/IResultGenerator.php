<?php
/**
 * @file Interface definition for creating a file on the basis of the processed survey data.
 *
 * @author Joschka Seydell
 * @date 07.03.2020
 */
namespace Step2;

abstract class DataObject
{
    public function __get($propertyName)
    {
        return $this->$propertyName;
    }
}

/**
 *
 */
interface IResultGenerator
{
    /**
     * @brief Generates a custom tailored result.
     *
     * @note Throws an exception if the reuslt cannot be generated.
     *
     * @param evaluatedData Used to determine how to generate the result.
     * @return result Actual result data (differs on used implementation)
     */
    public function Generate(array $evaluatedData): DataObject;
}
