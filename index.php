
<?php
// During development: Display all errors.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$ROOT = __DIR__;
require_once "$ROOT/modules/collectors/LimeSurveyCollector.php";
require_once "$ROOT/modules/processors/ScriptedProcessor.php";
require_once "$ROOT/modules/result-generators/FileGenerator.php";
require_once "$ROOT/Application.php";

$collect = new LimeSurveyCollector("http://localhost/limesurvey/index.php?r=", "admin", "admin");
$proc = new ScriptedProcessor("$ROOT/schema/raw.ini");
$gen = new FileGenerator("$ROOT/schema/evaluated.ini", "$ROOT/content/pdf");
$app = new Application($collect, $proc, $gen);

?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <h1>Application Test</h1>
    <?php echo "Generated file: ".$app->GetResultFile(); ?>
</body>
</html>