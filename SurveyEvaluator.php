<?php
/**
 * @file
 *
 * @author Joschka Seydell
 * @date 07.03.2020
 */
require_once __DIR__ . "/interfaces/IDataCollector.php";
require_once __DIR__ . "/interfaces/IProcessor.php";
require_once __DIR__ . "/interfaces/IResultGenerator.php";

require_once __DIR__ . "/utility/ServerVariables.php";

class SurveyEvaluator
{
    // Private data members
    private $data;
    private $processor;
    private $generator;

    private $resultFile;

    /**
     * @brief Constructor with dependency injection for the concrete implementations
     * @param data The interface to the external survey tool.
     * @param proc Processor of raw data to turn it into something meaningful for the ResultGenerator.
     * @param gen ResultGenerator
     */
    public function __construct(IDataCollector $data, IProcessor $proc, IResultGenerator $gen)
    {
        $this->data = $data;
        $this->processor = $proc;
        $this->generator = $gen;

        $token = GetVar("token");
        $surveyId = GetVar("sid");
        if (empty($token) or empty($surveyId)) {
            echo "<b>Token or surveyId not set! </b><br>";
            return;
        }
        $rawData = $this->data->FetchData(intval($surveyId), $token);
        $evaluatedData = $this->processor->Process($rawData);
        $this->resultFile = $this->generator->GenerateFile($evaluatedData);
        echo "Got result file '" . $this->resultFile . "'.<br>";
    }

    public function GetHtml()
    {
        $html = "<div>RESULTS WILL BE HERE!</div>";
        return $html;
    }

    private function GetTokenFromUrl()
    {
        return "some-token";
    }

    private function GetSurveyIdFromUrl()
    {

    }
}
