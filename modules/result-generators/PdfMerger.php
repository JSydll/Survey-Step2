<?php
/**
 * @file
 *
 * @author Joschka Seydell
 * @date 07.03.2020
 */
$ROOT = __DIR__ . "/../..";
require_once "$ROOT/vendor/autoload.php";
require_once "$ROOT/HttpException.php";

use iio\libmergepdf\Merger;

class PdfMerger
{
    // Private members
    private $contentPath;

    public function __construct($contentPath)
    {
        $this->contentPath = $contentPath;
    }

    /**
     * @brief Merges several PDF files into one.
     *
     * @param fileNames List of valid PDF files.
     * @return mergedFile Path of the resulting merged file.
     */
    public function MergeFiles($fileNames): string
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
        $fileName = sys_get_temp_dir() . "/gen_" . uniqid() . ".pdf";
        file_put_contents($fileName, $merger->merge());
        return $fileName;
    }
}