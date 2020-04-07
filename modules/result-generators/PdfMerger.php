<?php
/**
 * @file
 *
 * @author Joschka Seydell
 * @date 07.03.2020
 */
namespace Step2;

require_once __DIR__ . "/../../Step2.php";

use iio\libmergepdf\Merger;

class PdfMerger
{
    // Private members
    private $contentPath;
    private $basePath;

    public function __construct(string $contentPath, string $basePath = '')
    {
        $this->contentPath = $contentPath;
        $this->basePath = (empty($basePath) ? \sys_get_temp_dir() : $basePath);
    }

    /**
     * @brief Merges several PDF files into one.
     *
     * @param fileNames List of valid PDF files.
     * @return mergedFile Path of the resulting merged file.
     */
    public function MergeFiles(array &$fileNames): string
    {
        $merger = new Merger;
        foreach ($fileNames as $file) {
            $path = "$this->contentPath/$file";
            if (!file_exists($path) || !is_readable($path)) {
                throw new HttpException(
                    "Could not access file '$file'!",
                    HttpStatusCode::INTERNAL_ERR
                );
            }
            $merger->addFile($path);
        }
        $filePath = "$this->basePath/gen_" . uniqid() . ".pdf";
        file_put_contents($filePath, $merger->merge());
        return $filePath;
    }
}
