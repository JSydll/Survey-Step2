
<?php
// During development: Display all errors.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$Step2 = __DIR__ . "/..";
require_once "$Step2/Logger.php";
require_once "$Step2/HttpException.php";
require_once "$Step2/ExceptionHandler.php";
require_once "$Step2/modules/collectors/LimeSurveyCollector.php";
require_once "$Step2/modules/processors/ScriptedProcessor.php";
require_once "$Step2/modules/result-generators/FileGenerator.php";
require_once "$Step2/Step2.php";

require_once __DIR__ . "/UrlVariables.php";

Logger::Configure("./", "SurveyStep2-SampleApplication");
set_exception_handler('LogException');

$collect = new LimeSurveyCollector("http://localhost/limesurvey/index.php?r=", "admin", "admin");
$proc = new ScriptedProcessor("$Step2/schema/raw.ini");
$gen = new FileGenerator("$Step2/schema/evaluated.ini", "$Step2/content/pdf");
$step2 = new Step2($collect, $proc, $gen);

$responseId = GetVar("response");
$surveyId = GetVar("sid");
// Run the evaluation (without validation)
$step2->Run(intval($surveyId), intval($responseId), false);

?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <?php Logger::Log()->Info("Processed request for survey '$surveyId' and responseId '$responseId'.");?>
    <h1>Step2 Test</h1>
    <?php echo "Generated file: " . $step2->GetResultFile(); ?>
</body>
</html>