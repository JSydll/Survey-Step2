
<?php 
// During development: Display all errors.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("impl/LamaPollInterface.php");
require_once("impl/CharacteristicsAnalyzer.php");
require_once("impl/RecommendationFileGenerator.php");
require_once("SurveyEvaluator.php");

$data = new LamaPollInterface();
$proc = new CharacteristicsAnalyzer();
$gen = new RecommendationFileGenerator();
$evaluator = new SurveyEvaluator($data, $proc, $gen);

?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <div class="container" id="main-content">
        <h2>Welcome to my website!</h2>
        <p>Some content goes here! Let's go with the classic "lorem ipsum."</p>

        <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        </p>
        <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        </p>
    </div>
    <?php echo $evaluator->GetHtml(); ?>
</body>
</html>