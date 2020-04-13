<?php
// During development: Display all errors.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../../Step2.php";
require_once "EvaluationScript.php";
require_once "GenerationScript.php";

Step2\Logger::Configure("./", "Step2Api");
// Global exception handler for all non-caught exceptions
set_exception_handler("Step2\LogException");

$collect = new Step2\LimeSurveyCollector("http://localhost/limesurvey/index.php?r=", "admin", "admin");

$evalScript = new EvaluationScript();
$proc = new Step2\ScriptedProcessor("$location/schema/raw.ini", $evalScript);

$genScript = new GenerationScript();
$gen = new Step2\MapGenerator("$location/schema/evaluated.ini", $genScript);

$exec = new Step2\Executor($collect, $proc, $gen, false);

$sender = new Step2\EmailContact("admin@example.com", "The Admin");
$mailer = new Step2\Mailer("smtp-server.host.com", 587, "sample-user", "sample-pass", $sender);

$step2api = new Step2\Api($exec, $mailer, '/survey-step2/examples/api');
