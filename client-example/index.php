
<?php
// During development: Display all errors.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../vendor/autoload.php";

require_once "UrlVariables.php";

$responseId = GetVar("response");
$surveyId = GetVar("sid");

if (empty($responseId) or empty($surveyId)) {
    throw new Exception("SurveyId or ResponseId not set as URL parameters!");
}

$client = new GuzzleHttp\Client();
$res = $client->request('GET', "http://localhost:80/survey-step2/api-example/survey/$surveyId/response/$responseId/results");

if ($res->getStatusCode() != "200") {
    throw new Exception("Expected successful api call but got " . $res->getStatusCode());
}

$parsed = json_decode((string) $res->getBody(), true);

?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <h1>Step2 Test</h1>
    <?php echo "Generated file: " . $parsed["resultFileUrl"]; ?>
</body>
</html>