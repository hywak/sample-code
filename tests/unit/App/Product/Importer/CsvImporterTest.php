<?php

namespace Unit\App\Product\Importer;

use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger as Logger;
use App\Product\Importer;

class CsvImporterTest extends TestCase
{
    /**
     * @var Logger|\Mockery\MockInterface
     */
    private $loggerMock;

    /**
     * @var Importer\CsvImporter
     */
    private $csvImporter;

    public function setUp(): void
    {
        $this->loggerMock = \Mockery::mock(Logger::class);
        $this->csvImporter = new Importer\CsvImporter($this->loggerMock);
    }

    public function testIfImportThrowsExtensionNotSupportedExceptionWhenFileExtensionIsNotCsv(): void
    {
        $this->expectException(Importer\Exception\ExtensionNotSupportedException::class);
        $this->expectExceptionMessage('Extension `foo` not supported. Supported extension is `csv`');
        $this->csvImporter->import('file_path.foo');
    }

    public function testIfImportReturnsNothingOnSuccess(): void
    {
        $this->loggerMock->shouldReceive('info')
            ->once()
            ->with('Successfully imported');

        $this->csvImporter->import('file_path.csv');
        $this->assertTrue(true);
    }
}
