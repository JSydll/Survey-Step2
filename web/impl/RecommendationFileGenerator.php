<?php
/**
 * @file Implements generation of a recommendations file
 *
 * @author Joschka Seydell
 * @date 07.03.2020
 */
require_once __DIR__ . "/../interfaces/IResultGenerator.php";
require_once __DIR__ . "/../utility/PdfMerger.php";
require_once __DIR__ . "/schema/Validation.php";

class RecommendationFileGenerator implements IResultGenerator
{
    public function GenerateFile($evaluatedData): string
    {
        echo "RecommendationFileGenerator got as evaluated data:<br>";
        foreach ($evaluatedData as $key => $val) {
            echo $key . ": " . $val . "<br>";
        }
        if (!Validate($evaluatedData, __DIR__ . "/schema/evaluated.ini")) {
            echo "<b>Schema validation failed!</b><br>";
        }
        return "some/temporary/file.pdf";
    }
}
