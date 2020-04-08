<?php
/**
 * @file
 *
 * @author Joschka Seydell
 * @date 07.03.2020
 */
namespace Step2;

require_once "Step2.php";

class Executor
{
    // Private data members
    private $collector;
    private $processor;
    private $generator;

    private $validationOn;

    private $results;

    /**
     * @brief Constructor with dependency injection for the concrete implementations
     *
     * @param collector The interface to the external survey tool.
     * @param proc Processor of raw data to turn it into something meaningful for the ResultGenerator.
     * @param gen ResultGenerator
     * @þaram validationOn
     */
    public function __construct(IDataCollector $collector, IProcessor $proc, IResultGenerator $gen, bool $validationOn = true)
    {
        $this->collector = $collector;
        $this->processor = $proc;
        $this->generator = $gen;
        $this->validationOn = $validationOn;
    }

    /**
     * @brief
     */
    public function Run(int $surveyId, int $responseId)
    {
        if (empty($responseId) or empty($surveyId)) {
            throw new HttpException(
                "Request has responseId or surveyId not set!",
                HttpStatusCode::BAD_REQUEST
            );
        }
        $rawData = $this->collector->Fetch($surveyId, $responseId);
        $evaluatedData = $this->processor->Process($rawData, $this->validationOn);
        $this->results = $this->generator->Generate($evaluatedData, $this->validationOn);
    }

    public function GetResults(): ISurveyResult
    {
        return $this->results;
    }
}
