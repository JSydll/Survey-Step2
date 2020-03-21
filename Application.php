<?php
/**
 * @file
 *
 * @author Joschka Seydell
 * @date 07.03.2020
 */
$ROOT = __DIR__;
require_once "$ROOT/interfaces/IDataCollector.php";
require_once "$ROOT/interfaces/IProcessor.php";
require_once "$ROOT/interfaces/IResultGenerator.php";

require_once "$ROOT/utility/ServerVariables.php";

class Application
{
    // Private data members
    private $collector;
    private $processor;
    private $generator;

    private $resultFile;

    /**
     * @brief Constructor with dependency injection for the concrete implementations
     * @param collector The interface to the external survey tool.
     * @param proc Processor of raw data to turn it into something meaningful for the ResultGenerator.
     * @param gen ResultGenerator
     */
    public function __construct(IDataCollector $collector, IProcessor $proc, IResultGenerator $gen)
    {
        $this->collector = $collector;
        $this->processor = $proc;
        $this->generator = $gen;

        $token = GetVar("token");
        $surveyId = GetVar("sid");
        if (empty($token) or empty($surveyId)) {
            echo "<b>Token or surveyId not set! </b><br>";
            return;
        }
        $rawData = $this->collector->FetchData(intval($surveyId), $token);
        $evaluatedData = $this->processor->Process($rawData);
        $this->resultFile = $this->generator->GenerateFile($evaluatedData);
        echo "Got result file '" . $this->resultFile . "'.<br>";
    }

    public function GetResultFile()
    {
        return $this->resultFile;
    }
}
