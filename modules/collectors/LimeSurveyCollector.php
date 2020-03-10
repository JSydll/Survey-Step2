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

class LimeSurveyCollector implements IDataCollector
{
    // Data members
    private $rpcClient;
    private $sessionKey;

    public function __construct($baseUrl, $user, $pass)
    {
        $this->rpcClient = new \org\jsonrpcphp\JsonRPCClient($baseUrl . 'admin/remotecontrol');
        $this->sessionKey = $this->rpcClient->get_session_key($user, $pass);
    }

    public function __destruct()
    {
        $this->rpcClient->release_session_key($this->sessionKey);
    }

    public function FetchData($surveyId, $token)
    {
        echo "LimeSurveyCollector fetching data from LimeSurvey...<br>";

        $result = base64_decode($this->rpcClient->export_responses_by_token($this->sessionKey, $surveyId, "json", $token, null, "complete", "full"));
        var_dump($result);

        $data = array("field_name1" => "something", "field_name2" => 1, "field_name3" => true);
        return $data;
    }
}
