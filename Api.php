<?php
/**
 * @file
 *
 * @author Joschka Seydell
 * @date 07.03.2020
 */
namespace Step2;

require_once "Step2.php";

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ErrorDescriptor
{
    public $description;

    public function __construct(string $descr)
    {
        $this->description = $descr;
    }
}

class Api
{
    private $exec;
    private $mailer;

    private $slim;

    public function __construct(Executor $executor, Mailer $mailer)
    {
        $this->exec = $executor;
        $this->mailer = $mailer;

        $this->slim = \Slim\Factory\AppFactory::create();
        $this->SetupEndpoints();
        $this->SetupErrorHandling();

        $this->slim->run();
    }

    private function ParseJsonRequest(Request &$request)
    {
        try
        {
            return $request->getParsedBody();
        } catch (Exception $e) {
            throw new HttpException(
                "Request contains no valid JSON.",
                HttpStatusCode::BAD_REQUEST
            );
        }
    }

    private function CreateErrorResponse(string $message, int $code, Response &$response)
    {
        Logger::Log()->Error($message);
        $response = $response->withHeader('Content-Type', 'application/json');
        $error = new ErrorDescriptor($message);
        $response->getBody()->write(\json_encode($error));
        return $response->withStatus($code);
    }

    private function SetupEndpoints()
    {
        // Valid endpoints
        $this->SetupGetSurveyResults();
        $this->SetupPostServicesSendmail();
    }

    private function SetupGetSurveyResults()
    {
        $this->slim->get('/survey/{sid}/response/{rid}/results', function (Request $request, Response $response, array $args) {
            try {
                $this->exec->Run(intval($args['sid']), intval($args['rid']));
                $results = $this->exec->GetResults();
                $response->getBody()->write(\json_encode($results));
                // Standard response code is 200 which is fine here.
                return $response->withHeader('Content-Type', 'application/json');
            } catch (HttpException $e) {
                return $this->CreateErrorResponse($e->getMessage(), $e->getCode(), $response);
            }
        });
    }

    private function SetupPostServicesSendmail()
    {
        $this->slim->post('/services/sendmail', function (Request $request, Response $response) {
            try {
                $payload = $this->ParseJsonRequest($request);
                if (empty($payload['recipients']) or empty($payload['subject']) or empty($payload['message'])) {
                    throw new HttpException(
                        "Request is missing important data fields.",
                        HttpStatusCode::BAD_REQUEST
                    );
                }
                $this->mailer->Send($payload['recipients'], $payload['subject'], $payload['message']);
                return $response;
            } catch (HttpException $e) {
                return $this->CreateErrorResponse($e->getMessage(), $e->getCode(), $response);
            }
        });
    }

    private function SetupErrorHandling()
    {
        $customErrorHandler = function (
            \Psr\Http\Message\ServerRequestInterface $request,
            \Throwable $e, bool $displayDetails, bool $log, bool $logDetails
        ) {
            $response = $this->slim->getResponseFactory()->createResponse();

            if ($e instanceof \Slim\Exception\HttpNotFoundException) {
                $message = "Route '" . $request->getUri()->getPath() . "' not found!";
                $code = HttpStatusCode::NOT_FOUND;
            } elseif ($e instanceof HttpException) {
                $message = $e->getMessage();
                $code = $e->getCode();
            } else {
                $message = $e->getMessage();
                $code = HttpStatusCode::INTERNAL_ERR;
            }
            return $this->CreateErrorResponse($message, $code, $response);
        };
        $errorMiddleware = $this->slim->addErrorMiddleware(true, true, true);
        $errorMiddleware->setDefaultErrorHandler($customErrorHandler);
    }
}
