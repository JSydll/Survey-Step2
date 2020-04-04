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
require_once __DIR__ . "/PdfMerger.php";

class FileGenerator implements IResultGenerator
{
    // Private members
    private $schemaFile;
    private $contentPath;

    /**
     * @brief
     */
    public function __construct($schemaFile, $contentPath)
    {
        $this->schemaFile = $schemaFile;
        $this->contentPath = $contentPath;
    }

    /**
     * @brief
     */
    public function Generate($evaluatedData, bool $validate = true): string
    {
        if ($validate and !Validate($evaluatedData, $this->schemaFile)) {
            throw new HttpException(
                "Schema validation of raw data using '" . $this->schema . "' failed!",
                HttpStatusCode::INTERNAL_ERR
            );
        }
        $merger = new PdfMerger($this->contentPath);
        // Do something special here
        $fileName = $merger->MergeFiles(["page1.pdf", "page2.pdf"]);

        if (empty($fileName)) {
            throw new HttpException(
                "Failed to generate result file!",
                HttpStatusCode::INTERNAL_ERR
            );
        }
        return $fileName;
    }
}
