<?php

namespace Unit\App\Product\Importer;

use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger as Logger;
use App\Product\Importer;

class XmlImporterTest extends TestCase
{
    /**
     * @var Logger|\Mockery\MockInterface
     */
    private $loggerMock;

    /**
     * @var Importer\XmlImporter
     */
    private $xmlImporter;

    public function setUp(): void
    {
        $this->loggerMock = \Mockery::mock(Logger::class);
        $this->xmlImporter = new Importer\XmlImporter($this->loggerMock);
    }

    public function testIfImportThrowsExtensionNotSupportedExceptionWhenFileExtensionIsNotCsv(): void
    {
        $this->expectException(Importer\Exception\ExtensionNotSupportedException::class);
        $this->expectExceptionMessage('Extension `foo` not supported. Supported extension is `xml`');
        $this->xmlImporter->import('file_path.foo');
    }

    public function testIfImportReturnsNothingOnSuccess(): void
    {
        $this->loggerMock->shouldReceive('info')
            ->once()
            ->with('Successfully imported');

        $this->xmlImporter->import('file_path.xml');
        $this->assertTrue(true);
    }
}
