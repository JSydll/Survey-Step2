<?php
/**
 * @file Implements generation of a recommendations file
 *
 * @author Joschka Seydell
 * @date 07.03.2020
 */
namespace Step2;

require_once __DIR__ . "/../../Step2.php";

require_once "PdfMerger.php";

class FileResult implements ISurveyResult
{
    public $resultFileUrl;

    public function __construct(string $fileUrl)
    {
        $this->resultFileUrl = $fileUrl;
    }

    public function Get(string $key)
    {
        switch ($key) {
            case 'resultFileUrl':return $this->resultFileUrl;
            default:return null;
        }
    }
}

class FileGenerator implements IResultGenerator
{
    // Private members
    private $schemaFile;
    private $contentPath;
    private $scriptCallable;
    private $fileBasePath;

    /**
     * @brief
     * @param schemaFile Used to validate the preprocessed data passed in the Generate(...) method.
     * @param contentPath Path where the files are located that the Generate(...) method should combine.
     * @param scriptCallable A callable implementation of the script to be executed to
     * @param fileBasePath Determines where the files should be created
     * actually process the data.
     */
    public function __construct(string $schemaFile, string $contentPath, IScriptCallable &$scriptCallable, string $fileBasePath = '')
    {
        $this->schemaFile = $schemaFile;
        $this->contentPath = $contentPath;
        $this->scriptCallable = $scriptCallable;
        $this->fileBasePath = $fileBasePath;
    }

    /**
     * @brief
     */
    public function Generate(array $evaluatedData, bool $validate = true): ISurveyResult
    {
        if ($validate and !Validate($evaluatedData, $this->schemaFile)) {
            throw new HttpException(
                "Schema validation of raw data using '" . $this->schema . "' failed!",
                HttpStatusCode::INTERNAL_ERR
            );
        }
        $merger = new PdfMerger($this->contentPath, $this->fileBasePath);

        $sources = $this->scriptCallable->Run($evaluatedData);

        $filePath = $merger->MergeFiles($sources);

        if (empty($filePath)) {
            throw new HttpException(
                "Failed to generate result file!",
                HttpStatusCode::INTERNAL_ERR
            );
        }
        return new FileResult($filePath);
    }
}
