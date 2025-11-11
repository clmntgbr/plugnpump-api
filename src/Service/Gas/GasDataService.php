<?php

declare(strict_types=1);

namespace App\Service\Gas;

use App\Dto\GasStationListDto;

class GasDataService
{
    public function __construct(
        private string $gasUrl,
        private readonly GasXmlParser $xmlParser,
    ) {
    }

    public function download(): string
    {
        $fileData = file_get_contents($this->gasUrl);
        if (false === $fileData) {
            throw new \RuntimeException('Failed to download the zip file.');
        }

        $zipFilePath = sys_get_temp_dir().'/gas.zip';
        $result = file_put_contents($zipFilePath, $fileData);
        if (false === $result) {
            throw new \RuntimeException('Failed to save the zip file.');
        }

        return $zipFilePath;
    }

    public function extract(string $zipFilePath): string
    {
        if (!file_exists($zipFilePath)) {
            throw new \RuntimeException('ZIP file does not exist');
        }

        $zip = new \ZipArchive();
        if (true !== $zip->open($zipFilePath)) {
            throw new \RuntimeException('Failed to open ZIP file');
        }

        try {
            $firstFileName = $zip->getNameIndex(0);
            if (!$firstFileName) {
                throw new \RuntimeException('ZIP file is empty');
            }

            $tempDir = sys_get_temp_dir();
            $extractedPath = $tempDir.'/'.$firstFileName;
            $targetPath = $tempDir.'/gas.xml';

            $zip->extractTo($tempDir);

            if (file_exists($extractedPath)) {
                if (file_exists($targetPath)) {
                    unlink($targetPath);
                }
                rename($extractedPath, $targetPath);
            }
        } finally {
            $zip->close();
        }

        return $targetPath;
    }

    public function parse(string $xmlFilePath): GasStationListDto
    {
        return $this->xmlParser->parseXmlFile($xmlFilePath);
    }

    public function delete(string $filePath): void
    {
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
