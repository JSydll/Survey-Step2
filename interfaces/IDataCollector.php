<?php
/**
 * @file Interface definition for connections to external survey tools.
 *
 * @author Joschka Seydell
 * @date 07.03.2020
 */

interface IDataCollector
{
    /**
     * @brief Fetches data from an external survey tool given a token.
     *
     * @note Throws an exception if something fails in the process of
     * acquiring the data.
     *
     * @param surveyId Id of the survey.
     * @param responseId Id used to fetch the individual response from the external tool.
     * @return rawData Map of the data for all fields in the survey.
     */
    public function Fetch(int $surveyId, int $responseId): array;
}
