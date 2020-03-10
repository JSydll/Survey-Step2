<?php
/**
 * @file Implements generation of a recommendations file
 *
 * @author Joschka Seydell
 * @date 07.03.2020
 */
$ROOT = __DIR__ . "/../..";
require_once "$ROOT/interfaces/IResultGenerator.php";

require_once "$ROOT/schema/Validation.php";
require_once "$ROOT/utility/PdfMerger.php";

class FileGenerator implements IResultGenerator
{
    // Private members
    private $schemaFile;
    private $contentPath;
    
    public function __construct($schemaFile, $contentPath)
    {
        $this->schemaFile = $schemaFile;
        $this->contentPath = $contentPath;
    }

    public function GenerateFile($evaluatedData): string
    {
        echo "FileGenerator got as evaluated data:<br>";
        foreach ($evaluatedData as $key => $val) {
            echo $key . ": " . $val . "<br>";
        }
        if (!Validate($evaluatedData, $this->schemaFile)) {
            echo "<b>Schema validation failed!</b><br>";
        }
        echo "Creating result file...<br>";
        $merger = new PdfMerger($this->contentPath);
        return $merger->MergeFiles(["page1.pdf", "page2.pdf"]);
    }
}
