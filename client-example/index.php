
<?php
// During development: Display all errors.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../Step2.php";

require_once "UrlVariables.php";
require_once "EvaluationScript.php";
require_once "GenerationScript.php";

Step2\Logger::Configure("./", "SurveyStep2-SampleApplication");
set_exception_handler("Step2\LogException");

$collect = new Step2\LimeSurveyCollector("http://localhost/limesurvey/index.php?r=", "admin", "admin");

$evalScript = new EvaluationScript();
$proc = new Step2\ScriptedProcessor("$location/schema/raw.ini", $evalScript);

$genScript = new GenerationScript();
$gen = new Step2\FileGenerator("$location/schema/evaluated.ini", "$location/content/pdf", $genScript);

$exec = new Step2\Executor($collect, $proc, $gen, false);

$responseId = GetVar("response");
$surveyId = GetVar("sid");
// Run the evaluation (without validation)
$exec->Run(intval($surveyId), intval($responseId));

?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <?php Step2\Logger::Log()->Info("Processed request for survey '$surveyId' and responseId '$responseId'.");?>
    <h1>Step2 Test</h1>
    <?php echo "Generated file: " . $exec->GetResults()->Get("resultFileUrl"); ?>
</body>
</html>