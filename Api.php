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

    private function PopulateResponseFromException(HttpException $e, Response &$response)
    {
        $response = $response->withStatus($e->getCode());
        $error = new ErrorDescriptor($e->getMessage());
        $response->getBody()->write(\json_encode($error));
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
            } catch (HttpException $e) {
                LogException($e);
                $this->PopulateResponseFromException($e, $response);
            } finally {
                return $response->withHeader('Content-Type', 'application/json');
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
            } catch (HttpException $e) {
                LogException($e);
                $this->PopulateResponseFromException($e, $response);
            } finally {
                return $response->withHeader('Content-Type', 'application/json');
            }
        });
    }

    private function SetupErrorHandling()
    {
        $customErrorHandler = function (
            \Psr\Http\Message\ServerRequestInterface $request,
            \Throwable $exception,
            bool $displayErrorDetails,
            bool $logErrors,
            bool $logErrorDetails
        ) {
            $response = $this->slim->getResponseFactory()->createResponse();

            if ($exception instanceof \Slim\Exception\HttpNotFoundException) {
                $message = "Route '" . $request->getUri()->getPath() . "' not found!";
                $code = HttpStatusCode::NOT_FOUND;
            } elseif ($exception instanceof HttpException) {
                $message = $exception->getMessage();
                $code = $exception->getCode();
            } else {
                $message = $exception->getMessage();
                $code = HttpStatusCode::INTERNAL_ERR;
            }
            Logger::Log()->Warning($message);
            $response->getBody()->write($message);
            return $response->withStatus($code);
        };
        $errorMiddleware = $this->slim->addErrorMiddleware(true, true, true);
        $errorMiddleware->setDefaultErrorHandler($customErrorHandler);
    }
}
