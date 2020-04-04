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

require_once "$ROOT/HttpException.php";

class Step2
{
    // Private data members
    private $collector;
    private $processor;
    private $generator;

    private $results;

    /**
     * @brief Constructor with dependency injection for the concrete implementations
     *
     * @param collector The interface to the external survey tool.
     * @param proc Processor of raw data to turn it into something meaningful for the ResultGenerator.
     * @param gen ResultGenerator
     */
    public function __construct(IDataCollector $collector, IProcessor $proc, IResultGenerator $gen)
    {
        $this->collector = $collector;
        $this->processor = $proc;
        $this->generator = $gen;
    }

    /**
     * @brief
     */
    public function Run(int $surveyId, int $responseId, bool $validate = true)
    {
        if (empty($responseId) or empty($surveyId)) {
            throw new HttpException(
                "Request has responseId or surveyId not set!",
                HttpStatusCode::BAD_REQUEST
            );
        }
        $rawData = $this->collector->Fetch($surveyId, $responseId);
        $evaluatedData = $this->processor->Process($rawData, $validate);
        $this->results = $this->generator->Generate($evaluatedData, $validate);
    }

    public function GetResults()
    {
        return $this->results;
    }
}
