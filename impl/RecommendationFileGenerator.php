<?php
/**
 * @file Implements generation of a recommendations file
 *
 * @author Joschka Seydell
 * @date 07.03.2020
 */
require_once __DIR__ . "/../interfaces/IResultGenerator.php";

require_once __DIR__ . "/schema/Validation.php";
require_once __DIR__ . "/../pdf/PdfMerger.php";

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
        echo "Creating result file...<br>";
        $merger = new PdfMerger();
        return $merger->MergeFiles(["page1.pdf", "page2.pdf"]);
    }
}
