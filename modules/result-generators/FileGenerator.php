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

class FileGenerator implements IResultGenerator
{
    // Private members
    private $schemaFile;
    private $contentPath;
    private $scriptFunctor;

    /**
     * @brief
     * @param schemaFile Used to validate the preprocessed data passed in the Generate(...) method.
     * @param contentPath Path where the files are located that the Generate(...) method should combine.
     * @param scriptFunctor Method to be run to generate the results. Must match a signature
     * of function(array):array and pass a self-test with an empty array resulting in an
     * empty array itself.
     */
    public function __construct(string $schemaFile, string $contentPath, callable $scriptFunctor)
    {
        $this->schemaFile = $schemaFile;
        $this->contentPath = $contentPath;
        // Self-test
        if (($scriptFunctor == null) or (!is_array($scriptFunctor([])))) {
            throw new HttpException(
                "No suitable script functor set for FileGenerator.",
                HttpStatusCode::INTERNAL_ERR
            );
        }
        $this->scriptFunctor = $scriptFunctor;
    }

    /**
     * @brief
     */
    public function Generate(array $evaluatedData, bool $validate = true): array
    {
        if ($validate and !Validate($evaluatedData, $this->schemaFile)) {
            throw new HttpException(
                "Schema validation of raw data using '" . $this->schema . "' failed!",
                HttpStatusCode::INTERNAL_ERR
            );
        }
        $merger = new PdfMerger($this->contentPath);

        $sources = call_user_func($this->scriptFunctor, $evaluatedData);

        $resultFile = $merger->MergeFiles($sources);

        if (empty($resultFile)) {
            throw new HttpException(
                "Failed to generate result file!",
                HttpStatusCode::INTERNAL_ERR
            );
        }
        return ["generatedFile" => $resultFile];
    }
}
