<?php
/**
 * @file Implements a data interface for the LamaPoll survey tool
 *
 * @author Joschka Seydell
 * @date 07.03.2020
 */
$ROOT = __DIR__ . "/../..";
require_once "$ROOT/interfaces/IDataCollector.php";
require_once "$ROOT/schema/Validation.php";

require_once "$ROOT/vendor/autoload.php";
require_once "$ROOT/HttpException.php";

/**
 * @brief Implements the collection of raw survey data from LimeSurvey.
 *
 * Only works for non-anonymous surveys using tokens to authenticate participants
 * as only then the results can be queried individually and correlated to the requesting user.
 *
 * Also, remote access to the survey must be enabled (see settings for the Remote Control API).
 */
class LimeSurveyCollector implements IDataCollector
{
    // Data members
    private $rpcClient;
    private $sessionKey;

    /**
     * @brief Constructs the LimeSurveyCollector and establishes the connection to LimeSurvey.
     *
     * @param baseUrl Url where the LimeSurvey instance is hosted.
     * @param user User name used to connect to LimeSurvey's Remote Control API.
     * @param pass Password for the user.
     */
    public function __construct($baseUrl, $user, $pass)
    {
        $this->rpcClient = new \org\jsonrpcphp\JsonRPCClient($baseUrl . 'admin/remotecontrol');
        $this->sessionKey = $this->rpcClient->get_session_key($user, $pass);
        if (empty($this->sessionKey)) {
            throw new HttpException(
                "Could not get session key for connection to LimeSurvey instance at '" . $baseUrl . "'",
                HttpStatusCode::UNAUTHORIZED
            );
        }
    }

    /**
     * @brief Disconnects from the given LimeSurvey instance
     */
    public function __destruct()
    {
        $this->rpcClient->release_session_key($this->sessionKey);
    }

    /**
     * @brief Fetches the survey result for a single survey participant.
     */
    public function Fetch($surveyId, $token)
    {
        $result = base64_decode(
            $this->rpcClient->export_responses_by_token(
                $this->sessionKey, $surveyId, "json",
                $token, null, "complete", "full"
            ));
        if (empty($result)) {
            throw new HttpException(
                "Could not fetch data for token '$token' from LimeSurvey.",
                HttpStatusCode::NOT_FOUND
            );
        }
        $parsed = $this->ParseSurveyData($result);

        return $parsed;
    }

    /**
     * @brief Parses the serialized result from the call to the Remote Control API
     * Expects data to be in a certain format.
     */
    private function ParseSurveyData(string &$serialized)
    {
        $parsed = json_decode($serialized, true);
        if ((!array_key_exists("responses", $parsed)) or
            (count($parsed["responses"]) != 1) or
            (count($parsed["responses"][0]) != 1) or
            (count(array_values($parsed["responses"][0])[0]) == 0)
        ) {
            throw new HttpException(
                "Got unexpected data from LimeSurvey: " . $serialized,
                HttpStatusCode::INTERNAL_ERR
            );
        }
        return array_values($parsed["responses"][0])[0];
    }
}
