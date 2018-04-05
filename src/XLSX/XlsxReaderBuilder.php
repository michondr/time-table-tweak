<?php

namespace App\XLSX;

use App\File\FileDownloader;

class XlsxReaderBuilder
{
    private $fileDownloader;

    public function __construct(
        FileDownloader $fileDownloader
    ) {
        $this->fileDownloader = $fileDownloader;
    }

    public function buildFromPath(string $filePath)
    {
        try {
            $xslx = new XLSXReader($filePath);
        } catch (\Exception $e) {
            if (strpos($e->getMessage(), 'with zip error code: 19')) {
                throw new \Exception('Not a xlsx file');
            }
            throw $e;
        }

        return $xslx;
    }

    public function buildFromUrl(string $url = "http://michondr.cz/images/fulls/me_black.jpg")
    {
        $file = $this->fileDownloader->downloadToCache($url);

        return $this->buildFromPath($file);
    }
}
