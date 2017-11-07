<?php

namespace App\Product\Importer;

use Psr\Log\NullLogger as Logger;

class Importer implements ImporterInterface
{
    /**
     * @var ImporterInterface[]
     */
    private $availableImporters;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @param ImporterInterface[] $availableImporters
     * @param Logger              $logger
     */
    public function __construct(array $availableImporters, Logger $logger)
    {
        $this->availableImporters = $availableImporters;
        $this->logger = $logger;
    }

    /**
     * @param string $pathToFile
     *
     * @throws Exception\ExceptionInterface
     */
    public function import(string $pathToFile): void
    {
        foreach ($this->availableImporters as $availableImporter) {
            try {
                $availableImporter->import($pathToFile);

                return;
            } catch (Exception\ExtensionNotSupportedException $e) {
                $this->logger->info(
                    sprintf('Importer does not match to given file `%s`, skipping.', $pathToFile),
                    [
                        'previousExceptionMessage' => $e->getMessage(),
                    ]
                );
                continue;
            }
        }

        $this->logger->error(sprintf('Importer for `%s` file was not found', $pathToFile));
    }
}
