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

class FileDescriptor extends DataObject
{
    public $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }
}

class FileGenerator extends ScriptedGenerator
{
    // Private members
    private $contentPath;
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
        parent::__construct($schemaFile, $scriptCallable);
        $this->contentPath = $contentPath;
        $this->fileBasePath = $fileBasePath;
    }

    /**
     * @brief
     */
    public function Generate(array $processedData, bool $validate = true): DataObject
    {
        $container = parent::Generate($processedData, $validate);

        $merger = new PdfMerger($this->contentPath, $this->fileBasePath);
        $filePath = $merger->MergeFiles($container->items);

        if (empty($filePath)) {
            throw new HttpException(
                "Failed to generate result file!",
                HttpStatusCode::INTERNAL_ERR
            );
        }
        return new FileDescriptor($filePath);
    }
}
