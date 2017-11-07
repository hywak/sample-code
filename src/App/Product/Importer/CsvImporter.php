<?php

namespace App\Product\Importer;

use Psr\Log\NullLogger as Logger;

class CsvImporter implements ImporterInterface
{
    /**
     * @var Logger
     */
    private $logger;

    const SUPPORTED_FILE_EXTENSION = 'csv';

    /**
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param string $pathToFile
     *
     * @throws Exception\ExceptionInterface
     */
    public function import(string $pathToFile): void
    {
        $fileExtension = pathinfo($pathToFile, PATHINFO_EXTENSION);
        if ($fileExtension !== self::SUPPORTED_FILE_EXTENSION) {
            throw new Exception\ExtensionNotSupportedException(
                sprintf(
                    'Extension `%s` not supported. Supported extension is `%s`',
                    $fileExtension,
                    self::SUPPORTED_FILE_EXTENSION
                )
            );
        }

        $this->logger->info('Successfully imported');
    }
}
