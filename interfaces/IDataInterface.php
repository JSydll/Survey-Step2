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
     * @param token Access token used to connect to the external tool.
     * @return rawData Map of the data for all fields in the survey.
     */
    public function FetchData($token);
}
